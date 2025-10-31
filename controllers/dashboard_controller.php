<?php
require_once 'controller.php';

class DashboardController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
    }

    public function index()
    {
        $data['titulo'] = 'Dashboard';
        $data['anios'] = $this->getAnios();
        $data['etapas'] = $this->getEtapas();
        $data['usuarios'] = $this->getUsuarios();
        $this->view('dashboard/index', $data);
    }

    public function getAnios()
    {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare("CALL sp_dashboard_anios()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getEtapas()
    {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare("CALL sp_dashboard_etapas()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getUsuarios()
    {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare("CALL sp_dashboard_usuarios()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getData()
    {
        header('Content-Type: application/json');
        $anio = $_POST['anio'] ?? date('Y');
        $mes = $_POST['mes'] ?? date('m');
        $etapa = $_POST['etapa'] ?? null;
        $usuario = $_POST['usuario'] ?? null;

        $database = new Database();
        $db = $database->getConnection();

        // Funnel data
        $stmt = $db->prepare("CALL sp_dashboard_funnel(:p_anio, :p_mes, :p_id_etapa, :p_id_usuario)");
        $stmt->bindParam(':p_anio', $anio, PDO::PARAM_INT);
        $stmt->bindParam(':p_mes', $mes, PDO::PARAM_INT);
        $stmt->bindParam(':p_id_etapa', $etapa, PDO::PARAM_INT);
        $stmt->bindParam(':p_id_usuario', $usuario, PDO::PARAM_INT);
        $stmt->execute();
        $funnelData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Bar chart data
        $stmt = $db->prepare("CALL sp_dashboard_barras(:p_anio, :p_mes, :p_id_etapa, :p_id_usuario)");
        $stmt->bindParam(':p_anio', $anio, PDO::PARAM_INT);
        $stmt->bindParam(':p_mes', $mes, PDO::PARAM_INT);
        $stmt->bindParam(':p_id_etapa', $etapa, PDO::PARAM_INT);
        $stmt->bindParam(':p_id_usuario', $usuario, PDO::PARAM_INT);
        $stmt->execute();
        $barrasData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        echo json_encode(['funnel' => $funnelData, 'barras' => $barrasData]);
    }
}
