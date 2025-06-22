<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use App\Models\DocumentSignature;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function show($unique_code)
    {
        $verification = Verification::with(['dosen', 'documentSignature'])->where('unique_code', $unique_code)->firstOrFail();
        return view('verification.show', compact('verification'));
    }

    public function download($unique_code)
    {
        $verification = Verification::where('unique_code', $unique_code)->firstOrFail();
        $signature = DocumentSignature::find($verification->document_signature_id);
        
        if (!$signature) {
            abort(404, 'Document not found');
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
} 