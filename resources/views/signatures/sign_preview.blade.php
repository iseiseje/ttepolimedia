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
                    <form id="qrForm" action="{{ route('signatures.sign-finalize', $signature) }}" method="POST">
                        @csrf
                        <input type="hidden" name="page" id="page" value="1">
                        <input type="hidden" name="x" id="x">
                        <input type="hidden" name="y" id="y">
                        <div class="mb-4">
                            <label for="pageSelect" class="block text-sm font-medium text-gray-700">Pilih Halaman</label>
                            <select id="pageSelect" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                        </div>
                        <div id="pdf-container" class="relative border shadow" style="width: 600px; height: 800px;">
                            <canvas id="pdf-canvas" style="border:1px solid #ccc;"></canvas>
                            <img id="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=preview" alt="QR Code" style="position:absolute; top:50px; left:50px; width:100px; height:100px; cursor:move; z-index:10;">
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan & Tempel QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    </script>
    <script>
        // Path PDF
        const url = '{{ asset('storage/' . ltrim($signature->document_path, '/')) }}';
        let pdfDoc = null, pageNum = 1, pageRendering = false, pageNumPending = null;
        let scale = 1.2;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const qr = document.getElementById('qr-code');
        const pdfContainer = document.getElementById('pdf-container');
        let offsetX, offsetY, isDragging = false;

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
            renderPage(pageNum);
        }).catch(function(error) {
            alert('Gagal memuat PDF. Pastikan file dapat diakses dan format PDF valid. Error: ' + error.message);
        });

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                pdfContainer.style.width = viewport.width + 'px';
                pdfContainer.style.height = viewport.height + 'px';
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
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

        // Drag QR code
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

        // Submit posisi QR code
        document.getElementById('qrForm').addEventListener('submit', function(e) {
            const x = parseInt(qr.style.left);
            const y = parseInt(qr.style.top);
            document.getElementById('x').value = x;
            document.getElementById('y').value = y;
            // Kirim juga ukuran canvas agar backend bisa konversi posisi
            const canvasW = canvas.width;
            const canvasH = canvas.height;
            if (!document.getElementById('canvas_width')) {
                let cw = document.createElement('input');
                cw.type = 'hidden';
                cw.name = 'canvas_width';
                cw.id = 'canvas_width';
                cw.value = canvasW;
                document.getElementById('qrForm').appendChild(cw);
            } else {
                document.getElementById('canvas_width').value = canvasW;
            }
            if (!document.getElementById('canvas_height')) {
                let ch = document.createElement('input');
                ch.type = 'hidden';
                ch.name = 'canvas_height';
                ch.id = 'canvas_height';
                ch.value = canvasH;
                document.getElementById('qrForm').appendChild(ch);
            } else {
                document.getElementById('canvas_height').value = canvasH;
            }
        });
    </script>
</x-app-layout> 