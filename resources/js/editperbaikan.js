window.refreshEditPerbaikan = function (page = 1) {
    const nomorRod =
        document.getElementById("search_nomor_rod")?.value?.trim() ?? "";
    const tanggal =
        document.getElementById("search_tanggal")?.value?.trim() ?? "";

    const params = new URLSearchParams();
    if (nomorRod) params.append("nomor_rod", nomorRod);
    if (tanggal) params.append("tanggal", tanggal);
    params.append("page", page);

    fetch(`/editperbaikan/table?${params}`)
        .then((r) => r.text())
        .then((html) => {
            const body = document.getElementById("editperbaikan-body");
            if (body) body.innerHTML = html;
        });

    fetch(`/editperbaikan/info?${params}`)
        .then((r) => r.text())
        .then((html) => {
            const info = document.getElementById("editperbaikan-info");
            if (info) info.innerHTML = html;
        });
};

window.EditPerbaikan = (() => {
    const formId = "eper_form";
    let streamKamera = null;

    function eper_closeNotif() {
        document.getElementById("notifModal")?.remove();
    }

    function eper_unlockform() {
        document.querySelectorAll(`[form="${formId}"]`).forEach((el) => {
            el.readOnly = false;
            el.disabled = false;
        });
        document.getElementById("eper_btnSimpan")?.removeAttribute("disabled");
        document.getElementById("eper_openFile")?.removeAttribute("disabled");
        document.getElementById("eper_openKamera")?.removeAttribute("disabled");
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

    function eper_pilihData(data) {
        eper_unlockform();

        document.getElementById("eper_id").value = data.id;
        document.getElementById("eper_nomor_rod_history").value =
            data.nomor_rod;
        document.getElementById("eper_jenis").value = data.jenis;

        document.getElementById("eper_tanggal_perbaikan").value =
            formatToSqlDatetime(data.tanggal_perbaikan);

        document.getElementById("eper_shift").value = data.shift;

        document.getElementById("eper_tanggal_penerimaan").value =
            formatToSqlDatetime(data.tanggal_penerimaan);

        document.getElementById("eper_nomor_rod").value =
            data.penerimaan.identitas_rod.nomor_rod;

        document.querySelector('[name="e1_ers"]').value = data.e1_ers;
        document.querySelector('[name="e1_est"]').value = data.e1_est;
        document.getElementById("e1_jumlah").textContent =
            data.e1_jumlah ?? "-";

        document.querySelector('[name="e2_ers"]').value = data.e2_ers;
        document.querySelector('[name="e2_cst"]').value = data.e2_cst;
        document.querySelector('[name="e2_cstub"]').value = data.e2_cstub;
        document.getElementById("e2_jumlah").textContent =
            data.e2_jumlah ?? "-";

        [
            "e3",
            "e4",
            "s",
            "d",
            "b",
            "bac",
            "nba",
            "ba1",
            "r",
            "m",
            "cr",
            "c",
            "rl",
        ].forEach((k) => {
            const el = document.querySelector(`[name="${k}"]`);
            if (el) el.value = data[k] ?? 0;
        });

        document.getElementById("ba_jumlah").textContent = data.ba ?? "-";
        document.querySelector('[name="catatan"]').value = data.catatan ?? "";

        setTotalSebelumnya(data.jumlah);

        data.fotobuktiperubahan
            ? tampilkanFoto(data.fotobuktiperubahan)
            : resetFotoUI();

        document.getElementById("eper_existing_photo").value =
            data.fotobuktiperubahan ?? "";

        document.getElementById("eper_nomor_rod").focus();
    }

    function setTotalSebelumnya(val) {
        const totalEl = document.getElementById("eper_totalSebelumnya");
        const hiddenEl = document.getElementById("eper_totalSebelumnyaHidden");

        if (!totalEl || !hiddenEl) return;

        totalEl.textContent = val ?? 0;
        hiddenEl.value = val ?? 0;
    }

    let fotoAktif = null;
    let fotoDihapus = false;

    function tampilkanFoto(path) {
        const fileInfo = document.getElementById("fileInfo");
        const fileLink = document.getElementById("fileLink");

        fileLink.textContent = path.split("/").pop();
        fileLink.href = `/storage/${path}`;
        fileInfo.classList.remove("hidden");

        fotoAktif = path;
        fotoDihapus = false;
    }

    function resetFotoUI() {
        document.getElementById("fileInfo").classList.add("hidden");
        document.getElementById("fileLink").textContent = "";
        document.getElementById("fileLink").href = "#";
        fotoAktif = null;
    }

    function cekAmbilUlang(callback) {
        if (fotoAktif) {
            if (
                !confirm(
                    "Data ini sudah memiliki foto. Apakah ingin mengambil ulang?",
                )
            )
                return;
            fotoDihapus = true;
        }
        callback();
    }

    function handleFile(input) {
        const file = input.files[0];
        if (!file) return;

        resetFotoUI();

        document.getElementById("fileLink").textContent = file.name;
        document.getElementById("fileLink").href = URL.createObjectURL(file);
        document.getElementById("fileInfo").classList.remove("hidden");

        fotoAktif = file.name;
        fotoDihapus = false;
    }

    async function bukaKamera() {
        const video = document.getElementById("videoKamera");

        video.classList.remove("mirror");

        try {
            streamKamera = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: {
                        ideal: "environment",
                    },
                },
            });
        } catch (e) {
            streamKamera = await navigator.mediaDevices.getUserMedia({
                video: true,
            });

            video.classList.add("mirror");
        }

        video.srcObject = streamKamera;
        document.getElementById("kameraWrapper").classList.remove("hidden");
    }

    function tutupKamera() {
        streamKamera?.getTracks().forEach((t) => t.stop());
        streamKamera = null;
        document.getElementById("kameraWrapper").classList.add("hidden");
    }

    function ambilFoto() {
        const video = document.getElementById("videoKamera");
        const canvas = document.getElementById("canvasFoto");
        const ctx = canvas.getContext("2d");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0);

        canvas.toBlob(
            (blob) => {
                const file = new File([blob], `kamera-${Date.now()}.jpg`, {
                    type: "image/jpeg",
                });
                const dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById("eper_inputFoto").files = dt.files;
                handleFile(document.getElementById("eper_inputFoto"));
                tutupKamera();
            },
            "image/jpeg",
            0.9,
        );
    }

    function eper_initTotal() {
        const inputs = document.querySelectorAll(
            `input[type="number"][form="${formId}"]`,
        );
        const totalEl = document.getElementById("eper_totalUpdate");

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

    function eper_reset() {
        const formId = "eper_form";

        // 🔒 Reset & lock semua input form
        document.querySelectorAll(`[form="${formId}"]`).forEach((el) => {
            el.value = "";
            el.readOnly = true;
            el.disabled = true;
        });

        // 🔢 Reset total
        document.getElementById("eper_totalUpdate").textContent = "0";
        setTotalSebelumnya(
            document.getElementById("eper_totalSebelumnyaHidden").value || 0,
        );

        // 🔕 Disable tombol
        document.getElementById("eper_btnSimpan").disabled = true;
        document.getElementById("eper_openKamera").disabled = true;
        document.getElementById("eper_openFile").disabled = true;

        // 🖼️ Reset foto
        const inputFoto = document.getElementById("eper_inputFoto");
        if (inputFoto) inputFoto.value = "";

        document.getElementById("fileInfo")?.classList.add("hidden");
        document.getElementById("fileLink").textContent = "";
        document.getElementById("fileLink").href = "#";

        // 🔥 Tutup kamera + reset mirror
        tutupKamera();

        // 🔁 Reset flag foto
        fotoAktif = null;
        fotoDihapus = false;
    }

    function eper_search() {
        window.refreshEditPerbaikan(1);
    }

    function eper_resetSearch() {
        document.getElementById("search_nomor_rod").value = "";
        document.getElementById("search_tanggal").value = "";
        window.refreshEditPerbaikan(1);
    }

    function eper_init() {
        const unlocked =
            document.getElementById("eper_formUnlocked")?.value === "1";

        if (unlocked) eper_unlockform();

        const oldTotal = document.getElementById(
            "eper_totalSebelumnyaHidden",
        )?.value;
        if (oldTotal) setTotalSebelumnya(oldTotal);

        // 🔥 RESTORE LINK FOTO DI SAMPING BUTTON
        const existingPhoto = document.getElementById(
            "eper_existing_photo",
        )?.value;

        if (existingPhoto) {
            tampilkanFoto(existingPhoto);
        }

        eper_initTotal();
    }

    document.addEventListener("DOMContentLoaded", () => {
        document
            .getElementById("eper_inputFoto")
            .addEventListener("change", (e) => handleFile(e.target));

        document
            .getElementById("eper_openFile")
            .addEventListener("click", () =>
                cekAmbilUlang(() =>
                    document.getElementById("eper_inputFoto").click(),
                ),
            );

        document
            .getElementById("eper_openKamera")
            .addEventListener("click", () => cekAmbilUlang(bukaKamera));

        document
            .getElementById("btnTutupKamera")
            .addEventListener("click", tutupKamera);
        document
            .getElementById("btnAmbilFoto")
            .addEventListener("click", ambilFoto);

        document.getElementById("removeFile").addEventListener("click", () => {
            if (!confirm("Hapus foto ini?")) return;
            resetFotoUI();
            fotoDihapus = true;
        });

        eper_initTotal();
    });

    document.getElementById(formId).addEventListener("submit", () => {
        document.getElementById("loadingOverlay").classList.remove("hidden");
        document.getElementById("loadingOverlay").classList.add("flex");
        document.getElementById("eper_hapus_foto").value = fotoDihapus ? 1 : 0;
    });

    return {
        eper_init,
        eper_closeNotif,
        eper_pilihData,
        eper_reset,
        eper_search,
        eper_resetSearch,
    };
})();

function initEditPerbaikanSearch() {
    let timer;
    const nomorRod = document.getElementById("search_nomor_rod");
    const tanggal = document.getElementById("search_tanggal");

    if (!nomorRod && !tanggal) return;

    const handler = () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            window.refreshEditPerbaikan(1);
        }, 300);
    };

    nomorRod?.addEventListener("input", handler);
    tanggal?.addEventListener("change", handler);
}

document.addEventListener("click", function (e) {
    const link = e.target.closest("#editperbaikan-info a");
    if (!link) return;

    e.preventDefault();

    const url = new URL(link.href);
    const page = url.searchParams.get("page") ?? 1;

    window.refreshEditPerbaikan(page);
});

function initEditPerbaikanRealtime() {
    if (typeof registerRealtime !== "function") return;

    registerRealtime("perbaikan", () => {
        const nomorRod =
            document.getElementById("search_nomor_rod")?.value?.trim() ?? "";
        const tanggal =
            document.getElementById("search_tanggal")?.value?.trim() ?? "";

        // kalau user lagi filter, jangan auto refresh
        if (nomorRod || tanggal) return;

        window.refreshEditPerbaikan(1);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    EditPerbaikan.eper_init();
    initEditPerbaikanSearch();
    initEditPerbaikanRealtime();

    window.refreshEditPerbaikan(1);
});
