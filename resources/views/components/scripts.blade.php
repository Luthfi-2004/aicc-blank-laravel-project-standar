@livewireScripts
<!-- JAVASCRIPT -->


<script src={{ asset('assets/libs/jquery/jquery.min.js') }}></script>
<script src={{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}></script>
<script src={{ asset('assets/libs/metismenu/metisMenu.min.js') }}></script>
<script src={{ asset('assets/libs/simplebar/simplebar.min.js') }}></script>
<script src={{ asset('assets/libs/node-waves/waves.min.js') }}></script>

<!-- apexcharts -->
<!-- <script src={{ asset('assets/libs/apexcharts/apexcharts.min.js') }}></script> -->

<!-- jquery.vectormap map -->
<!-- <script src={{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}></script>
<script src={{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}></script> -->

<!-- Required datatable js -->
<script src={{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}></script>
<script src={{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}></script>

<!-- Responsive examples -->
<script src={{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}></script>
<script src={{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}></script>

<!-- <script src={{ asset('assets/js/pages/dashboard.init.js') }}></script> -->

{{-- datatable init js --}}
<!-- <script src={{ asset('assets/js/pages/datatables.init.js') }}></script> -->

<script src={{ asset('assets/libs/toastr/build/toastr.min.js') }}></script>
<script src={{ asset('assets/libs/select2/js/select2.min.js') }}></script>
<script src={{ asset('assets/js/app.js') }}></script>
@vite([
  'resources/js/app.js',
])
<script>
  window.addEventListener('gs:export', (e) => {
    // Livewire v3: detail bisa berupa object atau array args
    const d = e.detail;
    const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
    if (url) window.location.href = url;
  });
</script>

<script>
(function () {
  const sync = () => {
    const s = document.getElementById('startDate');
    const e = document.getElementById('endDate');
    if (!s || !e) return;

    // set batas minimum
    e.min = s.value || '';

    if (!s.value) {
      // kalau start kosong, end biarkan (atau kosongkan – sesuai selera)
      return;
    }

    // AUTO-FILL: end = start bila end kosong atau < start
    if (!e.value || e.value < s.value) {
      e.value = s.value;
      e.dispatchEvent(new Event('input',  { bubbles: true }));
      e.dispatchEvent(new Event('change', { bubbles: true }));
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    const s = document.getElementById('startDate');
    const e = document.getElementById('endDate');
    s?.addEventListener('input',  sync);
    s?.addEventListener('change', sync);
    e?.addEventListener('change', () => {
      // kalau user set end < start, luruskan
      if (s?.value && e.value < s.value) {
        e.value = s.value;
        e.dispatchEvent(new Event('input', { bubbles: true }));
      }
    });
    sync();
  });

  document.addEventListener('livewire:init', () => {
    document.addEventListener('livewire:navigated', sync);
  });
})();
</script>

<script>
  (function () {
    const MODAL_ID = '#confirmDeleteModal';

    window.addEventListener('gs:confirm-open',  () => { $(MODAL_ID).modal('show'); });
    window.addEventListener('gs:confirm-close', () => { $(MODAL_ID).modal('hide'); });
  })();
</script>

<script>
  // Event helper untuk buka/tutup via Livewire
  window.addEventListener('gs:open', () => { $('#modal-greensand').modal('show'); });
  window.addEventListener('gs:close', () => { $('#modal-greensand').modal('hide'); });
</script>
