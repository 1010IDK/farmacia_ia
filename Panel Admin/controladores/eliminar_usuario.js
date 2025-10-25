/* =======================================================================================================================================================================
         * LÓGICA PARA EL MODAL DE ELIMINACIÓN DE USUARIOS
         =========================================================================================================================================================================*/

$(document).ready(function () {
  // Delegación de eventos para el botón de eliminar en el cuerpo de la tabla
  $(".users_table_body").on("click", ".btn-eliminar-usuario", function () {
    var id_trabajador = $(this).data("id-trabajador");

    Swal.fire({
      title: "¿Estás seguro que desea eliminar este usuario?",
      text: "Esta acción no se puede deshacer",
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
          url: "controladores/eliminar_usuario.php",
          data: {
            id_trabajador: id_trabajador,
          },
          success: function (data) {
            var respuesta = data.trim();
            if (respuesta === "success") {
              Swal.fire({
                title: "¡Eliminado!",
                text: "El usuario ha sido eliminado correctamente.",
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
