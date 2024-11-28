$(document).ready(function () {
  const ctx = document.getElementById("grafico-indicadores").getContext("2d");
  let chart;

  // Variables para manejar las fechas
  const fechaDesde = $("#fecha-desde");
  const fechaHasta = $("#fecha-hasta");
  const hoy = new Date().toISOString().split("T")[0];

  // Establecer fecha máxima para "Hasta" como el día de hoy
  fechaHasta.attr("max", hoy);

  // Validar cambios en la fecha "Desde"
  fechaDesde.on("change", function () {
    if (fechaDesde.val()) {
      // Establecer la fecha mínima para "Hasta"
      fechaHasta.attr("min", fechaDesde.val());
    } else {
      // Restablecer el límite mínimo de "Hasta" si "Desde" está vacío
      fechaHasta.removeAttr("min");
    }
  });

  // Validar cambios en la fecha "Hasta"
  fechaHasta.on("change", function () {
    if (fechaHasta.val()) {
      // Establecer la fecha máxima para "Desde"
      fechaDesde.attr("max", fechaHasta.val());
    } else {
      // Restablecer el límite máximo de "Desde" si "Hasta" está vacío
      fechaDesde.removeAttr("max");
    }
  });

  // Función para generar el gráfico
  function generarGrafico(indicador, desde, hasta) {
    Swal.fire({
      title: "Cargando datos...",
      text: "Por favor espera mientras generamos el gráfico.",
      icon: "info",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    // Llamada a la API
    $.get(`https://mindicador.cl/api/${indicador}`, function (data) {
      // Filtrar los datos por rango de fechas
      const datosFiltrados = data.serie.filter((item) => {
        const fecha = new Date(item.fecha);
        return fecha >= new Date(desde) && fecha <= new Date(hasta);
      });

      datosFiltrados.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));

      // Preparar los datos para el gráfico
      const etiquetas = datosFiltrados.map((item) => item.fecha.split("T")[0]);
      const valores = datosFiltrados.map((item) => item.valor);

      // Destruir el gráfico anterior si existe
      if (chart) chart.destroy();

      // Crear un nuevo gráfico
      chart = new Chart(ctx, {
        type: "line",
        data: {
          labels: etiquetas,
          datasets: [
            {
              label: `Valor de ${indicador}`,
              data: valores,
              borderColor: "rgba(75, 192, 192, 1)",
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              borderWidth: 2,
              tension: 0.3,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top",
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return `$ ${context.raw.toLocaleString("es-CL")}`;
                },
              },
            },
          },
          scales: {
            x: {
              title: {
                display: true,
                text: "Fecha",
              },
            },
            y: {
              title: {
                display: true,
                text: "Valor",
              },
              ticks: {
                callback: function (value) {
                  return `$ ${value.toLocaleString("es-CL")}`;
                },
              },
            },
          },
        },
      });

      // Cerrar el mensaje de carga
      Swal.fire(
        "¡Listo!",
        "El gráfico se ha generado correctamente.",
        "success"
      );
    }).fail(function () {
      Swal.fire(
        "¡Error!",
        "No se pudieron obtener los datos de la API.",
        "error"
      );
    });
  }

  // Manejar el envío del formulario
  $("#form-grafico").submit(function (e) {
    e.preventDefault();

    const indicador = $("#indicador").val();
    const desde = fechaDesde.val();
    const hasta = fechaHasta.val();

    // Validar rango de fechas antes de generar el gráfico
    if (!desde || !hasta) {
      Swal.fire(
        "¡Advertencia!",
        "Por favor selecciona un rango de fechas.",
        "warning"
      );
      return;
    }

    if (desde > hasta) {
      Swal.fire(
        "¡Error!",
        'La fecha "Desde" no puede ser mayor que la fecha "Hasta".',
        "error"
      );
      return;
    }

    // Generar el gráfico
    generarGrafico(indicador, desde, hasta);
  });
});
