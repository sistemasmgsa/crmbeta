<?php require_once 'views/layout/header.php'; ?>

<!-- Chart.js y plugins -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel@4.2.0"></script>

<style>
    /* 🔹 Contenedor de filtros */
    .filtros-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .filtro-item label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        margin-bottom: 5px;
        display: block;
    }

    .filtro-item select {
        border-radius: 6px;
        border: 1px solid #bbb;
        padding: 10px 12px;
        font-size: 15px;
        width: 100%;
        background-color: #fff;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .filtro-item select:focus {
        border-color: #007bff;
        box-shadow: 0 0 3px rgba(0, 123, 255, 0.4);
        outline: none;
    }

    /* 🔹 Contenedor de gráficos */
    .graficos-container {
        display: flex;
        gap: 25px;
        align-items: stretch;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .grafico-card {
        flex: 1;
        min-width: 450px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
    }

    .grafico-card .card-header {
        background: #f4f4f4;
        padding: 12px 18px;
        border-bottom: 1px solid #ddd;
        border-radius: 10px 10px 0 0;
    }

    .grafico-card h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #333;
    }

    .grafico-card .card-body {
        padding: 20px;
        height: 420px;
    }

    @media (max-width: 992px) {
        .graficos-container {
            flex-direction: column;
        }
        .grafico-card {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <h2>Bienvenido al CRM</h2>

    <!-- 🔹 Filtros -->
    <div class="filtros-container">
        <div class="filtro-item">
            <label for="anio">Año</label>
            <select id="anio" class="form-control">
                <?php foreach ($data['anios'] as $row) : ?>
                    <option value="<?php echo $row['anio']; ?>" <?php echo $row['anio'] == date('Y') ? 'selected' : ''; ?>>
                        <?php echo $row['anio']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    <div class="filtro-item">
        <label for="mes">Mes</label>
        <select id="mes" class="form-control">
            <option value="0">Todos</option> <!-- 🔹 Nueva opción para mostrar todos los meses -->
            <?php 
            $meses = [
                1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
                5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
                9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
            ];
            $mes_actual = date('n');
            foreach ($meses as $num=>$nombre): ?>
                <option value="<?php echo $num; ?>" <?php echo $num==$mes_actual?'selected':''; ?>>
                    <?php echo $nombre; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


        <div class="filtro-item">
            <label for="etapa">Etapa</label>
            <select id="etapa" class="form-control">
                <option value="">Todas</option>
                <option>Calificación</option>
                <option>Propuesta</option>
                <option>Negociación</option>
                <option>Ganada</option>
                <option>Perdida</option>
            </select>
        </div>


        <!-- 🔹 Usuario: solo visible para administradores --> 
         <?php if (isset($_SESSION['usuario']['id_perfil']) && $_SESSION['usuario']['id_perfil'] == 1): ?> <div class="filtro-item"> 
            <label for="usuario">Usuario</label> <select id="usuario" class="form-control"> 
                <option value="">Todos</option> <?php foreach ($data['usuarios'] as $row): ?> 
                <option value="<?php echo $row['id_usuario']; ?>">
                <?php echo $row['nombre_usuario']; ?>
            </option> <?php endforeach; ?> </select> 
                
        </div> <?php else: ?> <!-- 🔹 Usuario normal: valor oculto --> <input type="hidden" id="usuario" value="<?php echo $_SESSION['usuario']['id_usuario']; ?>"> <?php endif; ?>



    </div>

    <!-- 🔹 Primera fila de gráficos -->
    <div class="graficos-container mt-4">
        <div class="grafico-card">
            <div class="card-header"><h4>Valor Estimado de Oportunidades por Etapa</h4></div>
            <div class="card-body"><canvas id="funnelChart"></canvas></div>
        </div>

        <div class="grafico-card">
            <div class="card-header"><h4>Oportunidades por Mes</h4></div>
            <div class="card-body"><canvas id="barrasChart"></canvas></div>
        </div>
    </div>

    <!-- 🔹 Segunda fila de gráficos -->
    <div class="graficos-container">

        <div class="grafico-card">
            <div class="card-header"><h4>Ganancias por Mes</h4></div>
            <div class="card-body"><canvas id="estadisticasChart_Ganancias"></canvas></div>
        </div>

        <div class="grafico-card">
            <div class="card-header"><h4>Perdida por Mes</h4></div>
            <div class="card-body"><canvas id="estadisticasChart_Perdidas"></canvas></div>
        </div>

    </div>
</div>

<!-- 🔹 Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxFunnel = document.getElementById('funnelChart').getContext('2d');
    const ctxBarras = document.getElementById('barrasChart').getContext('2d');
    const ctxEstadisticas_Ganancias = document.getElementById('estadisticasChart_Ganancias').getContext('2d');
    const ctxEstadisticas_Perdidas = document.getElementById('estadisticasChart_Perdidas').getContext('2d');


    let funnelChart, barrasChart, estadisticasChart_Ganancias, estadisticasChart_Perdidas;

    function fetchData() {
        const formData = new FormData();
        formData.append('anio', document.getElementById('anio').value);
        formData.append('mes', document.getElementById('mes').value);
        formData.append('etapa', document.getElementById('etapa').value);
        formData.append('usuario', document.getElementById('usuario').value);

        fetch('index.php?controller=dashboard&action=getData', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            renderFunnelChart(data.funnel);
            renderBarrasChart(data.barras);
            fetchGanancias();
            renderestadisticasChart_Ganancias(data.funnel);
            fetchPerdidas();
        });
    }

    function fetchGanancias() {
        const formData = new FormData();
        formData.append('anio', document.getElementById('anio').value);
        formData.append('mes', document.getElementById('mes').value);
        formData.append('usuario', document.getElementById('usuario').value);

        fetch('index.php?controller=dashboard&action=getGanadas', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => renderestadisticasChart_Ganancias(data.ganadas));
    }

    // 🔹 Obtener datos de Pérdidas
    function fetchPerdidas() {
        const formData = new FormData();
        formData.append('anio', document.getElementById('anio').value);
        formData.append('mes', document.getElementById('mes').value);
        formData.append('usuario', document.getElementById('usuario').value);

        fetch('index.php?controller=dashboard&action=getPerdidas', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => renderestadisticasChart_Perdidas(data.perdidas));
    }


    // 🔹 Gráfico Funnel
   function renderFunnelChart(data) {
    const labels = data.map(d => d.nombre_etapa);
    const values = data.map(d => parseFloat(d.valor_estimado));

    if (funnelChart) funnelChart.destroy();

    funnelChart = new Chart(ctxFunnel, {
        type: 'funnel',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#4CAF50', '#FF9800', '#9C27B0', '#2196F3', '#444444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 10 },
            plugins: {
                title: {
                    display: true,
                    text: `Oportunidades (S/ ${values.reduce((a,b)=>a+b,0).toLocaleString('es-PE')})`,
                    font: { size: 16, weight: 'bold' },
                    color: '#333'
                },
                legend: { display: false },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 13 },
                    align: 'center',
                    formatter: (value, ctx) => {
                        const label = ctx.chart.data.labels[ctx.dataIndex];
                        return `${label}\nS/ ${value.toLocaleString('es-PE')}`;
                    }
                }
            },
            funnel: {
                vertical: true,           // Embudo vertical
                inverted: true,           // 🔹 Esto invierte el embudo (de abajo hacia arriba)
                dynamicHeight: true,      // Ajusta la altura de cada etapa
                dynamicSlope: true,       // Ajusta la pendiente proporcional al valor
                minSize: 0.1,             // Altura mínima de etapa (evita que desaparezcan)
                topWidth: 1,              // Ancho relativo arriba (1 = 100%)
                bottomWidth: 0.2,         // Ancho relativo abajo
                gap: 0.03                 // Espacio entre etapas
            }
        },
        plugins: [ChartDataLabels]
    });
}

    // 🔹 Gráfico de Barras (Oportunidades)
function renderBarrasChart(data) {
    const mesesNombre = [
        '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const labelsNumeros = [...new Set(data.map(d => parseInt(d.mes)))].sort((a, b) => a - b);
    const labels = labelsNumeros.map(num => mesesNombre[num]);
    const etapas = [...new Set(data.map(d => d.nombre_etapa))];

    const datasets = etapas.map(etapa => ({
        label: etapa,
        data: labelsNumeros.map(mesNum => {
            const item = data.find(d => parseInt(d.mes) === mesNum && d.nombre_etapa === etapa);
            return item ? item.total_ventas : 0;
        }),
        backgroundColor: getRandomColor(),
    }));

    if (barrasChart) barrasChart.destroy();

    barrasChart = new Chart(ctxBarras, {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: S/ ${ctx.parsed.y.toLocaleString('es-PE')}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => `S/ ${v.toLocaleString('es-PE')}`
                    }
                }
            }
        }
    });
}


// 🔹 Gráfico Ganancias por Mes
function renderestadisticasChart_Ganancias(data) {
    if (estadisticasChart_Ganancias) estadisticasChart_Ganancias.destroy();

    const meses = [
        '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const labels = data.map(d => meses[parseInt(d.mes)]);
    const values = data.map(d => parseFloat(d.total_ganado));

    // 🎨 Gradiente azul con transparencia
    const gradient = ctxEstadisticas_Ganancias.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(0, 123, 255, 0.4)');
    gradient.addColorStop(1, 'rgba(0, 123, 255, 0)');

    estadisticasChart_Ganancias = new Chart(ctxEstadisticas_Ganancias, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Ganancia (S/)',
                data: values,
                borderColor: 'rgba(0, 123, 255, 0.9)',
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 5,                     // 🔹 tamaño del punto
                pointHoverRadius: 7,                // 🔹 más grande al pasar el mouse
                pointBackgroundColor: '#007bff',    // 🔹 color del punto
                pointBorderColor: '#fff',           // 🔹 borde blanco
                pointBorderWidth: 2,
                fill: 'origin',                      // 🔹 relleno bajo la línea
                tension: 0.3                         // 🔹 suaviza la curva
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 15, right: 10, bottom: 10, left: 5 }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: ctx => `S/ ${ctx.parsed.y.toLocaleString('es-PE')}`
                    }
                },
                datalabels: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(200,200,200,0.3)' },
                    ticks: {
                        color: '#333',
                        font: { size: 12, weight: 'bold' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(200,200,200,0.3)' },
                    ticks: {
                        color: '#333',
                        font: { size: 12, weight: 'bold' },
                        callback: v => `S/ ${v.toLocaleString('es-PE')}`
                    }
                }
            }
        }
    });
}

// 🔹 Gráfico Pérdidas por Mes
function renderestadisticasChart_Perdidas(data) {
    if (estadisticasChart_Perdidas instanceof Chart) {
        estadisticasChart_Perdidas.destroy();
    }

    const meses = [
        '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const labels = data.map(d => meses[parseInt(d.mes)]);
    const values = data.map(d => parseFloat(d.total_perdido));

    const gradient = ctxEstadisticas_Perdidas.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(255, 0, 0, 0.4)');
    gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');

    estadisticasChart_Perdidas = new Chart(ctxEstadisticas_Perdidas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Pérdida (S/)',
                data: values,
                borderColor: 'rgba(220, 53, 69, 0.9)',
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#dc3545',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                fill: 'origin',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 15, right: 10, bottom: 10, left: 5 }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: ctx => `S/ ${ctx.parsed.y.toLocaleString('es-PE')}`
                    }
                },
                datalabels: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(200,200,200,0.3)' },
                    ticks: { color: '#333', font: { size: 12, weight: 'bold' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(200,200,200,0.3)' },
                    ticks: {
                        color: '#333',
                        font: { size: 12, weight: 'bold' },
                        callback: v => `S/ ${v.toLocaleString('es-PE')}`
                    }
                }
            }
        }
    });
}


      

    function getRandomColor() {
        return `rgba(${Math.floor(Math.random()*255)},${Math.floor(Math.random()*255)},${Math.floor(Math.random()*255)},0.6)`;
    }

    document.querySelectorAll('#anio,#mes,#etapa,#usuario').forEach(e => e.addEventListener('change', fetchData));
    fetchData();
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
