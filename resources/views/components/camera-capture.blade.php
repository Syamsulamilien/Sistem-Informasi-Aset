@props([
    'id' => 'photo',
    'name' => 'photo',
    'label' => 'Foto Bukti',
    'currentPhoto' => null,
    'required' => false,
    'removeName' => null,
    'helperText' => 'Ambil langsung via kamera atau pilih gambar dari galeri (Maksimal 5MB).'
])

@php
    $hasCurrent = !empty($currentPhoto) && \Illuminate\Support\Facades\Storage::disk('public')->exists($currentPhoto);
    $initialImgUrl = $hasCurrent ? asset('storage/' . $currentPhoto) : '';
@endphp

<div class="space-y-2" id="{{ $id }}-wrapper">
    <!-- Header: Label -->
    <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @else
                <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
            @endif
        </label>
        <span id="{{ $id }}-badge" class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $hasCurrent ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
            {{ $hasCurrent ? 'Foto Tersedia' : 'Belum Ada Foto' }}
        </span>
    </div>

    <!-- Hidden Form Inputs -->
    <input type="file" name="{{ $name }}" id="{{ $id }}-file-input" accept="image/jpeg,image/png,image/webp" class="hidden">
    <input type="hidden" name="{{ $name }}_base64" id="{{ $id }}-base64-input" value="">
    @if($removeName)
        <input type="hidden" name="{{ $removeName }}" id="{{ $id }}-remove-input" value="0">
    @endif

    <!-- Dropzone / Preview Box -->
    <div class="relative w-full border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-gray-50/60 hover:bg-gray-50 hover:border-blue-400 transition-colors p-4 text-center flex flex-col items-center justify-center min-h-[190px]" id="{{ $id }}-preview-box">
        
        <!-- State 1: Kosong (Belum ada foto) -->
        <div id="{{ $id }}-empty-state" class="{{ $hasCurrent ? 'hidden' : 'flex' }} flex-col items-center justify-center w-full py-2">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2.5 shadow-sm border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            
            <p class="text-xs text-gray-500 mb-3 max-w-xs">{{ $helperText }}</p>

            <!-- 2 Tombol Aksi Simetris -->
            <div class="grid grid-cols-2 gap-2 w-full max-w-xs">
                <button type="button" onclick="{{ $id }}Capture.openCameraModal()" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    </svg>
                    <span>Buka Kamera</span>
                </button>

                <button type="button" onclick="document.getElementById('{{ $id }}-file-input').click()" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold border border-gray-300 shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Pilih Berkas</span>
                </button>
            </div>
        </div>

        <!-- State 2: Terisi (Preview Foto) -->
        <div id="{{ $id }}-preview-state" class="{{ $hasCurrent ? 'flex' : 'hidden' }} flex-col items-center justify-center w-full">
            <div class="relative w-full max-h-56 rounded-lg overflow-hidden border border-gray-200 bg-gray-900 group">
                <img id="{{ $id }}-preview-img" src="{{ $initialImgUrl }}" alt="Foto Bukti" class="w-full h-48 object-contain">
                
                <button type="button" onclick="window.viewFullImage('{{ $id }}-preview-img')" class="absolute top-2 right-2 px-2.5 py-1 rounded-md bg-black/70 hover:bg-black/90 text-white text-xs font-medium backdrop-blur-sm transition-all flex items-center gap-1 shadow-sm" title="Lihat Penuh">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                    </svg>
                    <span>Perbesar</span>
                </button>
            </div>

            <!-- Toolbar Ganti / Hapus yang Simetris -->
            <div class="mt-3 flex items-center justify-center gap-2 w-full">
                <button type="button" onclick="{{ $id }}Capture.openCameraModal()" class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 border border-blue-200">
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

                <button type="button" onclick="{{ $id }}Capture.resetPhoto()" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 border border-rose-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pop-up Live Webcam / Camera -->
<div id="{{ $id }}-camera-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 backdrop-blur-sm p-4 flex items-center justify-center transition-all">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200 flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                </svg>
                <h3 class="text-sm font-bold text-gray-900">{{ $label }}</h3>
            </div>
            
            <button type="button" onclick="{{ $id }}Capture.closeCameraModal()" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors" title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Viewfinder Kamera -->
        <div class="relative bg-black flex items-center justify-center overflow-hidden aspect-[4/3] w-full">
            <video id="{{ $id }}-video" autoplay playsinline muted class="w-full h-full object-cover"></video>
            
            <!-- Guideline Overlay -->
            <div class="absolute inset-5 border-2 border-white/40 border-dashed rounded-xl pointer-events-none flex items-center justify-center">
                <span class="text-[11px] text-white/70 px-2 py-0.5 bg-black/50 rounded-md font-medium">Arahkan kamera ke barang</span>
            </div>

            <!-- Loading Overlay -->
            <div id="{{ $id }}-camera-loading" class="absolute inset-0 bg-black/90 text-white flex flex-col items-center justify-center p-4 text-center">
                <div class="w-8 h-8 border-3 border-blue-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                <p class="text-xs font-medium">Menghubungkan ke kamera...</p>
            </div>

            <!-- Error Overlay -->
            <div id="{{ $id }}-camera-error" class="hidden absolute inset-0 bg-black/95 text-white flex flex-col items-center justify-center p-6 text-center">
                <svg class="w-10 h-10 text-rose-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-xs font-semibold text-rose-300" id="{{ $id }}-error-desc">Kamera tidak dapat diakses.</p>
                <button type="button" onclick="{{ $id }}Capture.closeCameraModal()" class="mt-3 px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-white text-xs font-semibold rounded-lg">
                    Tutup & Pilih File
                </button>
            </div>
        </div>

        <!-- Modal Controls: Shutter Bar -->
        <div class="p-4 bg-white border-t border-gray-200 flex items-center justify-between">
            <button type="button" id="{{ $id }}-flip-btn" onclick="{{ $id }}Capture.switchCamera()" class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 border border-gray-200 transition-colors flex items-center gap-1 text-xs font-medium" title="Ganti Kamera Depan/Belakang">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Putar</span>
            </button>

            <!-- Shutter Button -->
            <button type="button" id="{{ $id }}-shutter-btn" onclick="{{ $id }}Capture.snapPhoto()" class="w-14 h-14 rounded-full bg-blue-600 hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center shadow-lg border-4 border-blue-100" title="Ambil Foto">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                </svg>
            </button>

            <button type="button" onclick="{{ $id }}Capture.closeCameraModal()" class="px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 text-xs font-medium">
                Batal
            </button>
        </div>
    </div>
</div>

<canvas id="{{ $id }}-canvas" class="hidden"></canvas>

<script>
window.{{ $id }}Capture = {
    stream: null,
    currentFacingMode: 'environment',

    openCameraModal: function() {
        const modal = document.getElementById('{{ $id }}-camera-modal');
        const loading = document.getElementById('{{ $id }}-camera-loading');
        const error = document.getElementById('{{ $id }}-camera-error');
        
        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        error.classList.add('hidden');
        document.body.classList.add('overflow-hidden');

        this.startStream();
    },

    closeCameraModal: function() {
        const modal = document.getElementById('{{ $id }}-camera-modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        this.stopStream();
    },

    startStream: function() {
        const video = document.getElementById('{{ $id }}-video');
        const loading = document.getElementById('{{ $id }}-camera-loading');
        const error = document.getElementById('{{ $id }}-camera-error');
        const self = this;

        if (this.stream) {
            this.stopStream();
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
            document.getElementById('{{ $id }}-error-desc').innerText = 'Browser tidak mendukung akses kamera secara langsung.';
            return;
        }

        const constraints = {
            video: {
                facingMode: { ideal: self.currentFacingMode },
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                self.stream = stream;
                video.srcObject = stream;
                video.play();
                loading.classList.add('hidden');
                error.classList.add('hidden');
            })
            .catch(function(err) {
                console.error("Camera access error:", err);
                loading.classList.add('hidden');
                error.classList.remove('hidden');
                let msg = 'Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.';
                if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                    msg = 'Tidak ada perangkat kamera yang terdeteksi.';
                }
                document.getElementById('{{ $id }}-error-desc').innerText = msg;
            });
    },

    stopStream: function() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
    },

    switchCamera: function() {
        this.currentFacingMode = (this.currentFacingMode === 'environment') ? 'user' : 'environment';
        this.startStream();
    },

    snapPhoto: function() {
        const video = document.getElementById('{{ $id }}-video');
        const canvas = document.getElementById('{{ $id }}-canvas');
        if (!video || !this.stream) return;

        const w = video.videoWidth || 1280;
        const h = video.videoHeight || 720;
        canvas.width = w;
        canvas.height = h;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, w, h);

        const base64Data = canvas.toDataURL('image/jpeg', 0.85);

        document.getElementById('{{ $id }}-base64-input').value = base64Data;
        document.getElementById('{{ $id }}-file-input').value = '';

        if (document.getElementById('{{ $id }}-remove-input')) {
            document.getElementById('{{ $id }}-remove-input').value = '0';
        }

        this.showPreview(base64Data);
        this.closeCameraModal();
    },

    showPreview: function(srcUrl) {
        const previewImg = document.getElementById('{{ $id }}-preview-img');
        const emptyState = document.getElementById('{{ $id }}-empty-state');
        const previewState = document.getElementById('{{ $id }}-preview-state');
        const badge = document.getElementById('{{ $id }}-badge');
        const previewBox = document.getElementById('{{ $id }}-preview-box');

        previewImg.src = srcUrl;
        emptyState.classList.add('hidden');
        previewState.classList.remove('hidden');
        previewState.classList.add('flex');
        
        previewBox.classList.remove('border-dashed', 'border-gray-300');
        previewBox.classList.add('border-solid', 'border-blue-400');

        badge.className = 'text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
        badge.innerText = 'Foto Tersedia';
    },

    resetPhoto: function() {
        const previewImg = document.getElementById('{{ $id }}-preview-img');
        const emptyState = document.getElementById('{{ $id }}-empty-state');
        const previewState = document.getElementById('{{ $id }}-preview-state');
        const badge = document.getElementById('{{ $id }}-badge');
        const previewBox = document.getElementById('{{ $id }}-preview-box');

        previewImg.src = '';
        emptyState.classList.remove('hidden');
        emptyState.classList.add('flex');
        previewState.classList.add('hidden');
        previewState.classList.remove('flex');

        previewBox.classList.add('border-dashed', 'border-gray-300');
        previewBox.classList.remove('border-solid', 'border-blue-400');

        document.getElementById('{{ $id }}-file-input').value = '';
        document.getElementById('{{ $id }}-base64-input').value = '';

        if (document.getElementById('{{ $id }}-remove-input')) {
            document.getElementById('{{ $id }}-remove-input').value = '1';
        }

        badge.className = 'text-[11px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200';
        badge.innerText = 'Belum Ada Foto';
    }
};

document.getElementById('{{ $id }}-file-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
            alert('Format berkas harus berupa JPG, PNG, atau WebP.');
            this.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran berkas melebihi batas maksimum 5MB.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('{{ $id }}-base64-input').value = '';
            if (document.getElementById('{{ $id }}-remove-input')) {
                document.getElementById('{{ $id }}-remove-input').value = '0';
            }
            window.{{ $id }}Capture.showPreview(evt.target.result);
        };
        reader.readAsDataURL(file);
    }
});
</script>
