
@extends('master')
@section('content')
<!DOCTYPE html>
<html>

<head> </head>

<body style="background-color:#ffa500;">
<div class="container" style="margin-top: 2%;" style="text-align: center;">
    <div class="card bg-light" style="max-width: 500px;margin: 0 auto; float: none;">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;">
                <a class="navbar-brand" href="{{url(" / ") }}" style=" color: white; margin-top: 2%; margin-left: 6%; border:1px solid black; padding: 7px"><img alt="Qries" height="64" src="./img/logo.png" width="64"></a> <b style="font-size: large">TuTienda</b> </div>
            <br>
            <div class="alert alert-danger" role="alert" style="text-align: center; width: 90%; margin: 0 auto;"> Codigo incorrecto, intente nuevamente. </div>
            <h4 class="card-title mt-3 text-center">Confirma tu Cuenta</h4>
            <h6 class="card-title mt-3 text-center"><b>Te hemos enviado un codigo a tu email, ingresalo aqui para confirmar tu cuenta.</b></h6>
            <form id="formid" onsubmit="return formSubmit(this);" action="confirmar" method="POST"> @csrf
                <div class="form-group input-group">
                    <input name="codigo" class="form-control" placeholder="Ingresa el codigo aqui" type="text" style="text-align: center"> </div>
                <div class="form-group">
                    <button id="btnFetch" type="submit" class="btn btn-primary btn-block"> Confirmar Usuario </button>
                </div>
                <!-- form-group// -->
            </form>
        </article>
    </div>
    <!-- card.// -->
</div>
<div style="text-align: center;">
    <br> <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small> </div>
<!--container end.//-->
</body>

</html>
