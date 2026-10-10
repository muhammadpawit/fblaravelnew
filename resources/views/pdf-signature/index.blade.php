<x-layout title="Buat Dokumen & Tanda Tangan">
    <div class="mt-6 max-w-2xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="text-lg font-medium text-slate-800">Formulir Dokumen</h2>
            <p class="text-sm text-slate-500">Isi nama Anda dan buat tanda tangan di bawah ini untuk menghasilkan file PDF.</p>
        </div>
        <form method="POST" action="{{ route('pdf-signature.generate') }}" id="signatureForm" class="p-6">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border" placeholder="Contoh: Budi Santoso">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanda Tangan</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 overflow-hidden relative" style="height: 200px;">
                        <canvas id="signature-pad" class="w-full h-full cursor-crosshair"></canvas>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-xs text-slate-500">Tanda tangan di dalam kotak di atas.</p>
                        <button type="button" id="clearBtn" class="text-xs text-red-600 hover:text-red-800 font-medium">Hapus Tanda Tangan</button>
                    </div>
                    <input type="hidden" name="signature" id="signature" required>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="submit" class="rounded-lg bg-teal-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-teal-800 transition-colors">
                    Hasilkan PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Include signature_pad library from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('signature-pad');
            
            // Adjust canvas coordinate space to match its DOM size
            function resizeCanvas() {
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.onresize = resizeCanvas;
            resizeCanvas();

            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)', // transparent
                penColor: 'rgb(15, 23, 42)' // slate-900
            });

            document.getElementById('clearBtn').addEventListener('click', function () {
                signaturePad.clear();
            });

            document.getElementById('signatureForm').addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert("Tanda tangan tidak boleh kosong!");
                    return false;
                }
                
                // Set the base64 string to the hidden input
                var data = signaturePad.toDataURL('image/png');
                document.getElementById('signature').value = data;
                
                // Global layout script will handle the button loader automatically
            });
        });
    </script>
</x-layout>
