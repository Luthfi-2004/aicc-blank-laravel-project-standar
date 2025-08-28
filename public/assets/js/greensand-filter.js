(function () {
  const collapseId = 'filterCollapse';
  const iconId = 'filterIcon';
  const storeKey = 'gs:filter:collapse'; // 'open' | 'closed'

  function setIcon(open) {
    const iconEl = document.getElementById(iconId);
    if (!iconEl) return;
    iconEl.classList.remove('ri-add-line', 'ri-subtract-line');
    iconEl.classList.add(open ? 'ri-subtract-line' : 'ri-add-line');
  }

  function applyState() {
    const collapseEl = document.getElementById(collapseId);
    if (!collapseEl) return;

    // default = OPEN kalau belum pernah di-set
    const saved = localStorage.getItem(storeKey);
    const wantOpen = saved ? saved === 'open' : true;

    // pakai BS4 collapse API (Nazox)
    if (typeof $ !== 'undefined' && $.fn && $.fn.collapse) {
      $(collapseEl).collapse(wantOpen ? 'show' : 'hide');
    } else {
      // fallback
      collapseEl.classList.toggle('show', wantOpen);
      collapseEl.style.display = wantOpen ? 'block' : 'none';
    }
    setIcon(wantOpen);
  }

  function bindEvents() {
    const collapseEl = document.getElementById(collapseId);
    if (!collapseEl || !(typeof $ !== 'undefined' && $.fn && $.fn.collapse)) return;

    $(collapseEl).off('shown.bs.collapse.gs shown.bs.collapse.gs')
      .on('shown.bs.collapse', () => { localStorage.setItem(storeKey, 'open');  setIcon(true); })
      .on('hidden.bs.collapse', () => { localStorage.setItem(storeKey, 'closed'); setIcon(false); });
  }

  function init() { applyState(); bindEvents(); }

  // initial load
  document.addEventListener('DOMContentLoaded', init);

  // re-init tiap Livewire re-render (supaya tetap terbuka)
  document.addEventListener('livewire:init', () => {
    document.addEventListener('livewire:navigated', init); // LW v3
  });
})();
