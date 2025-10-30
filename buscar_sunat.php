<?php

$dato = $_GET['numero'];
$tipo = $_GET['tipo']; // 1 = DNI, 2 = RUC

if ($tipo == "1") {
    // ✅ Consulta DNI
    $url = "https://ww1.sunat.gob.pe/ol-ti-itfisdenreg/itfisdenreg.htm?accion=obtenerDatosDni&numDocumento=" . $dato . "&fbclid=IwY2xjawLl_8tleHRuA2FlbQIxMABicmlkETFPNWFzMGFXU0tlbFlRQUpsAR6FoFQGwykkYwy4VheXYxgsHxJ58Zx-JimIliJagvVJg-AJAEHb0ahYGMIaFQ_aem_AqXUp7Gbu8_DHXeXZz2COA";
} 
else if ($tipo == "2") {
    // ✅ Consulta RUC
    $url = "https://ww1.sunat.gob.pe/ol-ti-itfisdenreg/itfisdenreg.htm?accion=obtenerDatosRuc&nroRuc=" . $dato . "&fbclid=IwY2xjawLl_79leHRuA2FlbQIxMABicmlkETFPNWFzMGFXU0tlbFlRQUpsAR4ydNOOgEeCOS59X0FFrMF3Ncs_y7cuX8emrIDtMyILldGfFYE_IcwHB0q4QA_aem_dBaUPr4dpya67U2czqvIUA";
} 
else {
    echo json_encode(["success" => false, "msg" => "Tipo no soportado"]);
    exit;
}

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

if (!$data || $data["message"] !== "success") {
    echo json_encode(["success" => false]);
    exit;
}

// ✅ Respuesta para DNI
if ($tipo == "1") {
    echo json_encode([
        "success" => true,
        "nombre" => $data["lista"][0]["nombresapellidos"],
        "direccion" => ""
    ]);
}
// ✅ Respuesta para RUC
else if ($tipo == "2") {
    echo json_encode([
        "success" => true,
        "nombre" => trim($data["lista"][0]["apenomdenunciado"]),
        "direccion" => trim($data["lista"][0]["direstablecimiento"]),
        "departamento" => trim($data["lista"][0]["desdepartamento"]),
        "provincia" => trim($data["lista"][0]["desprovincia"]),
        "distrito" => trim($data["lista"][0]["desdistrito"])
    ]);
}

exit;
