<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tanda Tangan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-green-700 mb-2">Dokumen Sudah Ditanda Tangani Digital</h1>
            <p class="text-gray-600">Dokumen ini telah diverifikasi dan ditanda tangani secara digital</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Informasi Dokumen:</h3>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Nama Dokumen:</span></p>
                    <p class="text-gray-800 mb-3">{{ $verification->document_name }}</p>
                    
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Kode Verifikasi:</span></p>
                    <p class="text-gray-800 mb-3 font-mono text-sm">{{ $verification->unique_code }}</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Informasi Tanda Tangan:</h3>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Ditanda Tangani Oleh:</span></p>
                    <p class="text-gray-800 mb-3">{{ $verification->dosen->name }}</p>
                    
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Tanggal & Waktu:</span></p>
                    <p class="text-gray-800 mb-3">{{ $verification->signed_at->format('d-m-Y H:i:s') }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($verification->documentSignature)
                <a href="{{ route('verification.download', $verification->unique_code) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Dokumen
                </a>
            @endif
            
            <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Dokumen ini telah ditanda tangani secara digital dan diverifikasi menggunakan sistem keamanan blockchain.
                <br>Kode verifikasi: {{ $verification->unique_code }}
            </p>
        </div>
    </div>
</body>
</html> 