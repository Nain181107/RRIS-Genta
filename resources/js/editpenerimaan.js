/* ===============================
   GLOBAL REFRESH (SATU PINTU)
================================ */
window.refreshEditPenerimaan = function (page = 1) {
    const nomorRod =
        document.getElementById("search_nomor_rod")?.value?.trim() ?? "";
    const tanggal =
        document.getElementById("search_tanggal")?.value?.trim() ?? "";

    const params = new URLSearchParams();
    if (nomorRod) params.append("nomor_rod", nomorRod);
    if (tanggal) params.append("tanggal", tanggal);
    params.append("page", page);

    fetch(`/editpenerimaan/table?${params}`)
        .then((r) => r.text())
        .then((html) => {
            const body = document.getElementById("editpenerimaan-body");
            if (body) body.innerHTML = html;
        });

    fetch(`/editpenerimaan/info?${params}`)
        .then((r) => r.text())
        .then((html) => {
            const info = document.getElementById("editpenerimaan-info");
            if (info) info.innerHTML = html;
        });
};

/* ===============================
   MODULE EDIT PENERIMAAN
================================ */
window.EditPenerimaan = (() => {
    const formId = "ep_form";

    /* ---------- UI ---------- */
    function ep_closeNotif() {
        document.getElementById("notifModal")?.remove();
    }

    function ep_unlockform() {
        document.querySelectorAll(`[form="${formId}"]`).forEach((el) => {
            el.readOnly = false;
        });
        document.getElementById("ep_btnSimpan")?.removeAttribute("disabled");
    }

    function formatToSqlDatetime(isoString) {
        const d = new Date(isoString);

        const pad = (n) => n.toString().padStart(2, "0");

        return (
            d.getFullYear() +
            "-" +
            pad(d.getMonth() + 1) +
            "-" +
            pad(d.getDate()) +
            " " +
            pad(d.getHours()) +
            ":" +
            pad(d.getMinutes()) +
            ":" +
            pad(d.getSeconds())
        );
    }

    /* ---------- PILIH DATA ---------- */
    function ep_pilihData(data) {
        ep_unlockform();

        document.getElementById("ep_id").value = data.id;
        document.getElementById("ep_nomor_rod_history").value = data.nomor_rod;
        document.getElementById("ep_tanggal_penerimaan").value =
            formatToSqlDatetime(data.tanggal_penerimaan);
        document.getElementById("ep_shift").value = data.shift;

        document.getElementById("ep_nomor_rod").value =
            data.identitas_rod.nomor_rod;
        document.getElementById("ep_jenis").value = data.jenis;
        document.getElementById("ep_stasiun").value = data.stasiun;

        [
            "e1",
            "e2",
            "e3",
            "s",
            "d",
            "b",
            "ba",
            "r",
            "m",
            "cr",
            "c",
            "rl",
        ].forEach((k) => {
            const el = document.querySelector(`[name="${k}"]`);
            if (el) el.value = data[k] ?? 0;
        });

        document.querySelector('[name="catatan"]').value = data.catatan ?? "";

        setTotalSebelumnya(data.jumlah);
        document.getElementById("ep_nomor_rod").focus();
    }

    function setTotalSebelumnya(val) {
        const totalEl = document.getElementById("ep_totalSebelumnya");
        const hiddenEl = document.getElementById("ep_totalSebelumnyaHidden");

        if (!totalEl || !hiddenEl) return;

        totalEl.textContent = val ?? 0;
        hiddenEl.value = val ?? 0;
    }

    /* ---------- TOTAL REALTIME ---------- */
    function ep_initTotal() {
        const inputs = document.querySelectorAll(
            `input[type="number"][form="${formId}"]`,
        );
        const totalEl = document.getElementById("ep_totalUpdate");
        if (!inputs.length || !totalEl) return;

        function calculate() {
            let total = 0;
            inputs.forEach((input) => {
                const val = parseInt(input.value) || 0;
                if (["s", "c", "cr"].includes(input.name)) {
                    total += val > 0 ? 1 : 0;
                } else {
                    total += val;
                }
            });
            totalEl.textContent = total;
        }

        inputs.forEach((i) => i.addEventListener("input", calculate));
        calculate();
    }

    /* ---------- SUBMIT LOADING ---------- */
    function ep_bindSubmit() {
        const form = document.getElementById(formId);
        const overlay = document.getElementById("loadingOverlay");
        if (!form || !overlay) return;

        form.addEventListener("submit", () => {
            overlay.classList.remove("hidden");
            overlay.classList.add("flex");
        });
    }

    /* ---------- RESET FORM ---------- */
    function ep_reset(e) {
        e.preventDefault();

        const form = document.getElementById(formId);
        if (!form) return;

        form.reset();

        document.querySelectorAll(`[form="${formId}"]`).forEach((el) => {
            if (el.type !== "hidden") {
                el.value = "";
                el.readOnly = true;
            }
        });

        document.getElementById("ep_btnSimpan").disabled = true;
        document.getElementById("ep_totalUpdate").textContent = 0;
        document.getElementById("ep_totalSebelumnya").textContent = 0;
        document.getElementById("ep_id").value = "";
    }

    /* ---------- SEARCH ---------- */
    function ep_search() {
        window.refreshEditPenerimaan(1);
    }

    function ep_resetSearch() {
        document.getElementById("search_nomor_rod").value = "";
        document.getElementById("search_tanggal").value = "";
        window.refreshEditPenerimaan(1);
    }

    /* ---------- INIT ---------- */
    function ep_init() {
        const unlocked =
            document.getElementById("ep_formUnlocked")?.value === "1";

        if (unlocked) ep_unlockform();

        ep_initTotal();
        ep_bindSubmit();
    }

    return {
        ep_init,
        ep_pilihData,
        ep_reset,
        ep_closeNotif,
        ep_search,
        ep_resetSearch,
    };
})();

/* ===============================
   SEARCH REALTIME (DEBOUNCE)
================================ */
function initEditPenerimaanSearch() {
    let timer;
    const nomorRod = document.getElementById("search_nomor_rod");
    const tanggal = document.getElementById("search_tanggal");

    if (!nomorRod && !tanggal) return;

    const handler = () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            window.refreshEditPenerimaan(1);
        }, 300);
    };

    nomorRod?.addEventListener("input", handler);
    tanggal?.addEventListener("change", handler);
}

/* ===============================
   PAGINATION AJAX
================================ */
document.addEventListener("click", function (e) {
    const link = e.target.closest("#editpenerimaan-info a");
    if (!link) return;

    e.preventDefault();

    const url = new URL(link.href);
    const page = url.searchParams.get("page") ?? 1;

    window.refreshEditPenerimaan(page);
});

/* ===============================
   REALTIME PUSH
================================ */
function initEditPenerimaanRealtime() {
    if (typeof registerRealtime !== "function") return;

    registerRealtime("penerimaan", () => {
        const nomorRod =
            document.getElementById("search_nomor_rod")?.value?.trim() ?? "";
        const tanggal =
            document.getElementById("search_tanggal")?.value?.trim() ?? "";

        // user lagi filter → jangan refresh
        if (nomorRod || tanggal) return;

        window.refreshEditPenerimaan(1);
    });
}

/* ===============================
   DOM READY
================================ */
document.addEventListener("DOMContentLoaded", function () {
    EditPenerimaan.ep_init();
    initEditPenerimaanSearch();
    initEditPenerimaanRealtime();

    // load awal
    window.refreshEditPenerimaan(1);
});
