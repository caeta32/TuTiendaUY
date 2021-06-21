<!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<body style="background-color:#ffa500;">

<div class="container" style="margin-top: 2%;">

    <div class="card bg-light">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;"><a class="navbar-brand" href="{{url("/") }}" style=" color: white; margin-top: 2%; margin-left: 6%; border:1px solid black; padding: 7px"><img
                        alt="Qries" height="64" src="./img/logo.png" width="64"></a>
                <b style="font-size: large">TuTienda</b>
            </div>
            <br>
            <div class="alert alert-danger" role="alert" style="text-align: center; width: 110%; margin-left: -4.45%;">
                Las contraseñas no coinciden, intente nuevamente.
            </div>
            <h4 class="card-title mt-3 text-center">Crea tu cuenta</h4>
            <form id="formid" onsubmit="return formSubmit(this);" action="registro" method="POST">
                @csrf
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="nombre" class="form-control" placeholder="Nombre" type="text" required>
                    <div class="input-group-prepend" style="margin-left: 5%;">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="apellido" class="form-control" placeholder="Apellido" type="text" required>
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-calendar"></i> </span>
                    </div>
                    <input name="fecha" type="text" class="form-control" placeholder="Fecha de Nacimiento" onfocus="(this.type='date')" required>
                </div>
                <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                    </div>
                    <input name="email" class="form-control" placeholder="Correo Electronico" type="email" required>
                </div>
                <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                    </div>
                    <input name="telefono" class="form-control" placeholder="Telefono" type="text" required>
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-map"></i> </span>
                    </div>
                    <input name="direccion" class="form-control" placeholder="Direccion" type="text" required>
                    <div class="input-group-prepend" style="margin-left: 5%;">
                        <span class="input-group-text"> <i class="fab fa-product-hunt"></i></span>
                    </div>
                    <input name="postal" class="form-control" placeholder="Codigo Postal" type="text" required>
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name = "pass" class="form-control" placeholder="Contraseña" minlength="8" type="password" required>
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name = "passconf" class="form-control" placeholder="Confirmar contraseña" minlength="8" type="password" required>
                </div> <!-- form-group// -->
                <div class="form-group">
                    <button  id="btnFetch" type="submit" class="btn btn-primary btn-block"> Registrate </button>
                </div> <!-- form-group// -->
                <p class="text-center">Ya tienes una cuenta? <a href="{{url('/login')}}">Ingresar</a> </p>
            </form>
        </article>
    </div> <!-- card.// -->

</div>
<div style="text-align: center;">
    <br>
    <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
</div>
<!--container end.//-->
</body>

</html>
