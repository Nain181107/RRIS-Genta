@extends('master.master')

@section('konten')
    <style>
        /* Dashboard Responsive Styles */
        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        /* Responsive Stats Cards */
        @media (max-width: 640px) {
            .stats-card h3 {
                font-size: 1rem !important;
            }

            .stats-card h1 {
                font-size: 2.5rem !important;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .stats-card h1 {
                font-size: 3.5rem !important;
            }
        }

        /* Chart Container Responsive */
        .chart-container {
            position: relative;
            width: 100%;
        }

        /* Chart Wrapper - Scrollable untuk mobile */
        .chart-wrapper {
            width: 100%;
            overflow-x: visible;
            overflow-y: hidden;
        }

        @media (max-width: 768px) {
            .chart-wrapper {
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 10px;
            }

            .chart-wrapper::-webkit-scrollbar {
                height: 8px;
            }

            .chart-wrapper::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
                margin: 0 10px;
            }

            .chart-wrapper::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 10px;
            }

            .chart-wrapper::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            .chart-container {
                height: 400px !important;
                min-height: 400px !important;
            }

            .filter-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.75rem;
            }

            .filter-header h1 {
                font-size: 0.875rem !important;
                line-height: 1.3 !important;
                word-break: break-word;
            }

            .filter-buttons {
                width: 100%;
                justify-content: flex-end !important;
            }

            .filter-button {
                padding: 0.5rem !important;
            }

            .filter-button svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }

            .filter-button span {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 400px !important;
                min-width: 600px;
                min-height: 400px !important;
            }

            .filter-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.75rem;
            }

            .filter-header h1 {
                font-size: 0.95rem !important;
                line-height: 1.3 !important;
                word-break: break-word;
            }

            .filter-buttons {
                width: 100%;
                justify-content: flex-end !important;
            }

            .filter-button {
                padding: 0.5rem !important;
            }

            .filter-button svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }

            .filter-button span {
                display: none;
            }
        }

        @media (min-width: 769px) {
            .chart-container {
                height: 450px !important;
                min-height: 450px !important;
            }
        }

        @media (min-width: 1024px) {
            .chart-container {
                height: 500px !important;
                min-height: 500px !important;
            }
        }

        /* Filter Form Responsive */
        @media (max-width: 768px) {
            .filter-form-grid {
                grid-template-columns: 1fr !important;
            }

            #filterOverlay {
                padding: 1rem !important;
                overflow-y: auto;
            }

            .filter-actions {
                position: sticky;
                bottom: 0;
                background: white;
                padding-top: 1rem;
                margin-top: 1rem;
                border-top: 1px solid #e5e7eb;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }

        /* Custom scrollbar for filter overlay */
        #filterOverlay::-webkit-scrollbar {
            width: 6px;
        }

        #filterOverlay::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #filterOverlay::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #filterOverlay::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <div class="flex flex-col flex-1 space-y-3 sm:space-y-4">

        <!-- Hidden Inputs -->
        <input type="hidden" id="master_datetime">
        <input type="hidden" id="master_shift">

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 font-(--font-sans) w-full">

            <!-- Total ROD RRS -->
            <div
                class="stats-card bg-white p-4 sm:p-5 border border-gray-200 rounded-xl shadow-sm hover:shadow-md flex flex-col">
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--blue) text-right mb-2">
                    Total ROD Di RRS
                </h3>
                <h1
                    class="flex-1 text-4xl sm:text-5xl lg:text-6xl font-bold leading-none flex items-center justify-center text-(--blue) my-2 sm:my-3">
                    {{ $total_rrs }}
                </h1>
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--green) text-right mt-2">
                    Assy
                </h3>
            </div>

            <!-- Stock ROD Reject -->
            <div
                class="stats-card bg-white p-4 sm:p-5 border border-gray-200 rounded-xl shadow-sm hover:shadow-md flex flex-col">
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--blue) text-right mb-2">
                    Stock ROD Reject
                </h3>
                <h1
                    class="flex-1 text-4xl sm:text-5xl lg:text-6xl font-bold leading-none flex items-center justify-center text-(--blue) my-2 sm:my-3">
                    {{ $data->stock_reject }}
                </h1>
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--green) text-right mt-2">
                    Assy
                </h3>
            </div>

            <!-- Stock ROD Ready-->
            <div
                class="stats-card bg-white p-4 sm:p-5 border border-gray-200 rounded-xl shadow-sm hover:shadow-md flex flex-col">
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--blue) text-right mb-2">
                    Stock ROD Ready
                </h3>
                <h1
                    class="flex-1 text-4xl sm:text-5xl lg:text-6xl font-bold leading-none flex items-center justify-center text-(--blue) my-2 sm:my-3">
                    {{ $data->stock_ready }}
                </h1>
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--green) text-right mt-2">
                    Assy
                </h3>
            </div>

            <!-- Penerimaan -->
            <div
                class="stats-card bg-white p-4 sm:p-5 border border-gray-200 rounded-xl shadow-sm hover:shadow-md flex flex-col">
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--blue) text-right mb-2">
                    Penerimaan
                </h3>
                <h1
                    class="flex-1 text-4xl sm:text-5xl lg:text-6xl font-bold leading-none flex items-center justify-center text-(--blue) my-2 sm:my-3">
                    {{ $penerimaan }}
                </h1>
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--green) text-right mt-2">
                    Assy
                </h3>
            </div>

            <!-- Perbaikan -->
            <div
                class="stats-card bg-white p-4 sm:p-5 border border-gray-200 rounded-xl shadow-sm hover:shadow-md flex flex-col">
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--blue) text-right mb-2">
                    Perbaikan
                </h3>
                <h1
                    class="flex-1 text-4xl sm:text-5xl lg:text-6xl font-bold leading-none flex items-center justify-center text-(--blue) my-2 sm:my-3">
                    {{ $perbaikan }}
                </h1>
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--green) text-right mt-2">
                    Assy
                </h3>
            </div>

            <!-- Pengiriman -->
            <div
                class="stats-card bg-white p-4 sm:p-5 border border-gray-200 rounded-xl shadow-sm hover:shadow-md flex flex-col">
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--blue) text-right mb-2">
                    Pengiriman
                </h3>
                <h1
                    class="flex-1 text-4xl sm:text-5xl lg:text-6xl font-bold leading-none flex items-center justify-center text-(--blue) my-2 sm:my-3">
                    {{ $pengiriman }}
                </h1>
                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-(--green) text-right mt-2">
                    Assy
                </h3>
            </div>

        </div>

        <!-- Chart Section -->
        <div class="bg-white p-4 sm:p-5 lg:p-6 border border-gray-200 rounded-xl shadow-sm flex flex-col relative">

            <!-- Chart Header -->
            <div class="filter-header flex items-center justify-between mb-4 sm:mb-5">
                <h1 class="text-lg sm:text-xl font-semibold text-(--blue)">
                    Stock ROD Reject
                </h1>
                <div class="filter-buttons flex items-center gap-2">
                    <button onclick="refreshChart()" title="Refresh"
                        class="filter-button p-2 sm:p-2.5 hover:bg-gray-100 text-(--blue) font-medium rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                    <button onclick="toggleFilter()"
                        class="filter-button show-text px-4 py-2 sm:px-6 sm:py-2.5 bg-(--blue) hover:opacity-90 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="chart-wrapper">
                <div class="chart-container">
                    <canvas id="rodChart"></canvas>
                </div>
            </div>

            <!-- Filter Overlay -->
            <div id="filterOverlay"
                class="hidden absolute inset-0 bg-white rounded-xl z-10 p-4 sm:p-6 animate-fadeIn overflow-y-auto">

                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-(--blue)">Filter Data</h2>
                    <button onclick="toggleFilter()"
                        class="text-gray-500 hover:text-gray-700 transition p-1 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form class="space-y-4 sm:space-y-6" id="filterForm" onsubmit="applyFilter(event)">
                    <div class="filter-form-grid grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">

                        <!-- Kotak 1 Kiri -->
                        <div class="space-y-3 sm:space-y-4 border border-gray-200 rounded-xl p-3 sm:p-4 bg-gray-50">
                            <div>
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Jenis
                                    Data</label>
                                <select id="jenisData" onchange="handleJenisDataChange()"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="" selected>Pilih Data</option>
                                    <option value="stock-rod">Stock ROD Reject</option>
                                    <option value="stock-rod-stasiun">Stock ROD Reject (Stasiun)</option>
                                    <option value="trend-kerusakan">Trend Jenis Kerusakan</option>
                                </select>
                            </div>

                            <!-- Rentang Waktu -->
                            <div id="rentangWaktuWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Rentang
                                    Waktu</label>
                                <select id="rentangWaktu" onchange="handleRentangWaktuChange()"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="" selected>Pilih Rentang Waktu</option>
                                    <option value="hari">Hari</option>
                                    <option value="bulan">Bulan</option>
                                    <option value="tahun">Tahun</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <!-- Tipe -->
                            <div id="tipeWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Tipe</label>
                                <select id="tipe"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="" selected>Pilih Tipe</option>
                                    <option value="Penerimaan">Penerimaan</option>
                                    <option value="Perbaikan">Perbaikan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kotak 2 Kanan -->
                        <div class="space-y-3 sm:space-y-4 border border-gray-200 rounded-xl p-3 sm:p-4 bg-gray-50">

                            <div id="tanggalWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Tanggal</label>
                                <input type="date"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <div id="tanggalMulaiWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Tanggal
                                    Mulai</label>
                                <input type="date"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <div id="tanggalAkhirWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Tanggal
                                    Akhir</label>
                                <input type="date"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <div id="bulanWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Bulan</label>
                                <input type="month"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <div id="tahunWrapper" class="hidden">
                                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Tahun</label>
                                <input type="number" min="2000" max="2099" placeholder="YYYY"
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>

                    </div>

                    <!-- Tombol Aksi -->
                    <div class="filter-actions flex gap-2 sm:gap-3 pt-4 justify-start">
                        <button type="button" onclick="toggleFilter()"
                            class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base bg-(--blue) hover:opacity-90 text-white font-medium rounded-lg shadow-sm transition">
                            Terapkan Filter
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        let rodChart = null;

        function createOrUpdateChart(labels, values) {
            const ctx = document.getElementById("rodChart").getContext("2d");

            const gradient = ctx.createLinearGradient(0, 0, 0, 600);
            gradient.addColorStop(0, "#1E2939");
            gradient.addColorStop(1, "#354866");

            // Jika chart sudah ada, destroy dulu sebelum buat baru
            if (rodChart) {
                rodChart.destroy();
            }

            // Responsive bar thickness
            const isMobile = window.innerWidth <= 768;
            const isSmallMobile = window.innerWidth <= 480;
            const barThickness = isMobile ? 50 : (window.innerWidth <= 1024 ? 60 : 100);
            const fontSize = isMobile ? 12 : (window.innerWidth <= 1024 ? 14 : 17);
            const xAxisFontSize = isMobile ? 11 : 15;

            if (isMobile) {
                const chartContainer = document.querySelector('.chart-container');
                const minBarSpace = 80;
                const minWidth = Math.max(600, labels.length * minBarSpace);
                chartContainer.style.width = minWidth + 'px';
            } else {
                const chartContainer = document.querySelector('.chart-container');
                chartContainer.style.width = '100%';
            }

            rodChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Jumlah Kerusakan",
                        data: values,
                        backgroundColor: gradient,
                        borderSkipped: false,
                        borderRadius: {
                            topLeft: isMobile ? 10 : 12,

                            topRight: isMobile ? 10 : 12,

                            bottomLeft: isMobile ? 10 : 12,

                            bottomRight: isMobile ? 10 : 12,
                        },
                        barThickness: barThickness,
                        hoverBackgroundColor: "#202B3C",
                        marginbottom: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 20,
                            right: isMobile ? 10 : 20,
                            bottom: isMobile ? 15 : 10,
                            left: isMobile ? 10 : 10
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: "#1f2937",
                            titleColor: "#fff",
                            bodyColor: "#fff",
                            padding: isMobile ? 8 : 10,
                            cornerRadius: 8,
                            titleFont: {
                                size: isMobile ? 12 : 14
                            },
                            bodyFont: {
                                size: isMobile ? 11 : 13
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: '#111827',
                            font: {
                                weight: '600',
                                size: fontSize
                            },
                            formatter: function(value) {
                                return value;
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: "#141414",
                                font: {
                                    size: xAxisFontSize
                                },
                                maxRotation: 0,
                                minRotation: 0,
                                autoSkip: false,
                                padding: isMobile ? 5 : 10
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grace: "15%",
                            grid: {
                                display: true,
                                borderDash: [5, 5],
                                color: "rgba(0,0,0,0.08)"
                            },
                            ticks: {
                                display: false
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        // Handle window resize to update chart responsiveness
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if (rodChart) {
                    const currentLabels = rodChart.data.labels;
                    const currentValues = rodChart.data.datasets[0].data;
                    createOrUpdateChart(currentLabels, currentValues);
                }
            }, 250);
        });

        function applyFilter(event) {
            event.preventDefault();

            const jenisData = document.getElementById('jenisData').value;
            const rentangWaktu = document.getElementById('rentangWaktu').value;
            const tipe = document.getElementById('tipe').value;

            if (!jenisData) {
                alert("Silakan pilih Jenis Data terlebih dahulu!");
                return;
            }

            if (jenisData === "stock-rod") {} else {
                if (!rentangWaktu) {
                    alert("Silakan pilih Rentang Waktu terlebih dahulu!");
                    return;
                }
            }

            let filterData = {
                jenis_data: jenisData,
                rentang_waktu: rentangWaktu
            };

            if (jenisData === "trend-kerusakan") {

                if (!tipe) {
                    alert("Silakan pilih tipe dulu!");
                    return;
                }

                filterData.tipe = tipe;
            }

            let dateInfo = '';

            // Ambil nilai input sesuai rentang waktu
            if (rentangWaktu === 'hari') {

                const tanggal = document.querySelector('#tanggalWrapper input').value;

                if (!tanggal) {
                    alert("Silakan pilih Tanggal dulu!");
                    return;
                }

                filterData.tanggal = tanggal;
                dateInfo = 'Tanggal ' + formatTanggal(tanggal);

            } else if (rentangWaktu === 'bulan') {

                const bulan = document.querySelector('#bulanWrapper input').value;

                if (!bulan) {
                    alert("Silakan pilih Bulan dulu!");
                    return;
                }

                filterData.bulan = bulan;
                dateInfo = 'Bulan ' + formatBulan(bulan);

            } else if (rentangWaktu === 'tahun') {

                const tahun = document.querySelector('#tahunWrapper input').value;

                if (!tahun) {
                    alert("Silakan isi Tahun dulu!");
                    return;
                }

                filterData.tahun = tahun;
                dateInfo = 'Tahun ' + tahun;

            } else if (rentangWaktu === 'custom') {

                const tanggalMulai = document.querySelector('#tanggalMulaiWrapper input').value;
                const tanggalAkhir = document.querySelector('#tanggalAkhirWrapper input').value;

                if (!tanggalMulai || !tanggalAkhir) {
                    alert("Silakan isi Tanggal Mulai dan Tanggal Akhir!");
                    return;
                }

                if (tanggalMulai > tanggalAkhir) {
                    alert("Tanggal Mulai tidak boleh lebih besar dari Tanggal Akhir!");
                    return;
                }

                filterData.tanggal_mulai = tanggalMulai;
                filterData.tanggal_akhir = tanggalAkhir;
                dateInfo = 'Tanggal ' + formatTanggal(tanggalMulai) + ' s/d ' + formatTanggal(tanggalAkhir);

            }

            // Kirim request ke server
            fetch('/api/rod-reject/filter', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(filterData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Response error:', text);
                            throw new Error('Server error: ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {

                    // Tutup filter overlay
                    toggleFilter();

                    // Update chart dengan data baru
                    createOrUpdateChart(data.labels, data.values);

                    // Update H1
                    updateH1Title(jenisData, tipe, dateInfo);
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error.message);
                });
        }

        function updateH1Title(jenisData, tipe, dateInfo) {
            const h1Element = document.querySelector('h1.text-lg');

            let title = '';

            if (jenisData === 'stock-rod') {
                title = 'Stock ROD Reject';
            } else if (jenisData === 'stock-rod-stasiun') {
                title = 'Stock ROD Reject (Stasiun)';
            } else if (jenisData === 'trend-kerusakan') {

                // ✅ Tambahkan tipe kalau ada
                if (tipe) {
                    title = `Trend Jenis Kerusakan ${tipe}`;
                } else {
                    title = 'Trend Jenis Kerusakan';
                }
            }

            if (dateInfo) {
                title += ' ' + dateInfo;
            }

            h1Element.textContent = title;
        }

        function formatTanggal(dateString) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'];
            const date = new Date(dateString);
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();

            return `${day} ${month} ${year}`;
        }

        function formatBulan(monthString) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'];
            const [year, month] = monthString.split('-');
            const monthName = months[parseInt(month) - 1];

            return `${monthName} ${year}`;
        }

        function handleJenisDataChange() {
            const jenisData = document.getElementById('jenisData').value;
            const rentangWaktuWrapper = document.getElementById('rentangWaktuWrapper');
            const tipeWrapper = document.getElementById('tipeWrapper');

            document.getElementById('rentangWaktu').value = '';
            hideAllDateInputs();

            rentangWaktuWrapper.classList.add('hidden');
            tipeWrapper.classList.add('hidden');

            if (jenisData === 'stock-rod') {} else if (jenisData === 'stock-rod-stasiun') {
                rentangWaktuWrapper.classList.remove('hidden');
            } else if (jenisData === 'trend-kerusakan') {
                rentangWaktuWrapper.classList.remove('hidden');
                tipeWrapper.classList.remove('hidden');
            }
        }

        function handleRentangWaktuChange() {
            const rentangWaktu = document.getElementById('rentangWaktu').value;

            hideAllDateInputs();

            if (rentangWaktu === 'hari') {
                document.getElementById('tanggalWrapper').classList.remove('hidden');
            } else if (rentangWaktu === 'bulan') {
                document.getElementById('bulanWrapper').classList.remove('hidden');
            } else if (rentangWaktu === 'tahun') {
                document.getElementById('tahunWrapper').classList.remove('hidden');
            } else if (rentangWaktu === 'custom') {
                document.getElementById('tanggalMulaiWrapper').classList.remove('hidden');
                document.getElementById('tanggalAkhirWrapper').classList.remove('hidden');
            }
        }

        function hideAllDateInputs() {
            document.getElementById('tanggalWrapper').classList.add('hidden');
            document.getElementById('tanggalMulaiWrapper').classList.add('hidden');
            document.getElementById('tanggalAkhirWrapper').classList.add('hidden');
            document.getElementById('bulanWrapper').classList.add('hidden');
            document.getElementById('tahunWrapper').classList.add('hidden');
        }

        function toggleFilter() {
            const overlay = document.getElementById('filterOverlay');
            overlay.classList.toggle('hidden');

            if (!overlay.classList.contains('hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function refreshChart() {
            const initialLabels = {!! json_encode($labels) !!};
            const initialValues = {!! json_encode($values) !!};

            createOrUpdateChart(initialLabels, initialValues);

            const h1Element = document.querySelector('h1.text-lg');
            if (h1Element) {
                h1Element.textContent = 'Stock ROD Reject';
            }

            document.getElementById('jenisData').value = '';
            document.getElementById('rentangWaktu').value = '';
            hideAllDateInputs();
            document.getElementById('rentangWaktuWrapper').classList.add('hidden');
            document.getElementById('tipeWrapper').classList.add('hidden');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const initialLabels = {!! json_encode($labels) !!};
            const initialValues = {!! json_encode($values) !!};
            createOrUpdateChart(initialLabels, initialValues);
        });
    </script>
@endpush
