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
<article class="card-body mx-auto" style="box-shadow:0px 0px 30px #ccc; border-radius: 10px; max-width: 1000px; max-height: 1500px;">
    <h4 class="card-title mt-3 text-center">Detalles del Producto</h4>
    <hr>
    <div class="card" style="max-width:950px; border: none; margin: 0 auto;  max-height:950px">
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
                        {{-- DISPARA MODAL DE CONFIRMACIÓN --}}
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmacionEliminar">Eliminar</button>
                    </div>
                    <div class="col-sm-auto">
                        <a href="#" class="btn btn-primary">Ver Publicación</a>
                    </div>
                    <div class="col-sm-auto">
                        <a href="{{ url('/editarprod') }}" class="btn btn-primary">Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</article>

{{-- ================================================================= --}}
{{-- MODAL --}}
<div class="modal fade" id="confirmacionEliminar" tabindex="-1" aria-labelledby="confirmacionEliminar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar el producto {{$producto->nombre}}.</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>¿Está seguro que desea eliminar este producto?</strong></p>
                <p class="text-muted">Presione Cancelar si no desea eliminarlo.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('productos.eliminarProd', $producto->codigo) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL --}}
{{-- ================================================================= --}}

</body>
</html>
