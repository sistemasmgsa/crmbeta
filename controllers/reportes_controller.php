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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['ajax_action'])) {
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
        $action = $_GET['ajax_action'] ?? '';
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

        // Nombre del archivo
    date_default_timezone_set('America/Lima'); // Ajusta la zona horaria a Perú
    $fechaHora = date('Y-m-d_H-i-s'); // Año-Mes-Día_Hora-Minuto-Segundo
    $filename = "reporte_{$report_type}_{$fechaHora}.xls";

        // Headers para descarga
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");

        // Generar XML de Excel simple
        echo '<?xml version="1.0"?>';
        echo '<?mso-application progid="Excel.Sheet"?>';
        echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                xmlns:html="http://www.w3.org/TR/REC-html40">';
        
        // Estilos
        echo '<Styles>
                <Style ss:ID="Header">
                    <Font ss:Bold="1" ss:Color="#FFFFFF"/>
                    <Interior ss:Color="#003366" ss:Pattern="Solid"/>
                    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
                </Style>
              </Styles>';

        // Hoja
        echo '<Worksheet ss:Name="Reporte">';
        
        // Freeze pane (inmovilizar primera fila)
        echo '<Table ss:DefaultColumnWidth="110">';
        
        // Cabecera
        echo '<Row ss:StyleID="Header">';
        foreach ($selected_columns as $col) {
            echo "<Cell><Data ss:Type=\"String\">$col</Data></Cell>";
        }
        echo '</Row>';

        // Datos
        foreach ($data as $row) {
            echo '<Row>';
            foreach ($selected_columns as $col) {
                $valor = htmlspecialchars($row[$col]);
                echo "<Cell><Data ss:Type=\"String\">$valor</Data></Cell>";
            }
            echo '</Row>';
        }

        echo '</Table>';

        // SheetViews para congelar fila 1
        echo '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
                <FreezePanes/>
                <FrozenNoSplit/>
                <SplitHorizontal>1</SplitHorizontal>
                <TopRowBottomPane>1</TopRowBottomPane>
                <ActivePane>2</ActivePane>
              </WorksheetOptions>';

        echo '</Worksheet>';
        echo '</Workbook>';
        exit();
    }
}


}
?>