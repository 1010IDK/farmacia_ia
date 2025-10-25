$(document).ready(function () {
  $("#formRegistro").submit(function (e) {
    e.preventDefault();

    var rol = $("#rol").val();
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();
    var cedula = $("#cedula").val();
    var telefono = $("#telefono").val();
    var nombre_de_usuario = $("#nombre_de_usuario").val();
    var correo_electronico = $("#correo_electronico").val();
    var contrasena = $("#contrasena").val();
    var confirmar_contraseña = $("#confirmar_contraseña").val();

    $.ajax({
      type: "POST",
      url: "controladores/completar_registro_usuario.php",
      data: {
        rol: rol,
        nombre: nombre,
        apellido: apellido,
        cedula: cedula,
        telefono: telefono,
        nombre_de_usuario: nombre_de_usuario,
        correo_electronico: correo_electronico,
        contrasena: contrasena,
        confirmar_contraseña: confirmar_contraseña,
      },
      success: function (data) {
        var respuesta = data.trim();
        if (respuesta === "user_found") {
          Swal.fire({
            title: "Nombre De Usuario ya existente",
            text: "El nombre de usuario que ingresaste ya existe, por favor elige otro.",
            icon: "warning",
          });
        } else if (respuesta === "cedula_found") {
          Swal.fire({
            title: "Cédula ya registrada",
            text: "La cédula que ingresaste ya existe en nuestra base de datos, por favor elige otra.",
            icon: "warning",
          });
        } else if (respuesta === "correo_found") {
          Swal.fire({
            title: "Correo ya registrado",
            text: "El correo electrónico que ingresaste ya está asociado a una cuenta.",
            icon: "warning",
          });
        } else if (respuesta === "telefono_found") {
          Swal.fire({
            title: "Teléfono ya registrado",
            text: "El número de teléfono que ingresaste ya está asociado a una cuenta.",
            icon: "warning",
          });
        } else if (respuesta === "success") {
          Swal.fire({
            title: "Éxito",
            text: "El usuario se ha registrado correctamente.",
            icon: "success",
          }).then(() => {
            window.location.reload();
          });
        } else if (respuesta.startsWith("error:")) {
          Swal.fire({
            title: "Error",
            text: respuesta.replace("error:", "").trim(),
            icon: "error",
          });
        } else {
          Swal.fire({
            title: "Error",
            text: "No se pudo registrar el usuario. Inténtelo de nuevo.",
            icon: "error",
          });
        }
      },
      error: function () {
        Swal.fire({
          title: "Error",
          text: "Error en la petición AJAX.",
          icon: "error",
        });
      },
    });
  });
});
