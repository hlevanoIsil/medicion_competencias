<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>Reporte Final - Medición de Competencias</title>
    <style>
        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            width: 100%;
            height: 100vh;
            background-size: cover;

        }

        td{
            font-size: 12px;
            padding-top: 5px;
            padding-bottom: 7px;
        }
        tdHead{
            font-size: 12px;
            padding-top: 3px;
            padding-bottom: 3px;
        }
        .tdBorderB{
            border-bottom: 1px solid #9c9c9c !important;
        }
        .logo {
            width: 180px;
        }

        .txtCenter {
            text-align: center !important;
        }

        .titulo {
            font-size: 18px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 19px;
            font-weight: bold;
            color: #00aeff ;
            font-size: 12px;

        }

        .cajaBgAzul {
            background-color: #e8f0fe;
            border-radius: 8px;
            padding: 8px;
        }

        .cajaCalif {
            background-color: #00aeef;
            font-weight: bold;
            color: #FFFFFF;
            font-size: 15px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 6px;
            padding-bottom: 6px;
            border-radius: 15px;
        }
        .parrafos {
            font-size: 13px;
        }

        .parrafos2 {
            font-size: 11px;
        }

        .subr {
            text-decoration: underline;
        }

        .tdLinea1 {
            border-bottom: 1px solid #000;
        }

        .tdLinea2 {
            border-bottom: 2px solid #000;
        }

        .tdPaddingTop {
            padding-top: 20px;
        }

        .tdPaddingTop2 {
            padding-top: 16px;
        }

        .cuadrado {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            float: left;
            margin-right: 5px;
            padding: 0px !important;
            font-weight: bold;
            text-transform: uppercase;

        }

        ol li {
            margin-top: 6px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        $num = 0;
    @endphp
    @foreach ($datos as $dato)
        @php
            $num++;
        @endphp
        <table width="100%" cellpading=0 cellspacing=0 border=0>
            <tr>
                <td class="titulo"><strong>MEDICIÓN DE COMPETENCIAS</strong></td>
                <td align="right">
                    <img class="logo" src="images/logo_isil.png" alt="">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="txtCenter">
                    <br>
                    <span><strong>REPORTE FINAL</strong></span><br>
                    <br>
                </td>
            </tr>
        </table>

        <table width="100%" cellpading=0 cellspacing=0 border=0>
            <tr>
                <td class="tdHead"><strong>NOMBRE DEL CURSO</strong></td>
                <td class="tdHead">
                     <strong>NRC</strong>
                </td>
                <td class="tdHead">
                     <strong>HORARIOS</strong>
                </td>
                <td class="tdHead">
                     <strong>MODALIDAD</strong>
                </td>
                <td class="tdHead">
                    <strong>PERIODO</strong>
               </td>
            </tr>
            <tr>
                <td class="tdHead">{{$curso}}</td>
                <td class="tdHead">{{$nrc}}</td>
                <td class="tdHead">{{$horarios}}</td>
                <td class="tdHead">{{$modalidad}}</td>
                <td class="tdHead">{{$periodo}}</td>
            </tr>
            <tr>
                <td class="tdHead" colspan="4"><strong>DOCENTE:</strong> <span class="subtitulo">{{$dato['DOCENTE']}}</span></td>
            </tr>
            @php
                $numJ = 0;
            @endphp
            @foreach ($jurados as $jurado)
            @php
                $numJ++;
            @endphp
            
                <tr>
                    <td class="tdHead" colspan="4"><strong>JURADO {{$jurado['TIPO']}} (jurado {{$numJ}}):</strong> <span class="subtitulo">{{$jurado['JURADO_NOMBRES']}}</span></td>
                </tr>
            @endforeach
        </table>
        <table cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td width="8%" >
                    Alumno:
                </td>
                <td width="45%" class="cajaBgAzul">
                    {{$dato['NOMBRES']}}
                </td>
                <td style="padding-left: 8px">
                    <strong>Grupo {{$dato['GRUPO']}}</strong>
                </td>
                <td align="right" >
                    Calificación <span class="cajaCalif">{{ceil($dato['NOTA_FINAL'])}}</span> 
                </td>
            </tr>
        </table>
        <br>
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo tdBorderB" align="center" >
                    Criterio
                </td>
                <td class="subtitulo tdBorderB" align="center" width="9%">
                    Puntaje<br>Docente
                </td>

                @if ($dato['flgJurado1'])
                    <td class="subtitulo tdBorderB" align="center" width="9%">
                        Puntaje<br>Jurado 1
                    </td>
                @endif

                @if ($dato['flgJurado2'])
                    <td class="subtitulo tdBorderB" align="center" width="9%">
                        Puntaje<br>Jurado 2
                    </td>
                @endif

                @if ($dato['flgJurado1'] || $dato['flgJurado2'])
                    <td class="subtitulo tdBorderB" align="center" width="9%">
                        Puntaje<br>Promedio
                    </td>
                @endif
                <td class="subtitulo tdBorderB" align="center" >
                    Descripción
                </td>
            </tr>
            @foreach ($dato['NOTAS'] as $nota)
                <tr>
                    <td class="tdBorderB">
                        {{$nota['NUM_CRITERIO']}} @if ($nota['NOM_JURADO'] != '') <br> <i> <small>(Jurado: {{$nota['NOM_JURADO']}})</small></i> @endif<br>
                        <br>
                        <strong>{{$nota['NOM_CRITERIO']}}</strong>
                    </td>

                    <td class="tdBorderB txtCenter" style="padding-left: 3px; padding-right: 3px;">                        
                        {{$nota['NOTA_DOCENTE']}}
                    </td>
                    @if ($dato['flgJurado1'])
                        <td class="tdBorderB txtCenter" style="padding-left: 3px; padding-right: 3px;">                        
                            {{$nota['NOTA_JURADO1']}}
                        </td>
                    @endif

                    @if ($dato['flgJurado2'])
                        <td class="tdBorderB txtCenter" style="padding-left: 3px; padding-right: 3px;">                        
                            {{$nota['NOTA_JURADO2']}}
                        </td>
                    @endif
                    @if ($dato['flgJurado1'] || $dato['flgJurado2'])
                        <td class="tdBorderB txtCenter" style="padding-left: 3px; padding-right: 3px;">                        
                            {{$nota['NOTA']}} @if ($nota['NOTA'] != "NP")<br>puntos @endif
                        </td>
                    @endif
                    <td class="tdBorderB" style="padding-left: 10px">
                        {{$nota['DESCR_NOTA']}}</td>
                </tr>
            @endforeach
        </table>
        @if ($dato['COMENTARIOS'] != '')
        <br>
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo">
                    Comentario Individual de Docente:
                </td>
            </tr>
            <tr>
                <td class="cajaBgAzul">
                    {!! nl2br($dato['COMENTARIOS']) !!}
                </td>
            </tr>
        </table> 
        @endif
        @if ($dato['COMENTARIO_GRUPAL'] != '')
        <br>
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo">
                    Comentario Grupal de Docente:
                </td>
            </tr>
            <tr>
                <td class="cajaBgAzul">
                    {!! nl2br($dato['COMENTARIO_GRUPAL']) !!}
                </td>
            </tr>
        </table> 
        @endif





        @if ($dato['COMENTARIOS_JURADO'] != '')
        <br>
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo">
                    Comentario Individual de Jurado:
                </td>
            </tr>
            <tr>
                <td class="cajaBgAzul">
                    {!! nl2br($dato['COMENTARIOS_JURADO']) !!}
                </td>
            </tr>
        </table> 
        @endif
        @if ($dato['COMENTARIO_JURADO_GRUPAL'] != '')
        <br>
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo">
                    Comentario Grupal de Jurado:
                </td>
            </tr>
            <tr>
                <td class="cajaBgAzul">
                    {!! nl2br($dato['COMENTARIO_JURADO_GRUPAL']) !!}
                </td>
            </tr>
        </table> 
        @endif
        

        @if ($num<count($datos))
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
