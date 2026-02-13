window.refreshHistoryPengiriman = function (page = 1) {
    const form = document.getElementById("historyFilterForm");
    if (!form) return;

    const params = new URLSearchParams(new FormData(form));
    params.set("page", page);

    fetch(`/historypengiriman/table?${params}`)
        .then((res) => res.text())
        .then((html) => {
            document.getElementById("historypengiriman-body").innerHTML = html;
        });

    fetch(`/historypengiriman/info?${params}`)
        .then((res) => res.text())
        .then((html) => {
            document.getElementById("historypengiriman-info").innerHTML = html;
        });
};

function initHistoryRealtime() {
    if (typeof registerRealtime !== "function") {
        return;
    }

    registerRealtime("pengiriman", () => {
        console.log("Realtime event masuk!");
        refreshHistoryPengiriman(1);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("historyFilterForm");
    const btnCari = document.getElementById("btnCari");
    const btnReset = document.getElementById("btnReset");
    const toggleBtn = document.getElementById("toggleFilterBtn");
    const filterContainer = document.getElementById("filterContainer");
    const filterIcon = document.getElementById("filterIcon");

    btnCari?.addEventListener("click", () => {
        refreshHistoryPengiriman(1);
    });

    btnReset?.addEventListener("click", () => {
        form.reset();
        refreshHistoryPengiriman(1);
    });

    form?.addEventListener("submit", (e) => {
        e.preventDefault();
        refreshHistoryPengiriman(1);
    });

    if (toggleBtn && filterContainer) {
        toggleBtn.addEventListener("click", function () {
            filterContainer.classList.toggle("hidden");
            filterIcon.classList.toggle("rotate-180");
        });
    }

    initHistoryRealtime();
    refreshHistoryPengiriman(1);
});

document.addEventListener("click", function (e) {
    const link = e.target.closest("#historypengiriman-info a");
    if (!link) return;

    e.preventDefault();

    const url = new URL(link.href);
    const page = url.searchParams.get("page") ?? 1;

    refreshHistoryPengiriman(page);
});

document.getElementById("btnExport").addEventListener("click", function () {
    const form = document.getElementById("historyFilterForm");

    const tanggalMulai = form.querySelector('[name="tanggalMulai"]').value;
    const tanggalAkhir = form.querySelector('[name="tanggalAkhir"]').value;
    const nomorRod = form.querySelector('[name="nomor_rod"]').value;
    const penginput = form.querySelector('[name="penginput"]').value;
    const tim = form.querySelector('[name="tim"]').value;
    const shift = form.querySelector('[name="shift"]').value;

    // VALIDASI: semua filter kosong
    if (
        !tanggalMulai &&
        !tanggalAkhir &&
        !nomorRod &&
        !penginput &&
        !tim &&
        !shift
    ) {
        alert("Silakan isi minimal satu filter sebelum export.");
        return;
    }

    // VALIDASI TANGGAL
    if (tanggalMulai && tanggalAkhir) {
        if (tanggalAkhir < tanggalMulai) {
            alert("Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
            return;
        }
    }

    // VALIDASI DATA TABEL KOSONG
    const rows = document.querySelectorAll("#historyperbaikan-body tr");
    if (rows.length === 0) {
        alert("Data tidak ditemukan, tidak ada yang bisa diexport.");
        return;
    }

    const params = new URLSearchParams(new FormData(form)).toString();
    const url = this.dataset.exportUrl;

    window.location.href = url + "?" + params;
});
