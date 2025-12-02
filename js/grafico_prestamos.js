// ====================================
// VERSIÓN ALTERNATIVA: CONTADOR SIMPLE Y VISUAL
// Más limpio y fácil de ver
// ====================================

fetch('controllers/consultar_total_prestamos.php')
  .then(response => response.json())
  .then(result => {
    if (!result.success) {
      console.error('Error al obtener datos de préstamos');
      mostrarMensajeSinDatosPrestamos();
      return;
    }

    const total = result.total || 0;

    const ctx = document.getElementById('graficoTotalPrestamos').getContext('2d');
    
    // Destruir gráfica anterior si existe
    const chartExistente = Chart.getChart('graficoTotalPrestamos');
    if (chartExistente) {
      chartExistente.destroy();
    }
    
    // Gráfico de barra horizontal con un solo valor
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Préstamos'],
        datasets: [{
          label: 'Total',
          data: [total],
          backgroundColor: ['#28a745'],
          borderColor: ['#1e7e34'],
          borderWidth: 2,
          borderRadius: 10
        }]
      },
      options: {
        indexAxis: 'y', // Barra horizontal
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Total de Préstamos Realizados',
            font: {
              size: 14,
              weight: 'bold'
            },
            padding: {
              top: 5,
              bottom: 10
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `Total: ${context.parsed.x} préstamos`;
              }
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: {
                size: 12,
                weight: 'bold'
              }
            },
            grid: {
              display: true,
              color: '#e9ecef'
            }
          },
          y: {
            ticks: {
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            grid: {
              display: false
            }
          }
        }
      },
      plugins: [{
        // Plugin para mostrar el número dentro de la barra
        id: 'barText',
        afterDatasetsDraw: function(chart) {
          const ctx = chart.ctx;
          chart.data.datasets.forEach((dataset, i) => {
            const meta = chart.getDatasetMeta(i);
            meta.data.forEach((bar, index) => {
              const data = dataset.data[index];
              
              ctx.fillStyle = '#ffffff';
              ctx.font = 'bold 20px Arial';
              ctx.textAlign = 'center';
              ctx.textBaseline = 'middle';
              
              const x = bar.x - 30;
              const y = bar.y;
              
              ctx.fillText(data, x, y);
            });
          });
        }
      }]
    });
  })
  .catch(error => {
    console.error('Error al obtener los datos:', error);
    mostrarMensajeSinDatosPrestamos();
  });

// Función para mostrar mensaje cuando no hay datos
function mostrarMensajeSinDatosPrestamos() {
  const canvas = document.getElementById('graficoTotalPrestamos');
  const ctx = canvas.getContext('2d');
  
  ctx.font = '14px Arial';
  ctx.fillStyle = '#666';
  ctx.textAlign = 'center';
  ctx.fillText('No hay datos de préstamos disponibles', canvas.width / 2, canvas.height / 2);
}