/* =======================================================================================================================================================================
         * LÓGICA PARA EL MODAL DE MODIFICACIÓN DE PRODUCTOS
         =========================================================================================================================================================================*/

$(document).ready(function () {
  let selectedFileModificar = null;

  function obtenerDatosProducto(id_producto) {
    $.ajax({
      type: "POST",
      url: "controladores/obtener_producto_por_id.php",
      data: { id_producto: id_producto },
      dataType: "json",
      success: function (data) {
        if (data.error) {
          console.error("Error from server:", data.error);
          Swal.fire({
            title: "Error",
            text: data.error,
            icon: "error",
          });
          return;
        }
        $("#id_producto_modificar").val(data.id_producto);
        $("#nombre_modificar").val(data.nombre);
        $("#descripcion_modificar").val(data.descripcion);
        $("#precio_modificar").val(data.precio);
        $("#cantidad_modificar").val(data.cantidad);
        $("#categoria_modificar").val(data.categoria); // Select the current category
        $("#fileNameModificar").text(
          "Imagen actual: " +
            (data.imagen ? data.imagen.split("/").pop() : "Ninguna")
        );
      },
      error: function (xhr, status, error) {
        console.error("AJAX error fetching product:", status, error);
        Swal.fire({
          title: "Error",
          text: "No se pudo obtener la información del producto.",
          icon: "error",
        });
      },
    });
  }

  $(".productos_table_body").on(
    "click",
    ".btn-modificar-producto",
    function () {
      var id_producto = $(this).data("id-producto");
      obtenerDatosProducto(id_producto);
    }
  );

  $("#selectImageModificar").on("click", async function () {
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
          title: "Tu imagen seleccionada",
          imageUrl: e.target.result,
          imageAlt: "Tu imagen seleccionada",
        });
        selectedFileModificar = file;
        $("#fileNameModificar").text(file.name);
      };
      reader.readAsDataURL(file);
    }
  });

  $("#formModificarProducto").on("submit", function (e) {
    e.preventDefault();

    var id_producto = $("#id_producto_modificar").val();
    var nombre = $("#nombre_modificar").val();
    var descripcion = $("#descripcion_modificar").val();
    var precio = $("#precio_modificar").val();
    var cantidad = $("#cantidad_modificar").val();
    var categoria = $("#categoria_modificar").val();

    if (!id_producto) {
      Swal.fire({
        title: "Error",
        text: "ID de producto no encontrado. Recargue la página.",
        icon: "error",
      });
      return;
    }

    if (!nombre || !descripcion || !precio || !cantidad || !categoria) {
      Swal.fire({
        title: "Error",
        text: "Todos los campos tienen que estar rellenos.",
        icon: "error",
      });
      return;
    }

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Los cambios del producto se guardarán.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, modificar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData();
        formData.append("id_producto", id_producto);
        formData.append("nombre", nombre);
        formData.append("descripcion", descripcion);
        formData.append("precio", precio);
        formData.append("cantidad", cantidad);
        formData.append("categoria", categoria);
        if (selectedFileModificar) {
          formData.append("imagen", selectedFileModificar);
        } else {
          formData.append("imagen", ""); // Ensure imagen is sent even if no new file
        }

        $.ajax({
          type: "POST",
          url: "controladores/modificar_producto.php", // Adjusted to match your PHP file location
          data: formData,
          processData: false,
          contentType: false,
          success: function (data) {
            console.log("Server response:", data); // Debug log
            var respuesta = data.trim();
            if (respuesta === "success") {
              Swal.fire({
                title: "¡Modificado!",
                text: "El producto se ha modificado correctamente.",
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
            console.error(
              "AJAX submission error:",
              status,
              error,
              xhr.responseText
            );
            Swal.fire({
              title: "Error",
              text:
                "Error en la petición AJAX: " + (xhr.responseText || status),
              icon: "error",
            });
          },
        });
      }
    });
  });
});
