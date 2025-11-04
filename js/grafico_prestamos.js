fetch('controllers/consulta_prestamos.php')
  .then(response => response.json())
  .then(data => {
    const total = data.total_prestamos || 0;

    const ctx = document.getElementById('graficoTotalPrestamos').getContext('2d');
    new Chart(ctx, {
      type: 'polarArea',
      data: {
        labels: ['Préstamos totales'],
        datasets: [{
          label: 'Total de préstamos',
          data: [total],
          backgroundColor: ['#87CEEB'], // Azul cielo
          borderColor: ['#4682B4'],     // Azul acero
          borderWidth: 2,
          hoverOffset: 12
        }]
      },
      options: {
        cutout: '70%',
        plugins: {
          title: {
            display: true,
            text: 'Total de préstamos realizados',
            font: { size: 18, weight: 'bold' }
          },
          legend: {
            display: true,
            position: 'bottom',
            labels: { color: '#333', font: { size: 14 } }
          }
        },
        animation: {
          animateRotate: true,
          animateScale: true
        }
      }
    });
  })
  .catch(error => console.error('Error al obtener los datos:', error));
