<?php require_once 'views/layout/header.php'; ?>

<style>
/* ✅ Contenedor de tabla con scroll horizontal */
.table-wrapper {
    width: 100%;
    max-height: calc(100vh - 250px); /* evita afectar al menú */
    overflow-x: auto;
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: white;
}

/* ✅ Tabla estilo grilla */
.table {
    width: max-content; /* permite crecer horizontalmente */
    min-width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 6px 10px;
    text-align: left;
    vertical-align: middle;
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ✅ Cabecera fija */
.table thead th {
    position: sticky;
    top: 0;
    background: #f5f5f5;
    z-index: 2;
}

/* ✅ Expansión visual tipo Excel al pasar el mouse */
.table td:hover {
    white-space: normal !important;
    background-color: #fffceb;
    z-index: 10;
    position: relative;
}

/* ✅ Botones */
.btn {
    padding: 4px 8px;
    font-size: 14px;
}
</style>

<h1>Clientes</h1>

<a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=crear" 
    style="
        font-size: 16px;
        padding: 10px 22px;
        border-radius: 6px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        transition: all 0.25s ease;
    "
class="btn btn-primary">Nuevo Cliente</a>
<p></p>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Nro. Documento</th>
                <th>Dirección</th>
                <th>Ubigeo</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <!-- <th>Website</th> -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['clientes'] as $cliente) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nombre_documento']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['numero_documento']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['direccion_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['ubigeo']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefono_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['correo_electronico']); ?></td>
                    <!-- <td>
                        <a href="<?php echo htmlspecialchars($cliente['website_cliente']); ?>" target="_blank">
                            <?php echo htmlspecialchars($cliente['website_cliente']); ?>
                        </a>
                    </td> -->
                    <td>
                        <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=editar&id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-secondary">Editar</a>
                        <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=eliminar&id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'views/layout/footer.php'; ?>
