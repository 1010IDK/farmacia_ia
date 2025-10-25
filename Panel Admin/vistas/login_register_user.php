<?php
    session_start();
    include("../config/conexion.php");
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Define la codificación de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Hace que el diseño sea responsivo -->
    <title>Farmamigo IV - iniciar sesion y Registro</title> <!-- Título que aparece en la pestaña del navegador -->
    <style>
        /* Variables de color para mantener consistencia */
        :root {
            --azul-primario: #456882;
            --azul-oscuro: #1B3C53;
            --blanco: #d4d3d2;
        }

        /* Estilo general del cuerpo de la página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #151110;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #151110 0%, #151110 100%);
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
            background: linear-gradient(to bottom right, var(--azul-primario), var(--azul-oscuro));
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

            .logo-section, .form-section {
                width: 100%;
            }

            .logo-section {
                padding: 20px;
            }
            .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            }

            .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            }
        }
    </style>
</head>
<body>
    <!-- Contenedor principal -->
    <div class="container">
        <!--  Sección del logo -->
        <div class="logo-section">
            <a href="../index.html"><div class="logo-placeholder"><img src="../diseno/img/farmamigo.png" alt="Logo Farmamigo IV" alt="Logo de la empresa" style="width: 150px; height: 150px;"></div></a>
            <h1>Farmamigo IV</h1>
            <p>Tu farmacia de confianza</p>
        </div>
        <!-- Sección del formulario -->
        <div class="form-section">
            <div class="form-container">
                <!--  Pestañas de navegación -->
                
                <div class="form-tabs">
                    <div class="tab active" onclick="showForm('login', event)">Iniciar Sesión</div>
                    <div class="tab" onclick="showForm('register', event)">Registrarse</div>
                </div>

                <!--  Contenido de los formularios -->
                <div class="form-content">
                    <!--  Formulario de Login -->
                    <div id="login-form" class="form active">
                        <h2>Bienvenido de vuelta</h2>
                        <form method="post" action="../controladores/controlador-login.php" id="formLogin">
                            <div class="input-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" name="email" id="email"  >
                            </div>

                            <div class="input-group">
                                <label for="password">Contraseña</label>
                                <input type="password" name="password" id="password" >
                            </div>

                            <div class="forgot-password">
                                <a href="#">¿Olvidaste tu contraseña?</a>
                            </div>

                            <button type="submit" name="btningresar" id="btningresar">Iniciar Sesión</button >
                        </form>
                    </div>
                    <!--  Formulario de Registro -->
                    <div id="register-form" class="form">
                        <h2>Crear una cuenta</h2>                            
                    
                        <form method="post" action="../controladores/controlador-registro.php">
                            <div class="input-group">
                                <label for="register-name">Nombre completo</label>
                                <input type="text" name="registrar-nombre" id="registrar-nombre">
                            </div>

                            <div class="input-group">
                                <label for="register-email">Correo electrónico</label>
                                <input type="email" name="registrar-correo">
                            </div>

                            <div class="input-group">
                                <label for="register-street">Ubicacion de residencia</label>
                                <input type="text" name="ubicacion" id="ubicacion">
                            </div>

                            <div class="input-group">
                                <label for="register-phone">Teléfono</label>
                                <input type="tel" name="registrar-telefono" id="registrar-telefono">
                            </div>

                            <div class="input-group">
                                <label for="register-password">Contraseña</label>
                                <input type="password" name="registrar-contrasena" id="registrar-contrasena">
                            </div>

                            <button type="submit" name="btn-registrar">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function showForm(formId, event) {
        //  Oculta todos los formularios
        document.querySelectorAll('.form').forEach(form => {
            form.classList.remove('active');
        });

        //  Muestra el formulario seleccionado (login o register)
        document.getElementById(formId + '-form').classList.add('active');

        //  Desactiva todas las pestañas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });

        //  Activa la pestaña que fue clickeada
        event.currentTarget.classList.add('active');
    }
</script>
</body>
</html>