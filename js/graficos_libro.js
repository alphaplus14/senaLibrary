fetch('controllers/consultar_reservas.php')
  .then(response => response.json())
  .then(result => {
    if (!result.success || !result.data || result.data.length === 0) {
      console.error('No hay datos de libros prestados');
      mostrarMensajeSinDatos();
      return;
    }

    const libros = result.data;
    
    // Extraer títulos y cantidades
    const titulos = libros.map(libro => {
      // Truncar titulos muy largos como mi v****a
      const tituloCorto = libro.titulo.length > 30 
        ? libro.titulo.substring(0, 30) + '...' 
        : libro.titulo;
      return tituloCorto;
    });
    
    const cantidades = libros.map(libro => libro.total);
    
    // Colores  para las barras
    const colores = [
      '#FF6384', 
      '#36A2EB', 
      '#FFCE56', 
      '#4BC0C0', 
      '#9966FF'  
    ];

    const ctx = document.getElementById('graficoTotalLibros').getContext('2d');
    new Chart(ctx, {
      type: 'bar', 
      data: {
        labels: titulos,
        datasets: [{
          label: 'Préstamos',
          data: cantidades,
          backgroundColor: colores.slice(0, libros.length),
          borderColor: colores.slice(0, libros.length).map(color => color + 'DD'),
          borderWidth: 2,
          borderRadius: 8,
          barThickness: 50
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              font: {
                size: 14,
                weight: 'bold'
              }
            }
          },
          title: {
            display: true,
            text: 'Top 5 Libros Más Prestados',
            font: {
              size: 18,
              weight: 'bold'
            },
            padding: {
              top: 10,
              bottom: 20
            }
          },
          tooltip: {
            callbacks: {
              // Mostrar el título completo 
              title: function(context) {
                const index = context[0].dataIndex;
                return libros[index].titulo;
              },
              afterLabel: function(context) {
                const index = context.dataIndex;
                return 'Autor: ' + libros[index].autor;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: {
                size: 12
              }
            },
            title: {
              display: true,
              text: 'Cantidad de Préstamos',
              font: {
                size: 14,
                weight: 'bold'
              }
            }
          },
          x: {
            ticks: {
              font: {
                size: 11
              },
              maxRotation: 45,
              minRotation: 45
            },
            title: {
              display: true,
              text: 'Libros',
              font: {
                size: 14,
                weight: 'bold'
              }
            }
          }
        }
      }
    });
  })
  .catch(error => {
    console.error('Error al obtener los datos:', error);
    mostrarMensajeSinDatos();
  });

// Función para mostrar mensaje cuando no hay datos
function mostrarMensajeSinDatos() {
  const canvas = document.getElementById('graficoTotalLibros');
  const ctx = canvas.getContext('2d');
  
  ctx.font = '16px Arial';
  ctx.fillStyle = '#666';
  ctx.textAlign = 'center';
  ctx.fillText('No hay datos de préstamos disponibles', canvas.width / 2, canvas.height / 2);
}