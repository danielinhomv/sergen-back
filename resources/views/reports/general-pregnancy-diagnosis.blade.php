<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Palpación General</title>
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
            width: 18%;
            /* 5 Tarjetas */
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
        .kpi-neutral {
            border-top-color: #34495e;
        }

        .kpi-success {
            border-top-color: #27ae60;
            background-color: #f0f9f4;
        }

        .kpi-danger {
            border-top-color: #c0392b;
        }

        .kpi-warning {
            border-top-color: #f39c12;
        }

        .kpi-dark {
            border-top-color: #7f8c8d;
        }

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
            min-width: 65px;
            text-align: center;
        }

        .bg-pregnant {
            background-color: #27ae60;
        }

        .bg-empty {
            background-color: #e74c3c;
        }

        .bg-discard {
            background-color: #7f8c8d;
        }

        .bg-abort {
            background-color: #d35400;
        }

        /* Naranja oscuro para aborto */

        /** Heat Quality Text **/
        .hq-text {
            font-weight: bold;
            font-size: 9px;
        }

        .hq-good {
            color: #27ae60;
        }

        .hq-regular {
            color: #f39c12;
        }

        .hq-bad {
            color: #c0392b;
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

        .text-bold {
            font-weight: bold;
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
            <h3>Informe de Palpación General (Diagnóstico Final)</h3>
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
                <td><span class="label">Rango Fechas:</span><br> {{ $filters['date_start'] ?? 'Histórico Completo' }}
                </td>
                <td><span class="label">Hato Objetivo:</span><br> {{ $summary['hato_objetivo'] }} Cabezas</td>
                <td><span class="label">Cobertura:</span><br> {{ number_format($summary['cobertura_pct'], 1) }}%</td>
            </tr>
        </table>
    </div>

    <div class="kpi-container clearfix">
        <div class="kpi-card kpi-neutral">
            <span class="label">Total Palpado</span>
            <span class="kpi-value">{{ number_format($summary['total_diagnosticos']) }}</span>
            <span class="kpi-sub">Animales revisados</span>
        </div>

        <div class="kpi-card kpi-success">
            <span class="label">PREÑEZ TOTAL</span>
            <span class="kpi-value">{{ number_format($summary['pregnancy_pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($summary['pregnant']['count']) }} animales</span>
        </div>

        <div class="kpi-card kpi-danger">
            <span class="label">Vacías</span>
            <span class="kpi-value">{{ number_format($summary['empty']['pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($summary['empty']['count']) }} animales</span>
        </div>

        <div class="kpi-card kpi-warning">
            <span class="label">Abortos</span>
            <span class="kpi-value">{{ number_format($summary['abort']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['abort']['pct'], 1) }}% pérdida</span>
        </div>

        <div class="kpi-card kpi-dark">
            <span class="label">Descartes</span>
            <span class="kpi-value">{{ number_format($summary['discard']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['discard']['pct'], 1) }}% salida</span>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 15px;">
        <div class="col-2">
            <h3>Resultado por Toro (Genética)</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Toro</th>
                        <th>Total</th>
                        <th>Preñez</th>
                        <th>% Eficacia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['pregnancy_by_bull'] as $bull)
                        <tr>
                            <td class="text-left">{{ $bull['name'] }}</td>
                            <td>{{ $bull['total'] }}</td>
                            <td>{{ $bull['pregnant'] }}</td>
                            <td class="text-bold">
                                @if ($bull['pregnancy_rate_pct'] == 100)
                                    <span style="color:#27ae60">100%</span>
                                @elseif($bull['pregnancy_rate_pct'] >= 50)
                                    <span
                                        style="color:#2980b9">{{ number_format($bull['pregnancy_rate_pct'], 1) }}%</span>
                                @else
                                    <span
                                        style="color:#e74c3c">{{ number_format($bull['pregnancy_rate_pct'], 1) }}%</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="col-2">
            <h3>Resultado por Calidad de Celo</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Calidad Celo</th>
                        <th>Total</th>
                        <th>Preñez</th>
                        <th>% Eficacia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['pregnancy_by_heat_quality'] as $heat)
                        <tr>
                            <td class="text-left">{{ ucfirst($heat['name']) }}</td>
                            <td>{{ $heat['total'] }}</td>
                            <td>{{ $heat['pregnant'] }}</td>
                            <td class="text-bold">{{ number_format($heat['pregnancy_rate_pct'], 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="page-break-before: auto;">
        <h2>Detalle de Diagnóstico General</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th style="width: 10%">Bovino / RGD</th>
                    <th style="width: 12%">Estado Final</th>
                    <th style="width: 10%">Fecha Palp.</th>
                    <th style="width: 20%">Datos I.A. Origen</th>
                    <th style="width: 8%">Celo I.A.</th>
                    <th style="width: 35%">Observación / Gestación</th>
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
                            @php
                                $statusMap = [
                                    'pregnant' => ['class' => 'bg-pregnant', 'text' => 'PREÑADA'],
                                    'empty' => ['class' => 'bg-empty', 'text' => 'VACÍA'],
                                    'discard' => ['class' => 'bg-discard', 'text' => 'DESCARTE'],
                                    'abort' => ['class' => 'bg-abort', 'text' => 'ABORTO'],
                                ];
                                $currStatus = $statusMap[$item['status']] ?? [
                                    'class' => 'bg-discard',
                                    'text' => strtoupper($item['status']),
                                ];
                            @endphp
                            <span class="badge {{ $currStatus['class'] }}">{{ $currStatus['text'] }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                        <td class="text-left">
                            <span class="small-text">Toro:</span>
                            <strong>{{ Str::limit($item['bull_name'], 15) }}</strong><br>
                            <span
                                class="small-text">{{ \Carbon\Carbon::parse($item['insemination_date'])->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            @php
                                $hq = strtolower($item['heat_quality_raw']);
                                $hqClass = 'hq-regular';
                                if ($hq === 'well' || $hq === 'bueno') {
                                    $hqClass = 'hq-good';
                                }
                                if ($hq === 'bad' || $hq === 'malo') {
                                    $hqClass = 'hq-bad';
                                }
                            @endphp
                            <span class="hq-text {{ $hqClass }}">{{ ucfirst($item['heat_quality']) }}</span>
                        </td>
                        <td class="text-left">
                            {{ $item['observation'] ?: 'Sin observaciones' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        CONFIDENCIAL | Sergen Empresa Ganadera | Palpación General | Pág. <span class="pagenum"></span>
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
