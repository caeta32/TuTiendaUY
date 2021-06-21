<!DOCTYPE html>
<html style="height: 100%;">

<head>
    <title>TuTienda</title>
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var framefenster = document.getElementsByTagName("iframe");
        var auto_resize_timer = window.setInterval("autoresize_frames()", 400);
        function autoresize_frames() {
            for (var i = 0; i < framefenster.length; ++i) {
                if(framefenster[i].contentWindow.document.body){
                    var framefenster_size = framefenster[i].contentWindow.document.body.offsetHeight;
                    if(document.all && !window.opera) {
                        framefenster_size = framefenster[i].contentWindow.document.body.scrollHeight;
                    }
                    framefenster[i].style.height = framefenster_size + 'px';
                }
            }
        }
    </script>
</head>

<body style="height: 100%; margin: 0; ">
<div style="text-align: center; background-color: #ffa500; width: 100%; position: fixed;">
    <a class="navbar-brand" href="#" style=" color: white; margin-left: 0%;"><img alt="Qries" src="img/logo.png" width="58" height="48" style="border-right: 1px solid black; padding-right: 10px; margin-left: 4%"></a>
    <a class="navbar-brand" style="margin-left: -0.5%; font-size: medium; font-weight: bold; padding-right: 1%">TuTienda</a>
    <input type="text" placeholder="Busca el producto que necesitas..." style="width:45%;border: 1px solid transparent; border-radius: 3px; box-shadow: 0 0 0 2px #f90;    outline: none;    padding: 0.1em; padding-left: 0.5% ;
        text-align: center; ">
    <a class="navbar-brand" href="{{ url('/login') }}" style=" color: white; font-size: medium; margin-left: 2%;">Iniciar Sesion</a>
    <a class="navbar-brand" href="{{ url('/registro') }}" style=" color: white; font-size: medium;">Registrate</a>

</div>
<iframe src="{{ url('/productos') }}" frameborder="0" style="display: block; border: none; width: 100%; height: 100%; overflow: hidden" scrolling="no"></iframe>

</body>

</html>
