<?php
class reportesController {
    public function dinamicos() {
        $data['titulo'] = "Reportes Dinámicos";
        require_once 'views/layout/header.php';
        require_once 'views/reportes/dinamicos.php';
        require_once 'views/layout/footer.php';
    }
}
?>