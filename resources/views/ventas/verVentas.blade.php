<!DOCTYPE html>
<html style="height: 100%; background-color: #F8F9FA">
<head>
    <title>TuTienda</title>
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/013a3a1db6.js" crossorigin="anonymous"></script>
    {{-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .noscrollbar::-webkit-scrollbar {
            display: none;
        }
    
        .noscrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    
</head>
<body style="background-color: #F8F9FA;">
    <div class="row justify-content-center mt-2">
        <div class="col-10 col-sm-8 col-md-8 col-lg-11 col-xl-15 mb-5">
            <div class="row justify-content-center mb-5 mx-5 mt-5">
                <div class="col">
                    {{-- ENVÍOS EN ESPERA --}}
                    @foreach(Arr::get($data, 'pedidosEnEspera') as $item)
                        <div class="row justify-content-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col-3 col-sm-3 col-md-3 col-lg-2 align-self-center mb-3">
                                        <img src="{{ asset($item->rutaImagen) }}" class="img-thumbnail" width="200" height="200" alt="imagen del producto" style="box-shadow:0px 0px 30px #ccc; border-radius: 10px;">
                                        </div>
                                    <div class="col-sm-9 col-md-9 col-lg-6">
                                        <p>
                                            <b>{{ $item->nombreProducto }}</b><br>
                                            <br>
                                            <b>Cantidad comprada: </b>{{ $item->cantidadPedida }}<br>
                                            <b>Sub Total: </b>$ {{ $item->cantidadPedida * $item->precio }}<br>
                                            <b>Compra Realizada: </b>{{$item->fechaPedido}}<br>
                                            <b>ID de Pedido: </b>{{$item->idPedido}}<br>
                                        </p>
                                    </div>
                                    <div class="col-lg-4 align-self-center mb-3">
                                        <div class="row justify-content-start border border-danger bg-light" style="box-shadow:0px 0px 30px rgb(255, 207, 207); border-radius: 8px;">
                                            <div class="col-sm-12">
                                                <p class="mt-3 mb-1 text-danger text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188);"><strong>Estado: En Espera.</strong></p>
                                                <p class="text-danger text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188); font-size: 0.9rem;"><strong>Desde </strong>{{$item->fechaEnvio}}</p>
                                                <p><strong>ID de Envío: </strong>{{$item->idEnvio}}</p>
                                                <p><strong>Nombre del Comprador: </strong>{{$item->nombreUsuario}}</p>
                                                <p><strong>Email del Comprador: </strong>{{$item->emailComprador}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row justify-content-center mb-1">
                                    <form action="" method="POST">
                                        @csrf
                                        <input type="hidden" value="" id="id" name="id">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-shipping-fast"></i>  Marcar como Despachado</button>
                                    </form>
                                </div> --}}
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    {{-- ENVÍOS DESPACHADOS --}}
                    @foreach(Arr::get($data, 'pedidosDespachados') as $item)
                        <div class="row justify-content-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col-3 col-sm-3 col-md-3 col-lg-2 align-self-center mb-3">
                                        <img src="{{ asset($item->rutaImagen) }}" class="img-thumbnail" width="200" height="200" alt="imagen del producto" style="box-shadow:0px 0px 30px #ccc; border-radius: 10px;">
                                        </div>
                                    <div class="col-sm-9 col-md-9 col-lg-6">
                                        <p>
                                            <b>{{ $item->nombreProducto }}</b><br>
                                            <br>
                                            <b>Cantidad comprada: </b>{{ $item->cantidadPedida }}<br>
                                            <b>Sub Total: </b>$ {{ $item->cantidadPedida * $item->precio }}<br>
                                            <b>Compra Realizada: </b>{{$item->fechaPedido}}<br>
                                            <b>ID de Pedido: </b>{{$item->idPedido}}<br>
                                        </p>
                                    </div>
                                    <div class="col-lg-4 align-self-center mb-3">
                                        <div class="row justify-content-start border border-success bg-light" style="box-shadow:0px 0px 30px rgb(205, 255, 188); border-radius: 8px;">
                                            <div class="col-sm-12">
                                                <p class="mt-3 mb-1 text-success text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188);"><strong>Estado: Despachado.</strong></p>
                                                <p class="text-success text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188); font-size: 0.9rem;"><strong>Desde </strong>{{$item->fechaEnvio}}</p>
                                                <p><strong>ID de Envío: </strong>{{$item->idEnvio}}</p>
                                                <p><strong>Nombre del Comprador: </strong>{{$item->nombreUsuario}}</p>
                                                <p><strong>Email del Comprador: </strong>{{$item->emailComprador}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row justify-content-center mb-1">
                                    <form action="" method="POST">
                                        @csrf
                                        <input type="hidden" value="" id="id" name="id">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-shipping-fast"></i>  Marcar como Despachado</button>
                                    </form>
                                </div> --}}
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>            
        </div>
    </div>
</body>