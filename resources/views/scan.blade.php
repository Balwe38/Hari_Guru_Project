@extends('layouts.app')

@section('title', 'Scan Sampah - EcoScan AI')

@section('header')
    <h1 class="text-2xl font-bold sm:text-3xl lg:text-4xl">Scan Sampah</h1>
    <p class="text-sm sm:text-base">Unggah gambar sampah untuk mengetahui jenis dan kategorinya.</p>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Form scan --}}
        <form action="{{ route('scan.store') }}" method="POST" enctype="multipart/form-data"
              class="rounded-2xl border border-green-200 bg-white p-5 text-center shadow-sm sm:p-6 lg:col-span-2">
            @csrf

            @if ($errors->any())
                <div class="mb-3 rounded-lg bg-red-100 p-3 text-left text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <h2 class="text-lg font-semibold text-green-800 sm:text-xl">Upload Gambar Sampah</h2>
            <p class="mb-4 text-xs text-gray-500 sm:text-sm">Pilih gambar sampah dengan jelas agar AI dapat mengenalinya.</p>

            <input type="file" name="image" id="image" accept="image/png,image/jpeg" class="hidden">

            <div id="menu" class="grid gap-3 sm:grid-cols-2">
                <button type="button" id="btnPick"
                        class="rounded-lg border border-green-600 py-4 hover:bg-green-50">
                    <span class="block font-semibold">Pilih Gambar</span>
                    <span class="text-xs text-gray-500">JPG, JPEG, atau PNG</span>
                </button>

                <button type="button" id="btnCamera"
                        class="rounded-lg bg-green-800 py-4 text-sm font-semibold text-white hover:bg-green-700">
                    Buka Kamera
                </button>
            </div>

            {{-- Tampilan kamera langsung --}}
            <div id="cameraBox" class="hidden">
                <video id="video" autoplay playsinline muted
                       class="mx-auto max-h-[60vh] w-full rounded-lg bg-black"></video>
                <div class="mt-3 flex gap-2">
                    <button type="button" id="btnSnap"
                            class="flex-1 rounded-lg bg-green-800 py-3 text-sm font-semibold text-white">
                        Ambil Foto
                    </button>
                    <button type="button" id="btnCancel"
                            class="flex-1 rounded-lg border border-gray-300 py-3 text-sm font-semibold">
                        Batal
                    </button>
                </div>
            </div>

            <canvas id="canvas" class="hidden"></canvas>

            <div id="previewBox" class="mt-5 hidden">
                <img id="preview" class="mx-auto max-h-72 rounded-lg sm:max-h-96" alt="Preview">
            </div>

            <button type="submit" id="submitBtn"
                    class="mt-5 hidden w-full rounded-lg bg-green-600 py-3 font-semibold text-white hover:bg-green-500">
                Scan Gambar Ini
            </button>
        </form>

        {{-- Tips --}}
        <aside class="h-fit rounded-2xl bg-green-100 p-5 text-sm text-green-900">
            <p class="mb-2 font-semibold">Tips Scanning</p>
            <ul class="list-disc space-y-1 pl-5 text-xs sm:text-sm">
                <li>Pastikan pencahayaan cukup.</li>
                <li>Foto satu jenis sampah saja dalam satu gambar.</li>
                <li>Dekatkan kamera agar objek terlihat jelas.</li>
            </ul>
        </aside>
    </div>

    <script>
        const input     = document.getElementById('image');
        const menu      = document.getElementById('menu');
        const cameraBox = document.getElementById('cameraBox');
        const video     = document.getElementById('video');
        const canvas    = document.getElementById('canvas');
        let stream = null;

        function showPreview(file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
            document.getElementById('previewBox').classList.remove('hidden');
            document.getElementById('submitBtn').classList.remove('hidden');
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(t => t.stop());
                stream = null;
            }
            video.srcObject = null;
            cameraBox.classList.add('hidden');
            menu.classList.remove('hidden');
        }

        document.getElementById('btnPick').addEventListener('click', () => {
            input.removeAttribute('capture');
            input.click();
        });

        input.addEventListener('change', () => {
            if (input.files[0]) showPreview(input.files[0]);
        });

        document.getElementById('btnCamera').addEventListener('click', async () => {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                input.setAttribute('capture', 'environment');
                input.click();
                return;
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' },
                    audio: false,
                });
                video.srcObject = stream;
                menu.classList.add('hidden');
                cameraBox.classList.remove('hidden');
            } catch (err) {
                alert('Kamera tidak bisa dibuka. Pastikan izin kamera sudah diizinkan di browser.');
            }
        });

        document.getElementById('btnSnap').addEventListener('click', () => {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob((blob) => {
                const file = new File([blob], 'kamera.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showPreview(file);
                stopCamera();
            }, 'image/jpeg', 0.9);
        });

        document.getElementById('btnCancel').addEventListener('click', stopCamera);
    </script>
@endsection