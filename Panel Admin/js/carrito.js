console.log("✅ carrito.js se está cargando");

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ DOM cargado - inicializando carrito");
    
    // Tu código actual del carrito...
});

class Carrito {
    constructor() {
        this.initEventListeners();
        this.actualizarContadorNavbar();
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
    }

    // ========== NUEVAS FUNCIONES PARA ACTUALIZAR PRECIOS ==========
    
    actualizarSubtotalProducto(item) {
        // Obtener el precio del producto
        const precioElement = item.querySelector('.text-warning');
        const precioTexto = precioElement.textContent;
        const precio = parseFloat(precioTexto.replace('$', ''));
        
        // Obtener la cantidad actual
        const cantidadElement = item.querySelector('.cantidad');
        const cantidad = parseInt(cantidadElement.textContent);
        
        // Calcular nuevo subtotal
        const subtotalProducto = precio * cantidad;
        
        // Actualizar el subtotal del producto en la interfaz
        const subtotalElement = item.querySelector('.text-end strong');
        if (subtotalElement) {
            subtotalElement.textContent = 'Subtotal: $' + subtotalProducto.toFixed(2);
        }
        
        return subtotalProducto;
    }

    actualizarTotales() {
        let subtotalGeneral = 0;
        
        // Recalcular subtotal de cada producto y sumarlos
        document.querySelectorAll('.producto-item').forEach(item => {
            const subtotalProducto = this.actualizarSubtotalProducto(item);
            subtotalGeneral += subtotalProducto;
        });
        
        // Calcular impuestos y total
        const envio = 5.00;
        const impuestos = subtotalGeneral * 0.16;
        const total = subtotalGeneral + envio + impuestos;
        
        // Actualizar el resumen en tiempo real
        // Buscar los elementos del resumen por su posición
        const resumenElements = document.querySelectorAll('.card-body .d-flex.justify-content-between');
        
        if (resumenElements.length >= 4) {
            // Subtotal (primer elemento)
            const subtotalSpan = resumenElements[0].querySelector('span:last-child');
            if (subtotalSpan) subtotalSpan.textContent = '$' + subtotalGeneral.toFixed(2);
            
            // Impuestos (tercer elemento)
            const impuestosSpan = resumenElements[2].querySelector('span:last-child');
            if (impuestosSpan) impuestosSpan.textContent = '$' + impuestos.toFixed(2);
            
            // Total (cuarto elemento)
            const totalStrong = resumenElements[3].querySelector('strong:last-child');
            if (totalStrong) totalStrong.textContent = '$' + total.toFixed(2);
        }
        
        console.log('💰 Totales actualizados - Subtotal: $' + subtotalGeneral.toFixed(2));
    }

    // ========== MÉTODOS MODIFICADOS ==========

    async eliminarProducto(e) {
        const item = e.target.closest('.producto-item');
        const idDetalle = item.dataset.id;

        if (!confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
            return;
        }

        try {
            const response = await fetch('../controladores/eliminar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_detalle_car=${idDetalle}`
            });

            const result = await response.json();

            if (result.success) {
                item.remove();
                this.actualizarContadorNavbar();
                // ACTUALIZAR TOTALES DESPUÉS DE ELIMINAR
                this.actualizarTotales();
                this.mostrarMensaje('Producto eliminado del carrito', 'success');
                
                // Recargar si el carrito queda vacío
                if (!document.querySelector('.producto-item')) {
                    location.reload();
                }
            } else {
                this.mostrarMensaje(result.message, 'error');
            }
        } catch (error) {
            this.mostrarMensaje('Error al eliminar producto', 'error');
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
                        location.reload();
                    }
                } else {
                    cantidadElement.textContent = nuevaCantidad;
                    // ACTUALIZAR PRECIOS EN TIEMPO REAL
                    this.actualizarTotales();
                }
                this.actualizarContadorNavbar();
                this.mostrarMensaje('Cantidad actualizada', 'success');
            } else {
                this.mostrarMensaje(result.message, 'error');
            }
        } catch (error) {
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
            
            // BUSCAR POR LA CLASE CORRECTA
            const badge = document.querySelector('.carrito-counter');
            if (badge) {
                badge.textContent = result.contador;
                console.log('✅ Contador actualizado:', result.contador);
            } else {
                console.log('❌ No se encontró el badge con clase carrito-counter');
                // Intentar con cualquier badge en el navbar como fallback
                const fallbackBadge = document.querySelector('.navbar .badge');
                if (fallbackBadge) {
                    fallbackBadge.textContent = result.contador;
                }
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
    new Carrito();
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
            // Actualizar contador en navbar
            const carrito = new Carrito();
            carrito.actualizarContadorNavbar();
            carrito.mostrarMensaje(result.message, 'success');
        } else {
            const carrito = new Carrito();
            carrito.mostrarMensaje(result.message, 'error');
        }
    } catch (error) {
        const carrito = new Carrito();
        carrito.mostrarMensaje('Error al agregar producto', 'error');
    }
}