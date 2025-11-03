fetch('controllers/consulta_libros.php')
  .then(response => response.json())
  .then(data => {
    const total = data.total_libros || 0;

    const ctx = document.getElementById('graficoTotalLibros').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Total de libros'],
        datasets: [{
          label: 'Cantidad total',
          data: [total],
          backgroundColor: ['#4e73df'],
          borderColor: ['#2e59d9'],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        },
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Cantidad total de libros en la biblioteca'
          }
        }
      }
    });
  })
  .catch(error => console.error('Error al obtener los datos:', error));
