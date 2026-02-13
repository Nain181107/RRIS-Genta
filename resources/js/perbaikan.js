/* ===============================
   GLOBAL / UTIL
================================ */

// tutup notif modal (dipakai blade)
window.closeNotifModal = function () {
    document.getElementById("notifModal")?.remove();
};

/* ===============================
   FORM PERBAIKAN
================================ */

function unlockForm() {
    document.querySelectorAll('input[form="formPerbaikan"]').forEach((el) => {
        el.readOnly = false;
        el.classList.remove("bg-gray-100", "cursor-not-allowed");
    });

    document.getElementById("btnSimpan")?.removeAttribute("disabled");
    document.getElementById("openKamera")?.removeAttribute("disabled");
    document.getElementById("openFile")?.removeAttribute("disabled");

    const nomorRod = document.getElementById("f_nomor_rod");
    if (nomorRod) {
        nomorRod.readOnly = true;
        nomorRod.classList.add("bg-gray-100", "cursor-not-allowed");
    }
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

// dipanggil dari tombol "Pilih"
function pilihData(data) {
    unlockForm();

    document.getElementById("penerimaan_id").value = data.id;
    document.getElementById("tanggal_penerimaan").value = formatToSqlDatetime(
        data.tanggal_penerimaan,
    );

    document.getElementById("f_nomor_rod").value = data.identitas_rod.nomor_rod;
    document.getElementById("f_jenis").value = data.jenis;

    document.querySelector('[name="e1_ers"]').value = data.e1;
    document.querySelector('[name="e2_ers"]').value = data.e2;
    document.querySelector('[name="e3"]').value = data.e3;
    document.querySelector('[name="s"]').value = data.s;
    document.querySelector('[name="d"]').value = data.d;
    document.querySelector('[name="b"]').value = data.b;
    document.querySelector('[name="bac"]').value = data.ba;
    document.querySelector('[name="r"]').value = data.r;
    document.querySelector('[name="m"]').value = data.m;
    document.querySelector('[name="cr"]').value = data.cr;
    document.querySelector('[name="c"]').value = data.c;
    document.querySelector('[name="rl"]').value = data.rl;
    document.querySelector('[name="catatan"]').value = data.catatan ?? "";

    document.getElementById("totalKerusakan").textContent = data.jumlah;

    document.getElementById("f_jenis").focus();
}

// expose ke global (dipakai HTML)
window.pilihData = pilihData;

/* ===============================
   HITUNG TOTAL
================================ */

function initPerbaikan() {
    const totalEl = document.getElementById("totalKerusakan");
    if (!totalEl) return;

    const inputs = document.querySelectorAll(
        'input[type="number"][form="formPerbaikan"]',
    );

    function calculateTotal() {
        let total = 0;

        inputs.forEach((input) => {
            let val = parseInt(input.value) || 0;
            if (["s", "c", "cr"].includes(input.name)) {
                total += val > 0 ? 1 : 0;
            } else {
                total += val;
            }
        });

        totalEl.textContent = total;
    }

    inputs.forEach((i) => i.addEventListener("input", calculateTotal));
    calculateTotal();
}

/* ===============================
   SEARCH + PAGINATION
================================ */

function initSearch() {
    let timer;
    const input = document.getElementById("caripenerimaan");
    if (!input) return;

    input.addEventListener("input", function () {
        clearTimeout(timer);

        timer = setTimeout(() => {
            const keyword = this.value.trim();
            refreshPerbaikanTable(keyword, 1);
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
    const keyword = document.getElementById("caripenerimaan")?.value ?? "";

    refreshPerbaikanTable(keyword, page);
});

/* ===============================
   AJAX REFRESH TABLE
================================ */

window.refreshPerbaikanTable = function (keyword = "", page = 1) {
    const q = encodeURIComponent(keyword);

    fetch(`/perbaikan/table?caripenerimaan=${q}&page=${page}`)
        .then((r) => r.text())
        .then((html) => {
            const body = document.getElementById("perbaikan-body");
            if (body) body.innerHTML = html;
        });

    fetch(`/perbaikan/info?caripenerimaan=${q}&page=${page}`)
        .then((r) => r.text())
        .then((html) => {
            const info = document.getElementById("perbaikan-info");
            if (info) info.innerHTML = html;
        });
};

/* ===============================
   FILE & KAMERA
================================ */

let streamKamera = null;
const inputFoto = document.getElementById("inputFoto");

function handleFile(input) {
    const file = input.files[0];
    if (!file) return;

    const url = URL.createObjectURL(file);

    document.getElementById("fileLink").textContent = file.name;
    document.getElementById("fileLink").href = url;
    document.getElementById("fileInfo").classList.remove("hidden");
}

async function bukaKamera() {
    try {
        streamKamera = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: { ideal: "environment" } },
            audio: false,
        });

        const video = document.getElementById("videoKamera");
        video.srcObject = streamKamera;

        document.getElementById("kameraWrapper").classList.remove("hidden");
    } catch (err) {
        alert("Kamera tidak bisa diakses");
        console.error(err);
    }
}

function tutupKamera() {
    if (streamKamera) {
        streamKamera.getTracks().forEach((t) => t.stop());
        streamKamera = null;
    }
    document.getElementById("kameraWrapper")?.classList.add("hidden");
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
            inputFoto.files = dt.files;

            handleFile(inputFoto);
            tutupKamera();
        },
        "image/jpeg",
        0.9,
    );
}

// Loading Overlay
function initSubmitLoading() {
    const form = document.getElementById("formPerbaikan");
    if (!form) return;

    form.addEventListener("submit", function () {
        const overlay = document.getElementById("loadingOverlay");
        if (!overlay) return;

        overlay.classList.remove("hidden");
        overlay.classList.add("flex");
    });
}

/* ===============================
   RESET FORM
================================ */

function batalForm() {
    document.querySelectorAll('input[form="formPerbaikan"]').forEach((el) => {
        el.value = "";
        el.readOnly = true;
    });

    document.getElementById("totalKerusakan").textContent = "0";
    document.getElementById("btnSimpan").disabled = true;
    document.getElementById("openKamera").disabled = true;
    document.getElementById("openFile").disabled = true;

    inputFoto.value = "";
    document.getElementById("fileInfo")?.classList.add("hidden");

    tutupKamera();
}

window.batalForm = batalForm;

/* ===============================
   INIT + REALTIME
================================ */

document.addEventListener("DOMContentLoaded", () => {
    initPerbaikan();
    initSearch();
    initSubmitLoading();

    const formUnlocked = document.getElementById("formUnlocked")?.value;
    if (formUnlocked === "1") {
        unlockForm();
    }

    document
        .getElementById("openKamera")
        ?.addEventListener("click", bukaKamera);
    document
        .getElementById("btnTutupKamera")
        ?.addEventListener("click", tutupKamera);
    document
        .getElementById("btnAmbilFoto")
        ?.addEventListener("click", ambilFoto);

    document
        .getElementById("openFile")
        ?.addEventListener("click", () => inputFoto.click());

    inputFoto?.addEventListener("change", function () {
        handleFile(this);
    });

    // === REALTIME LISTENER ===
    if (typeof registerRealtime === "function") {
        registerRealtime("penerimaan", () => {
            const keyword =
                document.getElementById("caripenerimaan")?.value ?? "";

            if (keyword.trim().length > 0) return;

            refreshPerbaikanTable("", 1);
        });
    }
});
