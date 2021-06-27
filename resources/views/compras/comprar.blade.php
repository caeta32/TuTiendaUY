<?php use Illuminate\Support\Arr; ?>
@extends('layouts.masterCliente')

@section('sectionCliente')
    <style>
        .noscrollbar::-webkit-scrollbar {
            display: none;
        }

        .noscrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @php
        // Tendrá los datos de precio total, precio de envío, etc.
        $data = array();
        // Cada item del array será un array con id y cantidad de un producto.
        // Tendrá todos los productos en la compra.
        $products = array();
    @endphp

    <div class="noscrollbar" style="background: #EBEBEB; height: 100%; overflow: auto;">
        <br>
        <br>
        <br>
        <br>
        <div class="row justify-content-center mt-4">
            <div class="col-md-11">
                <div class="card bg-light" style="box-shadow:0px 0px 15px #777777;">
                    <article class="card-body px-5">

                        @if (isset($orderData))
    {{-- CASO EN QUE EL USUARIO INTENTE COMPRAR DESDE EL CARRITO CON ALGÚN PROBLEMA ==========================--}}
                            @if (session()->has('failedStockInCart') && $orderData['cartBool'] === 'true')
                                <div class="row justify-content-center my-4">
                                    <div class="col-lg-7">
                                        <div class="row justify-content-center">
                                            <img src="{{ asset('img/logo-emoji-pensando/thinking.png') }}" alt="emoji pensando" width="130" height="130">
                                        </div>
                                        <br>
                                        <div class="row justify-content-center">
                                            <h3>¡Vaya! Encontramos un problema.</h3>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-lg-8">
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center text-center mb-4">
                                            <h5>Parece que estás intentando hacer una compra desde tu carrito y hay algún inconveniente con uno o más productos de los que has agregado.</h5>
                                        </div>
                                        <div class="row justify-content-center text-center mt-4">
                                            <p><strong>¡No te preocupes! Vuelve a tu carrito para poder ver el problema, solucionarlo rápidamente y realizar tu compra.</strong></p>
                                        </div>
                                        <div class="row justify-content-center">
                                            <a href="{{route('cart.checkout')}}" class="btn btn-success">Volver a tu Carrito</a>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            @else
    {{-- FIN CASO EN QUE EL USUARIO INTENTE COMPRAR DESDE EL CARRITO CON ALGÚN PROBLEMA ==========================--}}

    {{-- Comienzo --}}
    {{-- ------------------------------------------------------------------------------------------------------- --}}
                                <div class="row justify-content-center align-items-center mt-3 mb-4" >
                                    <div class="col-lg-12 px-5">
                                        {{-- Título --}}
                                        <h1 class="mb-4" style="text-align: center">¡Ya estás a un click de comprar!</h1>
                                        <br>
                                        {{-- Contenido --}}
                                        <div class="row justify-content-center">
                                            {{-- Targeta con el resumen del pedido --}}
                                            <div class="col-lg-6">
                                                <div class="bg-white p-5 border rounded shadow ml-3">
                                                    <div class="row justify-content-center">
                                                        <img src="{{ asset('img/logo-compra/bienes.png') }}" alt="logo realizar una compra" width="100" height="100">
                                                    </div>
                                                    <div class="row justify-content-center mt-3">
                                                        <h3 class="font-weight-bold">Resumen del pedido</h3>
                                                    </div>
                                                    <div class="row justify-content-center mt-3 mb-2">
                                                        <h5>Cantidad de Productos: {{Arr::get($orderData, 'totalQuantity')}}</h5>
                                                    </div>
                                                    <div class="row justify-content-center my-2">
                                                        <div class="col-md-10">
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-start">
                                                        <button class="btn" style="background: rgba(0, 0, 0, 0); color:rgba(58, 58, 58, 0.774);" type="button" data-bs-toggle="collapse" data-bs-target="#products" aria-expanded="false" aria-controls="products">
                                                            <span><i class="fa fa-chevron-right"></i></span>
                                                            <strong>Ver todos los productos</strong>
                                                        </button>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-12">
                        {{-- Listado de productos que el usuario puede visualizar expandiendo --}}
        {{-- ===================================================================================================== --}}
                                                            <div class="collapse mt-3" id="products">
                                                                <div class="row justify-content-center">
                                                                    <div class="col-lg-12">
                                                                        @foreach(Arr::get($orderData, 'products') as $item)
                                                                            {{-- Se almacena el id y cantidad de cada producto Para ser usado en caso de que el usuario confirme su compra. --}}
                                                                            @php
                                                                                $products[] = json_encode(['id' => Arr::get($item, 'id'), 'quantity' => Arr::get($item, 'quantity')]);
                                                                            @endphp
                                                                            {{-- ---------------------------------------- --}}
                                                                            <div class="row justify-content-start align-items-center px-3">
                                                                                <div class="col-sm-2 col-md-2 col-lg-2">
                                                                                    <img src="{{ asset(Arr::get($item, 'pathImage')) }}" class="img-fluid rounded" width="80" height="80" alt="imagen del producto">
                                                                                </div>
                                                                                <div class="col-sm-9 col-md-9 col-lg-9">
                                                                                    <div>
                                                                                        <b>{{ Arr::get($item, 'name') }}</b><br>
                                                                                        <b>Precio: </b>$ {{ Arr::get($item, 'price') }}<br>
                                                                                        <b>Cantidad: </b>{{Arr::get($item, 'quantity')}}<br>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row d-flex justify-content-between align-items-center border rounded mx-1 my-3 px-3 py-2">
                                                                                <strong>Sub Total: </strong>
                                                                                <span>$ {{ Arr::get($item, 'priceSum') }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div> {{--Fin collapse--}}
        {{-- ===================================================================================================== --}}
                                                        </div>
                                                    </div>
                                                    <ul class="list-group mt-4">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                            <strong>Precio Total:</strong>
                                                            <span>
                                                                @php
                                                                    echo '$ ' ,intval(Arr::get($orderData, 'totalPrice')) + intval(Arr::get($orderData, 'shippingPrice'));
                                                                @endphp
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            {{-- Información de envío --}}
                                            <div class="col-lg-6 mt-4">
                                                <div class="row justify-content-center mt-3">
                                                    <h3 class="font-weight-bold">Información de Envío</h3>
                                                </div>
                                                <div class="row justify-content-center my-2">
                                                    <div class="col-md-9">
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center align-items-center mt-3 text-center">
                                                    <div class="col-md-11">La dirección a la que enviaremos tu pedido será la que ingresaste en tu cuenta :</div>
                                                </div>
                                                <div class="row justify-content-center align-items-center">
                                                    <div class="col-md-10 mt-4">
                                                        <div class="row align-items-center p-2 bg-white border rounded">
                                                            <div class="col-md-1 mr-4 ml-2">
                                                                <img src="{{ asset('img/logo-ubicacion/globo.png') }}" alt="logo informacion de envio" width="60" height="60">
                                                            </div>
                                                            <div class="col-md-9 ml-3 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-11">
                                                                        <h6 class="font-weight-bold">Ubicación :</h6>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-11">
                                                                        <p>{{Arr::get($orderData, 'userLocation')}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center align-items-center mt-5">
                                                    <div class="col-md-11 text-center text-muted">En caso de que necesitemos ponernos en contacto contigo, lo haremos a través de los datos de contacto que ingresaste en tu cuenta.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-9 mt-3">
                                        <hr>
                                    </div>
                                </div>
                                <div class="row justify-content-around align-items-center mb-4">
                                    <div class="col-lg-4 mt-4">
                                        <button class="btn btn-secondary btn-lg btn-block">Cancelar compra</button>
                                    </div>
                                    <div class="col-lg-4 mt-4">
                                        <form action="{{ route('comprar') }}" method="post">
                                            @csrf
                                            {{-- La información del pedido se formatea antes para ser enviada al backend --}}
                                            @php
                                                $data['totalPrice'] = $orderData['totalPrice'];
                                                $data['shippingPrice'] = $orderData['shippingPrice'];
                                                $data['totalQuantity'] = $orderData['totalQuantity'];
                                                $data['cartBool'] = $orderData['cartBool'];

                                                $dataToSend = json_encode($data);
                                                $productsToSend = implode("|", $products);
                                            @endphp
                                            <input type="hidden" name="data" id="data" value="{{$dataToSend}}"/>
                                            <input type="hidden" name="products" id="products" value="{{$productsToSend}}">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Confirmar compra</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
    {{-- ------------------------------------------------------------------------------------------------------- --}}
                        @else
                            {{-- NO EXISTE LA INFORMACIÓN DEL PEDIDO --}}
                            {{-- Puede ocurrir por recargar la página o intentar acceder a la página sin haber hecho pedido --}}
                        @endif
                    </article>
                </div> <!-- card.// -->
            </div>
        </div>
        {{-- FOOTER --}}
        <div style="text-align: center; margin-top: 4%; margin-bottom: 2%;">
            <hr style="border: none; height: 1px; width: 80%; background-color: #82818190; ">
            <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
        </div>
        {{-- FIN FOOTER --}}

    </div>
@endsection
