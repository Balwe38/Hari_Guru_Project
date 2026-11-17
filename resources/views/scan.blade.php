@extends('layouts.app')

@section('title', 'Scan Sampah - EcoScan AI')

@section('header')
    <h1 class="mt-3 text-2xl font-bold">Scan Sampah</h1>
    <p class="text-sm">Unggah gambar sampah untuk mengetahui jenis dan kategorinya.</p>
@endsection

@section('content')
    <form action="{{ route('scan.store') }}" method="POST" enctype="multipart/form-data"
          class="rounded-2xl border border-green-200 bg-white p-4 text-center">
        @csrf

        @if ($errors->any())
            <div class="mb-3 rounded-lg bg-red-100 p-3 text-left text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <h2 class="text-lg font-semibold text-green-800">Upload Gambar Sampah</h2>
        <p class="mb-3 text-xs text-gray-500">Pilih gambar sampah dengan jelas agar AI dapat mengenalinya.</p>

        <input type="file" name="image" id="image" accept="image/png,image/jpeg" class="hidden">

        <div id="menu">
            <button type="button" id="btnPick"
                    class="w-full rounded-lg border border-green-600 py-4">
                <span class="block font-semibold">Pilih Gambar</span>
                <span class="text-xs text-gray-500">JPG, JPEG, atau PNG</span>
            </button>

            <p class="my-2 text-xs text-gray-500">ATAU</p>

            <button type="button" id="btnCamera"
                    class="w-full rounded-lg bg-green-800 py-3 text-sm font-semibold text-white">
                Buka Kamera
            </button>
        </div>

        {{-- Tampilan kamera langsung --}}
        <div id="cameraBox" class="hidden">
            <video id="video" autoplay playsinline muted class="w-full rounded-lg bg-black"></video>
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

        <div id="previewBox" class="mt-4 hidden">
            <img id="preview" class="mx-auto max-h-64 rounded-lg" alt="Preview">
        </div>

        <button type="submit" id="submitBtn"
                class="mt-4 hidden w-full rounded-lg bg-green-600 py-3 font-semibold text-white">
            Scan Gambar Ini
        </button>
    </form>

    <div class="rounded-2xl bg-green-100 p-4 text-sm text-green-900">
        <p class="mb-1 font-semibold">Tips Scanning</p>
        <ul class="list-disc space-y-1 pl-5 text-xs">
            <li>Pastikan pencahayaan cukup.</li>
            <li>Foto satu jenis sampah saja dalam satu gambar.</li>
            <li>Dekatkan kamera agar objek terlihat jelas.</li>
        </ul>
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

        // Pilih file dari galeri / komputer
        document.getElementById('btnPick').addEventListener('click', () => {
            input.removeAttribute('capture');
            input.click();
        });

        input.addEventListener('change', () => {
            if (input.files[0]) showPreview(input.files[0]);
        });

        // Buka kamera
        document.getElementById('btnCamera').addEventListener('click', async () => {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                // Fallback (misalnya HP yang membuka lewat http://IP-laptop)
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

        // Ambil foto dari kamera
        document.getElementById('btnSnap').addEventListener('click', () => {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob((blob) => {
                const file = new File([blob], 'kamera.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;   // masukkan ke input supaya ikut terkirim
                showPreview(file);
                stopCamera();
            }, 'image/jpeg', 0.9);
        });

        document.getElementById('btnCancel').addEventListener('click', stopCamera);
    </script>
@endsection