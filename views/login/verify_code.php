<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Dos Pasos</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .verify-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .verify-form {
            width: 350px;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .verify-form h1 {
            text-align: center;
            color: #8B0000;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }
        .verify-form h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: normal;
        }
        .verify-form p {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .code-inputs {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }
        .code-inputs input {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 22px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }
        .code-inputs input:focus {
            border-color: #8B0000;
            box-shadow: 0 0 3px rgba(139,0,0,0.5);
        }
        .btn-primary {
            background-color: #8B0000;
            border: none;
            padding: 10px;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #a00000;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-form">


            <div class="logo_crm" style="text-align: center; margin-bottom: -15px;">
                <img src="<?php echo SITE_URL; ?>assets/img/logocorreo.png" 
                    alt="Logo CRM" 
                    style="max-width: 300px; height: auto;">
            </div>



            <h2>Verificación de Dos Pasos</h2>
            <p>Se ha enviado un código de verificación a su correo electrónico.<br>Por favor, ingrese el código a continuación.</p>

            <?php if (isset($data['error'])): ?>
                <p class="error-message"><?php echo $data['error']; ?></p>
            <?php endif; ?>

            <form id="verifyForm" action="<?php echo SITE_URL; ?>index.php?controller=login&action=verify_code" method="post">
                <label for="codigo" style="font-weight: bold;">Código de Verificación</label>
                <div class="code-inputs">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                </div>
                <input type="hidden" name="codigo" id="codigo">
                <button type="submit" class="btn-primary">Verificar</button>
            </form>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.code-inputs input');
        const hiddenInput = document.getElementById('codigo');
        const form = document.getElementById('verifyForm');

        // Foco inicial
        inputs[0].focus();

        // Manejo de escritura y movimiento entre cuadros
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, ''); // Solo números
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        // Al enviar el formulario, unir los dígitos
        form.addEventListener('submit', (e) => {
            let code = '';
            inputs.forEach(input => code += input.value);
            hiddenInput.value = code;
        });
    </script>
</body>
</html>
