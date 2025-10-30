<?php
require_once 'controller.php';

class CalendarioController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();

        $query = "CALL sp_actividades_pendientes()";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $data['actividades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $data['titulo'] = 'Calendario';
        $this->view('calendario/index', $data);
    }
}
