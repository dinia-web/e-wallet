document.addEventListener('DOMContentLoaded', function () {

    const chartData = document.getElementById('chart-data');

    if(!chartData) return;

    // Ambil data dari Blade
    const bulan = JSON.parse(chartData.dataset.bulan);
    const dataDispen = JSON.parse(chartData.dataset.dispen);
    const dataSakit = JSON.parse(chartData.dataset.sakit);
    const dataIzin = JSON.parse(chartData.dataset.izin);
    const dataTerlambat = JSON.parse(chartData.dataset.terlambat); // tambahan

    // ===============================
    // Chart Dispen
    // ===============================
    const dispenCanvas = document.getElementById('dispenChart');

    if(dispenCanvas){
        const ctxDispen = dispenCanvas.getContext('2d');

        new Chart(ctxDispen, {
            type: 'bar',
            data: {
                labels: bulan,
                datasets: [{
                    label: 'Dispen',
                    data: dataDispen,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ===============================
    // Chart Perizinan
    // ===============================
    const izinCanvas = document.getElementById('izinChart');

    if(izinCanvas){
        const ctxIzin = izinCanvas.getContext('2d');

        new Chart(ctxIzin, {
            type: 'bar',
            data: {
                labels: bulan,
                datasets: [

                    {
                        label: 'Sakit',
                        data: dataSakit,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    },

                    {
                        label: 'Izin',
                        data: dataIzin,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },

                    {
                        label: 'Terlambat',
                        data: dataTerlambat,
                        backgroundColor: 'rgba(245, 158, 11, 0.7)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 1
                    }

                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

});