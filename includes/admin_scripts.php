<!-- Admin Dashboard Scripts -->
<!-- Lucide Icons Init -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    
    // ── Sidebar Toggle ──────────────────────────────────
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const collapseBtn = document.getElementById('sidebar-collapse-btn');
    const expandBtn = document.getElementById('sidebar-expand-btn');
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const collapsedToolbar = document.getElementById('collapsed-toolbar');
    
    function toggleSidebar() {
        if (!sidebar) return;
        
        // Desktop toggle
        if (window.innerWidth >= 1024) {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-0');
            sidebar.classList.toggle('overflow-hidden');
            
            const isCollapsed = sidebar.classList.contains('w-0');
            localStorage.setItem('sidebar_collapsed', isCollapsed);
            
            if (collapsedToolbar) {
                if (isCollapsed) {
                    collapsedToolbar.classList.replace('toolbar-hidden', 'toolbar-visible');
                } else {
                    collapsedToolbar.classList.replace('toolbar-visible', 'toolbar-hidden');
                }
            }
        }
    }
    
    function toggleMobileSidebar() {
        if (!sidebar) return;
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.toggle('translate-x-0');
        if (overlay) overlay.classList.toggle('hidden');
    }
    
    // Bind events
    if (collapseBtn) collapseBtn.addEventListener('click', toggleSidebar);
    if (expandBtn) expandBtn.addEventListener('click', toggleSidebar);
    if (mobileBtn) mobileBtn.addEventListener('click', toggleMobileSidebar);
    if (overlay) overlay.addEventListener('click', toggleMobileSidebar);
    
    // Restore sidebar state
    if (window.innerWidth >= 1024 && localStorage.getItem('sidebar_collapsed') === 'true') {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-0', 'overflow-hidden');
        if (collapsedToolbar) {
            collapsedToolbar.classList.replace('toolbar-hidden', 'toolbar-visible');
        }
    }
    
    // ── Alpine Watch for Lucide ─────────────────────────
    // Re-init icons when Alpine updates the DOM
    const observer = new MutationObserver(() => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>
