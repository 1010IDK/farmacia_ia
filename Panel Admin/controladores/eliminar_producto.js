/* =======================================================================================================================================================================
         * LÓGICA PARA LA ELIMINACIÓN DE PRODUCTOS
         =========================================================================================================================================================================*/

$(document).ready(function () {
  // Delegación de eventos para el botón de eliminar en la tabla
  $(".productos_table_body").on("click", ".btn-eliminar-producto", function () {
    var id_producto = $(this).data("id-producto");

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Estás seguro que desea eliminar este producto?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Confirmar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "controladores/eliminar_producto.php",
          data: {
            id_producto: id_producto,
          },
          success: function (data) {
            var respuesta = data.trim();
            if (respuesta === "success") {
              Swal.fire({
                title: "¡Eliminado!",
                text: "El producto ha sido eliminado correctamente.",
                icon: "success",
              }).then(() => {
                window.location.reload();
              });
            } else {
              Swal.fire({
                title: "Error",
                text: respuesta.replace("error:", "").trim(),
                icon: "error",
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Error en la petición AJAX:", status, error);
            Swal.fire({
              title: "Error",
              text: "Error en la petición AJAX.",
              icon: "error",
            });
          },
        });
      }
    });
  });
});
