$(document).ready(function () {
  $("#codigo_pedido").on("keyup", function () {
    let codigo = $(this).val().trim();

    if (codigo.length >= 5) {
      $.ajax({
        url: "controladores/buscar_pedido.php",
        type: "POST",
        data: { codigo_pedido: codigo },
        dataType: "json",
        success: function (data) {
          if (data.success) {
            mostrarPedidoEncontrado(data.pedido);
          } else {
            Swal.fire({
              icon: "error",
              title: "No encontrado",
              text:
                data.message ||
                "El código no corresponde a un pedido apartado.",
              timer: 2000,
              showConfirmButton: false,
            });
          }
        },
        error: function (xhr, status, error) {
          console.error("Error AJAX:", status, error);
          Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor. Verifica la consola.",
            footer: "<small>Estado: " + status + "</small>",
          });
        },
      });
    }
  });
});

function mostrarPedidoEncontrado(pedido) {
  let productosHTML = "";
  pedido.productos.forEach((p) => {
    productosHTML += `<li><strong>${p.nombre}</strong> × ${
      p.dp_cantidad
    } = Bs.${parseFloat(p.subtotal).toFixed(2)}</li>`;
  });

  Swal.fire({
    icon: "success",
    title: `Pedido Encontrado: ${pedido.codigo_pedido}`,
    html: `
    <div style="text-align: left; font-size: 15px;">
        <p><strong>Cliente:</strong> ${pedido.nombre_cliente}</p>
        <p><strong>Correo:</strong> ${pedido.correo}</p>
        <p><strong>Fecha:</strong> ${pedido.fecha_apartado}</p>
        <hr>
        <p><strong>Productos:</strong></p>
        <ul style="padding-left: 20px; margin: 0;">
            ${pedido.productos
              .map(
                (p) =>
                  `<li><strong>${p.nombre}</strong> × ${
                    p.dp_cantidad
                  } = Bs.${parseFloat(p.subtotal).toFixed(2)}</li>`
              )
              .join("")}
        </ul>
        <hr>
        <p style="font-size: 18px; color: #28a745;">
            <strong>Total: Bs.${parseFloat(pedido.total).toFixed(2)}</strong>
        </p>
    </div>
`,
    width: "600px",
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#007bff",
    showCancelButton: false,
  }).then((result) => {
    if (result.isConfirmed) {
      abrirModalPago(pedido);
    }
  });
}

function abrirModalPago(pedido) {
  const bancos = [
    "Banco de Venezuela (BDV)",
    "Banco Nacional de Crédito (BNC)",
    "BBVA Provincial",
    "Banco Mercantil",
    "Banesco",
    "Banco Bicentenario",
    "Banco del Tesoro",
    "Banco Exterior",
    "Banco Caroní",
    "Banco de la Mujer",
    "Banco de las Fuerzas Armadas (BFA)",
    "Banco Industrial de Venezuela (BIV)",
    "Banco Internacional de Desarrollo (BID)",
    "Banco Occidental de Descuento (BOD)",
    "Banco Popular Dominicano (BPD)",
    "Banco Provincial de Valencia (BPV)",
    "Banco Federal",
    "Banco Libertador",
    "Banco Plaza",
    "Banco Sofitasa",
    "Banco Unión",
    "Banco Venezolano de Crédito (BVC)",
    "Banco Visión",
    "Banco Way Carboní",
    "100% Banco",
  ];

  let selectBancos = "";
  bancos.forEach((banco) => {
    selectBancos += `<option value="${banco}">${banco}</option>`;
  });

  const modalHTML = `
        <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPagoLabel">Registrar Pago - ${
                          pedido.codigo_pedido
                        }</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Total a pagar: Bs.${parseFloat(
                              pedido.total
                            ).toFixed(2)}</label>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Método de pago:</label>
                            <select id="metodoPago" class="form-select">
                                <option value="">Selecciona...</option>
                                <option value="divisas">Divisas (Dólares)</option>
                                <option value="bolivares">Bolívares</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="pago_movil">Pago Móvil</option>
                            </select>
                        </div>

                        <!-- Divisas/Bolívares: Input monto recibido + vuelto -->
                        <div id="divisaSection" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Monto recibido:</label>
                                <input type="number" id="montoRecibido" class="form-control" step="0.01" min="${
                                  pedido.total
                                }" placeholder="Ej: 3000.00">
                            </div>
                            <div class="alert alert-info" id="vueltoInfo" style="display: none;">
                                <strong>Vuelto:</strong> <span id="vueltoValue">Bs.0.00</span>
                            </div>
                        </div>

                        <!-- Tarjeta: Select banco -->
                        <div id="tarjetaSection" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Banco de la tarjeta:</label>
                                <select id="bancoTarjeta" class="form-select">
                                    <option value="">Selecciona banco...</option>
                                    ${selectBancos}
                                </select>
                            </div>
                        </div>

                        <!-- Pago Móvil: Select banco + Referencia 4 dígitos -->
                        <div id="movilSection" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Banco:</label>
                                <select id="bancoMovil" class="form-select">
                                    <option value="">Selecciona banco...</option>
                                    ${selectBancos}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Referencia (4 dígitos):</label>
                                <input type="text" id="referenciaMovil" class="form-control" maxlength="4" placeholder="Ej: 1234" inputmode="numeric">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnConfirmarPago" class="btn btn-primary" disabled>Confirmar Pago</button>
                    </div>
                </div>
            </div>
        </div>
    `;

  $("body").append(modalHTML);
  $("#modalPago").modal("show");

  // Lógica dinámica
  $("#metodoPago").change(function () {
    const metodo = $(this).val();
    $("#divisaSection, #tarjetaSection, #movilSection").hide();
    $("#btnConfirmarPago").prop("disabled", true);

    if (metodo === "divisas" || metodo === "bolivares") {
      $("#divisaSection").show();
      $("#montoRecibido")
        .off("input")
        .on("input", function () {
          const recibido = parseFloat($(this).val()) || 0;
          const total = parseFloat(pedido.total);
          const vuelto = recibido - total;
          if (recibido >= total) {
            $("#vueltoValue").text(
              (metodo === "divisas" ? "Bs." : "Bs. ") + vuelto.toFixed(2)
            );
            $("#vueltoInfo").show();
            $("#btnConfirmarPago").prop("disabled", false);
          } else {
            $("#vueltoInfo").hide();
            $("#btnConfirmarPago").prop("disabled", true);
          }
        });
    } else if (metodo === "tarjeta") {
      $("#tarjetaSection").show();
      $("#bancoTarjeta")
        .off("change")
        .on("change", function () {
          $("#btnConfirmarPago").prop("disabled", $(this).val() === "");
        });
      $("#btnConfirmarPago").prop("disabled", $("#bancoTarjeta").val() === "");
    } else if (metodo === "pago_movil") {
      $("#movilSection").show();
      $("#btnConfirmarPago").prop("disabled", true);

      // Validación en tiempo real para Pago Móvil
      $("#bancoMovil").off("change").on("change", validarPagoMovil);
      $("#referenciaMovil")
        .off("input")
        .on("input", function () {
          let val = $(this).val().replace(/\D/g, "").substring(0, 4);
          $(this).val(val);
          validarPagoMovil();
        });

      function validarPagoMovil() {
        const banco = $("#bancoMovil").val();
        const ref = $("#referenciaMovil").val();
        const habilitar = banco && ref.length === 4;
        $("#btnConfirmarPago").prop("disabled", !habilitar);
      }

      validarPagoMovil(); // Estado inicial
    }
  });

  // Confirmar pago
  $("#btnConfirmarPago")
    .off("click")
    .on("click", function () {
      const metodo = $("#metodoPago").val();
      let datosPago = {
        codigo_pedido: pedido.codigo_pedido,
        metodo_pago: metodo,
      };

      if (metodo === "divisas" || metodo === "bolivares") {
        datosPago.monto_recibido = parseFloat($("#montoRecibido").val());
        datosPago.vuelto = datosPago.monto_recibido - pedido.total;
      } else if (metodo === "tarjeta") {
        datosPago.banco = $("#bancoTarjeta").val();
      } else if (metodo === "pago_movil") {
        datosPago.banco = $("#bancoMovil").val();
        datosPago.referencia_pago = $("#referenciaMovil").val();
      }

      $.ajax({
        url: "controladores/registrar_pago.php",
        type: "POST",
        data: datosPago,
        dataType: "json",
        success: function (data) {
          if (data.success) {
            Swal.fire("Éxito!", data.message, "success");
            $("#modalPago").modal("hide");
            $("#codigo_pedido").val("");
          } else {
            Swal.fire("Error", data.message, "error");
          }
        },
        error: function () {
          Swal.fire("Error", "No se pudo registrar el pago.", "error");
        },
      });
    });

  // Limpiar modal al cerrarse
  $("#modalPago").on("hidden.bs.modal", function () {
    $(this).remove();
  });
}
