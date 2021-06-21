<?php
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Session;

$nombre = Session::get('usuario')['nombre'];
$apellido = Session::get('usuario')['apellido'];
$fecha = Session::get('usuario')['fecha'];
$mail = Session::get('usuario')['email'];
$tel = Session::get('usuario')['telefono'];
$dir = Session::get('usuario')['direccion'];
$postal = Session::get('usuario')['postal'];

$fechaformat = explode("-", $fecha);
$fechafinal = $fechaformat[2] . "/" . $fechaformat[1] . "/" . $fechaformat[0];
?>
    <!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

</head>
<body style="background-color: #F8F9FA">
<article class="card-body mx-auto" style="max-width: 1000px; margin-top: -3%;">
    <h4 class="card-title mt-3 text-center">Mis Datos</h4>
    <div class="form-group input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
        </div>
        <input name="nombre" class="form-control" value="<?php echo $nombre?>" type="text" readonly>
        <div class="input-group-prepend" style="margin-left: 5%;">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
        </div>
        <input name="apellido" class="form-control" value="<?php echo $apellido ?>" type="text" readonly>
    </div>
    <div class="form-group input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fas fa-calendar"></i> </span>
        </div>
        <input name="fecha" type="text" class="form-control" value="<?php echo $fechafinal ?>"  readonly>
    </div>
    <!-- form-group// -->
    <div class="form-group input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
        </div>
        <input name="email" class="form-control" value="<?php echo $mail ?>" type="text" readonly>
    </div>
    <div class="form-group input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-map"></i> </span>
        </div>
        <input name="direccion" class="form-control" value="<?php echo $dir ?>" type="text" readonly>
        <div class="input-group-prepend" style="margin-left: 5%;">
            <span class="input-group-text"> <i class="fab fa-product-hunt"></i></span>
        </div>
        <input name="postal" class="form-control" value="<?php echo $postal ?>" type="text" readonly>
    </div>
</article>
</body>
</html>
