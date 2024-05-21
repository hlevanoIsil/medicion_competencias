<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>Enrollment Agreement</title>
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
    @foreach ($datos as $dato)
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
                    Calificación <span class="cajaCalif">{{$dato['NOTA_FINAL']}}</span> 
                </td>
            </tr>
        </table>
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo tdBorderB">
                    Criterio
                </td>
                <td class="subtitulo tdBorderB">
                    Puntaje obtenido
                </td>
                <td class="subtitulo tdBorderB">
                    Descripción
                </td>
            </tr>
            @foreach ($dato['NOTAS'] as $nota)
                <tr>
                    <td class="tdBorderB">
                        {{$nota['NUM_CRITERIO']}}<br>
                        <br>
                        <strong>{{$nota['NOM_CRITERIO']}}</strong>
                    </td>
                    <td class="tdBorderB txtCenter" style="padding-left: 3px; padding-right: 3px;">
                        {{$nota['NOTA']}} puntos
                    </td>
                    <td class="tdBorderB">
                        {{$nota['DESCR_NOTA']}}
                    </td>
                </tr>
            @endforeach
        </table>
        @if ($dato['COMENTARIOS'] != '')
        <table  cellspacing=0 cellpadding=0 width="100%">
            <tr>
                <td class="subtitulo">
                    Comentario:
                </td>
            </tr>
            <tr>
                <td class="cajaBgAzul">
                    {{$dato['COMENTARIOS']}}
                </td>
            </tr>
        </table> 
        @endif
        <br><br><br>
    @endforeach

</body>

</html>