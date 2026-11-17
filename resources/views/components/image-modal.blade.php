<!-- Global Image Modal Viewer -->
<div id="global-image-modal" style="z-index: 999999; display: none;" class="fixed inset-0 overflow-hidden bg-slate-950/90 backdrop-blur-md p-4 sm:p-6 flex items-center justify-center transition-all" onclick="window.closeFullImage();">
    <!-- Floating Close Button -->
    <button type="button" onclick="window.closeFullImage();" class="fixed top-4 right-4 sm:top-6 sm:right-6 p-2.5 sm:p-3 text-white/90 hover:text-white rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md transition-all shadow-xl border border-white/20 focus:outline-none flex items-center gap-2 group cursor-pointer" style="z-index: 1000000;" title="Tutup Pratinjau (Esc)">
        <span class="text-xs font-semibold hidden sm:inline-block text-white/80 group-hover:text-white">Tutup</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    
    <!-- Image Content -->
    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center" onclick="event.stopPropagation();">
        <img id="global-image-modal-img" src="" alt="Pratinjau Foto Bukti" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10 bg-slate-900 select-none">
    </div>
</div>

<script>
(function() {
    window.closeFullImage = function() {
        const modal = document.getElementById('global-image-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
        }
    };

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
            modal.style.display = 'flex';
            document.body.classList.add('overflow-hidden');
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeFullImage();
        }
    });
})();
</script>
