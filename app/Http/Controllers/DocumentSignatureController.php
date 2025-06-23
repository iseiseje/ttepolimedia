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
use Endroid\QrCode\Builder\Builder;

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

    public function signAsGuest(DocumentSignature $signature)
    {
        // No authentication check needed here, access is public for guest
        return view('signatures.sign_as_guest', compact('signature'));
    }

    public function signFinalizeAsGuest(Request $request, DocumentSignature $signature)
    {
        try {
            $request->validate([
                'page' => 'required|integer|min:1',
                'x' => 'required|numeric',
                'y' => 'required|numeric',
            ]);

            // 1. Generate unique code
            $unique_code = Str::uuid()->toString();
            $verificationUrl = url('/verification/' . $unique_code);

            // 2. Generate QR code PNG (MERAH, pakai endroid/qr-code v6.x builder)
            $qrCode = new \Endroid\QrCode\QrCode(
                $verificationUrl,
                new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                \Endroid\QrCode\ErrorCorrectionLevel::High,
                300,
                0,
                \Endroid\QrCode\RoundBlockSizeMode::Margin,
                new \Endroid\QrCode\Color\Color(255, 0, 0), // merah
                new \Endroid\QrCode\Color\Color(255, 255, 255) // putih
            );
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $qrResult = $writer->write($qrCode);
            $qrPath = storage_path('app/tmp_qr_' . $unique_code . '.png');
            $qrResult->saveToFile($qrPath);
            // Simpan salinan QR code merah untuk debug
            copy($qrPath, storage_path('app/public/tmp_qr_debug.png'));

            // 3. Add QR code to PDF
            $pdfPath = storage_path('app/public/' . $signature->document_path);
            $outputPdfPath = storage_path('app/public/signed_documents/signed_' . basename($signature->document_path));
            if (!file_exists(dirname($outputPdfPath))) {
                mkdir(dirname($outputPdfPath), 0755, true);
            }

            $this->addQrToPdf($pdfPath, $outputPdfPath, $qrPath, $request->page, $request->x, $request->y);

            // 4. Update signature record - QR code placed but not signed yet
            $signature->update([
                'status' => 'qr_placed', // New status indicating QR code has been placed
                'signed_document_path' => 'signed_documents/signed_' . basename($signature->document_path),
                'qr_page' => $request->page,
                'qr_x' => $request->x,
                'qr_y' => $request->y,
                'qr_canvas_width' => $request->input('canvas_width'),
                'qr_canvas_height' => $request->input('canvas_height'),
            ]);

            // 5. Create verification record
            Verification::create([
                'document_signature_id' => $signature->id,
                'unique_code' => $unique_code,
                'dosen_id' => $signature->dosen_id,
                'document_name' => $signature->original_filename,
                'signed_at' => now()
            ]);

            // 6. Clean up temporary QR code file
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }

            return redirect()->route('signatures.index')->with('success', 'QR code telah ditempatkan. Menunggu persetujuan dosen.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to place QR code: ' . $e->getMessage()]);
        }
    }

    public function approveQrPlacement(DocumentSignature $signature)
    {
        // Check if user is the assigned dosen or admin
        if ((!auth()->user()->isDosen() && !auth()->user()->isAdmin()) || 
            (auth()->user()->isDosen() && auth()->id() !== $signature->dosen_id)) {
            abort(403);
        }

        // Check if document has QR code placed
        if ($signature->status !== 'qr_placed') {
            return back()->withErrors(['error' => 'Document does not have QR code placed or has already been processed.']);
        }

        // Approve the document
        // Generate QR code warna hitam (pakai endroid/qr-code v6.x builder)
        $unique_code = Verification::where('document_signature_id', $signature->id)->value('unique_code');
        $verificationUrl = url('/verification/' . $unique_code);
        $qrCode = new \Endroid\QrCode\QrCode(
            $verificationUrl,
            new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
            \Endroid\QrCode\ErrorCorrectionLevel::High,
            300,
            0,
            \Endroid\QrCode\RoundBlockSizeMode::Margin,
            new \Endroid\QrCode\Color\Color(0, 0, 0), // hitam
            new \Endroid\QrCode\Color\Color(255, 255, 255) // putih
        );
        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $qrResult = $writer->write($qrCode);
        $qrPath = storage_path('app/tmp_qr_final_' . $unique_code . '.png');
        $qrResult->saveToFile($qrPath);
        // Tempel QR hitam ke PDF hasil final
        $inputPdfPath = storage_path('app/public/' . $signature->signed_document_path);
        $outputPdfPath = storage_path('app/public/signed_documents/signed_final_' . basename($signature->document_path));
        $this->addQrToPdf($inputPdfPath, $outputPdfPath, $qrPath, $signature->qr_page, $signature->qr_x, $signature->qr_y);
        // Update path dan status
        $signature->update([
            'status' => 'signed',
            'signed_at' => now(),
            'signed_document_path' => 'signed_documents/signed_final_' . basename($signature->document_path),
        ]);
        if (file_exists($qrPath)) {
            unlink($qrPath);
        }

        return redirect()->route('signatures.index')
            ->with('success', 'Document approved and signed successfully.');
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

        $path = $signature->document_path;
        
        // Use the signed document if it exists for signed or qr_placed statuses
        if (in_array($signature->status, ['signed', 'qr_placed']) && !empty($signature->signed_document_path)) {
            $path = $signature->signed_document_path;
        }

        $filePath = storage_path('app/public/' . $path);
        
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
        $user = auth()->user();
        if (
            ($user->isGuest() && $user->id !== $signature->guest_id) ||
            ($user->isDosen() && $user->id !== $signature->dosen_id) ||
            (!$user->isGuest() && !$user->isDosen() && !$user->isAdmin())
        ) {
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
                    \Log::info('Menempel QR ke PDF', ['qrImagePath' => $qrPath, 'xPos' => $x, 'yPos' => $y, 'outputPdfPath' => $outputPath]);
                    $pdf->Image('@' . $qrData, $x, $y, 30, 30);
                    \Log::info('Selesai menempel QR ke PDF', ['outputPdfPath' => $outputPath]);
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

        // Use the correct Ghostscript command for Windows
        $command = "gswin64c -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$outputPath}\" \"{$inputPath}\" 2>&1";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            $errorMessage = "Failed to sanitize PDF using Ghostscript. Return code: {$returnVar}. Output: " . implode("\n", $output);
            \Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }
        
        if (!file_exists($outputPath)) {
            $errorMessage = "Sanitized PDF file was not created. Command output: " . implode("\n", $output);
            \Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }
        
        return $outputPath;
    }

    private function addQrToPdf($inputPdfPath, $outputPdfPath, $qrImagePath, $page, $x, $y)
    {
        try {
            // Ensure output directory exists
            $outputDir = dirname($outputPdfPath);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Try to process the original PDF first
            try {
                return $this->processOriginalPdf($inputPdfPath, $outputPdfPath, $qrImagePath, $page, $x, $y);
            } catch (\Exception $e) {
                \Log::warning('Original PDF processing failed, creating simple PDF: ' . $e->getMessage());
                return $this->createSimplePdfWithQr($outputPdfPath, $qrImagePath, $page, $x, $y);
            }
        } catch (\Exception $e) {
            \Log::error('Error adding QR code to PDF: ' . $e->getMessage());
            throw new \Exception('Failed to add QR code to PDF: ' . $e->getMessage());
        }
    }

    private function processOriginalPdf($inputPdfPath, $outputPdfPath, $qrImagePath, $page, $x, $y)
    {
        // Try to sanitize the PDF first using Ghostscript
        $sanitizedPdfPath = storage_path('app/temp/sanitized_' . basename($inputPdfPath));
        $useSanitized = false;
        
        try {
            $this->sanitizePDF($inputPdfPath, $sanitizedPdfPath);
            $useSanitized = true;
        } catch (\Exception $e) {
            \Log::warning('Ghostscript sanitization failed, using original PDF: ' . $e->getMessage());
        }

        // Create TCPDI instance
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
        $pageCount = $pdf->setSourceFile($useSanitized ? $sanitizedPdfPath : $inputPdfPath);

        // Import all pages
        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            // Add QR code to the specified page
            if ($i == (int)$page) {
                // Convert pixel position to mm (assuming canvas size of 600x800)
                $pdfW = $size['width'];
                $pdfH = $size['height'];
                $canvasW = 600; // Default canvas width
                $canvasH = 800; // Default canvas height
                
                $xPos = ($x / $canvasW) * $pdfW;
                $yPos = ($y / $canvasH) * $pdfH;

                // Add QR code to the page
                \Log::info('Menempel QR ke PDF', ['qrImagePath' => $qrImagePath, 'xPos' => $xPos, 'yPos' => $yPos, 'outputPdfPath' => $outputPdfPath]);
                $pdf->Image($qrImagePath, $xPos, $yPos, 30, 30);
                \Log::info('Selesai menempel QR ke PDF', ['outputPdfPath' => $outputPdfPath]);
            }
        }

        // Output the modified PDF
        $pdf->Output($outputPdfPath, 'F');

        // Clean up temporary sanitized file
        if ($useSanitized && file_exists($sanitizedPdfPath)) {
            unlink($sanitizedPdfPath);
        }

        return $outputPdfPath;
    }

    private function createSimplePdfWithQr($outputPdfPath, $qrImagePath, $page, $x, $y)
    {
        // Create a simple PDF with QR code
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
        
        // Add a page
        $pdf->AddPage();
        
        // Add QR code at the specified position
        $xPos = $x * 0.5; // Convert to mm (approximate)
        $yPos = $y * 0.5; // Convert to mm (approximate)
        
        $pdf->Image($qrImagePath, $xPos, $yPos, 30, 30);
        
        // Add some text to indicate this is a signed document
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Document with QR Code - Page ' . $page, 0, 1, 'C');
        $pdf->Cell(0, 10, 'QR Code placed at position: X=' . $x . ', Y=' . $y, 0, 1, 'C');
        $pdf->Cell(0, 10, 'Original PDF could not be processed due to compression issues', 0, 1, 'C');
        
        // Output the modified PDF
        $pdf->Output($outputPdfPath, 'F');
        
        return $outputPdfPath;
    }
} 