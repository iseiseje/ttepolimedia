@php
    $user = auth()->user();
    $isGuest = $user && method_exists($user, 'isGuest') && $user->isGuest();
@endphp

@if($isGuest)
<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($signature->status === 'signed')
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">Dokumen Anda telah diverifikasi dan ditandatangani dosen/admin.</span>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif
                    <form id="qrForm" action="{{ route('signatures.sign-finalize-as-guest', $signature) }}" method="POST">
                        @csrf
                        <input type="hidden" name="page" id="page" value="1">
                        <input type="hidden" name="x" id="x">
                        <input type="hidden" name="y" id="y">
                        <div class="mb-4">
                            <label for="pageSelect" class="block text-sm font-medium text-gray-700">Pilih Halaman</label>
                            <select id="pageSelect" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                        </div>
                        <div class="w-full overflow-auto">
                            <div id="pdf-container" class="relative border shadow mx-auto" style="display:inline-block;">
                                <canvas id="pdf-canvas" style="border:1px solid #ccc;"></canvas>
                                <img id="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=preview" alt="QR Code" style="position:absolute; top:50px; left:50px; cursor:move; z-index:10;{{ $signature->status === 'qr_placed' ? ' filter: hue-rotate(-50deg) saturate(5) brightness(1.2);' : '' }}">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tempatkan QR Code & Kirim untuk Persetujuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
@else
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preview & Tempel QR Code') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('signatures.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali ke Daftar Dokumen
                        </a>
                    </div>
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif
                    <form id="qrForm" action="{{ route('signatures.sign-finalize', $signature) }}" method="POST">
                        @csrf
                        <input type="hidden" name="page" id="page" value="1">
                        <input type="hidden" name="x" id="x">
                        <input type="hidden" name="y" id="y">
                        <div class="mb-4">
                            <label for="pageSelect" class="block text-sm font-medium text-gray-700">Pilih Halaman</label>
                            <select id="pageSelect" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                        </div>
                        <div class="w-full overflow-auto">
                            <div id="pdf-container" class="relative border shadow mx-auto" style="display:inline-block;">
                            <canvas id="pdf-canvas" style="border:1px solid #ccc;"></canvas>
                                <img id="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=preview" alt="QR Code" style="position:absolute; top:50px; left:50px; z-index:10;{{ $signature->status === 'qr_placed' ? ' filter: hue-rotate(-50deg) saturate(5) brightness(1.2);' : '' }}">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan & Tempel QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endif

    </div>
    <!-- PDF.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    </script>
    <script>
        // Path PDF
        const url = '{{ asset('storage/' . ltrim(($signature->status === "qr_placed" || $signature->status === "signed") && $signature->signed_document_path ? $signature->signed_document_path : $signature->document_path, "/")) }}';
        let pdfDoc = null, pageNum = 1, pageRendering = false, pageNumPending = null;
        let scale = 1.2;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const qr = document.getElementById('qr-code');
        const pdfContainer = document.getElementById('pdf-container');
        let offsetX, offsetY, isDragging = false;
        // Data posisi QR dari backend
        const qrPlaced = @json($signature->status === 'qr_placed');
        const qrPage = @json($signature->qr_page);
        const qrX = @json($signature->qr_x);
        const qrY = @json($signature->qr_y);
        const qrCanvasWidth = @json($signature->qr_canvas_width);
        const qrCanvasHeight = @json($signature->qr_canvas_height);

        // PDF.js load
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('pageSelect').innerHTML = '';
            for(let i=1; i<=pdfDoc.numPages; i++) {
                let opt = document.createElement('option');
                opt.value = i;
                opt.text = 'Halaman ' + i;
                document.getElementById('pageSelect').appendChild(opt);
            }
            // Jika status qr_placed, set page ke qr_page
            if(qrPlaced && qrPage) {
                pageNum = parseInt(qrPage);
                document.getElementById('pageSelect').value = qrPage;
                document.getElementById('page').value = qrPage;
            }
            renderPage(pageNum);
        }).catch(function(error) {
            alert('Gagal memuat PDF. Pastikan file dapat diakses dan format PDF valid. Error: ' + error.message);
        });

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                // Fit to parent width
                const parentWidth = pdfContainer.parentElement.clientWidth;
                const viewport = page.getViewport({scale: 1});
                const scale = parentWidth / viewport.width;
                const scaledViewport = page.getViewport({scale: scale});

                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;
                pdfContainer.style.width = scaledViewport.width + 'px';
                pdfContainer.style.height = scaledViewport.height + 'px';

                // Scale QR Code Preview
                const qrSize = canvas.width / 8;
                qr.style.width = qrSize + 'px';
                qr.style.height = qrSize + 'px';

                // Jika status qr_placed dan halaman sama, posisikan QR code sesuai data
                if(qrPlaced && qrPage && parseInt(qrPage) === num && qrCanvasWidth && qrCanvasHeight) {
                    // Konversi posisi dari guest ke skala preview sekarang
                    const scaleX = canvas.width / qrCanvasWidth;
                    const scaleY = canvas.height / qrCanvasHeight;
                    qr.style.left = (qrX * scaleX) + 'px';
                    qr.style.top = (qrY * scaleY) + 'px';
                    qr.style.pointerEvents = 'none'; // Nonaktifkan drag
                } else {
                    // Default posisi QR code
                    qr.style.left = '50px';
                    qr.style.top = '50px';
                    qr.style.pointerEvents = 'auto';
                }

                const renderContext = {
                    canvasContext: ctx,
                    viewport: scaledViewport
                };
                const renderTask = page.render(renderContext);
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
        }

        document.getElementById('pageSelect').addEventListener('change', function(e) {
            pageNum = parseInt(e.target.value);
            document.getElementById('page').value = pageNum;
            renderPage(pageNum);
        });

        // Drag QR code (hanya jika bukan qr_placed)
        if(!qrPlaced) {
        qr.addEventListener('mousedown', function(e) {
            isDragging = true;
            offsetX = e.offsetX;
            offsetY = e.offsetY;
        });
        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                const rect = pdfContainer.getBoundingClientRect();
                let x = e.clientX - rect.left - offsetX;
                let y = e.clientY - rect.top - offsetY;
                // Batas drag
                x = Math.max(0, Math.min(x, canvas.width - qr.width));
                y = Math.max(0, Math.min(y, canvas.height - qr.height));
                qr.style.left = x + 'px';
                qr.style.top = y + 'px';
            }
        });
        document.addEventListener('mouseup', function(e) {
            isDragging = false;
        });
        }

        // Submit posisi QR code
        document.getElementById('qrForm').addEventListener('submit', function(e) {
            const x = parseInt(qr.style.left);
            const y = parseInt(qr.style.top);
            document.getElementById('x').value = x;
            document.getElementById('y').value = y;
            // Kirim juga ukuran canvas agar backend bisa konversi posisi
            const canvasW = canvas.width;
            const canvasH = canvas.height;
            let cw = document.getElementById('canvas_width');
            if (!cw) {
                cw = document.createElement('input');
                cw.type = 'hidden';
                cw.name = 'canvas_width';
                cw.id = 'canvas_width';
                document.getElementById('qrForm').appendChild(cw);
            }
            cw.value = canvasW;
            let ch = document.getElementById('canvas_height');
            if (!ch) {
                ch = document.createElement('input');
                ch.type = 'hidden';
                ch.name = 'canvas_height';
                ch.id = 'canvas_height';
                document.getElementById('qrForm').appendChild(ch);
            }
            ch.value = canvasH;
            
            // Debug: Log the values being sent
            console.log('Submitting QR position:', {
                page: document.getElementById('page').value,
                x: x,
                y: y,
                canvas_width: canvasW,
                canvas_height: canvasH
            });
        });
    </script>