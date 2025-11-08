// controladores/paginacion.js
function aplicarPaginacion(tablaId, filasPorPagina = 10) {
  const $tabla = $("#" + tablaId);
  if (!$tabla.length) return;

  const $filas = $tabla.find("tbody tr");
  const totalFilas = $filas.length;
  const totalPaginas = Math.ceil(totalFilas / filasPorPagina);

  if (totalFilas <= filasPorPagina) return;

  // Ocultar todas las filas
  $filas.hide();

  // Mostrar solo las de la página actual
  const mostrarPagina = (pagina) => {
    const inicio = (pagina - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;
    $filas.hide().slice(inicio, fin).show();
  };

  // Crear controles de paginación
  const $paginacion = $(`
    <div class="d-flex justify-content-center mt-3">
      <nav aria-label="Paginación">
        <ul class="pagination pagination-sm" id="paginacion_${tablaId}"></ul>
      </nav>
    </div>
  `);
  $tabla.after($paginacion);
  const $lista = $paginacion.find("ul");

  // Generar botones
  const crearBotones = (paginaActual) => {
    $lista.empty();

    // Anterior
    $lista.append(`
      <li class="page-item ${paginaActual === 1 ? "disabled" : ""}">
        <a class="page-link" href="#" data-pagina="${
          paginaActual - 1
        }">« Anterior</a>
      </li>
    `);

    // Números de página
    const inicio = Math.max(1, paginaActual - 2);
    const fin = Math.min(totalPaginas, paginaActual + 2);

    if (inicio > 1) {
      $lista.append(
        `<li class="page-item"><a class="page-link" href="#" data-pagina="1">1</a></li>`
      );
      if (inicio > 2)
        $lista.append(
          `<li class="page-item disabled"><span class="page-link">...</span></li>`
        );
    }

    for (let i = inicio; i <= fin; i++) {
      $lista.append(`
        <li class="page-item ${i === paginaActual ? "active" : ""}">
          <a class="page-link" href="#" data-pagina="${i}">${i}</a>
        </li>
      `);
    }

    if (fin < totalPaginas) {
      if (fin < totalPaginas - 1)
        $lista.append(
          `<li class="page-item disabled"><span class="page-link">...</span></li>`
        );
      $lista.append(
        `<li class="page-item"><a class="page-link" href="#" data-pagina="${totalPaginas}">${totalPaginas}</a></li>`
      );
    }

    // Siguiente
    $lista.append(`
      <li class="page-item ${paginaActual === totalPaginas ? "disabled" : ""}">
        <a class="page-link" href="#" data-pagina="${
          paginaActual + 1
        }">Siguiente »</a>
      </li>
    `);
  };

  // Eventos
  $lista.on("click", "a", function (e) {
    e.preventDefault();
    const pagina = parseInt($(this).data("pagina"));
    if (pagina >= 1 && pagina <= totalPaginas) {
      mostrarPagina(pagina);
      crearBotones(pagina);
    }
  });

  // Iniciar
  mostrarPagina(1);
  crearBotones(1);
}
