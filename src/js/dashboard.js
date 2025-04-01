if(window.location.pathname === '/admin/index'){

    // Obtén el contexto del canvas
    var ctx = document.getElementById('barChart').getContext('2d');

    // Crear el gráfico de barras
    var barChart = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico (en este caso es un gráfico de barras)
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'], // Etiquetas para el eje X
            datasets: [{
                label: 'Ventas Mensuales',
                data: [120, 150, 180, 130, 200], // Los datos que se mostrarán en el gráfico
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de las barras
                borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Hacer que el gráfico sea responsive
            scales: {
                y: {
                    beginAtZero: true // Empieza el eje Y en 0
                }
            }
        }
    });
    
}