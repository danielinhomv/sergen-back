    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Informe de Inseminación</title>
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

            /** KPI Cards **/
            .kpi-container {
                width: 100%;
                margin-bottom: 10px;
            }

            .kpi-card {
                float: left;
                width: 23%;
                /* 4 tarjetas grandes */
                background: #fff;
                border: 1px solid #ddd;
                border-top: 3px solid #95a5a6;
                padding: 8px;
                margin-right: 2%;
                box-sizing: border-box;
                height: 60px;
            }

            .kpi-card:last-child {
                margin-right: 0;
            }

            .kpi-blue {
                border-top-color: #3498db;
            }

            .kpi-green {
                border-top-color: #27ae60;
            }

            .kpi-red {
                border-top-color: #e74c3c;
            }

            .kpi-value {
                font-size: 18px;
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

            /** Heat Quality Badges **/
            .badge {
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 8px;
                font-weight: bold;
                color: #fff;
                text-transform: uppercase;
            }

            .hq-good {
                background-color: #27ae60;
            }

            /* Bueno - Verde */
            .hq-regular {
                background-color: #f39c12;
            }

            /* Regular - Naranja */
            .hq-bad {
                background-color: #c0392b;
            }

            /* Malo - Rojo */

            /** ECC Box **/
            .ecc-box {
                border: 1px solid #ddd;
                background: #eee;
                padding: 2px 5px;
                border-radius: 3px;
                font-weight: bold;
                font-size: 9px;
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
                <h3>Reporte de Inseminación Artificial</h3>
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
                    <td><span class="label">Control ID:</span><br> {{ $filters['control_id'] }}</td>
                    <td><span class="label">Fechas:</span><br> {{ $filters['date_start'] ?? 'Todas' }} -
                        {{ $filters['date_end'] ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div class="kpi-container clearfix">
            <div class="kpi-card kpi-blue">
                <span class="label">Animales Inseminados</span>
                <span class="kpi-value">{{ number_format($summary['total_animals_inseminated']) }}</span>
                <span class="kpi-sub">Total Registros: {{ $summary['total_records'] }}</span>
            </div>

            <div class="kpi-card">
                <span class="label">Hato Objetivo</span>
                <span class="kpi-value">{{ number_format($summary['hato_objetivo']) }}</span>
                <span class="kpi-sub">Cabezas programadas</span>
            </div>

            <div class="kpi-card kpi-green">
                <span class="label">Cobertura</span>
                <span class="kpi-value">{{ number_format($summary['cobertura_pct'], 1) }}%</span>
                <span class="kpi-sub">Ejecución vs Objetivo</span>
            </div>

            <div class="kpi-card kpi-red">
                <span class="label">Faltantes</span>
                <span class="kpi-value">{{ number_format($summary['faltantes']['count']) }}</span>
                <span class="kpi-sub">{{ number_format($summary['faltantes']['pct'], 1) }}% del hato</span>
            </div>
        </div>

        <div class="clearfix" style="margin-bottom: 15px;">
            <div class="col-2">
                <h3>Toros Utilizados</h3>
                <table>
                    <thead>
                        <tr>
                            <th class="text-left">Nombre del Toro</th>
                            <th>Cant.</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['distributions']['bulls'] as $bull)
                            <tr>
                                <td class="text-left">{{ $bull['name'] }}</td>
                                <td>{{ $bull['count'] }}</td>
                                <td>{{ number_format($bull['pct'], 1) }}%</td>
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
                <h3>Calidad de Celo</h3>
                <table>
                    <thead>
                        <tr>
                            <th class="text-left">Calidad</th>
                            <th>Cant.</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['distributions']['heat_quality'] as $heat)
                            @php
                                // Determinar color de barra lateral simple en tabla
                                $colorStyle = 'border-left: 3px solid #ccc;';
                                if ($heat['name'] == 'bueno') {
                                    $colorStyle = 'border-left: 3px solid #27ae60;';
                                }
                                if ($heat['name'] == 'regular') {
                                    $colorStyle = 'border-left: 3px solid #f39c12;';
                                }
                                if ($heat['name'] == 'malo') {
                                    $colorStyle = 'border-left: 3px solid #c0392b;';
                                }
                            @endphp
                            <tr>
                                <td class="text-left" style="{{ $colorStyle }} padding-left:8px;">
                                    {{ ucfirst($heat['name']) }}
                                </td>
                                <td>{{ $heat['count'] }}</td>
                                <td>{{ number_format($heat['pct'], 1) }}%</td>
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
            <h2>Detalle de Inseminación</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No.</th>
                        <th style="width: 10%">Bovino / RGD</th>
                        <th style="width: 10%">Fecha I.A.</th>
                        <th style="width: 25%">Toro Utilizado</th>
                        <th style="width: 10%">Calidad Celo</th>
                        <th style="width: 8%">ECC</th>
                        <th style="width: 32%">Observaciones</th>
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
                            <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                            <td class="text-left">{{ $item['bull_name'] }}</td>
                            <td>
                                @php
                                    $q = strtolower($item['heat_quality']);
                                    $badgeClass = 'hq-regular'; // Default
                                    if ($q == 'bueno' || $item['heat_quality_raw'] == 'well') {
                                        $badgeClass = 'hq-good';
                                    }
                                    if ($q == 'malo' || $item['heat_quality_raw'] == 'bad') {
                                        $badgeClass = 'hq-bad';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($item['heat_quality']) }}</span>
                            </td>
                            <td>
                                <span class="ecc-box">{{ number_format($item['body_condition_score'], 1) }}</span>
                            </td>
                            <td class="text-left small-text">
                                {{ $item['observation'] ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            CONFIDENCIAL | Sergen Empresa Ganadera | Control ID: {{ $data['filters_applied']['control_id'] }} | Pág.
            <span class="pagenum"></span>
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
