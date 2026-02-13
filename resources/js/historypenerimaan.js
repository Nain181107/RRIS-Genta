window.refreshHistoryPenerimaan = function (page = 1) {
    const form = document.getElementById("historyFilterForm");
    if (!form) return;

    const params = new URLSearchParams(new FormData(form));
    params.set("page", page);

    fetch(`/historypenerimaan/table?${params}`)
        .then((res) => res.text())
        .then((html) => {
            document.getElementById("historypenerimaan-body").innerHTML = html;
        });

    fetch(`/historypenerimaan/info?${params}`)
        .then((res) => res.text())
        .then((html) => {
            document.getElementById("historypenerimaan-info").innerHTML = html;
        });
};

function initHistoryRealtime() {
    if (typeof registerRealtime !== "function") {
        return;
    }

    registerRealtime("penerimaan", () => {
        console.log("Realtime event masuk!");
        refreshHistoryPenerimaan(1);
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
        refreshHistoryPenerimaan(1);
    });

    btnReset?.addEventListener("click", () => {
        form.reset();
        refreshHistoryPenerimaan(1);
    });

    form?.addEventListener("submit", (e) => {
        e.preventDefault();
        refreshHistoryPenerimaan(1);
    });

    if (toggleBtn && filterContainer) {
        toggleBtn.addEventListener("click", function () {
            filterContainer.classList.toggle("hidden");
            filterIcon.classList.toggle("rotate-180");
        });
    }

    initHistoryRealtime();
    refreshHistoryPenerimaan(1);
});

document.addEventListener("click", function (e) {
    const link = e.target.closest("#historypenerimaan-info a");
    if (!link) return;

    e.preventDefault();

    const url = new URL(link.href);
    const page = url.searchParams.get("page") ?? 1;

    refreshHistoryPenerimaan(page);
});

window.openModal = function () {
    const modal = document.getElementById("modalForm");
    const box = document.getElementById("modalBox");

    modal.classList.remove("hidden");
    modal.classList.add("flex", "items-center", "justify-center");

    setTimeout(() => {
        modal.classList.add("opacity-100");
        box.classList.remove("scale-95", "opacity-0");
        box.classList.add("scale-100", "opacity-100");
    }, 10);
};

window.closeModal = function () {
    const modal = document.getElementById("modalForm");
    const box = document.getElementById("modalBox");

    box.classList.add("scale-95", "opacity-0");
    box.classList.remove("scale-100", "opacity-100");

    modal.classList.remove("opacity-100");

    setTimeout(() => {
        modal.classList.remove("flex", "items-center", "justify-center");
        modal.classList.add("hidden");
    }, 300);
};

window.pilihPenerimaan = function (id) {
    openModal();
    loadDataSekarang(id);
    loadIdentitasAwal(id);
    loadRiwayatPerubahan(id);
};

function loadIdentitasAwal(id) {
    fetch(`/historypenerimaan/${id}/identitas-awal`, {
        credentials: "same-origin",
    })
        .then((res) => {
            if (!res.ok) throw new Error(res.status);
            return res.json();
        })
        .then((data) => {
            const tbody = document.getElementById("identitas-awal-body");
            tbody.innerHTML = "";

            if (!data.length) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="23" class="text-center py-4 text-gray-400 italic">
                        Data identitas awal tidak ditemukan
                    </td>
                </tr>`;
                return;
            }

            data.forEach((row, index) => {
                tbody.innerHTML += `
                <tr class="hover:bg-gray-200 text-center even:bg-gray-200 text-base whitespace-nowrap">
                    <td class="px-4 py-2">${index + 1}</td>
                    <td class="px-4 py-2">${formatDateTimeDB(row.tanggal_penerimaan)}</td>
                    <td class="px-4 py-2">${row.shift}</td>
                    <td class="px-4 py-3">
                        ${row.nomor_rod ?? row.penerimaan?.identitasRod?.nomor_rod ?? "-"}
                    </td>
                    <td class="px-4 py-2">${row.jenis}</td>
                    <td class="px-4 py-2">${row.stasiun}</td>
                    <td class="px-4 py-2">${row.e1}</td>
                    <td class="px-4 py-2">${row.e2}</td>
                    <td class="px-4 py-2">${row.e3}</td>
                    <td class="px-4 py-2">${row.s}</td>
                    <td class="px-4 py-2">${row.d}</td>
                    <td class="px-4 py-2">${row.b}</td>
                    <td class="px-4 py-2">${row.ba}</td>
                    <td class="px-4 py-2">${row.r}</td>
                    <td class="px-4 py-2">${row.m}</td>
                    <td class="px-4 py-2">${row.cr}</td>
                    <td class="px-4 py-2">${row.c}</td>
                    <td class="px-4 py-2">${row.rl}</td>
                    <td class="px-4 py-2 font-semibold">${row.jumlah}</td>
                    <td class="px-4 py-2">${row.catatan ?? "-"}</td>
                    <td class="px-4 py-2">${formatDateTimeDB(row.created_at)}</td>
                    <td class="px-4 py-2">${row.karyawan?.nama_lengkap ?? "-"}</td>
                    <td class="px-4 py-2">${row.tim}</td>
                </tr>`;
            });
        });
}

function loadRiwayatPerubahan(id) {
    fetch(`/historypenerimaan/${id}/riwayat-perubahan`, {
        credentials: "same-origin",
    })
        .then((res) => {
            if (!res.ok) throw new Error(res.status);
            return res.json();
        })
        .then((data) => {
            const tbody = document.getElementById("riwayat-perubahan-body");
            tbody.innerHTML = "";

            if (!data.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="23" class="text-center py-4 text-gray-400 italic">
                            Data Riwayat Perubahan tidak ditemukan
                        </td>
                    </tr>`;
                return;
            }

            data.forEach((row, index) => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-200 text-center even:bg-gray-200 text-base whitespace-nowrap">
                        <td class="px-4 py-2">${index + 1}</td>
                        <td class="px-4 py-2">${formatDateTimeDB(row.tanggal_penerimaan)}</td>
                        <td class="px-4 py-2">${row.shift}</td>
                        <td class="px-4 py-2">${row.nomor_rod}</td>
                        <td class="px-4 py-2">${row.jenis}</td>
                        <td class="px-4 py-2">${row.stasiun}</td>
                        <td class="px-4 py-2">${row.e1}</td>
                        <td class="px-4 py-2">${row.e2}</td>
                        <td class="px-4 py-2">${row.e3}</td>
                        <td class="px-4 py-2">${row.s}</td>
                        <td class="px-4 py-2">${row.d}</td>
                        <td class="px-4 py-2">${row.b}</td>
                        <td class="px-4 py-2">${row.ba}</td>
                        <td class="px-4 py-2">${row.r}</td>
                        <td class="px-4 py-2">${row.m}</td>
                        <td class="px-4 py-2">${row.cr}</td>
                        <td class="px-4 py-2">${row.c}</td>
                        <td class="px-4 py-2">${row.rl}</td>
                        <td class="px-4 py-2 font-semibold">${row.jumlah}</td>
                        <td class="px-4 py-2">${row.catatan ?? "-"}</td>
                        <td class="px-4 py-2">${formatDateTimeDB(row.created_at)}</td>
                        <td class="px-4 py-2">
                            ${row.karyawan?.nama_lengkap ?? "-"}
                        </td>
                        <td class="px-4 py-2">${row.tim}</td>
                    </tr>`;
            });
        })
        .catch((err) => {
            alert("Gagal ambil data Riwayat Perubahan");
        });
}

function loadDataSekarang(id) {
    fetch(`/penerimaan/${id}/data-sekarang`, {
        credentials: "same-origin",
    })
        .then((res) => {
            if (!res.ok) throw new Error(res.status);
            return res.json();
        })
        .then((row) => {
            document.getElementById("nomor-rod-title").textContent =
                row.identitas_rod?.nomor_rod ?? "-";

            const tbody = document.getElementById("data-sekarang-body");
            tbody.innerHTML = "";

            if (!row) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="23" class="text-center py-4 text-gray-400 italic">
                        Data sekarang tidak ditemukan
                    </td>
                </tr>`;
                return;
            }

            tbody.innerHTML = `
            <tr class="hover:bg-gray-200 text-center even:bg-gray-200 text-base whitespace-nowrap">
                <td class="px-4 py-2">1</td>
                <td class="px-4 py-2">${formatDateTimeDB(row.tanggal_penerimaan)}</td>
                <td class="px-4 py-2">${row.shift}</td>
                <td class="px-4 py-2">${row.identitas_rod?.nomor_rod ?? "-"}</td>
                <td class="px-4 py-2">${row.jenis}</td>
                <td class="px-4 py-2">${row.stasiun}</td>
                <td class="px-4 py-2">${row.e1}</td>
                <td class="px-4 py-2">${row.e2}</td>
                <td class="px-4 py-2">${row.e3}</td>
                <td class="px-4 py-2">${row.s}</td>
                <td class="px-4 py-2">${row.d}</td>
                <td class="px-4 py-2">${row.b}</td>
                <td class="px-4 py-2">${row.ba}</td>
                <td class="px-4 py-2">${row.r}</td>
                <td class="px-4 py-2">${row.m}</td>
                <td class="px-4 py-2">${row.cr}</td>
                <td class="px-4 py-2">${row.c}</td>
                <td class="px-4 py-2">${row.rl}</td>
                <td class="px-4 py-2">${row.jumlah}</td>
                <td class="px-4 py-2">${row.catatan ?? "-"}</td>
                <td class="px-4 py-2">${formatDateTimeDB(row.updated_at)}</td>
                <td class="px-4 py-2">${row.karyawan?.nama_lengkap ?? "-"}</td>
                <td class="px-4 py-2">${row.tim ?? "-"}</td>
            </tr>`;
        })
        .catch(() => {
            alert("Gagal ambil data sekarang");
        });
}

function formatDateTimeDB(value) {
    if (!value) return "-";

    const d = new Date(value);

    const pad = (n) => n.toString().padStart(2, "0");

    return (
        `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ` +
        `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`
    );
}

document.getElementById("btnExport").addEventListener("click", function () {
    const form = document.getElementById("historyFilterForm");
    const tanggalMulai = form.querySelector('[name="tanggalMulai"]').value;
    const tanggalAkhir = form.querySelector('[name="tanggalAkhir"]').value;
    const nomorRod = form.querySelector('[name="nomor_rod"]').value;
    const penginput = form.querySelector('[name="penginput"]').value;
    const jenis = form.querySelector('[name="jenis"]').value;
    const stasiun = form.querySelector('[name="stasiun"]').value;
    const tim = form.querySelector('[name="tim"]').value;
    const shift = form.querySelector('[name="shift"]').value;

    // VALIDASI: semua filter kosong
    if (
        !tanggalMulai &&
        !tanggalAkhir &&
        !nomorRod &&
        !penginput &&
        !jenis &&
        !stasiun &&
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
    const rows = document.querySelectorAll("#historypenerimaan-body tr");
    if (rows.length === 0) {
        alert("Data tidak ditemukan, tidak ada yang bisa diexport.");
        return;
    }

    const params = new URLSearchParams(new FormData(form)).toString();
    const url = this.dataset.exportUrl;

    window.location.href = url + "?" + params;
});
