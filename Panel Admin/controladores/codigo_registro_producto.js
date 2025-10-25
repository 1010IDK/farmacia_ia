/* =======================================================================================================================================================================
         * LÓGICA PARA EL FORMULARIO DE AGREGAR PRODUCTOS
         =========================================================================================================================================================================*/

$(document).ready(function () {
  let selectedFile = null;

  $("#selectImage").on("click", async function () {
    const { value: file } = await Swal.fire({
      title: "Select image",
      input: "file",
      inputAttributes: {
        accept: "image/*",
        "aria-label": "Upload your product picture",
      },
    });

    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        Swal.fire({
          title: "Your uploaded picture",
          imageUrl: e.target.result,
          imageAlt: "The uploaded picture",
        });
        selectedFile = file;
        $("#fileName").text(file.name);
      };
      reader.readAsDataURL(file);
    }
  });

  $("#formRegistroProducto").submit(function (e) {
    e.preventDefault();

    var nombre = $("#nombre").val();
    var descripcion = $("#descripcion").val();
    var precio = $("#precio").val();
    var cantidad = $("#cantidad").val();
    var categoria = $("#categoria").val();

    if (
      !nombre ||
      !descripcion ||
      !precio ||
      !cantidad ||
      !categoria ||
      isNaN(parseInt(categoria))
    ) {
      Swal.fire({
        title: "Error",
        text: "Todos los campos tienen que estar rellenos, y la categoría debe ser válida.",
        icon: "error",
      });
      return;
    }

    Swal.fire({
      title: "¿Estás seguro?",
      text: "El producto se registrará en la base de datos.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, registrar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData();
        formData.append("nombre", nombre);
        formData.append("descripcion", descripcion);
        formData.append("precio", precio);
        formData.append("cantidad", cantidad);
        formData.append("categoria", categoria);
        if (selectedFile) {
          formData.append("imagen", selectedFile);
        }

        $.ajax({
          type: "POST",
          url: "controladores/agregar_producto.php", // Ajusta si está en subcarpeta, e.g., "controladores/php/agregar_producto.php"
          data: formData,
          processData: false,
          contentType: false,
          success: function (data) {
            var respuesta = data.trim();
            if (respuesta === "success") {
              Swal.fire({
                title: "¡Registrado!",
                text: "El producto se ha registrado correctamente.",
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
