

fetch('controllers/consultar_top_usuarios.php')
  .then(response => response.json())
  .then(result => {
    if (!result.success || !result.data || result.data.length === 0) {
      console.error('No hay datos de usuarios');
      mostrarMensajeSinDatosUsuarios();
      return;
    }

    const usuarios = result.data;
    

    const nombres = usuarios.map(usuario => {

      const nombreCorto = usuario.nombre_completo.length > 20 
        ? usuario.nombre_completo.substring(0, 20) + '...' 
        : usuario.nombre_completo;
      return nombreCorto;
    });
    
    const cantidades = usuarios.map(usuario => usuario.total_libros);
    
    const colores = [
      '#FF6384', 
      '#36A2EB', 
      '#FFCE56', 
      '#4BC0C0', 
      '#9966FF'  
    ];

    const ctx = document.getElementById('graficoTotalReservas').getContext('2d');
    
    // Destruir gráfica anterior si existe
    const chartExistente = Chart.getChart('graficoTotalReservas');
    if (chartExistente) {
      chartExistente.destroy();
    }
    
    new Chart(ctx, {
      type: 'doughnut', 
      data: {
        labels: nombres,
        datasets: [{
          label: 'Libros Solicitados',
          data: cantidades,
          backgroundColor: colores.slice(0, usuarios.length),
          borderColor: '#ffffff',
          borderWidth: 2,
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'bottom',
            labels: {
              font: {
                size: 11
              },
              padding: 10,
              boxWidth: 15
            }
          },
          title: {
            display: true,
            text: 'Top 5 Usuarios con Más Solicitudes',
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
                const index = context.dataIndex;
                const usuario = usuarios[index];
                return [
                  `Libros solicitados: ${usuario.total_libros}`,
                  `Reservas realizadas: ${usuario.total_reservas}`
                ];
              }
            }
          }
        }
      }
    });
  })
  .catch(error => {
    console.error('Error al obtener los datos:', error);
    mostrarMensajeSinDatosUsuarios();
  });

// Funcinn para mostrar mensaje cuando no hay datos
function mostrarMensajeSinDatosUsuarios() {
  const canvas = document.getElementById('graficoTotalReservas');
  const ctx = canvas.getContext('2d');
  
  ctx.font = '14px Arial';
  ctx.fillStyle = '#666';
  ctx.textAlign = 'center';
  ctx.fillText('No hay datos de usuarios disponibles', canvas.width / 2, canvas.height / 2);
}