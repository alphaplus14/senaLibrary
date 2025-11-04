fetch('controllers/consultar_reservas.php')
  .then(response => response.json())
  .then(data => {
    const total = data.total_reservas || 0;

    const ctx = document.getElementById('graficoTotalReservas').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Total de reservas'],
        datasets: [{
          label: 'Cantidad total',
          data: [total],
          backgroundColor: ['#1cbfc8ff'],
          borderColor: ['#178ea6ff'],
          borderWidth: 1
        }]
      },
      options: {
        scales: { y: { beginAtZero: true } },
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Cantidad total de reservas registradas'
          }
        }
      }
    });
  })
  .catch(error => console.error('Error al obtener los datos:', error));
