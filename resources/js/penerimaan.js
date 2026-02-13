// Global
window.closeNotifModal = function () {
    document.getElementById("notifModal")?.remove();
};

// Penerimaan
function initPenerimaan() {
    const totalEl = document.getElementById("totalKerusakan");
    if (!totalEl) return;

    const inputs = document.querySelectorAll(
        'input[type="number"][name][form="formPenerimaan"]',
    );

    function calculateTotal() {
        let total = 0;
        inputs.forEach((input) => {
            let val = parseInt(input.value) || 0;
            if (["s", "c", "cr"].includes(input.name)) total += val > 0 ? 1 : 0;
            else total += val;
        });
        totalEl.textContent = total;
    }

    inputs.forEach((input) => input.addEventListener("input", calculateTotal));
    calculateTotal();

    window.resetPenerimaan = function (e) {
        e.preventDefault();
        const form = document.getElementById("formPenerimaan");
        if (!form) return;

        form.reset();

        document.querySelectorAll('[form="formPenerimaan"]').forEach((el) => {
            if (el.type !== "hidden") el.value = "";
        });

        totalEl.textContent = 0;
    };
}

// Search
function initSearch() {
    let timer;
    const input = document.getElementById("caripenerimaan");
    if (!input) return;

    input.addEventListener("input", function () {
        clearTimeout(timer);

        timer = setTimeout(() => {
            const keyword = this.value.trim();
            window.refreshAll(keyword, 1);
        }, 100);
    });
}

// AJAX PAGINATION INTERCEPT
document.addEventListener("click", function (e) {
    const link = e.target.closest("#penerimaan-info a");
    if (!link) return;

    e.preventDefault();

    const url = new URL(link.href);
    const page = url.searchParams.get("page") ?? 1;
    const keyword = document.getElementById("caripenerimaan")?.value ?? "";

    window.refreshAll(keyword, page);
});

// Loading Overlay
function initSubmitLoading() {
    const form = document.getElementById("formPenerimaan");
    if (!form) return;

    form.addEventListener("submit", function () {
        const overlay = document.getElementById("loadingOverlay");
        if (!overlay) return;

        overlay.classList.remove("hidden");
        overlay.classList.add("flex");
    });
}

// Modal
window.openPerputaranModal = function () {
    const modal = document.getElementById("perputaranModal");
    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
};

window.closePerputaranModal = function () {
    const modal = document.getElementById("perputaranModal");
    if (!modal) return;

    const input = modal.querySelector('input[name="hari"]');

    if (input && input.dataset.initial !== undefined) {
        input.value = input.dataset.initial;
        input.classList.remove("border-red-500", "ring-red-300");
    }

    modal.querySelectorAll(".text-red-600").forEach((el) => el.remove());

    modal.classList.add("hidden");
    modal.classList.remove("flex");
};

// Init
document.addEventListener("DOMContentLoaded", function () {
    initPenerimaan();
    initSearch();
    initSubmitLoading();

    if (window.hasHariError) {
        openPerputaranModal();
    }

    window.refreshAll = function (keyword = "", page = 1) {
        const q = encodeURIComponent(keyword);

        fetch(`/penerimaan/table?caripenerimaan=${q}&page=${page}`)
            .then((r) => r.text())
            .then((html) => {
                const body = document.getElementById("penerimaan-body");
                if (body) body.innerHTML = html;
            });

        fetch(`/penerimaan/info?caripenerimaan=${q}&page=${page}`)
            .then((r) => r.text())
            .then((html) => {
                const info = document.getElementById("penerimaan-info");
                if (info) info.innerHTML = html;
            });
    };

    if (typeof registerRealtime !== "function") return;

    registerRealtime("penerimaan", () => {
        const keyword = document.getElementById("caripenerimaan")?.value ?? "";

        // user lagi search → jangan refresh
        if (keyword.trim().length > 0) return;

        window.refreshAll("", 1);
    });
});
