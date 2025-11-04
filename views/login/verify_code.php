
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Dos Pasos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Verificación de Dos Pasos</h4>
                    </div>
                    <div class="card-body">
                        <p>Se ha enviado un código de verificación a su correo electrónico. Por favor, ingrese el código a continuación.</p>
                        <?php if (isset($data['error'])): ?>
                            <div class="alert alert-danger"><?php echo $data['error']; ?></div>
                        <?php endif; ?>
                        <form action="<?php echo SITE_URL; ?>index.php?controller=login&action=verify_code" method="post">
                            <div class="form-group">
                                <label for="codigo">Código de Verificación</label>
                                <input type="text" name="codigo" id="codigo" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Verificar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
