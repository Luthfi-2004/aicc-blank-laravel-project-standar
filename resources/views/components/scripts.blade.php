@livewireScripts

{{-- ========== CORE JS (jQuery wajib sebelum plugin lain) ========== --}}
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

{{-- ========== DATATABLES (CSS + JS) ========== --}}
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

{{-- ========== PLUGINS LAIN (opsional) ========== --}}
<script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

{{-- ========== APP CORE ========== --}}
<script src="{{ asset('assets/js/app.js') }}"></script>



{{-- ==================== Custom Events ==================== --}}
<script>
  // Export Handler
  window.addEventListener('gs:export', (e) => {
    const d = e.detail;
    const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
    if (url) window.location.href = url;
  });
</script>

<script>
  // Sync StartDate & EndDate
  (function () {
    const sync = () => {
      const s = document.getElementById('startDate');
      const e = document.getElementById('endDate');
      if (!s || !e) return;
      e.min = s.value || '';
      if (s.value && (!e.value || e.value < s.value)) {
        e.value = s.value;
        e.dispatchEvent(new Event('input',  { bubbles: true }));
        e.dispatchEvent(new Event('change', { bubbles: true }));
      }
    };
    document.addEventListener('DOMContentLoaded', sync);
    document.addEventListener('livewire:init', () => {
      document.addEventListener('livewire:navigated', sync);
      Livewire.hook('message.processed', sync);
    });
  })();
</script>

<script>
  // Confirm Delete Modal (Bootstrap 4 jQuery)
  (function () {
    const MODAL_ID = '#confirmDeleteModal';
    window.addEventListener('gs:confirm-open',  () => { $(MODAL_ID).modal('show'); });
    window.addEventListener('gs:confirm-close', () => { $(MODAL_ID).modal('hide'); });
  })();
</script>

<script>
  // Greensand Form Modal (Bootstrap 4 jQuery)
  window.addEventListener('gs:open',  () => { $('#modal-greensand').modal('show'); });
  window.addEventListener('gs:close', () => { $('#modal-greensand').modal('hide'); });
</script>
