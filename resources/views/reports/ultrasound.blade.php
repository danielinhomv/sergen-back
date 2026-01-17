<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Ecografía - Inicio Protocolo</title>
    <style>
        /** Configuración de Página **/
        @page {
            margin: 8mm 10mm;
            size: A4 landscape;
        }

        /** Estilos Generales **/
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.2;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            color: #2c3e50;
        }

        h1 {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        h2 {
            font-size: 13px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 4px;
            margin-bottom: 8px;
            margin-top: 12px;
        }

        h3 {
            font-size: 11px;
            margin-bottom: 5px;
            color: #555;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 2px;
        }

        /** Header **/
        .header-container {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
        }

        .header-left {
            float: left;
            width: 70%;
        }

        .header-right {
            float: right;
            width: 30%;
            text-align: right;
            font-size: 9px;
            color: #777;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /** Cajas de Información (Filtros) **/
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 6px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .filters-grid {
            width: 100%;
            font-size: 10px;
        }

        .filters-grid td {
            padding: 2px 10px 2px 0;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #666;
            font-size: 8px;
            text-transform: uppercase;
        }

        /** Resumen KPI Cards **/
        .kpi-container {
            width: 100%;
            margin-bottom: 10px;
        }

        .kpi-card {
            float: left;
            width: 15.5%;
            /* Ajustado para 6 tarjetas */
            background: #fff;
            border: 1px solid #ddd;
            border-top: 3px solid #95a5a6;
            /* Gris default */
            padding: 6px;
            margin-right: 1.4%;
            box-sizing: border-box;
            height: 65px;
        }

        .kpi-card:last-child {
            margin-right: 0;
        }

        /* Colores KPI */
        .kpi-blue {
            border-top-color: #3498db;
        }

        /* Hato */
        .kpi-green {
            border-top-color: #27ae60;
        }

        /* Efectividad */
        .kpi-purple {
            border-top-color: #9b59b6;
        }

        /* Implantadas */
        .kpi-gold {
            border-top-color: #f1c40f;
        }

        /* Preñadas */
        .kpi-red {
            border-top-color: #e74c3c;
        }

        /* Refugos/Faltantes */

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-top: 2px;
        }

        .kpi-sub {
            font-size: 9px;
            color: #7f8c8d;
            display: block;
            margin-top: 2px;
        }

        /** Tablas de Distribución (Side by Side) **/
        .col-2 {
            float: left;
            width: 49%;
            margin-right: 2%;
        }

        .col-2:last-child {
            margin-right: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        th {
            background-color: #34495e;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            padding: 5px 4px;
            text-align: center;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #ecf0f1;
            padding: 4px 4px;
            font-size: 9px;
            text-align: center;
            color: #444;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        /** Status Badges **/
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            display: inline-block;
        }

        .bg-implanted {
            background-color: #3498db;
        }

        /* Azul - Iniciando protocolo */
        .bg-pregnant {
            background-color: #27ae60;
        }

        /* Verde - Ya preñada */
        .bg-discarded {
            background-color: #e74c3c;
        }

        /* Rojo - Refugo */
        .bg-vitamins {
            background-color: #f39c12;
            color: #fff;
        }

        /** Footer **/
        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 15px;
            font-size: 8px;
            text-align: center;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 2px;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-bold {
            font-weight: bold;
        }

        .small-text {
            font-size: 8px;
            color: #777;
            display: block;
        }
    </style>
</head>

<body>
    <div class="header-container clearfix">
        <div class="header-left">
            <h1>Sergen Empresa Ganadera</h1>
            <h3>Reporte de Ecografía (Inicio de Protocolo)</h3>
        </div>
        <div class="header-right">
            <p>Generado: {{ \Carbon\Carbon::parse($generatedAt)->format('d/m/Y H:i') }}<br>
                Propiedad ID: {{ $data['filters_applied']['property_id'] }}</p>
        </div>
    </div>

    @php
        $summary = $data['summary'];
        $filters = $data['filters_applied'];
    @endphp

    <div class="info-box">
        <table class="filters-grid">
            <tr>
                <td><span class="label">Tipo Filtro:</span><br> {{ $filters['filter_type'] }}</td>
                <td><span class="label">Fechas:</span><br> {{ $filters['date_start'] ?? 'N/A' }} -
                    {{ $filters['date_end'] ?? 'N/A' }}</td>
                <td><span class="label">Control ID:</span><br> {{ $filters['control_id'] }}</td>
                <td><span class="label">Hato Objetivo:</span><br>
                    <strong>{{ number_format($summary['hato_objetivo']) }}</strong>
                </td>
                <td><span class="label">Cobertura:</span><br>
                    {{ number_format(($summary['hato_evaluado']['count'] / ($summary['hato_objetivo'] > 0 ? $summary['hato_objetivo'] : 1)) * 100, 1) }}%
                </td>
            </tr>
        </table>
    </div>

    <div class="kpi-container clearfix">
        <div class="kpi-card kpi-blue">
            <span class="label">Hato Evaluado</span>
            <span class="kpi-value">{{ number_format($summary['hato_evaluado']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['hato_evaluado']['pct'], 1) }}% del obj.</span>
        </div>

        <div class="kpi-card kpi-green">
            <span class="label">Efectividad Real</span>
            <span class="kpi-value">{{ number_format($summary['efectividad_real']['pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($summary['efectividad_real']['count']) }} vacas</span>
        </div>

        <div class="kpi-card kpi-purple">
            <span class="label">Implantadas</span>
            <span class="kpi-value">{{ number_format($summary['implanted']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['implanted']['pct'], 1) }}%</span>
        </div>

        <div class="kpi-card kpi-gold">
            <span class="label">Preñadas (Eco)</span>
            <span class="kpi-value">{{ number_format($summary['pregnant']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['pregnant']['pct'], 1) }}%</span>
        </div>

        <div class="kpi-card kpi-blue">
            <span class="label">Vitaminas</span>
            <span class="kpi-value">{{ number_format($summary['with_vitamins_and_minerals']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['with_vitamins_and_minerals']['pct'], 1) }}%</span>
        </div>

        <div class="kpi-card kpi-red">
            <span class="label">Faltantes / Ref.</span>
            <span class="kpi-value">{{ $summary['faltantes']['count'] }} / {{ $summary['refugos']['count'] }}</span>
            <span class="kpi-sub">Alertas</span>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 15px;">
        <div class="col-2">
            <h3>Desempeño por Equipo</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Equipo</th>
                        <th>Cant.</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['work_team'] as $team)
                        <tr>
                            <td class="text-left">{{ $team['name'] }}</td>
                            <td>{{ $team['count'] }}</td>
                            <td>{{ number_format($team['pct'], 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Sin datos de equipos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="col-2">
            <h3>Productos Utilizados</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Producto</th>
                        <th>Cant.</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['products'] as $prod)
                        <tr>
                            <td class="text-left">{{ Str::limit($prod['name'], 30) }}</td>
                            <td>{{ $prod['count'] }}</td>
                            <td>{{ number_format($prod['pct'], 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No se registraron productos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="page-break-before: auto;">
        <h2>Detalle de Animales Evaluados</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th style="width: 10%">Bovino / RGD</th>
                    <th style="width: 12%">Estado</th>
                    <th style="width: 25%">Detalle Protocolo</th>
                    <th style="width: 25%">Productos Aplicados</th>
                    <th style="width: 13%">Equipo</th>
                    <th style="width: 5%">Vit.</th>
                    <th style="width: 5%">Obs.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['details'] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">
                            <strong>{{ $item['serie'] }}</strong><br>
                            <span class="small-text">{{ $item['rgd'] }}</span>
                        </td>
                        <td>
                            @if ($item['status'] === 'implanted')
                                <span class="badge bg-implanted">En Protocolo</span>
                            @elseif($item['status'] === 'pregnant')
                                <span class="badge bg-pregnant">Preñada</span>
                            @elseif($item['status'] === 'discarded' || $item['status'] === 'refugo')
                                <span class="badge bg-discarded">Refugo</span>
                            @else
                                <span class="badge" style="background:#999">{{ ucfirst($item['status']) }}</span>
                            @endif
                        </td>
                        <td class="text-left">
                            {{ $item['protocol_details'] ?? '-' }}<br>
                            <span class="small-text">{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</span>
                        </td>
                        <td class="text-left small-text">
                            {{ $item['used_products_summary'] ?: '-' }}
                        </td>
                        <td>{{ $item['work_team'] }}</td>
                        <td>
                            @if ($item['tenfo_vitamins'])
                                <span style="color:#27ae60; font-weight:bold;">Sí</span>
                            @else
                                <span style="color:#ddd;">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($item['regufo'])
                                <span style="color:red; font-size:8px;">!</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        CONFIDENCIAL | Sergen Empresa Ganadera | Reporte ID: {{ $data['filters_applied']['control_id'] }} | Pág. <span
            class="pagenum"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 780;
            $y = 575;
            $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
            $font = null;
            $size = 8;
            $color = array(0.5, 0.5, 0.5);
            $pdf->page_text($x, $y, $text, $font, $size, $color, 0, 0, 0);
        }
    </script>
</body>

</html>
