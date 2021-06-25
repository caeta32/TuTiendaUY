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
    <div class="row justify-content-center">
        <div class="col-11 col-sm-9 col-md-8 col-lg-10 col-xl-14 mb-5">
            <div class="row justify-content-center mb-5 mx-5 mt-4">
                <div class="col">
                    {{-- SE MUESTRAN TODOS LOS PEDIDOS --}}
                    @foreach(Arr::get($data, 'pedidos') as $pedido)
                        <div class="row justify-content-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col-4 col-sm-4 col-md-3 col-lg-2 align-self-center mb-3">
                                        {{-- Se obtiene la imagen del primer producto del pedido --}}
                                        <img src="{{ asset(Arr::get($pedido, 'rutaImagenPrimerProducto')) }}" 
                                            class="img-thumbnail" 
                                            width="200" 
                                            height="200" 
                                            alt="imagen del producto" 
                                            style="box-shadow:0px 0px 30px #ccc; border-radius: 10px;"
                                        >
                                        </div>
                                    <div class="col-8 col-sm-8 col-md-9 col-lg-6 align-self-start">
                                        <div>
                                            <p class="mb-2 "><b>ID de Pedido: </b>{{ Arr::get($pedido, 'idPedido') }}</p>
                                            <p class="mb-2"><b>Cantidad Total de Productos: </b>{{ Arr::get($pedido, 'cantidadTotal') }}</p>
                                            <b>Pedido Realizado: </b>{{Arr::get($pedido, 'fechaPedido')}}
                                        </div>
                                        <br>
                                    </div>
                                    @if (Arr::get($pedido, 'status') === 'En Espera')
                                        <div class="col-lg-4 align-self-center mb-3">
                                            <div class="row justify-content-start border border-danger bg-light" style="box-shadow:0px 0px 30px rgb(255, 207, 207); border-radius: 8px;">
                                                <div class="col-sm-12">
                                                    <p class="mt-3 mb-1 text-danger text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188);"><strong>Estado: En Espera.</strong></p>
                                                    <p class="text-danger text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188); font-size: 0.9rem;"><strong>Desde </strong>{{Arr::get($pedido, 'fechaEnvio')}}</p>
                                                    <p class="text-center text-muted"><strong>ID de Envío: </strong>{{Arr::get($pedido, 'idEnvio')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif (Arr::get($pedido, 'status') === 'Despachado')
                                        <div class="col-lg-4 align-self-center mb-3">
                                            <div class="row justify-content-start border border-success bg-light" style="box-shadow:0px 0px 30px rgb(205, 255, 188); border-radius: 8px;">
                                                <div class="col-sm-12">
                                                    <p class="mt-3 mb-1 text-success text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188);"><strong>Estado: Despachado.</strong></p>
                                                    <p class="text-success text-center" style="text-shadow:0px 0px 30px rgb(255, 188, 188); font-size: 0.9rem;"><strong>Desde </strong>{{Arr::get($pedido, 'fechaEnvio')}}</p>
                                                    <p class="text-center text-muted"><strong>ID de Envío: </strong>{{Arr::get($pedido, 'idEnvio')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                {{-- <div class="row justify-content-center mb-1">
                                    <form action="" method="POST">
                                        @csrf
                                        <input type="hidden" value="" id="id" name="id">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-shipping-fast"></i>  Marcar como Despachado</button>
                                    </form>
                                </div> --}}
                                <div class="row justify-content-start">
                                    <div class="col">
                                    <button class="btn btn-outline-dark shadow mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#id{{Arr::get($pedido, 'idPedido')}}" aria-expanded="false" aria-controls="products">
                                        <span><i class="fa fa-chevron-right"></i></span>
                                        <strong>Ver todos los productos</strong>
                                    </button>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col">
    {{-- Listado de productos que el usuario puede visualizar expandiendo --}}
{{-- ===================================================================================================== --}}
                                        <div class="collapse mt-3" id="id{{Arr::get($pedido, 'idPedido')}}">
                                            @php
                                                $productosDelPedidoActual = Arr::get(
                                                    Arr::get($data, 'productosDePedidos'), Arr::get($pedido, 'idPedido')
                                                );
                                            @endphp
                                            @foreach($productosDelPedidoActual as $producto)
                                                <div class="row">
                                                    <div class="col-3 col-sm-3 col-md-2 col-lg-2 align-self-center d-flex justify-content-center">
                                                        <img 
                                                            src="{{ asset(Arr::get($producto, 'rutaImagen')) }}"
                                                            class="img-thumbnail"
                                                            width="80"
                                                            height="80"
                                                            alt="imagen del producto"
                                                            style="box-shadow:0px 0px 30px #ccc; border-radius: 10px;"
                                                        >
                                                    </div>
                                                    <div class="col-9 col-sm-9 col-md-9 col-lg-4 align-self-center p-0">
                                                        <p>
                                                            <p class="mb-2"><b>{{ Arr::get($producto, 'nombreProducto') }}</b></p>
                                                            <b>Precio: </b>$ {{ Arr::get($producto, 'precio') }}<br>
                                                            <b>Cantidad: </b>{{Arr::get($producto, 'cantidadPedida')}}<br>
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-6 align-self-center">
                                                        <div class="row justify-content-end">
                                                            <div class="col-sm-12 col-lg-10 text-center border border-secondary rounded shadow bg-light">
                                                                <p class="mt-3 mb-3 text-center" ><strong>Información del Vendedor:</strong></p>
                                                                <div class="text-center"
                                                                style="color: rgb(89, 89, 89)">
                                                                    <p class="mb-2"><strong>Nombre: </strong>{{Arr::get($producto, 'nombreVendedor')}}</p>
                                                                    <p><strong>Email: </strong>{{Arr::get($producto, 'emailVendedor')}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-flex justify-content-between align-items-center border rounded mx-1 my-3 px-3 py-2 shadow">
                                                    <strong>Sub Total: </strong>
                                                    <span>$ {{ Arr::get($producto, 'subTotal') }}</span>
                                                </div>
                                            @endforeach
                                        </div> {{--Fin collapse--}}
{{-- ===================================================================================================== --}}
                                    </div>
                                </div>
                                <ul class="list-group mt-4">
                                    <li class="list-group-item d-flex justify-content-between align-items-center shadow">
                                        <strong>Precio Total:</strong><span>{{Arr::get($pedido, 'precioTotal')}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>            
        </div>
    </div>
</body>