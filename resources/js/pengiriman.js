document.addEventListener("DOMContentLoaded", () => {
    const inputsRod = document.querySelectorAll(".rod-input");
    const formPengiriman = document.getElementById("formPengiriman");
    const loadingOverlay = document.getElementById("loadingOverlay");

    if (!formPengiriman) return;

    /* ================= UTIL ================= */

    function showAlert(message) {
        alert(message);
    }

    function showLoading() {
        loadingOverlay?.classList.remove("hidden");
        loadingOverlay?.classList.add("flex");
    }

    function hideLoading() {
        loadingOverlay?.classList.add("hidden");
        loadingOverlay?.classList.remove("flex");
    }

    /* ================= MODAL ================= */

    window.closeNotifModal = function () {
        document.getElementById("notifModal")?.remove();
    };

    /* ================= PILIH DATA ================= */

    window.pilihData = function (row) {
        const nomorRod = row?.penerimaan?.identitas_rod?.nomor_rod ?? null;
        if (!nomorRod) return showAlert("Nomor ROD tidak tersedia!");

        for (let input of inputsRod) {
            if (input.value.trim() === nomorRod) {
                return showAlert(`Nomor ROD ${nomorRod} sudah dipilih!`);
            }
        }

        for (let input of inputsRod) {
            if (input.value.trim() === "") {
                input.value = nomorRod;
                return;
            }
        }

        showAlert("Slot input ROD sudah penuh (maksimal 10).");
    };

    /* ================= VALIDASI ROD ================= */

    async function validasiRod(input) {
        const nomorRod = input.value.trim().toUpperCase();
        input.value = nomorRod;

        input.classList.remove("border-red-500");
        delete input.dataset.invalidMsg;

        if (!nomorRod) return;

        for (let i of inputsRod) {
            if (i !== input && i.value.trim() === nomorRod) {
                input.classList.add("border-red-500");
                input.dataset.invalidMsg = `Nomor ROD ${nomorRod} sudah dipakai di slot lain!`;
                return;
            }
        }

        try {
            const response = await fetch(`/cek-rod/${nomorRod}`);
            const data = await response.json();

            if (!data.exists) {
                input.classList.add("border-red-500");
                input.dataset.invalidType = "NOT_FOUND";
                input.dataset.invalidRod = nomorRod;
            }
        } catch {
            input.classList.add("border-red-500");
            input.dataset.invalidMsg = "Gagal terhubung ke server.";
        }
    }

    inputsRod.forEach((input) => {
        input.addEventListener("blur", () => validasiRod(input));
    });

    /* ================= RESET ================= */

    window.resetPerbaikan = function (e) {
        e.preventDefault();

        inputsRod.forEach((input) => {
            input.value = "";
            input.classList.remove("border-red-500");
            // hapus SEMUA jejak error
            delete input.dataset.invalidMsg;
            delete input.dataset.invalidType;
            delete input.dataset.invalidRod;
        });
    };

    /* ===============================
   SEARCH + PAGINATION
================================ */

    function initSearch() {
        let timer;
        const input = document.getElementById("cariperbaikan");
        if (!input) return;

        input.addEventListener("input", function () {
            clearTimeout(timer);

            timer = setTimeout(() => {
                refreshPengirimanTable(this.value.trim(), 1);
            }, 100);
        });
    }

    // intercept pagination
    document.addEventListener("click", function (e) {
        const link = e.target.closest("#perbaikan-info a");
        if (!link) return;

        e.preventDefault();

        const url = new URL(link.href);
        const page = url.searchParams.get("page") ?? 1;
        const keyword = document.getElementById("cariperbaikan")?.value ?? "";

        refreshPengirimanTable(keyword, page);
    });

    //ajax refresh table
    window.refreshPengirimanTable = function (keyword = "", page = 1) {
        const q = encodeURIComponent(keyword);

        fetch(`/pengiriman/table?cariperbaikan=${q}&page=${page}`)
            .then((r) => r.text())
            .then((html) => {
                const body = document.getElementById("perbaikan-body");
                if (body) body.innerHTML = html;
            });

        fetch(`/pengiriman/info?cariperbaikan=${q}&page=${page}`)
            .then((r) => r.text())
            .then((html) => {
                const info = document.getElementById("perbaikan-info");
                if (info) info.innerHTML = html;
            });
    };

    /* ================= SUBMIT ================= */

    formPengiriman.addEventListener("submit", async function (e) {
        e.preventDefault();
        showLoading();

        let errors = [];
        let notFoundRods = [];

        for (let input of inputsRod) {
            if (!input.value.trim()) continue;

            await validasiRod(input);

            if (input.dataset.invalidType === "NOT_FOUND") {
                notFoundRods.push(input.dataset.invalidRod);
            } else if (input.dataset.invalidMsg) {
                errors.push(input.dataset.invalidMsg);
            }
        }

        if (notFoundRods.length) {
            const list = notFoundRods.map((r) => `[${r}]`).join(", ");
            errors.push(
                `Nomor ROD ${list} belum ditemukan. ` +
                    `Kemungkinan data baru dikirim atau masih menunggu proses perbaikan.`,
            );
        }

        if (errors.length) {
            hideLoading();
            showAlert(errors.join("\n"));
            return;
        }

        this.submit();
    });

    if (typeof registerRealtime === "function") {
        registerRealtime("perbaikan", () => {
            const keyword =
                document.getElementById("cariperbaikan")?.value ?? "";

            if (keyword.trim().length > 0) return;

            refreshPengirimanTable("", 1);
        });
    }

    initSearch();
});
