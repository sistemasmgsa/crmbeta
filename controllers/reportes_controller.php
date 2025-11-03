<?php
require_once 'models/ReportesModel.php';

class reportesController extends Controller {

    public function dinamicos() {
        $this->checkAuth();
        $data['titulo'] = "Reportes Dinámicos";

        $reportesModel = new ReportesModel($this->db);
        $report_type = isset($_POST['report_type']) ? $_POST['report_type'] : 'oportunidades';

        $data['report_type'] = $report_type;
        $data['available_columns'] = $reportesModel->obtener_columnas($report_type);
        $data['templates'] = $reportesModel->listar_plantillas($report_type);
        $data['selected_columns'] = [];
        $data['report_data'] = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
            if (isset($_POST['generate_report']) && isset($_POST['selected_columns'])) {
                $data['selected_columns'] = $_POST['selected_columns'];
                $data['report_data'] = $reportesModel->generar_reporte($report_type, $_POST['selected_columns']);
            } elseif (isset($_POST['load_template']) && isset($_POST['template_id'])) {
                $data['selected_columns'] = $reportesModel->cargar_plantilla($_POST['template_id']);
                $data['report_data'] = $reportesModel->generar_reporte($report_type, $data['selected_columns']);
            }
        }

        require_once 'views/layout/header.php';
        require_once 'views/reportes/dinamicos.php';
        require_once 'views/layout/footer.php';
    }

    public function ajax() {
        header('Content-Type: application/json');
        $reportesModel = new ReportesModel($this->db);
        $response = ['status' => 'error', 'data' => []];
        $action = $_GET['action'] ?? '';
        $report_type = $_GET['report_type'] ?? '';

        switch ($action) {
            case 'get_columns_and_templates':
                if ($report_type) {
                    $response['status'] = 'success';
                    $response['data']['columns'] = $reportesModel->obtener_columnas($report_type);
                    $response['data']['templates'] = $reportesModel->listar_plantillas($report_type);
                }
                break;

            case 'load_template':
                if (isset($_GET['template_id'])) {
                    $response['status'] = 'success';
                    $response['data']['columns'] = $reportesModel->cargar_plantilla($_GET['template_id']);
                }
                break;

            case 'save_template':
                if (isset($_POST['template_name']) && $report_type && isset($_POST['selected_columns'])) {
                    $result = $reportesModel->guardar_plantilla($_POST['template_name'], $report_type, $_POST['selected_columns']);
                    if($result) {
                        $response['status'] = 'success';
                        $response['data']['templates'] = $reportesModel->listar_plantillas($report_type);
                    }
                }
                break;

            case 'generate_report':
                 if ($report_type && isset($_POST['selected_columns'])) {
                    $response['status'] = 'success';
                    $response['data'] = $reportesModel->generar_reporte($report_type, $_POST['selected_columns']);
                }
                break;
        }

        echo json_encode($response);
        exit;
    }

    public function exportar_excel() {
        $this->checkAuth();
        if (isset($_POST['report_type']) && isset($_POST['selected_columns'])) {
            $report_type = $_POST['report_type'];
            $selected_columns = json_decode($_POST['selected_columns'], true);

            $reportesModel = new ReportesModel($this->db);
            $data = $reportesModel->generar_reporte($report_type, $selected_columns);

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="reporte_' . $report_type . '.xls"');
            header('Cache-Control: max-age=0');

            $output = fopen('php://output', 'w');

            // Encabezados
            fputcsv($output, $selected_columns, "\t");

            // Datos
            foreach ($data as $row) {
                $filtered_row = [];
                foreach($selected_columns as $col){
                    $filtered_row[] = $row[$col];
                }
                fputcsv($output, $filtered_row, "\t");
            }
            fclose($output);
            exit();
        }
    }
}
?>