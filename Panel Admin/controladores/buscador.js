// controladores/buscador.js (versión jQuery para tu sistema)
function aplicarBuscador(tablaId, inputId) {
  $("#" + inputId).on("input", function () {
    var texto = $(this).val().toLowerCase().trim();

    $("#" + tablaId + " tbody tr").each(function () {
      var filaText = $(this).text().toLowerCase();
      $(this).toggle(filaText.indexOf(texto) > -1);
    });
  });

  // Limpiar al usar la X o borrar todo
  $("#" + inputId).on("search keyup", function () {
    if ($(this).val() === "") {
      $("#" + tablaId + " tbody tr").show();
    }
  });
}
