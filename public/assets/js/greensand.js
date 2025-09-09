// public/assets/js/greensand.js

// ==============================
// 1) Filter collapse sinkron dgn Livewire property 'filterOpen' (KHUSUS Greensand)
// ==============================
document.addEventListener("livewire:init", () => {
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
});

// ==============================
// 2) Modal form Greensand + Export (KHUSUS Greensand)
//    - Event: gs:open, gs:close -> #modal-greensand
//    - Event: gs:export -> window.location ke URL
// ==============================
(function () {
    const $ = window.jQuery;
    if (!$) return;

    // Modal form Greensand
    window.addEventListener("gs:open", () => {
        $("#modal-greensand").modal("show");
    });
    window.addEventListener("gs:close", () => {
        $("#modal-greensand").modal("hide");
    });

    // Export navigasi
    window.addEventListener("gs:export", (e) => {
        const d = e.detail;
        const url = (d && (d.url || (Array.isArray(d) && d[0]?.url))) || null;
        if (url) window.location.href = url;
    });
})();
