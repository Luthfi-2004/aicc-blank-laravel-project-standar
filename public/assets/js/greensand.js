// resources/js/greensand.js

/* =======================================================
   Livewire bootstrap: sync date & filter collapse control
======================================================= */
document.addEventListener("livewire:init", () => {
    /* ========== DATE SYNC (Start/End) ========== */
    function syncDates() {
        const s = document.getElementById("startDate");
        const e = document.getElementById("endDate");
        if (!s || !e) return;

        e.min = s.value || "";
        if (s.value && (!e.value || e.value < s.value)) {
            e.value = s.value;
            e.dispatchEvent(new Event("input", { bubbles: true }));
            e.dispatchEvent(new Event("change", { bubbles: true }));
        } else if (!s.value) {
            e.removeAttribute("min");
        }
    }
    document.addEventListener("input", (ev) => {
        if (ev.target?.id === "startDate") syncDates();
    });
    document.addEventListener("change", (ev) => {
        if (ev.target?.id === "startDate") syncDates();
    });

    /* ========== FILTER COLLAPSE (BS4 + jQuery) ========== */
    const $ = window.jQuery;
    if (!$) return;

    const $col = $("#filterCollapse");
    const $icon = $("#filterIcon");
    const $header = $("#filterHeader");

    if ($col.length) {
        // inisialisasi tanpa auto-toggle
        $col.collapse({ toggle: false });

        let reconcileTimer = null;

        function setIcon(isOpen) {
            if (!$icon.length) return;
            $icon
                .removeClass("ri-add-line ri-subtract-line")
                .addClass(isOpen ? "ri-subtract-line" : "ri-add-line");
        }

        function getLW() {
            const root = $col.closest("[wire\\:id]");
            if (!root.length || !window.Livewire) return null;
            return window.Livewire.find(root.attr("wire:id"));
        }

        // guard klik ketika sedang lock
        $header.on("click", (e) => {
            if ($col.attr("data-lock") === "1") {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });

        function lock(on) {
            $col.attr("data-lock", on ? "1" : "0");
            $header.toggleClass("locked", !!on);
        }

        function reconcileCollapse() {
            if ($col.attr("data-lock") === "1") return;

            const shouldOpen = $col.attr("data-open") === "1";
            const lastState = $col.attr("data-gs-state") === "1";
            const isShown = $col.hasClass("show");

            if (!$col.data("gs-boot")) {
                setIcon(isShown);
                $col.attr("data-gs-state", isShown ? "1" : "0");
                $col.data("gs-boot", 1);
                return;
            }

            if (shouldOpen !== lastState) {
                // force reflow
                // eslint-disable-next-line no-unused-expressions
                $col[0].offsetHeight;
                lock(true);
                $col.collapse(shouldOpen ? "show" : "hide");
            } else if (isShown !== lastState) {
                setIcon(isShown);
            }
        }

        function scheduleReconcile() {
            if (reconcileTimer) clearTimeout(reconcileTimer);
            reconcileTimer = setTimeout(() => {
                syncDates();
                reconcileCollapse();
            }, 80);
        }

        if (!$col.data("gs-bound")) {
            $col.on("show.bs.collapse", () => lock(true));
            $col.on("hide.bs.collapse", () => lock(true));

            $col.on("shown.bs.collapse", () => {
                setIcon(true);
                $col.attr("data-gs-state", "1");
                lock(false);
                const lw = getLW();
                if (lw?.get("filterOpen") !== true) lw.set("filterOpen", true);
            });

            $col.on("hidden.bs.collapse", () => {
                setIcon(false);
                $col.attr("data-gs-state", "0");
                lock(false);
                const lw = getLW();
                if (lw?.get("filterOpen") !== false)
                    lw.set("filterOpen", false);
            });

            $col.data("gs-bound", 1);
        }

        document.addEventListener("DOMContentLoaded", scheduleReconcile);
        document.addEventListener("livewire:navigated", scheduleReconcile);
        window.Livewire?.hook?.("message.processed", scheduleReconcile);
    }

    /* ========== MODAL OPEN/CLOSE (generic) ========== */
    window.addEventListener("gs:open", () => {
        $("#modal-greensand").modal("show");
    });
    window.addEventListener("gs:close", () => {
        $("#modal-greensand").modal("hide");
    });
});

/* =======================================================
   Export trigger (download link diarahkan)
======================================================= */
window.addEventListener("gs:export", (e) => {
    const d = e.detail;
    const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
    if (url) window.location.href = url;
});

/* =======================================================
   DataTables (MODE B: client-side) - clean & predictable
   - stateSave per tab (mm1/mm2/all) via localStorage
   - destroy(true) ONLY tepat sebelum re-render, lalu init
======================================================= */
(function attachDataTablesClientSide() {
    const $ = window.jQuery;
    if (!$ || !$.fn || !$.fn.DataTable) return;

    const TABLE_ID = "#datatable1";

    function isDT() {
        try {
            return $.fn.DataTable.isDataTable(TABLE_ID);
        } catch (_) {
            return false;
        }
    }

    function destroyDT() {
        try {
            if (isDT()) {
                $(TABLE_ID).DataTable().clear().destroy(true); // buang wrapper lama
            }
        } catch (_) {}
        // bersihkan width inline biar kolom tidak ngunci
        $(TABLE_ID).find("thead th, tbody td").css("width", "");
        // tandai status
        $(TABLE_ID).data("dt-initialized", false);
    }

    function isReady() {
        const t = document.querySelector(TABLE_ID);
        // thead & tbody harus sudah ada (tbody boleh kosong)
        return !!(t && t.tHead && t.tBodies && t.tBodies[0]);
    }

    function initDT() {
        if (!isReady()) return;
        if (isDT()) return;
        if ($(TABLE_ID).data("dt-initialized") === true) return;

        const $tbl = $(TABLE_ID);
        const tab = ($tbl.data("tab") || "all").toString(); // mm1|mm2|all
        const storageKey = `dt:datatable1:${tab}`;

        const opts = {
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: false,
            scrollX: true,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,

            // state per-tab
            stateSave: true,
            stateDuration: -1,
            stateSaveCallback: function (_s, data) {
                try {
                    localStorage.setItem(storageKey, JSON.stringify(data));
                } catch (e) {}
            },
            stateLoadCallback: function () {
                try {
                    const raw = localStorage.getItem(storageKey);
                    return raw ? JSON.parse(raw) : null;
                } catch (e) {
                    return null;
                }
            },

            
        };

        if ($.fn.dataTable && $.fn.dataTable.Buttons) {
            opts.dom =
                "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                "tr<'row'<'col-sm-5'i><'col-sm-7'p>>";
            opts.buttons = [
                { extend: "copyHtml5", titleAttr: "Copy" },
                { extend: "csvHtml5", titleAttr: "CSV" },
                { extend: "excelHtml5", titleAttr: "Excel" },
                { extend: "print", titleAttr: "Print" },
            ];
        } else {
            opts.dom =
                "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "tr<'row'<'col-sm-5'i><'col-sm-7'p>>";
        }

        $tbl.DataTable(opts);
        $tbl.data("dt-initialized", true);
    }

    // ===== siklus yang jelas: destroy -> init =====

    // 1) DOM siap pertama kali
    document.addEventListener("DOMContentLoaded", () => {
        // jangan destroy di sini — cukup init
        setTimeout(initDT, 120);
    });

    // 2) Livewire v2: setelah DOM selesai di-patch → init
    window.Livewire?.hook?.("message.processed", () => {
        // sebelum re-render Livewire, instance lama biasanya sudah “copot”
        // untuk aman, destroy dulu lalu init
        destroyDT();
        setTimeout(initDT, 80);
    });

    // 3) Livewire v3 (morph lifecycle)
    if (window.Livewire?.hook) {
        // sebelum node lama dilepas (kalau tabel akan diganti), destroy
        window.Livewire.hook("morph.removing", (el) => {
            if (
                el &&
                (el.matches?.(TABLE_ID) || el.querySelector?.(TABLE_ID))
            ) {
                destroyDT();
            }
        });
        // setelah node baru masuk/diupdate → init
        window.Livewire.hook("morph.added", () => setTimeout(initDT, 80));
        window.Livewire.hook("morph.updated", () => setTimeout(initDT, 80));
    }

    // 4) Navigasi Livewire (jika dipakai)
    document.addEventListener("livewire:navigated", () => {
        destroyDT();
        setTimeout(initDT, 100);
    });

    // 5) Event manual (opsional)
    document.addEventListener("gs:before-redraw", () => destroyDT());
    document.addEventListener("gs:after-redraw", () => setTimeout(initDT, 80));
})();

/* =======================================================
   DELETE FLOW (tanpa alert Edge)
   - Klik tombol .js-delete => buka modal konfirmasi
   - Klik YA => panggil Livewire method `delete(id)`
======================================================= */
(function handleDeleteFlow() {
    const $ = window.jQuery;
    if (!$) return;

    const MODAL_ID = "#confirmDeleteModal";
    const BTN_YES = "#confirmDeleteYes";
    const MODAL_TITLE = "#confirmDeleteTitle";
    const MODAL_TEXT = "#confirmDeleteText";

    let pendingId = null;

    // Delegasi klik tombol delete
    document.addEventListener("click", (ev) => {
        const btn = ev.target.closest(".js-delete");
        if (!btn) return;
        ev.preventDefault();

        pendingId = btn.getAttribute("data-id");
        const label = btn.getAttribute("data-label") || `ID ${pendingId}`;

        // set konten modal
        const $title = document.querySelector(MODAL_TITLE);
        const $text = document.querySelector(MODAL_TEXT);
        if ($title) $title.textContent = "Konfirmasi Hapus";
        if ($text)
            $text.textContent = `Yakin ingin menghapus data ${label}? Tindakan ini tidak dapat dibatalkan.`;

        $(MODAL_ID).modal("show");
    });

    // Klik tombol YA pada modal
    document.addEventListener("click", (ev) => {
        const yes = ev.target.closest(BTN_YES);
        if (!yes) return;

        if (!pendingId) return;

        // Temukan komponen Livewire terdekat dari tabel
        const table = document.querySelector("#datatable1");
        let lw = null;
        if (table) {
            const root = table.closest("[wire\\:id]");
            if (root && window.Livewire) {
                lw = window.Livewire.find(root.getAttribute("wire:id"));
            }
        }

        // Panggil method delete di server
        if (lw?.call) {
            lw.call("delete", parseInt(pendingId, 10));
        }

        // reset pending id
        pendingId = null;

        // lepas fokus dari tombol sebelum modal ditutup
        if (document.activeElement) {
            document.activeElement.blur();
        }

        // tutup modal via bootstrap API
        $(MODAL_ID).modal("hide");
    });

    // Jika modal ditutup, reset pending id
    $(MODAL_ID).on("hidden.bs.modal", () => {
        pendingId = null;
        // pastikan fokus kembali ke body
        document.body.focus();
    });
})();
