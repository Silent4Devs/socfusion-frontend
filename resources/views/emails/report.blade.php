<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reporte de actividad sospechosa</title>
</head>

<style>
    :root {
        --headersbgc: #1b4669;
        --headerstc: white;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid;
        border-color: gray;
    }
    th, td {
        padding: 10px
    }
    table tr td:first-child {
        background-color: var(--headersbgc);
        color: var(--headerstc);
        max-width: 5rem;
    }
    img {
        max-width: 100%;
    }
</style>
@php
    $en_es = [
        'High' => 'Alta',
        'Low' => 'Baja'
    ];
    $logrhythmCols = ['Application',
                    'Object',
                    'URL',
                    'Subject',
                    'Process Name',
                    'Amount',
                    'Known Application',
                    'Classification',
                    'Common Event',
                    'MPE Rule Name',
                    'Host (Origin)',
                    'Host (Impacted)',
                    'Hostname (Origin)',
                    'Hostname (Impacted)',
                    'Known Host (Origin)',
                    'Location (Origin)',
                    'Location (Impacted)',
                    'Region (Impacted)',
                    'Log Source',
                    'TCP/UDP Port (Origin)',
                    'Log Date',
                    'TCP/UDP Port (Impacted)'
];
@endphp
<body>
    <p>Estimado cliente,</p>
    <p>Mediante el monitoreo continuo, se detecta la siguiente actividad sospechosa:</p>

    @if ($report['alarm_type'] == 'logrhythm')
        <table>
            <tr>
                <th><img style="max-width:5rem" src="{{asset('images\logo.png')}}" alt="logo"></th>
                <th style="background-color: var(--headersbgc); color: var(--headerstc);">REPORTE DE ACTIVIDAD SOSPECHOSA</th>
            </tr>

            <tr>
                <td>Herramienta de detección:</td>
                <td>SIEM — LOGRHYTHM</td>
            </tr>
            <tr>
                <td>Evento/Firma:</td>
                <td>{{$alarm['alarm_rule_name']}}</td>
            </tr>
            <tr>
                <td>Severidad:</td>
                <td>{{$en_es[$alarm['model_classification']]}}</td>
            </tr>

            @if ($csvData != [])
                @foreach ($logrhythmCols as $col)
                    <tr>
                        @switch($col)
                            @case('Amount')
                                <td>Número de eventos:</td>
                                <td>
                                    {{array_sum($csvData['Amount'])}}
                                </td>
                                @break
                            @case('Log Date')
                                <td>Fecha:</td>
                                <td>
                                    @foreach ($csvData[$col] as $data)
                                        {{$data}}<br>
                                    @endforeach
                                </td>
                                @break
                            @default
                                @if ($csvData[$col][0]!='' || $csvData[$col][0]!=null)
                                    <td>{{$col}}:</td>
                                    <td>
                                        @foreach ($csvData[$col] as $data)
                                            {{$data}}<br>
                                        @endforeach
                                    </td>
                                @endif
                        @endswitch
                    </tr>
                @endforeach
            @endif
            
            <tr>
                <td>Descripción:</td>
                <td>{{$details['description']}}</td>
            </tr>
            <tr>
                <td>Recomendación:</td>
                <td>{{$details['recomendations']}}</td>
            </tr>
            <tr>
                <td>Ticket de seguimiento:</td>
                <td>{{$details['ticket_de_seguimiento']}}</td>
            </tr>
        </table>
    @endif

    @if ($details['log_time'])
        <p><b>Hora en la que se detecta el log:</b></p>
        <img src="{{asset('storage/'.$details['log_time'])}}" alt="hora del log">
    @endif

    @if ($details['tool_info'])
        <p><b>Información proporcionada por la herramienta:</b></p>
        @foreach ($details['tool_info'] as $info)
            <img src="{{asset('storage/'.$info)}}" alt="información de la herramienta"><br>
        @endforeach
    @endif

    @if (!empty($ips) || !empty($urls))
        <p><b>Análisis de reputación</b></p>
        <ul id='ips'>
            <li style="list-style: none"><b>IP:</b></li>
            @foreach ($ips as $ip => $value)
                <li>{{$ip}} — {{$value['regional_internet_registry']!=null ? $value['regional_internet_registry'] : 'HOST DESCONOCIDO'}} — {{$value['country']!=null ? $value['country'] : 'PAÍS DESCONOCIDO'}} — {{$value['last_analysis_stats']['malicious']==0 ? 'NO MALICIOSA' : 'MALICIOSA'}}</li>
            @endforeach
        </ul>

        <ul>
            <li style="list-style: none"><b>URL:</b></li>
            @foreach ($urls as $url => $value)
                <li>{{$url}} — - — - — {{!array_key_exists('malicious', $value['results']) ? 'NO MALICIOSA' : 'MALICIOSA'}}</li>
            @endforeach
        </ul>
    @endif

    <p><b>{{$details['actions_taken']}}</b></p>

    @if ($details['orden_de_trabajo'])
        <p><b>Buenos días estimado cliente,</b><br>
        En seguimiento al número de ticket: <b>{{$details['orden_de_trabajo']}}</b></p>
    @endif

    @if($details['stored_evidence'] != [])
        <p>Se adjunta evidencia del proceso realizado:</p>
        @foreach ($details['stored_evidence'] as $evidence_path)
            <img src="{{asset("storage/".$evidence_path)}}" alt="evidencia"><br><br>
        @endforeach
    @endif

    @if ($details['notes'])
        <p><b>{{$details['notes']}}</b></p>
    @endif

    <p>Quedo atento a sus comentarios,<br>Saludos</p>
</body>
</html>
