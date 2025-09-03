// resources/js/greensand.js

// Livewire init: sync start/end date, control filter collapse (with jQuery), and modal open/close
document.addEventListener("livewire:init", () => {
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

    const $ = window.jQuery;
    if (!$) return;

    const $col = $("#filterCollapse");
    const $icon = $("#filterIcon");
    const $header = $("#filterHeader");

    if ($col.length) {
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

    window.addEventListener("gs:open", () => {
        $("#modal-greensand").modal("show");
    });
    window.addEventListener("gs:close", () => {
        $("#modal-greensand").modal("hide");
    });
});

// Export trigger: navigate to provided download URL
window.addEventListener("gs:export", (e) => {
    const d = e.detail;
    const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
    if (url) window.location.href = url;
});

// DataTables client-side: init/destroy with state per tab and Livewire/morph hooks
(function attachDataTablesClientSide() {
    const $ = window.jQuery;
    if (!$ || !$.fn || !$.fn.DataTable) return;

    const TABLE_ID = "#datatable1";
    const INIT_FLAG = "__gs_dt_inited";
    const $tbl = () => $(TABLE_ID);
    const el = () => document.querySelector(TABLE_ID);

    const isDT = () => {
        const n = el();
        if (!n) return false;
        try {
            return $.fn.DataTable.isDataTable(n);
        } catch {
            return false;
        }
    };

    function sanitizeWrappers() {
        const n = el();
        if (!n) return;
        document.querySelectorAll(".dataTables_wrapper").forEach((w) => {
            if (!w.contains(n)) {
                try {
                    w.remove();
                } catch {}
            }
        });
        if (!isDT() && $(n).closest(".dataTables_wrapper").length) {
            try {
                $(n).DataTable().destroy(true);
            } catch {}
        }
    }

    function destroyDT() {
        const n = el();
        if (!n) return;
        try {
            if (isDT()) $(n).DataTable().clear().destroy(true);
        } catch {}
        $(n).find("thead th, tbody td").css("width", "");
        $tbl().data(INIT_FLAG, false);
        sanitizeWrappers();
    }

    function isReady() {
        const n = el();
        return !!(n && n.tHead && n.tBodies && n.tBodies[0]);
    }

    function buildOptions() {
        const tab = ($tbl().data("tab") || "all").toString();
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
            stateSave: true,
            stateDuration: -1,
            stateSaveCallback: (_s, data) => {
                try {
                    localStorage.setItem(storageKey, JSON.stringify(data));
                } catch {}
            },
            stateLoadCallback: () => {
                try {
                    return JSON.parse(
                        localStorage.getItem(storageKey) || "null"
                    );
                } catch {
                    return null;
                }
            },
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_–_END_ of _TOTAL_ entries",
                infoEmpty: "No data available",
                zeroRecords: "No matching records found",
                paginate: { first: "<<", last: ">>", next: ">", previous: "<" },
                processing: "Processing...",
            },
        };
        if ($.fn.dataTable && $.fn.dataTable.Buttons) {
            opts.dom =
                "<'row'<'col-sm-6'B><'col-sm-6'f>>tr<'row'<'col-sm-5'i><'col-sm-7'p>>";
            opts.buttons = [
                { extend: "copyHtml5", titleAttr: "Copy" },
                { extend: "csvHtml5", titleAttr: "CSV" },
                { extend: "excelHtml5", titleAttr: "Excel" },
                { extend: "print", titleAttr: "Print" },
            ];
        } else {
            opts.dom =
                "<'row'<'col-sm-6'l><'col-sm-6'f>>tr<'row'<'col-sm-5'i><'col-sm-7'p>>";
        }
        return opts;
    }

    function initDT(retry = 0) {
        const n = el();
        if (!n) return;
        if (isDT() || $tbl().data(INIT_FLAG) === true) return;
        if (!isReady()) {
            if (retry < 12) setTimeout(() => initDT(retry + 1), 60);
            return;
        }
        sanitizeWrappers();
        $(n).DataTable(buildOptions());
        $tbl().data(INIT_FLAG, true);
    }

    document.addEventListener("DOMContentLoaded", () =>
        setTimeout(() => initDT(0), 100)
    );

    if (window.Livewire?.hook) {
        window.Livewire.hook("message.sent", () => destroyDT());
        window.Livewire.hook("message.processed", () =>
            Promise.resolve().then(() => requestAnimationFrame(() => initDT(0)))
        );
        window.Livewire.hook("morph.removing", (node) => {
            const n = el();
            if (!n) return;
            if (node === n || node?.querySelector?.(TABLE_ID)) destroyDT();
        });
        window.Livewire.hook("morph.added", () => initDT(0));
        window.Livewire.hook("morph.updated", () => initDT(0));
    }

    document.addEventListener("livewire:navigated", () => {
        destroyDT();
        setTimeout(() => initDT(0), 120);
    });

    (function ensureObserver() {
        const n = el();
        if (!n || $tbl().data("dt-observer")) return;
        const obs = new MutationObserver((muts) => {
            if (
                muts.some(
                    (m) => m.type === "childList" || m.type === "attributes"
                )
            )
                initDT(0);
        });
        obs.observe(n, { childList: true, subtree: true, attributes: true });
        $tbl().data("dt-observer", obs);
    })();
})();

// Delete flow: confirm via modal and call Livewire delete(id)
(function handleDeleteFlow() {
    const $ = window.jQuery;
    if (!$) return;

    const MODAL_ID = "#confirmDeleteModal";
    const BTN_YES = "#confirmDeleteYes";
    const MODAL_TITLE = "#confirmDeleteTitle";
    const MODAL_TEXT = "#confirmDeleteText";
    let pendingId = null;

    document.addEventListener("click", (ev) => {
        const btn = ev.target.closest(".js-delete");
        if (!btn) return;
        ev.preventDefault();
        pendingId = btn.getAttribute("data-id");
        const label = btn.getAttribute("data-label") || `ID ${pendingId}`;
        const $title = document.querySelector(MODAL_TITLE);
        const $text = document.querySelector(MODAL_TEXT);
        if ($title) $title.textContent = "Konfirmasi Hapus";
        if ($text)
            $text.textContent = `Yakin ingin menghapus data ${label}? Tindakan ini tidak dapat dibatalkan.`;
        $(MODAL_ID).modal("show");
    });

    document.addEventListener("click", (ev) => {
        const yes = ev.target.closest(BTN_YES);
        if (!yes || !pendingId) return;

        const table = document.querySelector("#datatable1");
        let lw = null;
        if (table) {
            const root = table.closest("[wire\\:id]");
            if (root && window.Livewire)
                lw = window.Livewire.find(root.getAttribute("wire:id"));
        }
        if (lw?.call) lw.call("delete", parseInt(pendingId, 10));
        pendingId = null;
        if (document.activeElement) document.activeElement.blur();
        $(MODAL_ID).modal("hide");
    });

    $(MODAL_ID).on("hidden.bs.modal", () => {
        pendingId = null;
        document.body.focus();
    });
})();
