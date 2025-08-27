// resources/js/greensand.js
document.addEventListener('livewire:init', () => {
  const $modal = () => document.getElementById('modal-greensand');

  // Helper open/close yang support BS4 & BS5
  function openModal() {
    const el = $modal();
    if (!el) return;
    // Bootstrap 4 (jQuery)
    if (window.$ && $.fn.modal) {
      $('#modal-greensand').modal('show');
      return;
    }
    // fallback
    el.classList.add('show');
    el.style.display = 'block';
  }

  function closeModal() {
    const el = $modal();
    if (!el) return;
    if (window.bootstrap && bootstrap.Modal) {
      bootstrap.Modal.getOrCreateInstance(el).hide();
      return;
    }
    if (window.$ && $.fn.modal) {
      $('#modal-greensand').modal('hide');
      return;
    }
    el.classList.remove('show');
    el.style.display = 'none';
  }

  Livewire.on('gs:open',  () => openModal());
  Livewire.on('gs:close', () => closeModal());
  Livewire.on('gs:toast', (payload = {}) => {
    const { type = 'success', text = 'OK' } = payload;
    if (window.toastr) toastr[type](text);
    else alert(text);
  });

  // (opsional) konfirmasi hapus terpusat, panggil dari PHP: $this->dispatch('gs:confirm-delete', id: 123)
  Livewire.on('gs:confirm-delete', ({ id }) => {
    if (confirm('Hapus data ini?')) Livewire.dispatch('gs:do-delete', { id });
  });
});
