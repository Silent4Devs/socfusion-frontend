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
            size: A4;
            margin: 0.1in;
        }

        body {
            font-family: "Roboto", sans-serif;
            background-color: #ffffff;
            color: #333;
            line-height: 1.4;
            padding: 0px;
            margin: 0;
            font-size: 14px;
            width: 222mm;
            height: auto; 
            min-height: 320mm;
            box-sizing: border-box;
            display: flex; 
            flex-direction: column;
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
            margin-bottom: 10px;
            width: 100%;
            position: absolute;
            bottom: 0;
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
                            @isset($client)
                            <td style="padding: 0 10px;">
                                <div style="
                                    width: 70px;
                                    height: 70px;
                                    border-radius: 50%;
                                    border: 1px solid #e0e0e0;
                                    background-image: url('{{ public_path('storage/' . $client['logo']) }}');
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    display: block;
                                "></div>
                            </td>
                            @endisset
                                <td style="padding: 0;">
                                    <h1 style="margin: 0; font-size: 20px; color: #222222; white-space: nowrap;">
                                        {{ $client['name'] ?? ($client->name ?? 'Reporte de seguridad') ?? 'Reporte de seguridad' }}
                                    </h1>
                                    <p style="margin: 0; color: #666666; font-size: 14px;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
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
        @isset($client)
            <h2 class="report-title">Reporte de Incidente de Seguridad</h2>
            <div style="width: 80px; height: 3px; margin: 12px auto 0; border-radius: 3px; overflow: hidden; font-size: 0;">
                <div style="display: inline-block; width: 50%; height: 100%; background-color: #38a6cc;"></div>
                <div style="display: inline-block; width: 50%; height: 100%; background-color: #92c13b;"></div>
            </div>
        @endisset
        
        <div class="min-height: 200mm;;">
            <div style="width: 48%; box-sizing: border-box; display: inline-block; vertical-align: top; padding: 10px;">
                <div style="font-weight: bold; font-size: 16px; margin-top: 10px; border-bottom: 1px solid #ccc; margin-botom: 10px;">
                    Información Básica
                </div>

                <div style="margin-bottom: 10px; margin-top: 10px; ">
                    <span style="display: block; font-weight: bold; font-size: 13px;">Herramienta de Detección</span>
                    <div style="margin-left: 5px; font-size: 13px;">
                        @if ($alarmType === 'logrhythm')
                            LogRhythm SIEM
                        @else
                            PRTG
                        @endif
                    </div>
                </div>

                @isset($alarm['alarm_rule_name'])
                    @if (!empty($alarm['alarm_rule_name']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Alarma</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['alarm_rule_name'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['date_inserted'])
                    @if (!empty($alarm['date_inserted']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Fecha</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['date_inserted'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['log_source_type_name'])
                    @if (!empty($alarm['log_source_type_name']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Tipo de origen de log</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['log_source_type_name'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['event_count'])
                    @if (!empty($alarm['event_count']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Conteo de eventos</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['event_count'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['name_raw'])
                    @if (!empty($alarm['name_raw']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Nombre</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['name_raw'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['status'])
                    @if (!empty($alarm['status']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Estado</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['status'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['message_raw'])
                    @if (!empty($alarm['message_raw']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Mensaje</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['message_raw'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['datetime'])
                    @if (!empty($alarm['datetime']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Fecha y hora</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['datetime'] }}</div>
                        </div>
                    @endif
                @endisset
            </div>

            <div style="width: 48%; box-sizing: border-box; display: inline-block; vertical-align: top; padding: 10px;">
                 <div style="font-weight: bold; font-size: 16px; margin-top: 10px; border-bottom: 1px solid #ccc; margin-botom: 10px;">
                    Detalles técnicos
                </div>

                @isset($alarm['impacted_entity_name'])
                    @if (!empty($alarm['impacted_entity_name']))
                        <div style="margin-bottom: 10px; margin-top: 10px; ">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Entidad impactada</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['impacted_entity_name'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['impacted_ip'])
                    @if (!empty($alarm['impacted_ip']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">IP impactada</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['impacted_ip'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['impacted_host'])
                    @if (!empty($alarm['impacted_host']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Host impactado</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['impacted_host'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['priority'])
                    @if (!empty($alarm['priority']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Prioridad</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['priority'] }}</div>
                        </div>
                    @endif
                @endisset

                @isset($alarm['active'])
                    <div style="margin-bottom: 10px;">
                        <span style="display: block; font-weight: bold; font-size: 13px;">Activo</span>
                        <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['active'] ? 'Sí' : 'No' }}</div>
                    </div>
                @endisset

                @isset($alarm['sensor'])
                    @if (!empty($alarm['sensor']))
                        <div style="margin-bottom: 10px;">
                            <span style="display: block; font-weight: bold; font-size: 13px;">Sensor</span>
                            <div style="margin-left: 5px; font-size: 13px;">{{ $alarm['sensor'] }}</div>
                        </div>
                    @endif
                @endisset
            </div>

            @if (!empty($evidence))
                <div style="margin-top: 30px; text-align: center; vertical-align: top;">
                    <div style="
                        display: inline-block;
                        width: 100%;
                        max-width: 600px;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                    ">
                        <h3 style="font-size: 18px; margin-bottom: 15px; color: #444;"></h3>
                        <img src="{{ public_path($evidence) }}" alt="Evidencia" style="max-width: 100%; height: auto; border-radius: 6px;">
                    </div>
                </div>
            @endif
        </div>
        <div class="section-full" style="margin-top: 30px;">
                <div class="section-title">Acciones y Recomendaciones</div>
                @if(isset($suggestion) && !empty($suggestion))
                <div class="detail-item">
                    <span class="detail-label">Recomendaciones</span>
                    <div class="detail-value">
                        {{ $suggestion }}
                    </div>
                </div>
                @endif
                
                @if(isset($comments) && !empty($comments))
                    <div class="detail-item">
                        <span class="detail-label">Comentarios Adicionales</span>
                        <div class="detail-value">
                            {{ $comments }}
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