document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('barChart').getContext('2d');
    var barChart;
    var periodoSelect = document.getElementById('periodoSelect');

    async function cargarDatos(periodo) {
        try {
            const response = await fetch(`/api/ganancias?anio=2025&periodo=${periodo}`);
    
            // Si el servidor responde con un 204 No Content, devolvemos un array vacío
            if (response.status === 204) {
                actualizarGrafica([], []);
                return;
            }
    
            const data = await response.json();
    
            // Procesar los datos de la API
            const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            const labels = data.length > 0 ? data.map(item => meses[item.mes - 1]) : ["Sin datos"];
            const ganancias = data.length > 0 ? data.map(item => parseFloat(item.ganancias)) : [0];
    
            actualizarGrafica(labels, ganancias);
        } catch (error) {
            console.error("Error al cargar datos:", error);
        }
    }
    
    function actualizarGrafica(labels, ganancias) {
        if (barChart) {
            barChart.destroy();
        }
    
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ganancias',
                    data: ganancias,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Cargar datos iniciales
    cargarDatos(periodoSelect.value);

    // Cambiar los datos cuando el usuario seleccione otro período
    periodoSelect.addEventListener("change", function() {
        cargarDatos(this.value);
    });
});
