(function (window, $) {
    'use strict';

    const MONTH_NAMES_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const MONTH_NAMES_FULL = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    const state = {
        period: 'month',
        year: new Date().getFullYear(),
        month: new Date().getMonth() + 1,
    };

    let revenueChart = null;
    let ordersChart = null;

    function isDark() {
        return document.documentElement.classList.contains('dark');
    }

    function chartColors() {
        const dark = isDark();
        return {
            text: dark ? '#9CA3AF' : '#6B7280',
            grid: dark ? 'rgba(75, 85, 99, 0.35)' : 'rgba(229, 231, 235, 0.9)',
            tooltipBg: dark ? '#111827' : '#FFFFFF',
            tooltipText: dark ? '#F3F4F6' : '#111827',
            revenueLine: dark ? '#60A5FA' : '#2563EB',
            revenueFill: dark ? 'rgba(96, 165, 250, 0.12)' : 'rgba(37, 99, 235, 0.08)',
            ordersLine: dark ? '#34D399' : '#059669',
            ordersFill: dark ? 'rgba(52, 211, 153, 0.12)' : 'rgba(5, 150, 105, 0.08)',
        };
    }

    function seededRandom(seed) {
        const x = Math.sin(seed * 12.9898 + seed * 78.233) * 43758.5453;
        return x - Math.floor(x);
    }

    function daysInMonth(year, month) {
        return new Date(year, month, 0).getDate();
    }

    function generateDailyData(year, month) {
        const days = daysInMonth(year, month);
        const labels = Array.from({ length: days }, (_, i) => String(i + 1));
        const revenue = [];
        const orders = [];
        const baseSeed = year * 100 + month;

        for (let day = 1; day <= days; day++) {
            const seed = baseSeed * 100 + day;
            revenue.push(Math.floor(3500000 + seededRandom(seed) * 6000000));
            orders.push(Math.floor(18 + seededRandom(seed + 1) * 45));
        }

        return {
            labels,
            revenue,
            orders,
            subtitle: 'Per hari · ' + MONTH_NAMES_FULL[month - 1] + ' ' + year,
        };
    }

    function generateYearlyData(year) {
        const revenue = [];
        const orders = [];

        for (let month = 1; month <= 12; month++) {
            const seed = year * 100 + month;
            revenue.push(Math.floor(85000000 + seededRandom(seed) * 75000000));
            orders.push(Math.floor(550 + seededRandom(seed + 1) * 400));
        }

        return {
            labels: MONTH_NAMES_SHORT,
            revenue,
            orders,
            subtitle: 'Per bulan · ' + year,
        };
    }

    function getChartData() {
        if (state.period === 'year') {
            return generateYearlyData(state.year);
        }

        return generateDailyData(state.year, state.month);
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(value);
    }

    function compactCurrency(value) {
        if (value >= 1000000000) {
            return (value / 1000000000).toFixed(1) + ' M';
        }
        if (value >= 1000000) {
            return (value / 1000000).toFixed(0) + ' jt';
        }
        if (value >= 1000) {
            return (value / 1000).toFixed(0) + ' rb';
        }
        return value;
    }

    function buildChartOptions(colors, valueFormatter) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: colors.tooltipBg,
                    titleColor: colors.tooltipText,
                    bodyColor: colors.tooltipText,
                    borderColor: colors.grid,
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label(context) {
                            return ' ' + valueFormatter(context.parsed.y);
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: colors.text,
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: state.period === 'month' ? 15 : 12,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: colors.grid },
                    ticks: {
                        color: colors.text,
                        callback(value) {
                            return valueFormatter(value, true);
                        },
                    },
                },
            },
        };
    }

    function createLineChart(canvasId, dataset, colors, options) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) {
            return null;
        }

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataset.labels,
                datasets: [{
                    data: dataset.values,
                    borderColor: dataset.lineColor,
                    backgroundColor: dataset.fillColor,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: dataset.lineColor,
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                }],
            },
            options,
        });
    }

    function populateYearOptions() {
        const $year = $('#chart-filter-year');
        const currentYear = new Date().getFullYear();
        $year.empty();

        for (let year = currentYear; year >= currentYear - 4; year--) {
            $year.append(
                $('<option>', { value: year, text: 'Tahun ' + year, selected: year === state.year })
            );
        }
    }

    function syncFilterControls() {
        $('#chart-filter-year').val(String(state.year));
        $('#chart-filter-month').val(String(state.month));
        $('#chart-filter-month').toggleClass('hidden', state.period === 'year');
    }

    function updateCharts() {
        const data = getChartData();
        const colors = chartColors();

        $('#revenue-chart-subtitle, #orders-chart-subtitle').text(data.subtitle);

        if (revenueChart) {
            revenueChart.data.labels = data.labels;
            revenueChart.data.datasets[0].data = data.revenue;
            revenueChart.data.datasets[0].borderColor = colors.revenueLine;
            revenueChart.data.datasets[0].backgroundColor = colors.revenueFill;
            revenueChart.data.datasets[0].pointBackgroundColor = colors.revenueLine;
            revenueChart.options = buildChartOptions(colors, function (v, compact) {
                return compact ? compactCurrency(v) : formatCurrency(v);
            });
            revenueChart.update();
        }

        if (ordersChart) {
            ordersChart.data.labels = data.labels;
            ordersChart.data.datasets[0].data = data.orders;
            ordersChart.data.datasets[0].borderColor = colors.ordersLine;
            ordersChart.data.datasets[0].backgroundColor = colors.ordersFill;
            ordersChart.data.datasets[0].pointBackgroundColor = colors.ordersLine;
            ordersChart.options = buildChartOptions(colors, function (v, compact) {
                return compact ? v : v + ' order';
            });
            ordersChart.update();
        }
    }

    function initDashboardCharts() {
        if (typeof Chart === 'undefined') {
            return;
        }

        populateYearOptions();
        syncFilterControls();

        const colors = chartColors();
        const initialData = getChartData();

        revenueChart = createLineChart('revenue-chart', {
            labels: initialData.labels,
            values: initialData.revenue,
            lineColor: colors.revenueLine,
            fillColor: colors.revenueFill,
        }, colors, buildChartOptions(colors, function (v, compact) {
            return compact ? compactCurrency(v) : formatCurrency(v);
        }));

        ordersChart = createLineChart('orders-chart', {
            labels: initialData.labels,
            values: initialData.orders,
            lineColor: colors.ordersLine,
            fillColor: colors.ordersFill,
        }, colors, buildChartOptions(colors, function (v, compact) {
            return compact ? v : v + ' order';
        }));

        $('#revenue-chart-subtitle, #orders-chart-subtitle').text(initialData.subtitle);

        $('.chart-period-btn').on('click', function () {
            state.period = $(this).data('period');
            $('.chart-period-btn').removeClass('is-active');
            $(this).addClass('is-active');
            syncFilterControls();
            updateCharts();
        });

        $('#chart-filter-year').on('change', function () {
            state.year = Number($(this).val());
            updateCharts();
        });

        $('#chart-filter-month').on('change', function () {
            state.month = Number($(this).val());
            updateCharts();
        });

        $('#theme-toggle').on('click', function () {
            setTimeout(updateCharts, 50);
        });
    }

    $(initDashboardCharts);
})(window, jQuery);
