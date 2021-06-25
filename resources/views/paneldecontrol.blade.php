<?php
use Illuminate\Support\Facades\Session;
$nombre = Session::get('usuario')['nombre'];
$mail = Session::get('usuario')['email'];
?>
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
@extends('layouts.layoutAdmin')
@section('sectionCliente')
    <div style="background: #FFFFFF; height: 100%; margin: 0;">
        <br>
        <br>
        <br>

        <div class="container" style="margin-top: 0%;" style="text-align: center;">
            <div class="card bg-light mb-auto" style="box-shadow:0px 0px 15px #777777;">
                <article class="card-body mx-auto" style="max-width: 1000px; display: inline; text-align: center">
                    <h1>Panel de Control</h1>

                    <div style="text-align: center">
                        <hr style=" width:75%; margin:0 auto;">
                        <a class="navbar-brand" href="{{ url('/verprodadmin') }}" target="miiframe" style=" font-size: large;">Productos</a>
                        <a class="navbar-brand" href="{{ url('/verpedidosadmin') }}" target="miiframe" style=" font-size: large;">Pedidos</a>
                        <a class="navbar-brand" href="{{ url('/verprod') }}" target="miiframe" style=" font-size: large;">Envios</a>
                    </div>
                </article>
                <iframe name="miiframe" frameborder="0" style="
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100%;
    width: 100%;">
                </iframe>
            </div> <!-- card.// -->
<br>
        </div>


        <div style="text-align: center; background: #FFFFFF;">
            <br>
            <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
        </div>
    </div>
@endsection
