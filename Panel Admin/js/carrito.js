console.log("✅ carrito.js se está cargando");

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ DOM cargado - inicializando carrito");
});

// Variable global para mantener la instancia del carrito
let carritoInstance = null;

class Carrito {
    constructor() {
        this.initEventListeners();
        this.actualizarContadorNavbar();
        this.actualizarTotales(); // INICIALIZAR TOTALES AL CARGAR
    }
    
    initEventListeners() {
        // Eliminar producto
        document.querySelectorAll('.eliminar-producto').forEach(btn => {
            btn.addEventListener('click', (e) => this.eliminarProducto(e));
        });

        // Cambiar cantidad
        document.querySelectorAll('.restar-cantidad').forEach(btn => {
            btn.addEventListener('click', (e) => this.cambiarCantidad(e, -1));
        });

        document.querySelectorAll('.sumar-cantidad').forEach(btn => {
            btn.addEventListener('click', (e) => this.cambiarCantidad(e, 1));
        });

        // Vaciar carrito
        const vaciarBtn = document.querySelector('.vaciar-carrito');
        if (vaciarBtn) {
            vaciarBtn.addEventListener('click', () => this.vaciarCarrito());
        }

        // Proceder al pago
        const pagoBtn = document.querySelector('.proceder-pago');
        if (pagoBtn) {
            pagoBtn.addEventListener('click', () => this.procederPago());
        }

        // Proceder al apartado
        const apartadoBtn = document.querySelector('.proceder-apartado');
        if (apartadoBtn) {
            apartadoBtn.addEventListener('click', () => this.procesarApartado());
        }
    }

    // ========== MÉTODO CORREGIDO PARA ACTUALIZAR PRECIOS ==========
    
    actualizarSubtotalProducto(item) {
        try {
            // Obtener el precio del producto - CORREGIDO PARA BS.
            const precioElement = item.querySelector('.text-warning');
            if (!precioElement) {
                console.error('No se encontró elemento de precio');
                return 0;
            }
            
            const precioTexto = precioElement.textContent;
            console.log('Precio texto:', precioTexto);
            
            // Extraer número del precio (maneja "Bs.100.00" o "$100.00")
            const precioMatch = precioTexto.match(/(\d+\.?\d*)/);
            const precio = precioMatch ? parseFloat(precioMatch[1]) : 0;
            
            console.log('Precio extraído:', precio);

            // Obtener la cantidad actual
            const cantidadElement = item.querySelector('.cantidad');
            const cantidad = cantidadElement ? parseInt(cantidadElement.textContent) : 1;
            
            console.log('Cantidad:', cantidad);

            // Calcular nuevo subtotal
            const subtotalProducto = precio * cantidad;
            
            console.log('Subtotal producto:', subtotalProducto);

            // Actualizar el subtotal del producto en la interfaz
            const subtotalElement = item.querySelector('.text-end strong');
            if (subtotalElement) {
                subtotalElement.textContent = 'Subtotal: Bs.' + subtotalProducto.toFixed(2);
            }
            
            return subtotalProducto;
        } catch (error) {
            console.error('Error en actualizarSubtotalProducto:', error);
            return 0;
        }
    }

    actualizarTotales() {
        try {
            let subtotalGeneral = 0;
            const productos = document.querySelectorAll('.producto-item');
            
            console.log(`📊 Encontrados ${productos.length} productos para calcular totales`);
            
            // Recalcular subtotal de cada producto y sumarlos
            productos.forEach((item, index) => {
                const subtotalProducto = this.actualizarSubtotalProducto(item);
                subtotalGeneral += subtotalProducto;
                console.log(`Producto ${index + 1}: ${subtotalProducto}`);
            });
            
            console.log('Subtotal general calculado:', subtotalGeneral);

            // Calcular impuestos y total
            const envio = 5.00;
            const impuestos = subtotalGeneral * 0.16;
            const total = subtotalGeneral + envio + impuestos;
            
            console.log('Impuestos:', impuestos, 'Total:', total);

            // Actualizar el resumen en tiempo real
            // Subtotal
            const subtotalSpan = document.getElementById('subtotal-general');
            if (subtotalSpan) {
                subtotalSpan.textContent = 'Bs.' + subtotalGeneral.toFixed(2);
                console.log('✅ Subtotal actualizado');
            } else {
                console.error('❌ No se encontró elemento subtotal-general');
            }
            
            // Impuestos
            const impuestosSpan = document.getElementById('impuestos');
            if (impuestosSpan) {
                impuestosSpan.textContent = 'Bs.' + impuestos.toFixed(2);
                console.log('✅ Impuestos actualizados');
            } else {
                console.error('❌ No se encontró elemento impuestos');
            }
            
            // Total
            const totalStrong = document.getElementById('total-final');
            if (totalStrong) {
                totalStrong.textContent = 'Bs.' + total.toFixed(2);
                console.log('✅ Total actualizado');
            } else {
                console.error('❌ No se encontró elemento total-final');
            }
            
            console.log('💰 Totales actualizados - Subtotal: Bs.' + subtotalGeneral.toFixed(2));
            
        } catch (error) {
            console.error('❌ Error en actualizarTotales:', error);
        }
    }

    async procesarApartado() {
        try {
            // Obtener información directamente de los productos visibles
            const productosElements = document.querySelectorAll('.producto-item');
            
            if (productosElements.length === 0) {
                this.mostrarMensaje('El carrito está vacío', 'error');
                return;
            }

            // Construir mensaje de confirmación desde los productos visibles
            let mensajeConfirmacion = "¿Confirmar apartado de pedido?\n\n";
            mensajeConfirmacion += "📦 PRODUCTOS EN EL CARRITO:\n";
            mensajeConfirmacion += "─".repeat(50) + "\n";
            
            let totalProductos = 0;

            productosElements.forEach((producto, index) => {
                try {
                    // Obtener nombre
                    const nombreElement = producto.querySelector('h5.text-white');
                    const nombre = nombreElement ? nombreElement.textContent.trim() : `Producto ${index + 1}`;
                    
                    // Obtener cantidad
                    const cantidadElement = producto.querySelector('.cantidad');
                    const cantidad = cantidadElement ? parseInt(cantidadElement.textContent) : 1;
                    
                    // Obtener precio del producto
                    const precioElement = producto.querySelector('.text-warning');
                    let precioTexto = '0';
                    if (precioElement) {
                        precioTexto = precioElement.textContent.replace('Bs.', '').replace('$', '').trim();
                    }
                    const precio = parseFloat(precioTexto) || 0;
                    
                    // Obtener subtotal del producto
                    const subtotalElement = producto.querySelector('.text-end strong');
                    let subtotalTexto = '0';
                    if (subtotalElement) {
                        subtotalTexto = subtotalElement.textContent.replace('Subtotal:', '')
                                                                  .replace('Bs.', '')
                                                                  .replace('$', '')
                                                                  .trim();
                    }
                    const subtotal = parseFloat(subtotalTexto) || (precio * cantidad);
                    
                    totalProductos += subtotal;

                    // Agregar al mensaje
                    mensajeConfirmacion += `• ${nombre}\n`;
                    mensajeConfirmacion += `  Cantidad: ${cantidad} x Bs. ${precio.toFixed(2)} = Bs. ${subtotal.toFixed(2)}\n\n`;
                    
                } catch (error) {
                    console.error('Error procesando producto:', error);
                }
            });

            // Calcular impuestos y total
            const impuestos = totalProductos * 0.16;
            const totalFinal = totalProductos + impuestos;

            mensajeConfirmacion += "─".repeat(50) + "\n";
            mensajeConfirmacion += `Subtotal:    Bs. ${totalProductos.toFixed(2)}\n`;
            mensajeConfirmacion += `Impuestos 16%: Bs. ${impuestos.toFixed(2)}\n`;
            mensajeConfirmacion += `TOTAL:       Bs. ${totalFinal.toFixed(2)}\n\n`;
            mensajeConfirmacion += "¿Deseas proceder con el apartado?\n\n";
            mensajeConfirmacion += "📧 Se te enviará un código único por email.";

            console.log("Mensaje de confirmación:", mensajeConfirmacion);

            // Mostrar confirmación
            if (!confirm(mensajeConfirmacion)) {
                return;
            }

            // Proceder con el apartado
            const response = await fetch('../controladores/procesar_apartado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            });

            const text = await response.text();
            console.log('Response text from server:', text);
            const result = JSON.parse(text);

            if (result.success) {
                this.mostrarMensaje(result.message, 'success');
                
                // Mostrar código en alerta
                let mensajeExito = "🎉 PEDIDO APARTADO EXITOSAMENTE\n\n";
                mensajeExito += "🛒 TU CÓDIGO DE APARTADO:\n";
                mensajeExito += "════════════════════════════\n";
                mensajeExito += `📋 ${result.codigo}\n`;
                mensajeExito += "════════════════════════════\n\n";
                
                if (result.email_enviado) {
                    mensajeExito += "📧 Se ha enviado un email con los detalles\n\n";
                }
                
                mensajeExito += "💡 INSTRUCCIONES:\n";
                mensajeExito += "• Guarda este código\n";
                mensajeExito += "• Preséntalo en la farmacia\n";
                mensajeExito += "• Realiza el pago\n";
                mensajeExito += "• Recoge tus productos\n\n";
                mensajeExito += "⏰ Válido por 7 días";
                
                alert(mensajeExito);
                
                // Redirigir a página principal
                setTimeout(() => {
                    window.location.href = '../index_logeado.php';
                }, 5000);
                
            } else {
                this.mostrarMensaje(result.message, 'error');
            }
            
        } catch (error) {
            console.error('Error en procesarApartado:', error);
            this.mostrarMensaje('Error de conexión al procesar apartado', 'error');
        }
    }

    // ========== MÉTODOS MODIFICADOS ==========

    async eliminarProducto(e) {
        const item = e.target.closest('.producto-item');
        if (!item) return;
        const idDetalle = item.dataset.id;

        if (!confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
            return;
        }

        // Optimistic UI: eliminar del DOM inmediatamente y actualizar totales/contador
        const parent = item.parentNode;
        const nextSibling = item.nextSibling;
        item.remove();
        this.actualizarContadorNavbar();
        this.actualizarTotales();

        // Helper para normalizar mensajes (extrae "message" si la respuesta JSON viene anidada o como string)
        const normalizeMessage = (msg) => {
            if (!msg && msg !== '') return '';
            try {
                if (typeof msg === 'object') {
                    if (msg.message) return normalizeMessage(msg.message);
                    return String(msg);
                }
                const str = String(msg).trim();
                // Si la cadena parece JSON, intentar parsear
                if ((str.startsWith('{') && str.endsWith('}')) || (str.startsWith('[') && str.endsWith(']'))) {
                    try {
                        const parsed = JSON.parse(str);
                        return normalizeMessage(parsed);
                    } catch (err) {
                        // no es JSON válido, retornar la cadena tal cual
                        return str;
                    }
                }
                return str;
            } catch (err) {
                return String(msg);
            }
        };

        try {
            const response = await fetch('../controladores/eliminar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_detalle_car=${encodeURIComponent(idDetalle)}`
            });

            const contentType = response.headers.get('content-type') || '';
            let result = null;

            if (contentType.includes('application/json')) {
                result = await response.json();
            } else {
                // Intentar parsear texto a JSON si el servidor devuelve texto con warnings
                const text = await response.text();
                try {
                    result = JSON.parse(text);
                } catch (err) {
                    // Si no es JSON, construir un resultado sensible
                    result = {
                        success: response.ok,
                        message: text || (response.ok ? 'Eliminado (respuesta no JSON)' : 'Error del servidor')
                    };
                }
            }

            const mensajeLimpio = normalizeMessage( ( 'Producto eliminado del carrito' ));

            if (result && result.success) {
                this.mostrarMensaje(mensajeLimpio || 'Producto eliminado del carrito', 'success');
                // Si no quedan productos, recargar para sincronizar (opcional)
                if (!document.querySelector('.producto-item')) {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            } else {
                // Revertir UI si el servidor no confirmó eliminación
                if (nextSibling) parent.insertBefore(item, nextSibling); else parent.appendChild(item);
                this.actualizarContadorNavbar();
                this.actualizarTotales();
                this.mostrarMensaje(mensajeLimpio || 'No se pudo eliminar el producto', 'error');
            }
        } catch (error) {
            console.error('Error al eliminar producto:', error);
            // Revertir UI en caso de error de red
            if (!document.querySelector('.producto-item[data-id="' + idDetalle + '"]')) {
                if (nextSibling) parent.insertBefore(item, nextSibling); else parent.appendChild(item);
            }
            this.actualizarContadorNavbar();
            this.actualizarTotales();
            this.mostrarMensaje('Error al eliminar producto (conexión)', 'error');
        }
    }

    async cambiarCantidad(e, cambio) {
        const item = e.target.closest('.producto-item');
        const idDetalle = item.dataset.id;
        const cantidadElement = item.querySelector('.cantidad');
        let cantidad = parseInt(cantidadElement.textContent);
        
        const nuevaCantidad = cantidad + cambio;

        if (nuevaCantidad < 1) {
            this.eliminarProducto(e);
            return;
        }

        try {
            const response = await fetch('../controladores/actualizar_cantidad_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_detalle_car=${idDetalle}&cantidad=${nuevaCantidad}`
            });

            const result = await response.json();

            if (result.success) {
                if (result.eliminado) {
                    item.remove();
                    if (!document.querySelector('.producto-item')) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    cantidadElement.textContent = nuevaCantidad;
                    // ACTUALIZAR TOTALES INMEDIATAMENTE
                    this.actualizarTotales();
                }
                // SIEMPRE ACTUALIZAR CONTADOR
                this.actualizarContadorNavbar();
                this.mostrarMensaje('Cantidad actualizada', 'success');
            } else {
                this.mostrarMensaje(result.message, 'error');
            }
        } catch (error) {
            console.error('Error al actualizar cantidad:', error);
            this.mostrarMensaje('Error al actualizar cantidad', 'error');
        }
    }

    async vaciarCarrito() {
        if (!confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) {
            return;
        }

        try {
            const response = await fetch('../controladores/vaciar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            });

            const result = await response.json();

            if (result.success) {
                this.mostrarMensaje('Carrito vaciado', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                this.mostrarMensaje(result.message, 'error');
            }
        } catch (error) {
            this.mostrarMensaje('Error al vaciar carrito', 'error');
        }
    }

    async actualizarContadorNavbar() {
        try {
            const response = await fetch('../controladores/obtener_contador_carrito.php');
            const result = await response.json();
            
            const badge = document.querySelector('.carrito-counter') || 
                         document.querySelector('.navbar .badge') || 
                         document.querySelector('.badge');
            
            if (badge) {
                badge.textContent = result.contador;
            }
        } catch (error) {
            console.error('Error al actualizar contador:', error);
        }
    }

    procederPago() {
        // Redirigir a página de pago o mostrar modal
        alert('Funcionalidad de pago en desarrollo');
        // window.location.href = 'pago.php';
    }

    mostrarMensaje(mensaje, tipo) {
        // Crear toast de Bootstrap
        const toastContainer = document.getElementById('toast-container') || this.crearToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${tipo === 'success' ? 'success' : 'danger'} border-0`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    crearToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    carritoInstance = new Carrito();
});

// Función global para agregar productos desde el catálogo
async function agregarAlCarrito(idProducto, cantidad = 1) {
    try {
        const response = await fetch('../controladores/agregar_producto_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_producto=${idProducto}&cantidad=${cantidad}`
        });

        const result = await response.json();

        if (result.success) {
            // Usar la instancia existente del carrito
            if (carritoInstance) {
                carritoInstance.actualizarContadorNavbar();
                carritoInstance.mostrarMensaje(result.message, 'success');
            } else {
                // Si no hay instancia, crear una nueva
                carritoInstance = new Carrito();
            }
        } else {
            if (carritoInstance) {
                carritoInstance.mostrarMensaje(result.message, 'error');
            }
        }
    } catch (error) {
        console.error('Error al agregar producto:', error);
        if (carritoInstance) {
            carritoInstance.mostrarMensaje('Error al agregar producto', 'error');
        }
    }
}