<?php
require_once 'controller.php';

class DashboardController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $database = new Database();
        $db = $database->getConnection();

        $query = "CALL sp_dashboard_estadisticas()";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $data['estadisticas'] = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $data['titulo'] = 'Dashboard';
        $this->view('dashboard/index', $data);
    }
}
