<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Retiro de Implante</title>
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

        /** Cajas de Información **/
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

        /** KPI Dashboard **/
        .kpi-container {
            width: 100%;
            margin-bottom: 15px;
        }

        .kpi-card {
            float: left;
            width: 23%;
            /* 4 tarjetas */
            background: #fff;
            border: 1px solid #ddd;
            border-top: 3px solid #95a5a6;
            padding: 8px;
            margin-right: 2%;
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

        /* Retirados OK */
        .kpi-red {
            border-top-color: #e74c3c;
            background-color: #fdedec;
        }

        /* Perdidos (Alerta) */
        .kpi-neutral {
            border-top-color: #34495e;
        }

        /* Total */
        .kpi-green {
            border-top-color: #27ae60;
        }

        /* Cobertura */

        .kpi-value {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-top: 2px;
        }

        .kpi-sub {
            font-size: 9px;
            color: #7f8c8d;
            display: block;
            margin-top: 4px;
        }

        /** Tablas **/
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
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .bg-retrieved {
            background-color: #3498db;
        }

        /* Azul: Proceso normal */
        .bg-lost {
            background-color: #c0392b;
        }

        /* Rojo oscuro: Pérdida */

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

        .small-text {
            font-size: 8px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header-container clearfix">
        <div class="header-left">
            <h1>Sergen Empresa Ganadera</h1>
            <h3>Informe de Retiro de Implante</h3>
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
                <td><span class="label">Control ID:</span><br> {{ $filters['control_id'] }}</td>
                <td><span class="label">Fechas:</span><br> {{ $filters['date_start'] ?? 'Todas' }} -
                    {{ $filters['date_end'] ?? 'Todas' }}</td>
                <td><span class="label">Hato Objetivo:</span><br> {{ $summary['hato_objetivo'] }} Cabezas</td>
                <td><span class="label">Cobertura:</span><br> {{ number_format($summary['cobertura_pct'], 1) }}%</td>
            </tr>
        </table>
    </div>

    <div class="kpi-container clearfix">
        <div class="kpi-card kpi-neutral">
            <span class="label">Total Procesado</span>
            <span class="kpi-value">{{ number_format($summary['total_animals_processed']) }}</span>
            <span class="kpi-sub">Animales en manga</span>
        </div>

        <div class="kpi-card kpi-blue">
            <span class="label">Implantes Retirados</span>
            <span class="kpi-value">{{ number_format($summary['total_animals_retrieved']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['total_animals_retrieved']['pct'], 1) }}% Recupe.</span>
        </div>

        <div class="kpi-card kpi-red">
            <span class="label">Implantes Perdidos</span>
            <span class="kpi-value">{{ number_format($summary['implant_losses']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['implant_losses']['pct'], 1) }}% Tasa Pérdida</span>
        </div>

        <div class="kpi-card kpi-green">
            <span class="label">Faltantes en Manga</span>
            <span class="kpi-value">{{ number_format($summary['faltantes']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['faltantes']['pct'], 1) }}% Ausentismo</span>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 15px;">
        <div class="col-2">
            <h3>Desempeño por Equipo</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Equipo</th>
                        <th>Procesados</th>
                        <th>% Participación</th>
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
                            <td colspan="3">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="col-2">
            <h3>Productos Aplicados al Retiro</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Producto</th>
                        <th>Aplicaciones</th>
                        <th>% Cobertura</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['products'] as $prod)
                        <tr>
                            <td class="text-left">{{ $prod['name'] }}</td>
                            <td>{{ $prod['count'] }}</td>
                            <td>{{ number_format($prod['pct'], 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Sin productos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="page-break-before: auto;">
        <h2>Detalle de Retiro</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th style="width: 15%">Bovino / RGD</th>
                    <th style="width: 15%">Estado Implante</th>
                    <th style="width: 15%">Fecha Retiro</th>
                    <th style="width: 15%">Equipo</th>
                    <th style="width: 35%">Productos Aplicados</th>
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
                            @if ($item['status'] === 'retrieved')
                                <span class="badge bg-retrieved">RETIRADO</span>
                            @elseif($item['status'] === 'lost')
                                <span class="badge bg-lost">PERDIDO</span>
                            @else
                                <span class="badge" style="background:#777">{{ strtoupper($item['status']) }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                        <td>{{ $item['work_team'] }}</td>
                        <td class="text-left small-text">
                            {{ $item['used_products_summary'] ?: 'Ninguno' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        CONFIDENCIAL | Sergen Empresa Ganadera | Retiro de Implante | Pág. <span class="pagenum"></span>
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
