// resources/js/greensand.js
document.addEventListener("livewire:init", () => {
    // Dates
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

    // Collapse
    const $ = window.jQuery;
    if (!$) return;

    const $col = $("#filterCollapse");
    const $icon = $("#filterIcon");
    const $header = $("#filterHeader");
    if (!$col.length) return;

    $col.collapse({ toggle: false });

    let reconcileTimer = null;

    // Icon
    function setIcon(isOpen) {
        if (!$icon.length) return;
        $icon
            .removeClass("ri-add-line ri-subtract-line")
            .addClass(isOpen ? "ri-subtract-line" : "ri-add-line");
    }

    // Livewire
    function getLW() {
        const root = $col.closest("[wire\\:id]");
        if (!root.length || !window.Livewire) return null;
        return window.Livewire.find(root.attr("wire:id"));
    }

    // Guard
    $header.on("click", (e) => {
        if ($col.attr("data-lock") === "1") {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    });

    // Lock
    function lock(on) {
        $col.attr("data-lock", on ? "1" : "0");
        $header.toggleClass("locked", !!on);
    }

    // Reconcile
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
            // Reflow
            // eslint-disable-next-line no-unused-expressions
            $col[0].offsetHeight;
            lock(true);
            $col.collapse(shouldOpen ? "show" : "hide");
        } else {
            if (isShown !== lastState) setIcon(isShown);
        }
    }

    // Events
    if (!$col.data("gs-bound")) {
        $col.on("show.bs.collapse", () => {
            lock(true);
        });
        $col.on("hide.bs.collapse", () => {
            lock(true);
        });

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
            if (lw?.get("filterOpen") !== false) lw.set("filterOpen", false);
        });

        $col.data("gs-bound", 1);
    }

    // Debounce
    function scheduleReconcile() {
        if (reconcileTimer) clearTimeout(reconcileTimer);
        reconcileTimer = setTimeout(() => {
            syncDates();
            reconcileCollapse();
        }, 80);
    }

    document.addEventListener("DOMContentLoaded", scheduleReconcile);
    document.addEventListener("livewire:navigated", scheduleReconcile);
    window.Livewire?.hook?.("message.processed", scheduleReconcile);

    // Confirm
    const MODAL_ID = "#confirmDeleteModal";
    window.addEventListener("gs:confirm-open", () => {
        $(MODAL_ID).modal("show");
    });
    window.addEventListener("gs:confirm-close", () => {
        $(MODAL_ID).modal("hide");
    });

    // Modal
    window.addEventListener("gs:open", () => {
        $("#modal-greensand").modal("show");
    });
    window.addEventListener("gs:close", () => {
        $("#modal-greensand").modal("hide");
    });
});

// Export
window.addEventListener("gs:export", (e) => {
    const d = e.detail;
    const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
    if (url) window.location.href = url;
});
