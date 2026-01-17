<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Partos y Nacimientos</title>
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

        .kpi-pink {
            border-top-color: #e84393;
        }

        .kpi-green {
            border-top-color: #27ae60;
        }

        .kpi-red {
            border-top-color: #e74c3c;
            background-color: #fdedec;
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

        /* Sex Split bar in KPI */
        .sex-split {
            display: flex;
            width: 100%;
            margin-top: 5px;
        }

        .sex-m {
            color: #2980b9;
            font-weight: bold;
            margin-right: 10px;
        }

        .sex-f {
            color: #d63031;
            font-weight: bold;
        }

        /* Usando rojo/rosa oscuro para contraste */

        /** Tablas Distribución **/
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

        /** Badges **/
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            display: inline-block;
        }

        .bg-normal {
            background-color: #27ae60;
        }

        .bg-abort {
            background-color: #c0392b;
        }

        .bg-premature {
            background-color: #f39c12;
        }

        /* Sex Badges */
        .badge-sex {
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 9px;
        }

        .sex-male {
            background-color: #ebf5fb;
            color: #2980b9;
            border: 1px solid #aed6f1;
        }

        .sex-female {
            background-color: #fce4ec;
            color: #c2185b;
            border: 1px solid #f8bbd0;
        }

        .weight-box {
            font-weight: bold;
            background: #eee;
            padding: 2px 5px;
            border-radius: 3px;
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
            <h3>Reporte de Partos y Nacimientos</h3>
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
                <td><span class="label">Objetivo Partos:</span><br> {{ $summary['hato_objetivo'] }} vacas</td>
                <td><span class="label">Partos Registrados:</span><br> {{ $summary['total_mothers_with_birth'] }}
                    ({{ number_format($summary['cobertura_pct'], 1) }}%)</td>
            </tr>
        </table>
    </div>

    <div class="kpi-container clearfix">
        <div class="kpi-card kpi-green">
            <span class="label">Partos Normales</span>
            <span class="kpi-value">{{ number_format($summary['normal']['count']) }}</span>
            <span class="kpi-sub">{{ number_format($summary['normal']['pct'], 1) }}% Éxito</span>
        </div>

        <div class="kpi-card kpi-blue">
            <span class="label">Proporción Sexo</span>
            <div class="sex-split">
                <span class="sex-m"> {{ $summary['male_calves']['count'] }}
                    ({{ number_format($summary['male_calves']['pct'], 0) }}%)</span>
                <span class="sex-f"> {{ $summary['female_calves']['count'] }}
                    ({{ number_format($summary['female_calves']['pct'], 0) }}%)</span>
            </div>
            <span class="kpi-sub">Total Crías: {{ $summary['calves_with_sex_data'] }}</span>
        </div>

        <div class="kpi-card kpi-red">
            <span class="label">Pérdidas (Abort/Mortinato)</span>
            <span class="kpi-value">{{ $summary['abort']['count'] + $summary['stillbirth']['count'] }}</span>
            <span class="kpi-sub">Alertas reproductivas</span>
        </div>

        <div class="kpi-card">
            <span class="label">Pendientes de Parto</span>
            <span class="kpi-value">{{ $summary['faltantes']['count'] }}</span>
            <span class="kpi-sub">Según hato objetivo</span>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 15px;">
        <div class="col-2">
            <h3>Distribución por Tipo de Parto</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Tipo</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['type_of_birth'] as $type)
                        <tr>
                            <td class="text-left">{{ ucfirst($type['name']) }}</td>
                            <td>{{ $type['count'] }}</td>
                            <td>{{ number_format($type['pct'], 1) }}%</td>
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
            <h3>Distribución por Sexo</h3>
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Sexo Cría</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['distributions']['calf_sex'] as $sex)
                        <tr>
                            <td class="text-left">
                                @if ($sex['name'] == 'male')
                                    Macho
                                @else
                                    Hembra
                                @endif
                            </td>
                            <td>{{ $sex['count'] }}</td>
                            <td>{{ number_format($sex['pct'], 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="page-break-before: auto;">
        <h2>Detalle de Nacimientos</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th style="width: 15%">Madre (Vaca)</th>
                    <th style="width: 15%">Cría (Becerro/a)</th>
                    <th style="width: 10%">Sexo</th>
                    <th style="width: 10%">Peso Nac.</th>
                    <th style="width: 12%">Tipo Parto</th>
                    <th style="width: 10%">Fecha</th>
                    <th style="width: 23%">Nota / Regla</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['details'] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">
                            <strong>{{ $item['mother_serie'] }}</strong><br>
                            <span class="small-text">{{ $item['mother_rgd'] }}</span>
                        </td>
                        <td class="text-left">
                            <strong>{{ $item['calf_serie'] }}</strong><br>
                            <span class="small-text">{{ $item['calf_rgd'] }}</span>
                        </td>
                        <td>
                            @if ($item['calf_sex'] === 'male')
                                <span class="badge-sex sex-male">Macho</span>
                            @else
                                <span class="badge-sex sex-female">Hembra</span>
                            @endif
                        </td>
                        <td>
                            <span class="weight-box">{{ number_format($item['calf_weight'], 2) }} kg</span>
                        </td>
                        <td>
                            @if ($item['type_of_birth'] === 'normal')
                                <span class="badge bg-normal">Normal</span>
                            @elseif($item['type_of_birth'] === 'abort')
                                <span class="badge bg-abort">Aborto</span>
                            @elseif($item['type_of_birth'] === 'premature')
                                <span class="badge bg-premature">Prematuro</span>
                            @else
                                <span class="badge"
                                    style="background:#777">{{ ucfirst($item['type_of_birth']) }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item['birth_date'])->format('d/m/Y') }}</td>
                        <td class="text-left small-text">
                            {{ Str::limit($item['weaning_separation_rule'], 40) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        CONFIDENCIAL | Sergen Empresa Ganadera | Reporte de Partos | Pág. <span class="pagenum"></span>
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
