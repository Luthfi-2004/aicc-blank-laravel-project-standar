// resources/js/greensand.js
document.addEventListener('livewire:init', () => {
  /* ========== DATE SYNC (Start/End) ========== */
  function syncDates() {
    const s = document.getElementById('startDate');
    const e = document.getElementById('endDate');
    if (!s || !e) return;

    e.min = s.value || '';
    if (s.value && (!e.value || e.value < s.value)) {
      e.value = s.value;
      e.dispatchEvent(new Event('input',  { bubbles: true }));
      e.dispatchEvent(new Event('change', { bubbles: true }));
    } else if (!s.value) {
      e.removeAttribute('min');
    }
  }
  document.addEventListener('input',  (ev) => { if (ev.target?.id === 'startDate') syncDates(); });
  document.addEventListener('change', (ev) => { if (ev.target?.id === 'startDate') syncDates(); });

  /* ========== FILTER COLLAPSE (BS4 + jQuery) ========== */
  const $ = window.jQuery;
  if (!$) return; // pastikan jQuery sudah diload di components.scripts

  const $col    = $('#filterCollapse');
  const $icon   = $('#filterIcon');
  const $header = $('#filterHeader');

  if (!$col.length) return;

  // Inisialisasi tanpa auto-toggle; status awal ikut Blade
  $col.collapse({ toggle: false });

  let reconcileTimer = null;

  // Ikon plus/min: open => minus, closed => plus
  function setIcon(isOpen) {
    if (!$icon.length) return;
    $icon.removeClass('ri-add-line ri-subtract-line')
         .addClass(isOpen ? 'ri-subtract-line' : 'ri-add-line');
  }

  function getLW() {
    const root = $col.closest('[wire\\:id]');
    if (!root.length || !window.Livewire) return null;
    return window.Livewire.find(root.attr('wire:id'));
  }

  // Tolak klik saat animasi (anti-spam)
  $header.on('click', (e) => {
    if ($col.attr('data-lock') === '1') {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    }
  });

  function lock(on) {
    $col.attr('data-lock', on ? '1' : '0');
    $header.toggleClass('locked', !!on); // opsional: bisa diberi style pointer-events:none
  }

  function reconcileCollapse() {
    // Jangan ganggu saat animasi
    if ($col.attr('data-lock') === '1') return;

    const shouldOpen = $col.attr('data-open') === '1';     // niat dari Livewire
    const lastState  = $col.attr('data-gs-state') === '1'; // state riil terakhir yang diterapkan
    const isShown    = $col.hasClass('show');              // DOM sekarang

    // Render pertama: set ikon & cache sesuai DOM, selesai
    if (!$col.data('gs-boot')) {
      setIcon(isShown);
      $col.attr('data-gs-state', isShown ? '1' : '0');
      $col.data('gs-boot', 1);
      return;
    }

    // Hanya toggle jika niat berubah dari state terakhir (delta-based)
    if (shouldOpen !== lastState) {
      // force reflow biar Bootstrap hitung tinggi akurat
      // eslint-disable-next-line no-unused-expressions
      $col[0].offsetHeight;
      lock(true);
      $col.collapse(shouldOpen ? 'show' : 'hide');
      // Ikon & state akan dipastikan di event 'shown/hidden'
    } else {
      // Jika DOM nyasar (jarang), sinkronkan ikon ke DOM
      if (isShown !== lastState) setIcon(isShown);
    }
  }

  // Lifecycle events: kunci saat animasi, update ikon/state saat selesai
  if (!$col.data('gs-bound')) {
    $col.on('show.bs.collapse',  () => { lock(true);  });
    $col.on('hide.bs.collapse',  () => { lock(true);  });

    $col.on('shown.bs.collapse', () => {
      setIcon(true);
      $col.attr('data-gs-state', '1');
      lock(false);
      const lw = getLW();
      if (lw?.get('filterOpen') !== true) lw.set('filterOpen', true);
    });

    $col.on('hidden.bs.collapse', () => {
      setIcon(false);
      $col.attr('data-gs-state', '0');
      lock(false);
      const lw = getLW();
      if (lw?.get('filterOpen') !== false) lw.set('filterOpen', false);
    });

    $col.data('gs-bound', 1);
  }

  // Debounced reconcile (dipanggil saat Livewire render & navigasi)
  function scheduleReconcile() {
    if (reconcileTimer) clearTimeout(reconcileTimer);
    reconcileTimer = setTimeout(() => {
      syncDates();
      reconcileCollapse();
    }, 80);
  }

  document.addEventListener('DOMContentLoaded',      scheduleReconcile);
  document.addEventListener('livewire:navigated',    scheduleReconcile);
  window.Livewire?.hook?.('message.processed',       scheduleReconcile);


  // ===== Confirm Delete Modal =====
  const MODAL_ID = '#confirmDeleteModal';
  window.addEventListener('gs:confirm-open',  () => { $(MODAL_ID).modal('show'); });
  window.addEventListener('gs:confirm-close', () => { $(MODAL_ID).modal('hide'); });

  // ===== Greensand Form Modal =====
  window.addEventListener('gs:open',  () => { $('#modal-greensand').modal('show'); });
  window.addEventListener('gs:close', () => { $('#modal-greensand').modal('hide'); });
});
/* ===== Handler Export Excel ===== */
window.addEventListener('gs:export', (e) => {
  const d = e.detail;
  const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
  if (url) {
    window.location.href = url;
  }
});

