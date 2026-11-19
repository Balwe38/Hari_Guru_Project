@extends('layouts.app')

@section('title', 'Scan Sampah - EcoScan AI')

@section('header')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                EcoScan AI
            </div>

<h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
    Kenali Sampahmu dengan
    <span class="text-emerald-300">AI</span>
</h1>

<p class="mt-2 max-w-2xl text-sm text-green-50 sm:text-base">
    Unggah atau ambil foto sampah untuk mengetahui jenis dan kategorinya secara otomatis.
</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- AREA SCAN --}}
        <form action="{{ route('scan.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="relative overflow-hidden rounded-3xl border border-green-100 bg-white p-5 shadow-sm sm:p-7 lg:col-span-2">

            @csrf

            {{-- Dekorasi --}}
            <div class="pointer-events-none absolute -right-20 -top-20 h-48 w-48 rounded-full bg-green-100 opacity-60 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-emerald-100 opacity-50 blur-3xl"></div>

            @if ($errors->any())
                <div class="relative mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-left text-sm text-red-700">
                    <div class="flex items-start gap-3">
                        <span class="text-lg">⚠️</span>
                        <div>
                            <p class="font-semibold">Terjadi kesalahan</p>
                            <p class="mt-1">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Judul --}}
            <div class="relative mb-6 text-center">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl">
                    ♻️
                </div>

                <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">
                    Upload Gambar Sampah
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pilih gambar atau gunakan kamera untuk memulai pemindaian.
                </p>
            </div>

            <input type="file"
                   name="image"
                   id="image"
                   accept="image/png,image/jpeg"
                   class="hidden">

            <input type="hidden" name="label" id="label">
            <input type="hidden" name="confidence" id="confidence">

            {{-- MENU UPLOAD --}}
            <div id="menu" class="relative grid gap-4 sm:grid-cols-2">

                {{-- Pilih Gambar --}}
                <button type="button"
                        id="btnPick"
                        class="group rounded-2xl border-2 border-dashed border-green-200 bg-green-50/50 p-6 text-center transition hover:border-green-500 hover:bg-green-50">

                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm transition group-hover:scale-105">
                        🖼️
                    </div>

                    <span class="block font-bold text-gray-800">
                        Pilih Gambar
                    </span>

                    <span class="mt-1 block text-xs text-gray-500">
                        JPG, JPEG, atau PNG
                    </span>
                </button>

                {{-- Kamera --}}
                <button type="button"
                        id="btnCamera"
                        class="group rounded-2xl bg-gradient-to-br from-green-700 to-emerald-600 p-6 text-center text-white shadow-lg shadow-green-100 transition hover:-translate-y-0.5 hover:from-green-800 hover:to-emerald-700">

                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-2xl backdrop-blur">
                        📷
                    </div>

                    <span class="block font-bold">
                        Buka Kamera
                    </span>

                    <span class="mt-1 block text-xs text-green-100">
                        Ambil foto secara langsung
                    </span>
                </button>
            </div>

            {{-- CAMERA --}}
            <div id="cameraBox" class="relative hidden">

                <div class="overflow-hidden rounded-2xl bg-black shadow-lg">
                    <video id="video"
                           autoplay
                           playsinline
                           muted
                           class="mx-auto max-h-[60vh] w-full object-contain">
                    </video>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <button type="button"
                            id="btnSnap"
                            class="rounded-xl bg-green-700 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-green-800">
                        📸 Ambil Foto
                    </button>

                    <button type="button"
                            id="btnCancel"
                            class="rounded-xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </button>
                </div>
            </div>

            <canvas id="canvas" class="hidden"></canvas>

            {{-- PREVIEW --}}
            <div id="previewBox" class="relative mt-6 hidden">

                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-gray-50 p-3 shadow-inner">
                    <div class="relative overflow-hidden rounded-xl">

                        <img id="preview"
                             class="mx-auto max-h-72 w-full object-contain sm:max-h-96"
                             alt="Preview gambar sampah">

                        {{-- Scan Animation --}}
                        <div id="scanLine"
                             class="pointer-events-none absolute left-0 right-0 top-0 hidden h-1 bg-green-400 shadow-[0_0_15px_rgba(74,222,128,0.9)]">
                        </div>

                        {{-- Kotak deteksi objek (YOLO) --}}
                        <div id="detBox"
                             class="pointer-events-none absolute hidden"
                             style="border:3px solid #22c55e;border-radius:8px;"></div>
                    </div>
                </div>

                {{-- Status AI --}}
                <div id="aiStatusBox"
                     class="mt-4 rounded-2xl border border-green-100 bg-green-50 p-4">

                    <div class="flex items-center gap-3">

                        <div id="aiIcon"
                             class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100">
                            🤖
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium uppercase tracking-wide text-green-600">
                                Status AI
                            </p>

                            <p id="aiStatus"
                               class="mt-0.5 text-sm font-semibold text-green-900">
                                Menunggu analisis...
                            </p>
                        </div>

                        <div id="loadingDots" class="hidden">
                            <div class="flex gap-1">
                                <span class="h-2 w-2 animate-bounce rounded-full bg-green-500"></span>
                                <span class="h-2 w-2 animate-bounce rounded-full bg-green-500 [animation-delay:150ms]"></span>
                                <span class="h-2 w-2 animate-bounce rounded-full bg-green-500 [animation-delay:300ms]"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <button type="submit"
                    id="submitBtn"
                    disabled
                    class="relative mt-6 hidden w-full overflow-hidden rounded-2xl bg-gradient-to-r from-green-700 to-emerald-600 py-4 font-bold text-white shadow-lg shadow-green-100 transition hover:-translate-y-0.5 hover:from-green-800 hover:to-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0">

                <span class="relative flex items-center justify-center gap-2">
                    🔍
                    <span>Lihat Hasil Scan</span>
                </span>
            </button>

            <p class="relative mt-3 text-center text-xs text-gray-400">
                AI akan menganalisis gambar secara otomatis sebelum hasil disimpan.
            </p>
        </form>

        {{-- SIDEBAR TIPS --}}
        <aside class="h-fit space-y-4">

            {{-- Tips --}}
            <div class="rounded-3xl border border-green-100 bg-gradient-to-br from-green-50 to-emerald-50 p-6">

                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-600 text-xl text-white shadow-sm">
                        💡
                    </div>

                    <div>
                        <p class="font-bold text-green-900">
                            Tips Scanning
                        </p>

                        <p class="text-xs text-green-700">
                            Agar hasil AI lebih akurat
                        </p>
                    </div>
                </div>

                <div class="space-y-4">

                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm shadow-sm">
                            ☀️
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-green-900">
                                Pencahayaan cukup
                            </p>
                            <p class="mt-0.5 text-xs leading-relaxed text-green-700">
                                Hindari foto yang terlalu gelap atau terlalu terang.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm shadow-sm">
                            🗑️
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-green-900">
                                Satu jenis sampah
                            </p>
                            <p class="mt-0.5 text-xs leading-relaxed text-green-700">
                                Sebaiknya hanya satu objek sampah dalam satu foto.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm shadow-sm">
                            🔎
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-green-900">
                                Objek terlihat jelas
                            </p>
                            <p class="mt-0.5 text-xs leading-relaxed text-green-700">
                                Dekatkan kamera dan pastikan objek tidak tertutup.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Info AI --}}
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="mb-3 flex items-center gap-2">
                    <span class="text-lg">🤖</span>
                    <p class="font-bold text-gray-900">
                        Cara Kerja AI
                    </p>
                </div>

                <div class="space-y-3 text-xs text-gray-500">

                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 font-bold text-green-700">
                            1
                        </span>
                        <span>Upload atau ambil foto sampah</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 font-bold text-green-700">
                            2
                        </span>
                        <span>AI mendeteksi objek lalu mengenali jenisnya</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 font-bold text-green-700">
                            3
                        </span>
                        <span>Hasil klasifikasi ditampilkan</span>
                    </div>

                </div>
            </div>

        </aside>
    </div>

    {{-- TENSORFLOW --}}
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.3.1/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8/dist/teachablemachine-image.min.js"></script>

    <script>
        const MODEL_URL    = "{{ asset('model/model.json') }}";
        const METADATA_URL = "{{ asset('model/metadata.json') }}";

        const input       = document.getElementById('image');
        const menu        = document.getElementById('menu');
        const cameraBox   = document.getElementById('cameraBox');
        const video       = document.getElementById('video');
        const canvas      = document.getElementById('canvas');
        const preview     = document.getElementById('preview');
        const previewBox  = document.getElementById('previewBox');
        const aiStatus    = document.getElementById('aiStatus');
        const aiIcon      = document.getElementById('aiIcon');
        const loadingDots = document.getElementById('loadingDots');
        const scanLine    = document.getElementById('scanLine');
        const submitBtn   = document.getElementById('submitBtn');
        const detBox      = document.getElementById('detBox');

        // Server deteksi objek YOLO (folder ai-server). Jika tidak aktif, scan memakai gambar penuh.
        const YOLO_URL        = @json(rtrim((string) config('services.yolo.url'), '/'));
        const YOLO_TIMEOUT_MS = 8000;

        let stream = null;
        let tmModel = null;

        async function getModel() {
            if (!tmModel) {
                tmModel = await tmImage.load(MODEL_URL, METADATA_URL);
            }

            return tmModel;
        }

        // Render gambar ke canvas (mengikuti orientasi EXIF) agar koordinat YOLO konsisten
        function toCanvas(img, maxSide = 1280) {
            const k = Math.min(1, maxSide / Math.max(img.naturalWidth, img.naturalHeight));
            const c = document.createElement('canvas');

            c.width  = Math.round(img.naturalWidth * k);
            c.height = Math.round(img.naturalHeight * k);
            c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);

            return c;
        }

        // Tahap 1: YOLO mencari objek. Mengembalikan null jika server mati / tidak ada objek.
        async function detectObject(frame) {
            if (!YOLO_URL) return null;

            const ctrl  = new AbortController();
            const timer = setTimeout(() => ctrl.abort(), YOLO_TIMEOUT_MS);

            try {
                const blob = await new Promise(r => frame.toBlob(r, 'image/jpeg', 0.9));
                const fd   = new FormData();

                fd.append('file', blob, 'scan.jpg');

                const res = await fetch(YOLO_URL + '/api/detect', {
                    method: 'POST',
                    headers: { 'ngrok-skip-browser-warning': '1' },
                    body: fd,
                    signal: ctrl.signal,
                });

                if (!res.ok) return null;

                const data = await res.json();

                return data.detections && data.detections.length
                    ? data.detections[0]
                    : null;

            } catch (err) {
                console.warn('YOLO tidak tersedia, memakai gambar penuh.', err);
                return null;
            } finally {
                clearTimeout(timer);
            }
        }

        // Potong persegi di sekitar objek (Teachable Machine memakai input persegi 224x224)
        function cropSquare(src, box) {
            const [x1, y1, x2, y2] = box;
            const W = src.width, H = src.height;

            const side = Math.min(Math.max(x2 - x1, y2 - y1) * 1.15, W, H);
            const sx   = Math.min(Math.max((x1 + x2) / 2 - side / 2, 0), W - side);
            const sy   = Math.min(Math.max((y1 + y2) / 2 - side / 2, 0), H - side);

            const out = document.createElement('canvas');

            out.width = out.height = 224;
            out.getContext('2d').drawImage(src, sx, sy, side, side, 0, 0, 224, 224);

            return out;
        }

        function showBox(box, W, H) {
            const [x1, y1, x2, y2] = box;
            const cw = preview.clientWidth, ch = preview.clientHeight;
            const k  = Math.min(cw / W, ch / H);
            const ox = (cw - W * k) / 2, oy = (ch - H * k) / 2;

            Object.assign(detBox.style, {
                left:   (ox + x1 * k) + 'px',
                top:    (oy + y1 * k) + 'px',
                width:  ((x2 - x1) * k) + 'px',
                height: ((y2 - y1) * k) + 'px',
            });

            detBox.classList.remove('hidden');
        }

        async function classify() {
            submitBtn.disabled = true;

            document.getElementById('label').value = '';
            document.getElementById('confidence').value = '';
            detBox.classList.add('hidden');

            // Tampilan loading
            aiStatus.textContent = 'AI sedang menganalisis gambar...';
            loadingDots.classList.remove('hidden');
            aiIcon.textContent = '🤖';

            scanLine.classList.remove('hidden');

            // Animasi scan line
            scanLine.animate(
                [
                    { top: '0%' },
                    { top: '100%' },
                    { top: '0%' }
                ],
                {
                    duration: 1800,
                    iterations: 1,
                    easing: 'ease-in-out'
                }
            );

            try {
                const model = await getModel();

                // Tahap 1: deteksi objek (YOLO). Tahap 2: klasifikasi material (Teachable Machine)
                const frame = toCanvas(preview);
                const det   = await detectObject(frame);

                let target = preview; // cadangan: gambar penuh

                if (det) {
                    target = cropSquare(frame, det.box);
                    showBox(det.box, frame.width, frame.height);
                }

                console.info('Mode scan:', det ? 'YOLO + Teachable Machine' : 'Teachable Machine (gambar penuh)');

                const preds = await model.predict(target);

                console.table(
                    preds.map(p => [
                        p.className,
                        (p.probability * 100).toFixed(1)
                    ])
                );

                const best = preds.reduce((a, b) =>
                    a.probability > b.probability ? a : b
                );

                const pct = (best.probability * 100).toFixed(2);

                document.getElementById('label').value = best.className;
                document.getElementById('confidence').value = pct;

                // Hasil berhasil
                aiStatus.textContent =
                    `Terdeteksi: ${best.className} (${pct}%)`;

                aiIcon.textContent = '✅';

                loadingDots.classList.add('hidden');
                scanLine.classList.add('hidden');

                submitBtn.disabled = false;

            } catch (err) {

                console.error(err);

                aiStatus.textContent =
                    'Model AI gagal dimuat. Hasil akan tercatat sebagai tidak dikenali.';

                aiIcon.textContent = '⚠️';

                loadingDots.classList.add('hidden');
                scanLine.classList.add('hidden');

                submitBtn.disabled = false;
            }
        }

        function showPreview(file) {

            preview.onload = classify;

            preview.src = URL.createObjectURL(file);

            previewBox.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
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

            if (input.files[0]) {
                showPreview(input.files[0]);
            }

        });

        document.getElementById('btnCamera').addEventListener('click', async () => {

            if (!navigator.mediaDevices ||
                !navigator.mediaDevices.getUserMedia) {

                input.setAttribute('capture', 'environment');
                input.click();

                return;
            }

            try {

                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    },
                    audio: false,
                });

                video.srcObject = stream;

                menu.classList.add('hidden');
                cameraBox.classList.remove('hidden');

            } catch (err) {

                alert(
                    'Kamera tidak bisa dibuka. Pastikan izin kamera sudah diizinkan di browser.'
                );

            }

        });

        document.getElementById('btnSnap').addEventListener('click', () => {

            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;

            canvas.getContext('2d').drawImage(
                video,
                0,
                0
            );

            canvas.toBlob((blob) => {

                const file = new File(
                    [blob],
                    'kamera.jpg',
                    {
                        type: 'image/jpeg'
                    }
                );

                const dt = new DataTransfer();

                dt.items.add(file);

                input.files = dt.files;

                showPreview(file);

                stopCamera();

            }, 'image/jpeg', 0.9);

        });

        document.getElementById('btnCancel').addEventListener(
            'click',
            stopCamera
        );
    </script>
@endsection