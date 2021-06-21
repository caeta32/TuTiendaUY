@extends('master')
    @section('content')
<!DOCTYPE html>
<html>

<head>

</head>

<body style="background-color:#ffa500;">

    <div class="container" style="margin-top: 2%;" style="text-align: center;">

        <div class="card bg-light" style="max-width: 500px;margin: 0 auto; float: none;">
            <article class="card-body mx-auto" style="max-width: 1000px;">
                <div style="text-align: center;"><a class="navbar-brand" href="{{url("/") }}" style=" color: white; margin-top: 2%; margin-left: 6%; border:1px solid black; padding: 7px"><img
                            alt="Qries" height="64" src="./img/logo.png" width="64"></a>
                    <b style="font-size: large">TuTienda</b>
                </div>

                <h4 class="card-title mt-3 text-center">Ingresa</h4>
                <form id="formid" onsubmit="return formSubmit(this);" action="login" method="POST">
                    @csrf
                    <div class="form-group input-group" >
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="email" class="form-control" placeholder="Email" type="text">
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="pass" class="form-control" placeholder="Contraseña" type="password">
                    </div>

                    <div class="form-group">
                        <button  id="btnFetch" type="submit" class="btn btn-primary btn-block"> Acceder </button>
                    </div> <!-- form-group// -->
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
