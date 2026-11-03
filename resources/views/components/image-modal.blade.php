<!-- Global Image Modal Viewer -->
<div id="global-image-modal" class="fixed inset-0 z-[100] hidden overflow-hidden bg-slate-950/90 backdrop-blur-md p-4 flex items-center justify-center transition-opacity" onclick="this.classList.add('hidden'); document.body.classList.remove('overflow-hidden');">
    <div class="relative max-w-4xl max-h-[92vh] flex flex-col items-center justify-center" onclick="event.stopPropagation();">
        <!-- Close Button -->
        <button type="button" onclick="document.getElementById('global-image-modal').classList.add('hidden'); document.body.classList.remove('overflow-hidden');" class="absolute -top-12 right-0 p-2 text-white/80 hover:text-white rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all focus:outline-none" title="Tutup">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <!-- Image Content -->
        <img id="global-image-modal-img" src="" alt="Pratinjau Foto Bukti" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10 bg-slate-900">
    </div>
</div>

<script>
if (!window.viewFullImage) {
    window.viewFullImage = function(srcOrElementId) {
        let src = srcOrElementId;
        const el = document.getElementById(srcOrElementId);
        if (el && el.src) {
            src = el.src;
        }
        if (!src) return;
        const modal = document.getElementById('global-image-modal');
        const img = document.getElementById('global-image-modal-img');
        if (modal && img) {
            img.src = src;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    };
}
</script>
