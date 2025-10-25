$(document).ready(function () {
  // Escucha el evento de envío del formulario
  $("#formLogin").submit(function (e) {
    // Evita que el formulario se envíe de la forma tradicional
    e.preventDefault();

    // Agarra los valores del formulario
    var usuario = $.trim($("#usuario").val());
    var password = $.trim($("#password").val());

    // Establece mensajes de validación personalizados para cada campo
    if (usuario == "") {
      $("#usuario")[0].setCustomValidity(
        "Por favor, ingresa tu nombre de usuario."
      );
    } else {
      $("#usuario")[0].setCustomValidity("");
    }

    if (password == "") {
      $("#password")[0].setCustomValidity("Por favor, ingresa la contraseña.");
    } else {
      $("#password")[0].setCustomValidity("");
    }

    // Si el formulario está vacío, el sistema da la siguiente alerta
    if (usuario == "" || password == "") {
      Swal.fire({
        icon: "error",
        title: "Ha Ocurrido un Error",
        text: "Debe ingresar un nombre de usuario y/o contraseña.",
      });
      return false;
    }

    // Si el formulario fue rellenado en su totalidad, le aparecerá el mensaje:
    $.ajax({
      url: "logouts/login.php",
      type: "POST",
      datatype: "json",
      data: { usuario: usuario, password: password },
      success: function (data) {
        if (data === "user_not_found") {
          Swal.fire({
            title: "Usuario no existe",
            text: "El nombre de usuario que ingresaste no está registrado.",
            icon: "error",
          });
        } else if (data === "incorrect_password") {
          Swal.fire({
            title: "Contraseña incorrecta",
            text: "La contraseña no coincide con el nombre de usuario.",
            icon: "error",
          });
        } else if (data === "success") {
          Swal.fire({
            title: "Acceso Concedido",
            confirmButtonColor: "#1b3c53",
            confirmButtonText: "Ingresar",
            icon: "success",
            draggable: true,
          }).then((result) => {
            window.location.href = "../Panel Admin/index.php";
          });
        } else {
          // Mensaje de error genérico si algo inesperado ocurre
          Swal.fire({
            title: "Error inesperado",
            text: "Ocurrió un problema, intente de nuevo más tarde.",
            icon: "error",
          });
        }
      },
    });
    return false;
  });

  $("#usuario, #password").on("input", function () {
    this.setCustomValidity("");
  });
});
