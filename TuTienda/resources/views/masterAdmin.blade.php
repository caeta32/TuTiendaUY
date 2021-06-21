<?php
use Illuminate\Support\Facades\Session;
$nombre = Session::get('usuario')['nombre'];
$mail = Session::get('usuario')['email'];
?>

<!DOCTYPE html>
<html style="height: 100%; background-color: #F8F9FA">
<head>
    <title>TuTienda</title>
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var framefenster = document.getElementsByTagName("iframe");
        var auto_resize_timer = window.setInterval("autoresize_frames()", 0);

        function autoresize_frames() {
            for (var i = 0; i < framefenster.length; ++i) {
                if (framefenster[i].contentWindow.document.body) {
                    var framefenster_size = framefenster[i].contentWindow.document.body.offsetHeight;
                    if (document.all && !window.opera) {
                        framefenster_size = framefenster[i].contentWindow.document.body.scrollHeight;
                    }
                    framefenster[i].style.height = framefenster_size + 'px';
                }
            }
        }
    </script>
</head>

<body style="margin: 0; background-color: #F8F9FA; height: 100%;">
<div style="width: 100%; position: fixed; z-index: 9999999;">
    <div style="text-align: center; background-color: #ffa500; width: 100%; border-bottom: solid 2px black">
        <a class="navbar-brand" href="{{ url('/administradores') }}" style=" color: white; margin-left: 0%;"><img alt="Qries" src="{{ asset('img/logo.png') }}" width="58" height="48" style="border-right: 1px solid black; padding-right: 10px; margin-left: 4%"></a>
        <a class="navbar-brand" style="margin-left: -0.5%; font-size: medium; font-weight: bold; padding-right: 1%">TuTienda</a>
        <input type="text" placeholder="Busca el producto que necesitas..." style="width:45%;border: 1px solid transparent; border-radius: 3px; box-shadow: 0 0 0 2px #f90;    outline: none;    padding: 0.1em; padding-left: 0.5% ;
            text-align: center; ">
        <a class="navbar-brand" href="{{ url('/datoscliente') }}" style=" color: white; font-size: medium; margin-left: 1%; "><i class="fa fa-user-circle-o fa-lg" aria-hidden="true" style="padding-right: 5%"></i><?php echo $nombre?></a>
        <a class="navbar-brand" href="{{ url('/paneldecontrol') }}" style=" color: white; font-size: medium;">Panel de Control</a>
        <a class="navbar-brand" href="{{ url('/logout') }}" style=" color: white; font-size: medium;">Salir</a>
    </div>
    <!-- Barra superior secundaria -->
</div>

@yield('sectionAdmin')

{{--
<div style="text-align: center; margin-bottom: 2%; border-top: 1px solid #8f8f8f;">
    <br>
    <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
</div>
--}}

</body>

</html>
