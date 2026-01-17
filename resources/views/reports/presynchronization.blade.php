<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe Ejecutivo de Presincronización - Sergen</title>
    <style>
        /** Configuración de Página **/
        @page {
            margin: 10mm 10mm;
            size: A4 landscape;
        }

        /** Estilos Generales **/
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.2;
        }

        h1, h2, h3 { margin: 0; color: #2c3e50; }
        h1 { font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        h2 { font-size: 14px; border-bottom: 2px solid #2c3e50; padding-bottom: 5px; margin-bottom: 10px; margin-top: 15px; }
        h3 { font-size: 12px; margin-bottom: 5px; color: #555; }

        /** Header **/
        .header-container {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .header-left { float: left; width: 70%; }
        .header-right { float: right; width: 30%; text-align: right; font-size: 9px; color: #777; }
        .clearfix::after { content: ""; display: table; clear: both; }

        /** Cajas de Información (Filtros y KPIs) **/
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .filters-grid {
            width: 100%;
            font-size: 10px;
        }
        .filters-grid td { padding: 2px 10px 2px 0; vertical-align: top; }
        .label { font-weight: bold; color: #666; font-size: 9px; text-transform: uppercase; }

        /** Resumen KPI Cards **/
        .kpi-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .kpi-card {
            float: left;
            width: 18%; /* 5 tarjetas aprox */
            background: #fff;
            border: 1px solid #ddd;
            border-top: 3px solid #3498db; /* Azul */
            padding: 8px;
            margin-right: 1.5%;
            box-sizing: border-box;
            font-size: 10px;
            height: 80px; /* Altura fija para uniformidad */
        }
        .kpi-card:last-child { margin-right: 0; }
        .kpi-card.success { border-top-color: #27ae60; } /* Verde */
        .kpi-card.warning { border-top-color: #f39c12; } /* Naranja */

        .kpi-value { font-size: 14px; font-weight: bold; color: #2c3e50; display: block; margin-top: 5px; }
        .kpi-sub { font-size: 9px; color: #7f8c8d; }

        /** Tablas **/
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }

        th {
            background-color: #2c3e50;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            padding: 6px 4px;
            text-align: center;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #ddd;
            padding: 5px 4px;
            font-size: 10px;
            text-align: center;
            color: #444;
        }

        /* Cebra para legibilidad */
        tr:nth-child(even) { background-color: #f9f9f9; }

        /* Importante para PDFs multipágina */
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }

        /** Layout de Columnas para Distribuciones **/
        .col-3 {
            float: left;
            width: 32%;
            margin-right: 2%;
        }
        .col-3:last-child { margin-right: 0; }

        /** Badges (Si/No) **/
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
        }
        .badge-yes { background-color: #27ae60; }
        .badge-no { background-color: #e74c3c; opacity: 0.7; }

        /** Footer **/
        .footer {
            position: fixed;
            bottom: -10px; left: 0; right: 0;
            height: 20px;
            font-size: 8px;
            text-align: center;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        /* Utilidad de texto */
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
    </style>
</head>

<body>
    <div class="header-container clearfix">
        <div class="header-left">
            <h1>Sergen Empresa Ganadera</h1>
            <h3>Informe Ejecutivo de Presincronización</h3>
        </div>
        <div class="header-right">
            <p>Generado: {{ \Carbon\Carbon::parse($generatedAt)->format('d/m/Y H:i') }}<br>
            Usuario: Sistema</p>
        </div>
    </div>

    @php $filters = $data['filters_applied']; @endphp

    <div class="info-box">
        <table class="filters-grid">
            <tr>
                <td><span class="label">Tipo Filtro:</span> <br> {{ ucfirst($filters['filter_type'] ?? 'Todos') }}</td>
                <td><span class="label">Rango Fechas:</span> <br> {{ $filters['date_start'] ?? '-' }} al {{ $filters['date_end'] ?? '-' }}</td>
                <td><span class="label">Propiedad:</span> <br> {{ $filters['property_id'] ?? 'Todas' }}</td>
                <td><span class="label">Control ID:</span> <br> {{ $filters['control_id'] ?? 'Todos' }}</td>
            </tr>
        </table>
    </div>

    <div class="kpi-container clearfix">
        <div class="kpi-card">
            <span class="label">Cobertura</span>
            <span class="kpi-value">{{ number_format($data['summary']['cobertura_pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($data['summary']['total_records']) }} regs / {{ number_format($data['summary']['hato_objetivo'] ?? 0) }} obj</span>
        </div>

        <div class="kpi-card success">
            <span class="label">Vac. Reproductiva</span>
            <span class="kpi-value">{{ number_format($data['summary']['with_reproductive_vaccine']['pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($data['summary']['with_reproductive_vaccine']['count']) }} animales</span>
        </div>

        <div class="kpi-card success">
            <span class="label">Sincrogest</span>
            <span class="kpi-value">{{ number_format($data['summary']['with_sincrogest_product']['pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($data['summary']['with_sincrogest_product']['count']) }} animales</span>
        </div>

        <div class="kpi-card warning">
            <span class="label">Antiparasitario</span>
            <span class="kpi-value">{{ number_format($data['summary']['with_antiparasitic_product']['pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($data['summary']['with_antiparasitic_product']['count']) }} animales</span>
        </div>

         <div class="kpi-card warning">
            <span class="label">Vitaminas</span>
            <span class="kpi-value">{{ number_format($data['summary']['with_vitamins_and_minerals']['pct'], 1) }}%</span>
            <span class="kpi-sub">{{ number_format($data['summary']['with_vitamins_and_minerals']['count']) }} animales</span>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 20px;">
        <div class="col-3">
            <h3>Vacuna Reproductiva</h3>
            <table>
                <thead>
                    <tr><th class="text-left">Producto</th><th>Cant.</th><th>%</th></tr>
                </thead>
                <tbody>
                    @foreach ($data['distributions']['reproductive_vaccine'] as $item)
                    <tr>
                        <td class="text-left">{{ Str::limit($item['name'], 20) }}</td>
                        <td>{{ $item['count'] }}</td>
                        <td>{{ number_format($item['pct'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-3">
            <h3>Sincrogest</h3>
            <table>
                <thead>
                    <tr><th class="text-left">Producto</th><th>Cant.</th><th>%</th></tr>
                </thead>
                <tbody>
                    @foreach ($data['distributions']['sincrogest_product'] as $item)
                    <tr>
                        <td class="text-left">{{ Str::limit($item['name'], 20) }}</td>
                        <td>{{ $item['count'] }}</td>
                        <td>{{ number_format($item['pct'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-3">
            <h3>Antiparasitario</h3>
            <table>
                <thead>
                    <tr><th class="text-left">Producto</th><th>Cant.</th><th>%</th></tr>
                </thead>
                <tbody>
                    @foreach ($data['distributions']['antiparasitic_product'] as $item)
                    <tr>
                        <td class="text-left">{{ Str::limit($item['name'], 20) }}</td>
                        <td>{{ $item['count'] }}</td>
                        <td>{{ number_format($item['pct'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="page-break-before: auto;">
        <h2>Detalle de Registros</h2>
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 8%">Bovino / RGD</th>
                    <th style="width: 15%">Propiedad</th>
                    <th style="width: 7%">Fecha</th>
                    <th style="width: 18%">Vac. Repro</th>
                    <th style="width: 18%">Sincrogest</th>
                    <th style="width: 18%">Antiparasitario</th>
                    <th style="width: 5%">Vit.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['details'] as $detail)
                    <tr>
                        <td>{{ $detail['control_bovine_id'] }}</td>
                        <td class="text-left">
                            <strong>{{ $detail['bovine_id'] }}</strong><br>
                            <span style="color:#777">{{ $detail['rgd'] ?? '-' }}</span>
                        </td>
                        <td class="text-left">{{ Str::limit($detail['property_name'], 25) }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail['application_date'])->format('d/m/y') }}</td>

                        <td class="text-left">{{ $detail['reproductive_vaccine'] ?: '-' }}</td>
                        <td class="text-left">{{ $detail['sincrogest_product'] ?: '-' }}</td>
                        <td class="text-left">{{ $detail['antiparasitic_product'] ?: '-' }}</td>

                        <td>
                            @if ($detail['vitamins_and_minerals'])
                                <span class="badge badge-yes">Sí</span>
                            @else
                                <span class="badge badge-no">No</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        CONFIDENCIAL | Sergen Empresa Ganadera | Pág. <span class="page-number"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 780;
            $y = 575;
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $font = null;
            $size = 8;
            $color = array(0.5, 0.5, 0.5);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>
</html>
