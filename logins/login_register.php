<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <!-- Define la codificación de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Hace que el diseño sea responsivo -->
    <title>Farmamigo IV - Login y Registro</title>
    <!-- Título que aparece en la pestaña del navegador -->

    <style>
      /* Variables de color para mantener consistencia */
      :root {
        --azul-primario: #517d9b;
        --azul-oscuro: #022338;
        --blanco: #d4d3d2;
      }

      /* Estilo general del cuerpo de la página */
      body {
        font-family: "Arial", sans-serif;
        color: black;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-image: linear-gradient(135deg, #f5f9fc 0%, #e0f0fa 100%);
      }

      /* Contenedor principal que agrupa logo y formularios */
      .container {
        display: flex;
        max-width: 900px;
        width: 100%;
        background-color: var(--blanco);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
      }

      /* Sección del logo con fondo degradado */
      .logo-section {
        background: linear-gradient(
          to bottom right,
          var(--azul-primario),
          var(--azul-oscuro)
        );
        width: 40%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: var(--blanco);
        text-align: center;
      }

      /* Espacio reservado para el logo */
      .logo-placeholder {
        width: 120px;
        height: 120px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        font-size: 24px;
      }

      /* Sección del formulario (login y registro) */
      .form-section {
        width: 60%;
        padding: 40px;
      }

      /* Contenedor interno de los formularios */
      .form-container {
        display: flex;
        flex-direction: column;
        height: 100%;
      }

      /* Pestañas para cambiar entre login y registro */
      .form-tabs {
        display: flex;
        margin-bottom: 30px;
        border-bottom: 1px solid #e0e0e0;
      }

      .tab {
        padding: 10px 20px;
        cursor: pointer;
        font-weight: bold;
        color: #777;
      }

      .tab.active {
        color: var(--azul-oscuro);
        border-bottom: 2px solid var(--azul-oscuro);
      }

      /* Contenido del formulario */
      .form-content {
        flex-grow: 1;
      }

      /* Oculta formularios por defecto */
      .form {
        display: none;
      }

      /* Muestra el formulario activo */
      .form.active {
        display: block;
      }

      /* Títulos de sección */
      h2 {
        color: var(--azul-oscuro);
        margin-bottom: 20px;
      }

      /*  Grupo de entrada (label + input) */
      .input-group {
        margin-bottom: 15px;
      }

      label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: bold;
      }

      input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
      }

      input:focus {
        outline: none;
        border-color: var(--azul-primario);
        box-shadow: 0 0 0 2px rgba(88, 171, 255, 0.2);
      }

      /*  Botón de acción */
      button {
        background-color: var(--azul-primario);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        width: 100%;
        margin-top: 10px;
        transition: background-color 0.3s;
      }

      button:hover {
        background-color: var(--azul-oscuro);
      }

      /* Enlace para recuperar contraseña */
      .forgot-password {
        text-align: right;
        margin-top: 10px;
      }

      .forgot-password a {
        color: var(--azul-primario);
        text-decoration: none;
        font-size: 14px;
      }

      .forgot-password a:hover {
        text-decoration: underline;
      }

      /* Diseño responsivo para móviles */
      @media (max-width: 768px) {
        .container {
          flex-direction: column;
        }

        .logo-section,
        .form-section {
          width: 100%;
        }

        .logo-section {
          padding: 20px;
        }
      }
    </style>
  </head>
  <body>
    <!-- Contenedor principal -->
    <div class="container">
      <!--  Sección del logo -->
      <div class="logo-section">
        <div class="logo-placeholder">
          <img
            src="data:image/png;base64,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"
            alt="Logo Farmamigo IV"
            alt="Logo de la empresa"
            style="width: 150px; height: 150px"
          />
        </div>
        <h1>Farmamigo IV</h1>
        <p>Tu farmacia de confianza</p>
      </div>

      <!-- Sección del formulario -->
      <div class="form-section">
        <div class="form-container">
          <!--  Pestañas de navegación -->
          <div class="form-tabs">
            <div class="tab active" onclick="showForm('login', event)">
              Iniciar Sesión
            </div>
            <div class="tab" onclick="showForm('register', event)">
              Registrarse
            </div>
          </div>

          <!--  Contenido de los formularios -->
          <div class="form-content">
            <!--  Formulario de Login -->
            <div id="login-form" class="form active">
              <h2>Bienvenido de vuelta</h2>
              <form>
                <div class="input-group">
                  <label for="login-email">Correo electrónico</label>
                  <input type="email" id="login-email" required />
                </div>

                <div class="input-group">
                  <label for="login-password">Contraseña</label>
                  <input type="password" id="login-password" required />
                </div>

                <div class="forgot-password">
                  <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit">Iniciar Sesión</button>
              </form>
            </div>

            <!--  Formulario de Registro -->
            <div id="register-form" class="form">
              <h2>Crear una cuenta</h2>
              <form>
                <div class="input-group">
                  <label for="register-name">Nombre completo</label>
                  <input type="text" id="register-name" required />
                </div>

                <div class="input-group">
                  <label for="register-email">Correo electrónico</label>
                  <input type="email" id="register-email" required />
                </div>

                <div class="input-group">
                  <label for="register-city">Ciudad</label>
                  <input type="text" id="register-city" required />
                </div>

               
                <div class="input-group">
                  <label for="register-phone">Teléfono</label>
                  <input type="tel" id="register-phone" required />
                </div>

                <div class="input-group">
                  <label for="register-password">Contraseña</label>
                  <input type="password" id="register-password" required />
                </div>

                <button type="submit">Registrarse</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      function showForm(formId, event) {
        //  Oculta todos los formularios
        document.querySelectorAll(".form").forEach((form) => {
          form.classList.remove("active");
        });

        //  Muestra el formulario seleccionado (login o register)
        document.getElementById(formId + "-form").classList.add("active");

        //  Desactiva todas las pestañas
        document.querySelectorAll(".tab").forEach((tab) => {
          tab.classList.remove("active");
        });

        //  Activa la pestaña que fue clickeada
        event.currentTarget.classList.add("active");
      }
    </script>
  </body>
</html>
