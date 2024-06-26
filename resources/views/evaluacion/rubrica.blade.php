<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>Detalle de Rúbrica - NRC {{$nrc}}</title>
    <style>
        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            width: 100%;
            height: 100vh;
            background-size: cover;

        }
        .tblBody{
            border-top: 1px solid #242424;
            border-left: 1px solid #242424
        }
        .tblBody td{
            font-size: 12px;
            padding: 7px;
            border-bottom: 1px solid #242424;
            border-right: 1px solid #242424
        }
        .tblBody th{
            font-size: 11px;
            padding: 7px;
            border-bottom: 1px solid #242424;
            border-right: 1px solid #242424;
            background-color: #909090;
            color: #ffffff
        }
        .tdBorderB{
            border-bottom: 1px solid #9c9c9c !important;
        }


        .head2 {
            font-size: 11px;
            font-weight: bold;
            padding: 7px;
            text-align: center;
            border-bottom: 1px solid #242424;
            border-right: 1px solid #242424;
            background-color: #44c7ff;
            color: #ffffff
        }

        .tit1 {
            font-weight: bold;
            color: #00aeff ;
            font-size: 15px;

        }



        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <table width="100%" cellpading=0 cellspacing=0 border=0>
        <tr>
           <td colspan="6" style="padding-bottom:8px" >DETALLE DE RÚBRICA</td>
        </tr>
        <tr>
            <td width="35%"><strong>NOMBRE DE CURSO</strong></td>
            <td width="5%"><strong>NRC</strong></td>
            <td ><strong>COD. CURSO</strong></td>
            <td ><strong>HORARIOS</strong></td>
            <td ><strong>MODALIDAD</strong></td>
            <td ><strong>PERIODO</strong></td>
        </tr>
        <tr>
           
            <td >{{$nomcurso}}</td>
            <td >{{$nrc}}</td>
            <td >{{$curso}}</td>
            <td >{{$horario}}</td>
            <td >{{$sede}}</td>
            <td >{{$periodo}}</td>
        </tr>
    </table>
    <br>
    <table width="100%" cellpading=0 cellspacing=0 border=0 class="tblBody">
        <tr>
            <th>CRITERIOS</th>
            <th>EXCELENTE</th>
            <th>BUENO</th>
            <th>EN PROCESO</th>
            <th>DEFICIENTE</th>
            <th>INSATISFACTORIO</th>
        </tr>
        @php
            $cont = 0;
        @endphp
        @foreach ($data as $dato)
            @if ($cont == 0)
                <tr>
                    <td class="head2">
                        {{$dato['NOM_CRITERIO']}}
                    </td>
                    <td class="head2"> {{$dato['PUN_EXC']}} </td>
                    <td class="head2"> {{$dato['PUN_BUE']}}</td>
                    <td class="head2"> {{$dato['PUN_ENPR']}}</td>
                    <td class="head2"> {{$dato['PUN_DEFI']}}</td>
                    <td class="head2"> {{$dato['PUN_INSAT']}}</td>
                </tr>
            @else
                <tr>
                    <td>
                        {{$dato['CRITERIO']}}:
                        <br><br>
                        <span class="tit1">{{$dato['NOM_CRITERIO']}}</span>
                    </td>
                    <td> {{$dato['DESC_EXC']}} <br> <p style="text-align: center; font-weight:bold">{{$dato['PUN_EXC']}}</p> </td>
                    <td> {{$dato['DESC_BUE']}} <br> <p style="text-align: center; font-weight:bold">{{$dato['PUN_BUE']}}</p></td>
                    <td> {{$dato['DESC_ENPR']}} <br> <p style="text-align: center; font-weight:bold">{{$dato['PUN_ENPR']}}</p></td>
                    <td> {{$dato['DESC_DEFI']}} <br> <p style="text-align: center; font-weight:bold">{{$dato['PUN_DEFI']}}</p></td>
                    <td> {{$dato['DESC_INSAT']}} <br> <p style="text-align: center; font-weight:bold">{{$dato['PUN_INSAT']}}</p></td>
                </tr>
            @endif

            @php
                $cont++;
            @endphp
        @endforeach
    </table>
</body>
</html>