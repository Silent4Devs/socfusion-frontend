<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Seguridad | Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        @page {
            size: 8.5in 11in;
            margin: .1in;
        }

        body {
            font-family: "Roboto", sans-serif;
            background-color: #ffffff;
            color: #333;
            line-height: 1.4;
            padding: 0px;
            margin: 0;
            font-size: 14px;
        }

        table, td, tr, div, .section-title, h3, h1, h2, p {
            font-family: "Roboto", sans-serif !important;
        }
        .container {

            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        
        .client-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
            margin-right: 20px;
        }
        
        .header-info h1 {
            font-size: 22px;
            margin: 0 0 5px 0;
            color: #222222;
            font-weight: 600;
        }
        
        .header-info p {
            color: #666666;
            margin: 0;
            font-size: 14px;
        }
        
        .report-title {
            font-weight: 700;
            font-size: 1.3rem;
            color: #020617;
            text-align: center;
            padding: 10px;
            position: relative;
            letter-spacing: -0.025em;
        }

        .report-title::after {
            content: ' ';
            display: block;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #38a6cc, #92c13b);
            margin: 0.75rem auto 0;
            border-radius: 3px;
        }
        
        .section {
            padding: 10px;
            width: 50%;
            box-sizing: border-box;
        }
        
        .section-title {
            color: #222222;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
        }
        
        
        .detail-label {
            letter-spacing: 0.5px;
            color: #666666;
            margin-bottom: 5px;
            display: block;
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 15px;
            word-break: break-word;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e0e0e0;
            color: #333333;
        }
        
        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .events-table th {
            text-align: left;
            padding: 10px;
            background-color: #f8f8f8;
            color: #444444;
            font-weight: 500;
       
        }
        
        .events-table td {
            padding: 10px;
        }
        
        .critical {
            color: #d32f2f;
            font-weight: 500;
        }
        
        .signature {
            padding: 20px;
            text-align: right;
            border-top: 1px solid #f0f0f0;
        }
        
        .signature-line {
            display: inline-block;
            width: 180px;
            border-top: 1px solid #cccccc;
            margin-top: 25px;
        }
        
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #f0f0f0;
            margin-top: auto;
        }
        
        .section-full{
            width: 100%;
            padding: 10px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table width="100%" style="border-collapse: collapse; margin: 0; padding: 0;">
                <tr>
                    <td style="padding: 0; margin: 0; vertical-align: middle;">
                        <table style="margin: 0; padding: 0;">
                            <tr>
                                <td style="padding: 0 10px;">
                                    <img src="{{ public_path('images/cliente.png') }}" alt="Cliente" width="70" height="70" style="border-radius: 50%; border: 2px solid #e0e0e0;">
                                </td>
                                <td style="padding: 0;">
                                    <h1 style="margin: 0; font-size: 22px; color: #222222;">Nombre del Cliente</h1>
                                    <p style="margin: 0; color: #666666; font-size: 14px;">Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="text-align: right; padding: 0; margin: 0; vertical-align: middle;">
                        <img src="{{ $logo }}" alt="Silent4Business" style="height: 70px;">
                    </td>
                </tr>
            </table>

        </div>
        
        <h2 class="report-title">Reporte de Incidente de Seguridad</h2>
        
        <table style="width: 100%; height: 570px; vertical-align: top; border-spacing:0;">
            <tr style="width: 100%;">

                <td class="section" style="margin-top: 0;">
                    <div class="section-title">Información Básica</div>
                    <div class="detail-item">
                        <span class="detail-label">Herramienta de Detección</span>
                            <div class="detail-value">
                                @if ($alarmType === 'logrhythm')
                                    LogRhythm SIEM
                                @else
                                    PRTG
                                @endif
                            </div>
                    </div>
                @isset($alarm['alarm_rule_name'])
                    @if (!empty($alarm['alarm_rule_name']))
                        <div class="detail-item">
                            <span class="detail-label">Alarma</span>
                            <div class="detail-value">{{ $alarm['alarm_rule_name'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['date_inserted'])
                    @if (!empty($alarm['date_inserted']))
                        <div class="detail-item">
                            <span class="detail-label">Fecha </span>
                            <div class="detail-value">{{ $alarm['date_inserted'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['log_source_type_name'])
                    @if (!empty($alarm['log_source_type_name']))
                        <div class="detail-item">
                            <span class="detail-label">Tipo de origen de log</span>
                            <div class="detail-value">{{ $alarm['log_source_type_name'] }}</div>
                        </div>
                    @endif
                @endisset


                @isset($alarm['event_count'])
                    @if (!empty($alarm['event_count']))
                        <div class="detail-item">
                            <span class="detail-label">Conteo de eventos</span>
                            <div class="detail-value">{{ $alarm['event_count'] }}</div>
                        </div>
                    @endif
                @endisset


                @isset($alarm['name_raw'])
                    @if (!empty($alarm['name_raw']))
                        <div class="detail-item">
                            <span class="detail-label">Nombre</span>
                            <div class="detail-value">{{ $alarm['name_raw'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['status'])
                    @if (!empty($alarm['status']))
                        <div class="detail-item">
                            <span class="detail-label">Estado</span>
                            <div class="detail-value">{{ $alarm['status'] }}</div>
                        </div>
                    @endif
                @endisset
                
                @isset($alarm['message_raw'])
                    @if (!empty($alarm['message_raw']))
                        <div class="detail-item">
                            <span class="detail-label">Mensaje crudo</span>
                            <div class="detail-value">{{ $alarm['message_raw'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['datetime'])
                    @if (!empty($alarm['datetime']))
                        <div class="detail-item">
                            <span class="detail-label">Fecha y hora</span>
                            <div class="detail-value">{{ $alarm['datetime'] }}</div>
                        </div>
                    @endif
                @endisset
                </td>
            
                <td class="section" style="margin-top: 0;">
                    <div class="section-title">Detalles Técnicos</div>
             
                    @isset($alarm['impacted_entity_name'])
                        @if (!empty($alarm['impacted_entity_name']))
                            <div class="detail-item">
                                <span class="detail-label">Entidad impactada</span>
                                <div class="detail-value">{{ $alarm['impacted_entity_name'] }}</div>
                            </div>
                        @endif
                    @endisset
                    @isset($alarm['impacted_ip'])
                        @if (!empty($alarm['impacted_ip']))
                            <div class="detail-item">
                                <span class="detail-label">IP impactada</span>
                                <div class="detail-value">{{ $alarm['impacted_ip'] }}</div>
                            </div>
                        @endif
                    @endisset


                @isset($alarm['priority'])
                    @if (!empty($alarm['priority']))
                        <div class="detail-item">
                            <span class="detail-label">Prioridad</span>
                            <div class="detail-value">{{ $alarm['priority'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['active'])
                    {{-- This checks if it's not null, will print Sí/No --}}
                    <div class="detail-item">
                        <span class="detail-label">Activo</span>
                        <div class="detail-value">{{ $alarm['active'] ? 'Sí' : 'No' }}</div>
                    </div>
                @endisset


                    @isset($alarm['sensor'])
                        @if (!empty($alarm['sensor']))
                            <div class="detail-item">
                                <span class="detail-label">Sensor</span>
                                <div class="detail-value">{{ $alarm['sensor'] }}</div>
                            </div>
                        @endif
                    @endisset

                </td>
            </tr>
            
        </table>
        <div class="section-full">
                <div class="section-title">Acciones y Recomendaciones</div>
                @if(isset($suggestion) && !empty($suggestion))
                <div class="detail-item">
                    <span class="detail-label">Recomendaciones</span>
                    <div class="detail-value">
                        {{ $suggestion }}
                    </div>
                </div>
                @endif

                <!-- 
                Add Ticket Tracking Information
                <div class="detail-item">
                    <span class="detail-label">Ticket de Seguimiento</span>
                    <div class="detail-value" style="color:rgb(55, 107, 25);">TICKET-12345 (En progreso)</div>
                </div> -->
        </div>
        <div class="footer">
            <p>© 2025 Silent4Business • Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>