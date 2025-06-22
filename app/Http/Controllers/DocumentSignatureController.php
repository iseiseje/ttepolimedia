<?php

namespace App\Http\Controllers;

use App\Models\DocumentSignature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Verification;
use Illuminate\Support\Str;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use setasign\Fpdi\Fpdi;
use TCPDI\tcpdi_parser;
use Endroid\QrCode\Writer\PngWriter;

class DocumentSignatureController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin can see all documents and also upload their own
            $signatures = DocumentSignature::with(['guest', 'dosen'])->latest()->get();
        } elseif ($user->isDosen()) {
            // Show both documents to sign (from guests) and own uploaded documents
            $signatures = DocumentSignature::where('dosen_id', $user->id)
                ->with(['guest', 'dosen'])
                ->latest()
                ->get();
        } else {
            $signatures = $user->requestedSignatures()->with('dosen')->latest()->get();
        }

        return view('signatures.index', compact('signatures'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isDosen() || $user->isAdmin()) {
            // Dosen and Admin can upload their own documents
            return view('signatures.create_dosen');
        } else {
            // Guest users select a dosen to sign their document
            $dosen = User::where('role', 'dosen')->get();
            return view('signatures.create', compact('dosen'));
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isDosen() || $user->isAdmin()) {
            // Dosen or Admin uploading their own document
            $request->validate([
                'document' => 'required|file|mimes:pdf|max:10240',
                'notes' => 'nullable|string'
            ]);

            $file = $request->file('document');
            $path = $file->store('documents', 'public');

            DocumentSignature::create([
                'guest_id' => $user->id, // Dosen/Admin is both guest and dosen
                'dosen_id' => $user->id,
                'document_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'notes' => $request->notes,
                'status' => 'pending' // Will be signed immediately
            ]);

            return redirect()->route('signatures.index')
                ->with('success', 'Document uploaded successfully. You can now sign it.');
        } else {
            // Guest user submitting document for dosen signature
            $request->validate([
                'document' => 'required|file|mimes:pdf|max:10240',
                'dosen_id' => 'required|exists:users,id',
                'notes' => 'nullable|string'
            ]);

            $file = $request->file('document');
            $path = $file->store('documents', 'public');

            DocumentSignature::create([
                'guest_id' => auth()->id(),
                'dosen_id' => $request->dosen_id,
                'document_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'notes' => $request->notes
            ]);

            return redirect()->route('signatures.index')
                ->with('success', 'Document submitted for signature successfully.');
        }
    }

    public function sign(DocumentSignature $signature)
    {
        if ((!auth()->user()->isDosen() && !auth()->user()->isAdmin()) || auth()->id() !== $signature->dosen_id) {
            abort(403);
        }

        $signature->update([
            'status' => 'signed',
            'signed_at' => now()
        ]);

        return redirect()->route('signatures.index')
            ->with('success', 'Document signed successfully.');
    }

    public function reject(Request $request, DocumentSignature $signature)
    {
        if ((!auth()->user()->isDosen() && !auth()->user()->isAdmin()) || auth()->id() !== $signature->dosen_id) {
            abort(403);
        }

        $request->validate([
            'notes' => 'required|string'
        ]);

        $signature->update([
            'status' => 'rejected',
            'notes' => $request->notes
        ]);

        return redirect()->route('signatures.index')
            ->with('success', 'Document rejected successfully.');
    }

    public function download(DocumentSignature $signature)
    {
        if (!auth()->user()->isAdmin() && 
            auth()->id() !== $signature->guest_id && 
            auth()->id() !== $signature->dosen_id) {
            abort(403);
        }

        $filePath = storage_path('app/public/' . $signature->document_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download(
            $filePath,
            $signature->original_filename
        );
    }

    public function publicDownload(DocumentSignature $signature)
    {
        // Public download method for verification page - no authentication required
        $filePath = storage_path('app/public/' . $signature->document_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download(
            $filePath,
            $signature->original_filename
        );
    }

    public function signPreview(DocumentSignature $signature)
    {
        if ((!auth()->user()->isDosen() && !auth()->user()->isAdmin()) || auth()->id() !== $signature->dosen_id) {
            abort(403);
        }
        return view('signatures.sign_preview', compact('signature'));
    }

    public function signFinalize(Request $request, DocumentSignature $signature)
    {
        try {
            if ((!auth()->user()->isDosen() && !auth()->user()->isAdmin()) || auth()->id() !== $signature->dosen_id) {
                abort(403);
            }
            $request->validate([
                'page' => 'required|integer|min:1',
                'x' => 'required|numeric',
                'y' => 'required|numeric',
            ]);

            // 1. Generate unique code
            $unique_code = Str::uuid()->toString();
            $verificationUrl = url('/verification/' . $unique_code);

            // 2. Generate QR code PNG (ke file sementara)
            $qrPath = storage_path('app/tmp_qr_' . $unique_code . '.png');
            
            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_H,
                'scale' => 10,
                'imageBase64' => false,
                'moduleValues' => [
                    'finder' => '#000000',
                    'finder_dot' => '#000000',
                    'finder_dark' => '#000000',
                    'alignment' => '#000000',
                    'alignment_dark' => '#000000',
                    'timing' => '#000000',
                    'timing_dark' => '#000000',
                    'format' => '#000000',
                    'format_dark' => '#000000',
                    'version' => '#000000',
                    'version_dark' => '#000000',
                    'data' => '#000000',
                    'data_dark' => '#000000',
                    'darkmodule' => '#000000',
                    'separator' => '#000000',
                    'quietzone' => '#FFFFFF',
                ],
            ]);

            $qrcode = new QRCode($options);
            $qrcode->render($verificationUrl, $qrPath);

            // 3. Tempel QR code ke PDF pada halaman & posisi yang dipilih
            $pdfPath = storage_path('app/public/' . $signature->document_path);
            $sanitizedPdfPath = storage_path('app/temp/sanitized_' . basename($pdfPath));
            $this->sanitizePDF($pdfPath, $sanitizedPdfPath);
            $pdfPath = $sanitizedPdfPath; // Use the sanitized PDF for further processing

            $outputPath = storage_path('app/public/documents/signed_' . basename($signature->document_path));

            if (!file_exists($pdfPath)) {
                throw new \Exception('PDF file not found: ' . $pdfPath);
            }

            // Parse PDF using tcpdi_parser
            $pdfData = file_get_contents($pdfPath);
            $parser = new tcpdi_parser($pdfData, uniqid('parser_'));
            $pdfVersion = $parser->getPDFVersion();
            $pageCount = $parser->getPageCount();

            // Create TCPDI instance
            $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
            $pageCount = $pdf->setSourceFile($pdfPath);

            // Import all pages
            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                // Add QR code to the specified page
                if ($i == (int)$request->page) {
                    // Generate QR code using endroid/qr-code v6.0 with the correct verification URL
                    $qrCode = new \Endroid\QrCode\QrCode($verificationUrl);
                    
                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);
                    $qrData = $result->getString();

                    // Convert pixel position to mm
                    $pdfW = $size['width'];
                    $pdfH = $size['height'];
                    $canvasW = (int)$request->input('canvas_width', 600);
                    $canvasH = (int)$request->input('canvas_height', 800);
                    $x = ($request->x / $canvasW) * $pdfW;
                    $y = ($request->y / $canvasH) * $pdfH;

                    // Add QR code to the page
                    $pdf->Image('@' . $qrData, $x, $y, 30, 30);
                }
            }

            // Output the modified PDF
            $pdf->Output($outputPath, 'F');
            unlink($qrPath);

            // 4. Simpan data verifikasi
            Verification::create([
                'unique_code' => $unique_code,
                'document_signature_id' => $signature->id,
                'dosen_id' => auth()->id(),
                'document_name' => $signature->original_filename,
                'signed_at' => now(),
            ]);

            // 5. Update dokumen signature
            $signature->update([
                'status' => 'signed',
                'signed_at' => now(),
                'document_path' => 'documents/signed_' . basename($signature->document_path),
            ]);

            return redirect()->route('signatures.index')->with('success', 'Dokumen berhasil ditandatangani dan QR code sudah ditempel.');
        } catch (\Exception $e) {
            \Log::error('PDF processing error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF processing failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Advanced example: Parse a PDF using tcpdi_parser and add a QR code using TCPDI.
     */
    public function parsePdfExample()
    {
        $pdfPath = storage_path('app/public/yourfile.pdf');
        $pdfData = file_get_contents($pdfPath);

        // Create parser instance
        $parser = new tcpdi_parser($pdfData, uniqid('parser_'));
        $pdfVersion = $parser->getPDFVersion();
        $pageCount = $parser->getPageCount();

        // Create TCPDI instance
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);

        // Import the first page
        $tpl = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tpl);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tpl);

        // Generate QR code (using chillerlan/php-qrcode)
        $qrcode = new \chillerlan\QRCode\QRCode([
            'outputType' => \chillerlan\QRCode\Output\QRImage::OUTPUT_IMAGE_PNG,
            'eccLevel' => \chillerlan\QRCode\QRCode::ECC_L,
            'scale' => 5,
            'imageBase64' => false,
        ]);
        $qrData = $qrcode->render('https://example.com/verify');

        // Add QR code to the first page
        $pdf->Image($qrData, 10, 10, 30, 30);

        // Output the modified PDF
        $outputPath = storage_path('app/public/modified.pdf');
        $pdf->Output($outputPath, 'F');

        return response()->json([
            'version' => $pdfVersion,
            'pages' => $pageCount,
            'modified_pdf' => 'modified.pdf',
        ]);
    }

    private function sanitizePDF($inputPath, $outputPath) {
        // Ensure the temp directory exists
        $tempDir = dirname($outputPath);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Use the correct Ghostscript command
        $command = "gswin64c -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$outputPath}\" \"{$inputPath}\" 2>&1";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            $errorMessage = "Failed to sanitize PDF using Ghostscript. Return code: {$returnVar}. Output: " . implode("\n", $output);
            throw new \Exception($errorMessage);
        }
        
        if (!file_exists($outputPath)) {
            throw new \Exception("Sanitized PDF file was not created. Command output: " . implode("\n", $output));
        }
        
        return $outputPath;
    }
} 