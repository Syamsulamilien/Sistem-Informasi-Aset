@props([
    'id' => 'photo',
    'name' => 'photo',
    'label' => 'Foto Bukti',
    'currentPhoto' => null,
    'required' => false,
    'removeName' => null,
    'helperText' => 'Ambil langsung via webcam atau pilih file foto dari laptop/HP (Maksimal 5MB).'
])

@php
    $hasCurrent = !empty($currentPhoto) && \Illuminate\Support\Facades\Storage::disk('public')->exists($currentPhoto);
    $initialImgUrl = $hasCurrent ? asset('storage/' . $currentPhoto) : '';
    $jsName = str_replace('-', '_', $id) . 'Capture';
@endphp

<div class="space-y-2" id="{{ $id }}-wrapper">
    <!-- Header: Label & Status Badge -->
    <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @else
                <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
            @endif
        </label>
        <span id="{{ $id }}-badge" class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full {{ $hasCurrent ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
            {{ $hasCurrent ? 'Foto Tersedia' : 'Belum Ada Foto' }}
        </span>
    </div>

    <!-- Hidden Form Inputs -->
    <input type="file" name="{{ $name }}" id="{{ $id }}-file-input" accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden">
    <input type="file" id="{{ $id }}-camera-fallback-input" accept="image/jpeg,image/png,image/webp,image/jpg" capture="environment" class="hidden">
    <input type="hidden" name="{{ $name }}_base64" id="{{ $id }}-base64-input" value="">
    @if($removeName)
        <input type="hidden" name="{{ $removeName }}" id="{{ $id }}-remove-input" value="0">
    @endif

    <!-- Dropzone / Inline Camera / Preview Container Box -->
    <div class="relative w-full border-2 border-dashed border-gray-300 rounded-2xl overflow-hidden bg-gray-50/70 p-4 text-center flex flex-col items-center justify-center min-h-[220px] transition-all" id="{{ $id }}-preview-box">
        
        <!-- State 1: Kosong (Default / Awal) -->
        <div id="{{ $id }}-empty-state" style="{{ $hasCurrent ? 'display: none;' : 'display: flex;' }}" class="flex-col items-center justify-center w-full py-2">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2.5 shadow-sm border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            
            <p class="text-xs text-gray-500 mb-3.5 max-w-xs">{{ $helperText }}</p>

            <!-- 2 Tombol Aksi Simetris -->
            <div class="grid grid-cols-2 gap-2.5 w-full max-w-xs">
                <button type="button" onclick="{{ $jsName }}.startCamera()" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    </svg>
                    <span>Buka Kamera</span>
                </button>

                <button type="button" onclick="document.getElementById('{{ $id }}-file-input').click()" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white hover:bg-gray-100 active:scale-95 text-gray-700 rounded-xl text-xs font-semibold border border-gray-300 shadow-sm transition-all">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Pilih Berkas</span>
                </button>
            </div>
        </div>

        <!-- State 2: Live Viewfinder Kamera (Inline Tepat di Dalam Box) -->
        <div id="{{ $id }}-camera-state" style="display: none;" class="flex-col items-center justify-center w-full">
            <div class="relative w-full max-w-md mx-auto rounded-xl overflow-hidden bg-black shadow-inner flex items-center justify-center border border-gray-800" style="min-height: 250px;">
                <video id="{{ $id }}-video" autoplay playsinline muted style="width: 100%; height: 250px; object-fit: cover; background: #000;"></video>
                
                <!-- Guideline Box -->
                <div class="absolute inset-4 border-2 border-white/50 border-dashed rounded-xl pointer-events-none flex items-center justify-center">
                    <span class="text-[11px] text-white/90 bg-black/60 px-2.5 py-1 rounded-md font-medium">Arahkan kamera ke barang</span>
                </div>

                <!-- Loading Spinner Kamera -->
                <div id="{{ $id }}-camera-loading" class="absolute inset-0 bg-black/90 text-white flex flex-col items-center justify-center p-4">
                    <div class="w-8 h-8 border-3 border-blue-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                    <p class="text-xs font-medium">Menghubungkan ke webcam laptop...</p>
                </div>
            </div>

            <!-- Kontrol Shutter Kamera Inline -->
            <div class="mt-3.5 flex items-center justify-center gap-2.5 w-full">
                <button type="button" onclick="{{ $jsName }}.switchCamera()" class="px-3.5 py-2 bg-white hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold border border-gray-300 shadow-xs flex items-center gap-1.5 transition-all active:scale-95" title="Putar Kamera">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Putar</span>
                </button>

                <button type="button" onclick="{{ $jsName }}.snapPhoto()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-md flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    </svg>
                    <span>Ambil Foto</span>
                </button>

                <button type="button" onclick="{{ $jsName }}.stopCamera()" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors active:scale-95">
                    Batal
                </button>
            </div>
        </div>

        <!-- State 3: Error Kamera (Jika Izin Ditolak / Kamera Sibuk / Switch Fisik Mati) -->
        <div id="{{ $id }}-camera-error" style="display: none;" class="flex-col items-center justify-center w-full py-5 text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2.5 border border-amber-200 shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <p class="text-xs font-bold text-gray-900 mb-1 max-w-md mx-auto" id="{{ $id }}-error-desc">Kamera tidak dapat diakses.</p>
            <p class="text-[11px] text-gray-500 max-w-md mb-4" id="{{ $id }}-error-hint">
                Jika di laptop ASUS: periksa apakah slider penutup fisik di atas webcam tertutup, atau tombol Fn + F10 menonaktifkan kamera. Anda juga bisa langsung memilih file foto dari komputer.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-2.5">
                <button type="button" onclick="{{ $jsName }}.startCamera()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Coba Nyalakan Lagi</span>
                </button>
                <button type="button" onclick="document.getElementById('{{ $id }}-file-input').click()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Pilih Foto dari Komputer</span>
                </button>
                <button type="button" onclick="{{ $jsName }}.resetToEmpty()" class="px-3 py-2 text-gray-500 hover:text-gray-700 text-xs">
                    Batal
                </button>
            </div>
        </div>

        <!-- State 4: Terisi (Preview Foto Hasil Jepretan / Upload) -->
        <div id="{{ $id }}-preview-state" style="{{ $hasCurrent ? 'display: flex;' : 'display: none;' }}" class="flex-col items-center justify-center w-full">
            <div class="relative w-full max-h-60 rounded-xl overflow-hidden border border-gray-200 bg-gray-900 group shadow-xs">
                <img id="{{ $id }}-preview-img" src="{{ $initialImgUrl }}" alt="Foto Bukti" class="w-full h-52 object-contain">
                
                <button type="button" onclick="window.viewFullImage('{{ $id }}-preview-img')" class="absolute top-2 right-2 px-2.5 py-1 rounded-lg bg-black/70 hover:bg-black/90 text-white text-xs font-medium backdrop-blur-xs transition-all flex items-center gap-1 shadow-sm" title="Lihat Penuh">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                    </svg>
                    <span>Perbesar</span>
                </button>
            </div>

            <!-- Toolbar Aksi Foto yang Simetris & Jelas -->
            <div class="mt-3 flex items-center justify-center gap-2 w-full">
                <button type="button" onclick="{{ $jsName }}.startCamera()" class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 border border-blue-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    </svg>
                    Foto Ulang
                </button>

                <button type="button" onclick="document.getElementById('{{ $id }}-file-input').click()" class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 border border-gray-200">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Ganti File
                </button>

                <button type="button" onclick="{{ $jsName }}.resetPhoto()" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 border border-rose-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<canvas id="{{ $id }}-canvas" class="hidden"></canvas>

<script>
(function() {
    const jsName = '{{ $jsName }}';
    const id = '{{ $id }}';

    window[jsName] = {
        stream: null,
        currentFacingMode: 'user',

        isMobileDevice: function() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                   (navigator.maxTouchPoints && navigator.maxTouchPoints > 2 && /Macintosh/.test(navigator.userAgent));
        },

        startCamera: function() {
            const self = this;

            // 1. Jika di HP (Mobile):
            // Langsung panggil kamera bawaan HP via HTML5 capture="environment".
            // Ini 100% bekerja di semua Android & iOS tanpa butuh HTTPS dan tanpa diblokir browser!
            if (this.isMobileDevice()) {
                this.triggerNativeCamera();
                return;
            }

            // 2. Jika diakses via HTTP non-localhost (misal IP server http://192.168.x.x atau domain tanpa SSL):
            // Browser modern (Chrome/Safari) secara ketat memblokir WebRTC getUserMedia di insecure origin.
            const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
            const isSecure = window.isSecureContext || isLocalhost;

            if (!isSecure) {
                self.showCameraError(
                    'Kamera Langsung Memerlukan HTTPS',
                    'Akses webcam langsung di browser membutuhkan protokol aman (HTTPS). Karena saat ini menggunakan alamat HTTP non-localhost (' + window.location.hostname + '), silakan gunakan tombol di bawah untuk memilih file foto langsung dari laptop atau gunakan HP Anda.'
                );
                return;
            }

            // 3. Pada desktop/laptop di localhost / HTTPS: Hubungkan ke webcam
            const emptyState = document.getElementById(id + '-empty-state');
            const previewState = document.getElementById(id + '-preview-state');
            const cameraState = document.getElementById(id + '-camera-state');
            const cameraError = document.getElementById(id + '-camera-error');
            const loading = document.getElementById(id + '-camera-loading');
            const video = document.getElementById(id + '-video');
            const previewBox = document.getElementById(id + '-preview-box');

            if (emptyState) emptyState.style.display = 'none';
            if (previewState) previewState.style.display = 'none';
            if (cameraError) cameraError.style.display = 'none';

            if (cameraState) cameraState.style.display = 'flex';
            if (loading) loading.style.display = 'flex';

            if (previewBox) {
                previewBox.classList.remove('border-dashed', 'border-gray-300');
                previewBox.classList.add('border-solid', 'border-blue-500');
            }

            if (this.stream) {
                this.stopStream();
            }

            // Validasi dukungan mediaDevices
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                self.showCameraError(
                    'Browser Tidak Mengizinkan Akses Kamera',
                    'Browser tidak mengizinkan akses webcam pada protokol ini. Silakan gunakan tombol Pilih Foto dari Komputer.'
                );
                return;
            }

            // Minta akses webcam
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(function(stream) {
                    self.stream = stream;
                    video.muted = true;
                    video.playsInline = true;
                    video.setAttribute('playsinline', '');
                    video.setAttribute('muted', '');
                    video.srcObject = stream;

                    const dismissLoading = function() {
                        if (loading) loading.style.display = 'none';
                    };

                    video.onloadeddata = dismissLoading;
                    video.onloadedmetadata = dismissLoading;
                    video.oncanplay = dismissLoading;

                    video.play().then(dismissLoading).catch(function(e) {
                        console.warn("Video play notice:", e);
                        dismissLoading();
                    });

                    setTimeout(dismissLoading, 400);
                })
                .catch(function(err) {
                    console.error("Camera access error:", err);
                    let title = 'Akses Kamera Tidak Diizinkan';
                    let hint = '';

                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        title = 'Izin Kamera Diblokir di Browser';
                        hint = 'Browser menolak izin akses webcam. Silakan klik ikon pengaturan / gembok di sebelah kiri URL (' + window.location.origin + ') dan ubah izin Kamera menjadi "Allow". Tips laptop: Buka via http://localhost:8000 (bukan 127.0.0.1) agar browser otomatis mengizinkan kamera.';
                    } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                        title = 'Webcam Sedang Digunakan Aplikasi Lain';
                        hint = 'Webcam laptop sedang dipakai oleh aplikasi lain (Zoom, Teams, Meet, atau Windows Camera) atau privacy slider kamera tertutup. Tutup aplikasi lain lalu coba lagi.';
                    } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                        title = 'Webcam Tidak Terdeteksi';
                        hint = 'Periksa apakah ada tombol fisik kamera di keyboard (misal Fn + F10 di laptop ASUS) atau slider penutup webcam.';
                    } else {
                        title = 'Gagal Menghubungkan Kamera';
                        hint = 'Error: [' + err.name + ' - ' + (err.message || 'Tidak diizinkan') + ']. Anda dapat langsung memilih file foto dari komputer.';
                    }

                    self.showCameraError(title, hint);
                });
        },

        showCameraError: function(title, hint) {
            this.stopStream();
            const cameraState = document.getElementById(id + '-camera-state');
            const cameraError = document.getElementById(id + '-camera-error');
            const errDesc = document.getElementById(id + '-error-desc');
            const errHint = document.getElementById(id + '-error-hint');
            const loading = document.getElementById(id + '-camera-loading');
            const previewBox = document.getElementById(id + '-preview-box');

            if (cameraState) cameraState.style.display = 'none';
            if (loading) loading.style.display = 'none';
            if (errDesc) errDesc.innerText = title;
            if (errHint) errHint.innerText = hint;
            if (cameraError) cameraError.style.display = 'flex';

            if (previewBox) {
                previewBox.classList.remove('border-solid', 'border-blue-500', 'border-blue-400');
                previewBox.classList.add('border-dashed', 'border-amber-300');
            }
        },

        triggerNativeCamera: function() {
            const fallbackInput = document.getElementById(id + '-camera-fallback-input');
            if (fallbackInput) {
                fallbackInput.click();
            } else {
                const fileInput = document.getElementById(id + '-file-input');
                if (fileInput) fileInput.click();
            }
        },

        stopCamera: function() {
            this.stopStream();
            const previewImg = document.getElementById(id + '-preview-img');
            if (previewImg && previewImg.src && previewImg.src !== window.location.href && !previewImg.src.endsWith('/')) {
                this.showPreview(previewImg.src);
            } else {
                this.resetToEmpty();
            }
        },

        stopStream: function() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
        },

        switchCamera: function() {
            this.currentFacingMode = (this.currentFacingMode === 'environment') ? 'user' : 'environment';
            this.startCamera();
        },

        compressImage: function(fileOrDataUrl, maxWidth, maxHeight, quality, callback) {
            const img = new Image();
            img.onload = function() {
                let width = img.width;
                let height = img.height;

                // Hitung aspek rasio agar resolusi mengecil secara proporsional (max 960px)
                if (width > height) {
                    if (width > maxWidth) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                // Kompresi ke format JPEG (kualitas 0.65 menghasilkan ~40KB - 90KB)
                const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                callback(compressedDataUrl);
            };

            if (typeof fileOrDataUrl === 'string') {
                img.src = fileOrDataUrl;
            } else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(fileOrDataUrl);
            }
        },

        snapPhoto: function() {
            const video = document.getElementById(id + '-video');
            const canvas = document.getElementById(id + '-canvas');
            if (!video || !this.stream) return;

            const w = video.videoWidth || 1280;
            const h = video.videoHeight || 720;
            
            // Batasi ukuran ke max 960px agar hasil dalam hitungan KB
            let targetW = w;
            let targetH = h;
            const maxDim = 960;
            if (targetW > maxDim || targetH > maxDim) {
                if (targetW > targetH) {
                    targetH = Math.round((targetH * maxDim) / targetW);
                    targetW = maxDim;
                } else {
                    targetW = Math.round((targetW * maxDim) / targetH);
                    targetH = maxDim;
                }
            }

            canvas.width = targetW;
            canvas.height = targetH;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, targetW, targetH);

            const base64Data = canvas.toDataURL('image/jpeg', 0.65);

            document.getElementById(id + '-base64-input').value = base64Data;
            const fileInput = document.getElementById(id + '-file-input');
            if (fileInput) fileInput.value = '';
            const fallback = document.getElementById(id + '-camera-fallback-input');
            if (fallback) fallback.value = '';

            if (document.getElementById(id + '-remove-input')) {
                document.getElementById(id + '-remove-input').value = '0';
            }

            const approxBytes = Math.round((base64Data.length - 22) * 3 / 4);
            const sizeKb = Math.round(approxBytes / 1024);

            this.stopStream();
            this.showPreview(base64Data, sizeKb);
        },

        showPreview: function(srcUrl, sizeKb) {
            const previewImg = document.getElementById(id + '-preview-img');
            const emptyState = document.getElementById(id + '-empty-state');
            const cameraState = document.getElementById(id + '-camera-state');
            const cameraError = document.getElementById(id + '-camera-error');
            const previewState = document.getElementById(id + '-preview-state');
            const badge = document.getElementById(id + '-badge');
            const previewBox = document.getElementById(id + '-preview-box');

            if (emptyState) emptyState.style.display = 'none';
            if (cameraState) cameraState.style.display = 'none';
            if (cameraError) cameraError.style.display = 'none';

            if (previewImg) previewImg.src = srcUrl;
            if (previewState) previewState.style.display = 'flex';
            
            if (previewBox) {
                previewBox.classList.remove('border-dashed', 'border-gray-300', 'border-amber-300');
                previewBox.classList.add('border-solid', 'border-blue-400');
            }

            if (badge) {
                badge.className = 'text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
                badge.innerText = sizeKb ? ('Foto Siap (' + sizeKb + ' KB)') : 'Foto Tersedia';
            }
        },

        resetToEmpty: function() {
            this.stopStream();
            const emptyState = document.getElementById(id + '-empty-state');
            const cameraState = document.getElementById(id + '-camera-state');
            const cameraError = document.getElementById(id + '-camera-error');
            const previewState = document.getElementById(id + '-preview-state');
            const badge = document.getElementById(id + '-badge');
            const previewBox = document.getElementById(id + '-preview-box');

            if (cameraState) cameraState.style.display = 'none';
            if (cameraError) cameraError.style.display = 'none';
            if (previewState) previewState.style.display = 'none';

            if (emptyState) emptyState.style.display = 'flex';

            if (previewBox) {
                previewBox.classList.add('border-dashed', 'border-gray-300');
                previewBox.classList.remove('border-solid', 'border-blue-400', 'border-blue-500', 'border-amber-300');
            }

            if (badge) {
                badge.className = 'text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200';
                badge.innerText = 'Belum Ada Foto';
            }
        },

        resetPhoto: function() {
            const previewImg = document.getElementById(id + '-preview-img');
            if (previewImg) previewImg.src = '';

            const fileInput = document.getElementById(id + '-file-input');
            if (fileInput) fileInput.value = '';

            const fallbackInput = document.getElementById(id + '-camera-fallback-input');
            if (fallbackInput) fallbackInput.value = '';

            const base64Input = document.getElementById(id + '-base64-input');
            if (base64Input) base64Input.value = '';

            if (document.getElementById(id + '-remove-input')) {
                document.getElementById(id + '-remove-input').value = '1';
            }

            this.resetToEmpty();
        }
    };

    function processSelectedFile(file) {
        if (!file) return;
        if (!file.type.match(/^image\/(jpeg|png|webp|jpg)$/i) && !file.name.match(/\.(jpe?g|png|webp)$/i)) {
            alert('Format berkas harus berupa JPG, PNG, atau WebP.');
            return;
        }

        const badge = document.getElementById(id + '-badge');
        if (badge) {
            badge.className = 'text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 animate-pulse';
            badge.innerText = 'Mengompres Foto...';
        }

        // Kompresi otomatis ke resolusi optimal (max 960px) & kualitas 0.65 (~40KB - 90KB)
        window[jsName].compressImage(file, 960, 960, 0.65, function(compressedDataUrl) {
            const base64Input = document.getElementById(id + '-base64-input');
            if (base64Input) {
                base64Input.value = compressedDataUrl;
            }

            // Kosongkan fileInput agar browser tidak mengunggah file raw berukuran besar (MB)
            const fileInput = document.getElementById(id + '-file-input');
            if (fileInput) fileInput.value = '';

            const fallback = document.getElementById(id + '-camera-fallback-input');
            if (fallback) fallback.value = '';

            if (document.getElementById(id + '-remove-input')) {
                document.getElementById(id + '-remove-input').value = '0';
            }

            // Hitung ukuran hasil kompresi dalam KB
            const approxBytes = Math.round((compressedDataUrl.length - 22) * 3 / 4);
            const sizeKb = Math.round(approxBytes / 1024);

            window[jsName].showPreview(compressedDataUrl, sizeKb);
        });
    }

    const fileInput = document.getElementById(id + '-file-input');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (file) {
                processSelectedFile(file);
            }
        });
    }

    const fallbackInput = document.getElementById(id + '-camera-fallback-input');
    if (fallbackInput) {
        fallbackInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (file) {
                try {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    if (fileInput) fileInput.files = dataTransfer.files;
                } catch(e) {
                    console.log("DataTransfer sync fallback", e);
                }
                processSelectedFile(file);
            }
        });
    }
})();
</script>
