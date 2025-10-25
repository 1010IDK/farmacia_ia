/* =======================================================================================================================================================================
         * LÓGICA PARA EL MODAL DE MODIFICACIÓN DE USUARIOS
         =========================================================================================================================================================================*/

$(document).ready(function () {
  // La función `obtenerDatosUsuario` se ejecuta cuando se abre el modal para rellenar los campos
  function obtenerDatosUsuario(id_trabajador) {
    $.ajax({
      type: "POST",
      url: "controladores/obtener_usuario_por_id.php",
      data: {
        id_trabajador: id_trabajador,
      },
      dataType: "json",
      success: function (data) {
        // Rellenar los campos del modal de modificación con los datos del usuario
        $("#id_trabajador_modificar").val(data.id_trabajador);
        $("#rol_modificar").val(data.id_rol);
        $("#nombre_modificar").val(data.nombre);
        $("#apellido_modificar").val(data.apellido);
        $("#cedula_modificar").val(data.cedula);
        $("#telefono_modificar").val(data.numero_de_telefono);
        $("#nombre_de_usuario_modificar").val(data.nombre_de_usuario);
        $("#correo_electronico_modificar").val(data.correo_electronico);
      },
      error: function (xhr, status, error) {
        console.error("Error en la petición AJAX:", status, error);
        Swal.fire({
          title: "Error",
          text: "No se pudo obtener la información del usuario.",
          icon: "error",
        });
      },
    });
  }

  // Delegación de eventos para el botón de modificar en el cuerpo de la tabla
  $(".users_table_body").on("click", ".btn-modificar-usuario", function () {
    var id_trabajador = $(this).data("id-trabajador");
    obtenerDatosUsuario(id_trabajador);
  });

  // La función que maneja el envío del formulario de modificación
  $("#formModificarUsuario").submit(function (e) {
    e.preventDefault();

    // Obtener los datos del formulario de modificación
    var id_trabajador = $("#id_trabajador_modificar").val();
    var rol = $("#rol_modificar").val();
    var nombre = $("#nombre_modificar").val();
    var apellido = $("#apellido_modificar").val();
    var cedula = $("#cedula_modificar").val();
    var telefono = $("#telefono_modificar").val();
    var nombre_de_usuario = $("#nombre_de_usuario_modificar").val();
    var correo_electronico = $("#correo_electronico_modificar").val();

    if (!id_trabajador) {
      Swal.fire({
        title: "Error",
        text: "ID de usuario no encontrado. Recargue la página.",
        icon: "error",
      });
      return;
    }

    // Validación de campos vacíos
    if (
      !rol ||
      !nombre ||
      !apellido ||
      !cedula ||
      !telefono ||
      !nombre_de_usuario ||
      !correo_electronico
    ) {
      Swal.fire({
        title: "Error",
        text: "Todos los campos tienen que estar rellenos.",
        icon: "error",
      });
      return;
    }

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Los cambios del usuario se guardarán.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, modificar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "controladores/modificar_usuario.php",
          data: {
            id_trabajador: id_trabajador,
            rol: rol,
            nombre: nombre,
            apellido: apellido,
            cedula: cedula,
            telefono: telefono,
            nombre_de_usuario: nombre_de_usuario,
            correo_electronico: correo_electronico,
          },
          success: function (data) {
            var respuesta = data.trim();
            if (respuesta === "success") {
              Swal.fire({
                title: "¡Modificado!",
                text: "El usuario se ha modificado correctamente.",
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
