<?php
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Session;
$mail = Session::get('usuario')['email'];

?>
    <!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> --}}

    {{-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

</head>
<body style="background-color: #F8F9FA">
<br>
<br>

<article class="card-body mx-auto" style="max-width: 1000px; max-height: 1500px; ">
    <h4 class="card-title mt-3 text-center">Detalles del Producto</h4>
    <hr>
    <div class="card" style="box-shadow:0px 0px 30px #ccc; border-radius: 10px max-width:950px; border: none; margin: 0 auto;  max-height:950px">
        <div class="row">
            <div class="col-sm-6">
                <img class="card-img" src="{{asset($producto->rutaImagen)}}" alt="Card image" style="border-right:3px solid black"/>
            </div>
            <div class="col-sm-6">
                <div class="card-body-right">
                    <h4 class="card-title" style="padding-top:5%"><b>{{$producto->nombre}}</b></h4>
                    <p class="card-text" style="padding-right:7%; text-align: justify">{{$producto->descripcion}}</p>
                    <br>
                    <b>CODIGO: {{$producto->codigo}}</b>
                    <br>
                    <br>
                    <b>PRECIO: ${{$producto->precio}}</b>
                    <br>
                    <br>
                    <b>STOCK: {{$producto->cantidadDisponible}} Unidades</b>
                    <?php if($mail == "administradores@tutienda.com") {
                        ?><br><br><b>EMAIL DEL VENDEDOR: {{$producto->emailVendedor}}</b>
                <?php ;
                    }?>
                </div>
            </div>
            <div class="container text-center mt-4 mb-3">
                <hr style="width: 76%; margin-left: 12%; margin-bottom: 6%;">
                <div class="row align-items-center justify-content-around mt-5">
                    <div class="col-sm-auto">
                        <a href="{{ url('/paneldecontrol') }}" class="btn btn-primary">Ir a Panel de Control</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
</body>
</html>
