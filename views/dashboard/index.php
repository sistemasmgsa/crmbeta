<?php require_once 'views/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2>Bienvenido al CRM, <?php echo $_SESSION['usuario']['nombre_usuario']; ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="anio">Año</label>
                <select id="anio" class="form-control">
                    <?php foreach ($data['anios'] as $row) : ?>
                        <option value="<?php echo $row['anio']; ?>" <?php echo $row['anio'] == date('Y') ? 'selected' : ''; ?>><?php echo $row['anio']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mes">Mes</label>
                <select id="mes" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == date('n') ? 'selected' : ''; ?>><?php echo ucfirst(strftime('%B', mktime(0, 0, 0, $i, 1))); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="etapa">Etapa</label>
                <select id="etapa" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($data['etapas'] as $row) : ?>
                        <option value="<?php echo $row['id_etapa']; ?>"><?php echo $row['nombre_etapa']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <select id="usuario" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach ($data['usuarios'] as $row) : ?>
                        <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['nombre_usuario']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Valor Estimado de Oportunidades por Etapa</h4>
                </div>
                <div class="card-body">
                    <canvas id="funnelChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Ventas por Mes y Etapa</h4>
                </div>
                <div class="card-body">
                    <canvas id="barrasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxFunnel = document.getElementById('funnelChart').getContext('2d');
        const ctxBarras = document.getElementById('barrasChart').getContext('2d');
        let funnelChart, barrasChart;

        function fetchData() {
            const anio = document.getElementById('anio').value;
            const mes = document.getElementById('mes').value;
            const etapa = document.getElementById('etapa').value;
            const usuario = document.getElementById('usuario').value;

            const formData = new FormData();
            formData.append('anio', anio);
            formData.append('mes', mes);
            formData.append('etapa', etapa);
            formData.append('usuario', usuario);

            fetch('index.php?controller=dashboard&action=getData', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    renderFunnelChart(data.funnel);
                    renderBarrasChart(data.barras);
                });
        }

        function renderFunnelChart(data) {
            const labels = data.map(d => d.nombre_etapa);
            const values = data.map(d => d.valor_estimado);

            if (funnelChart) {
                funnelChart.destroy();
            }

            funnelChart = new Chart(ctxFunnel, {
                type: 'funnel',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Valor Estimado',
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    sort: 'desc',
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(context.raw);
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderBarrasChart(data) {
            const labels = [...new Set(data.map(d => d.mes))];
            const etapas = [...new Set(data.map(d => d.nombre_etapa))];
            const datasets = etapas.map(etapa => {
                return {
                    label: etapa,
                    data: labels.map(mes => {
                        const item = data.find(d => d.mes === mes && d.nombre_etapa === etapa);
                        return item ? item.total_ventas : 0;
                    }),
                    backgroundColor: getRandomColor(),
                };
            });

            if (barrasChart) {
                barrasChart.destroy();
            }

            barrasChart = new Chart(ctxBarras, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(context.raw);
                                }
                            }
                        }
                    }
                }
            });
        }

        function getRandomColor() {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return `rgba(${r}, ${g}, ${b}, 0.5)`;
        }

        document.getElementById('anio').addEventListener('change', fetchData);
        document.getElementById('mes').addEventListener('change', fetchData);
        document.getElementById('etapa').addEventListener('change', fetchData);
        document.getElementById('usuario').addEventListener('change', fetchData);

        fetchData();
    });
</script>

<?php require_once 'views/layout/footer.php'; ?>
