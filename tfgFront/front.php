<!-- Código PHP para highcharts -->

<?php

    require('conexion.php');

    $sql = "SELECT temperatura FROM sensores ORDER BY 'fecha' LIMIT 12";

    $temperatura = mysqli_query($conexion,$sql);
    $temperatura = mysqli_fetch_all($temperatura,MYSQLI_ASSOC);
    $temperatura = json_encode(array_column($temperatura, 'temperatura'),JSON_NUMERIC_CHECK);

    $sql = "SELECT humedad FROM sensores ORDER BY 'fecha' LIMIT 12";

    $humedad = mysqli_query($conexion,$sql);
    $humedad = mysqli_fetch_all($humedad,MYSQLI_ASSOC);
    $humedad = json_encode(array_column($humedad, 'humedad'),JSON_NUMERIC_CHECK);

    $sql5 = "SELECT fecha FROM sensores ORDER BY 'fecha' LIMIT 12";

    $fecha = mysqli_query($conexion,$sql);
    $fecha = mysqli_fetch_all($fecha,MYSQLI_ASSOC);
    $fecha = json_encode(array_column($fecha, 'fecha'),JSON_NUMERIC_CHECK);

    $sql2 = "SELECT temperatura FROM sensores where fecha=(select max(fecha) from sensores)";

    $temp = mysqli_query($conexion,$sql2);
    $temparr = mysqli_fetch_array($temp);

    $sql3 = "SELECT humedad FROM sensores where fecha=(select max(fecha) from sensores)";

    $hum = mysqli_query($conexion,$sql3);
    $humarr = mysqli_fetch_array($hum);

    $sql4 = "SELECT alerta FROM sensores where fecha=(select max(fecha) from sensores)";

    $alert = mysqli_query($conexion,$sql4);
    $alertarr = mysqli_fetch_array($alert);

    $sql6 = "SELECT dispositivo FROM sensores where fecha=(select max(fecha) from sensores)";

    $disp = mysqli_query($conexion,$sql4);
    $disparr = mysqli_fetch_array($disp);

    if ($alertarr[0] > "1") {
            $estado = "Si";
    } else {
            $estado = "No";
    }

?>

    <!DOCTYPE html>

    <html>

    <head>

        <title>Dashboard</title>

        <link rel="stylesheet" href="recursos/bootstrap.min.css">
        <script type="text/javascript" src="recursos/jquery.js"></script>
        <script type="text/javascript" src="recursos/highcharts.js"></script>

        <!-- CSS del Core -->
        <link href="Jumbotron%20Template%20for%20Bootstrap_files/bootstrap.css" rel="stylesheet">
        <link href="Jumbotron%20Template%20for%20Bootstrap_files/jumbotron.css" rel="stylesheet">

    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/tfgFront/about.html">Acerca de</a>

                </div>
                <div class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right">
                        <a href="/tfgFront/index.php" onclick="return confirm('¿Confirma que desea salir?')" class="btn btn-success">Deconectar &raquo; </a>
                    </form>
                </div>
                <!--/.navbar-collapse -->
            </div>
        </div>

        <div class="container">

            <br/>
            <br/>
            <h2 class="text-center">Monitor de sistema anti heladas</h2>

            <div class="row">

                <div class="col-md-12">

                    <div class="panel panel-default">

                        <div class="panel-heading">Panel de Control</div>

                        <div class="panel-body">

                            <div id="container"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="container">

            <div class="row">
                <div class="col-md-4">
                    <h2>Valores Actuales</h2>
                    <br/>
                    <h4>Temperatura :   <?php echo $temparr[0]; ?></h4>
                    <h4>Humedad rel :   <?php echo $humarr[0]; ?></h4>
                    <br/>
                    <p><a class="btn btn-default" href="front.php" role="button">Actualizar &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2>Alarmas</h2>
                    <br/>
                    <h4>En estado de Alarma : <?php echo $estado; ?> hay alarmas</h4>
                    <h4>SMS enviado : <?php echo $estado; ?> hay mensaje enviado</h4>

                </div>
                <div class="col-md-4">
                    <h2>Dispositivo</h2>
                    <br/>
                    <h4>Dispositivo : Se muestra el dispositivo <?php echo $disparr[0]; ?></h4>
                </div>
            </div>

            <hr>

            <footer>
                <p>&copy; 2018 M.Callejón para UNIR</p>
            </footer>
        </div>

        <!-- Código JS para highcharts -->

        <script type="text/javascript">
            $(function() {

                var data_temp = <?php echo $temperatura; ?>;
                var data_hum = <?php echo $humedad; ?>;
                var data_fecha = <?php echo $fecha; ?>;

                $('#container').highcharts({

                    chart: {
                        type: 'column'
                    },

                    credits: {
                        enabled: false
                    },

                    title: {
                        text: 'Estado actual'
                    },

                    xAxis: {

                        data: [{
                            x: data_fecha
                        }]
                    },

                    yAxis: {
                        title: {
                            text: 'valores ºC / %'
                        }

                    },

                    series: [{

                        name: 'Temperatura',
                        data: data_temp

                    }, {

                        name: 'Humedad',
                        data: data_hum

                    }]

                });
            });
        </script>
    </body>

    </html>
