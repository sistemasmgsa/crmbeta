<?php
require_once 'controller.php';

class DashboardController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $data['titulo'] = 'Dashboard';
        $this->view('dashboard/index', $data);
    }
}
