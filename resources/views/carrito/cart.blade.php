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
    <div class="noscrollbar" style="background: #EBEBEB; height: 100%; overflow: auto;">
        <br>
        <br>
        <br>
        <br>
        <div class="container" style="margin-top: 2%; max-width: 100%;">
            <div class="card bg-light" style="box-shadow:0px 0px 15px #777777;">
                <article class="card-body" style="max-width: 100%; display: inline; margin-left: 5%; margin-right: 5%;">
{{-- --------------------------------------------------------------------------------------------------------- --}}
                    <div class="container">
                        <h1 style="text-align: center">Tu Carrito</h1>
                        <hr style=" width:75%; margin: auto;">
                        <br>
                        {{-- ========================================================= --}}
                        {{-- Manejo de mensajes --}}
                        @if (session()->has('message'))
                            <div class="row justify-content-center align-items-center my-auto">
                                <div class="col-md-auto">
                                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                        @if (session()->get('message') === 'updateOK')
                                            @php
                                                $updatedProductData = array();
                                                $updatedProductData = session()->pull('updatedProductData');
                                            @endphp
                                            <h5>¡La cantidad del producto {{Arr::get($updatedProductData, 'name')}} ha sido editada correctamente!</h5>
                                            <p>Ahora tienes en el carrito una cantidad de {{Arr::get($updatedProductData, 'quantity')}} unidad/es del producto.</p>
                                        @else
                                            {{ session()->get('message') }}
                                        @endif
                                        {{ session()->forget('message') }}
                                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (session()->has('errors'))
                            <div class="row justify-content-center align-items-center my-auto">
                                <div class="col-md-auto">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul>
                                            @foreach(session()->pull('errors') as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--FIN - Manejo de mensajes --}}
                        {{-- ========================================================= --}}

                        <div class="row justify-content-center">
                            <div class="col-11 col-sm-12 col-md-11 col-lg-7">
                                <br>
                                @if(\Cart::getTotalQuantity()>0)
                                    <h4>Hay {{ \Cart::getTotalQuantity()}} Producto/s en tu carrito</h4><br>
                                @else
                                    <div class="row justify-content-center">
                                        <h4>¡Aún no has agregado ningún producto a tu carrito!</h4>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center">
                                        <p class="text-muted">Te animamos a que continues echando un vistazo.</p>
                                    </div>
                                    <div class="row justify-content-center">
                                        <a href="/" class="btn btn-success">Continuar Viendo Productos</a>
                                    </div>
                                    <br>
                                @endif
                                <hr>
                                @foreach(\Cart::getContent() as $item)
                                    <div class="row justify-content-center">
                                        @if (session()->has('failedStockInCart'))
                                            @foreach (session()->get('failedStockInCart') as $failed)
                                                @if (Arr::get($failed, 'codigo') === $item->id)
                                                    <div class="row">
                                                        <div class="alert alert-danger text-center" role="alert">
                                                            @if (Arr::get($failed, 'reason') === 'emptyStock')
                                                                <b>¡Lo sentimos! Ya no queda stock de este producto.</b>
                                                                <p>Eliminalo del carrito para poder realizar tu compra.</p>
                                                            @elseif (Arr::get($failed, 'reason') === 'exceededStock')
                                                                <b>¡Lo sentimos! No hay suficiente stock del siguiente producto disponible.</b>
                                                                <p>La cantidad del producto <strong>{{$item->name}}</strong> que has agregado al carrito excede su stock disponible.</p>
                                                                <p>Edita la cantidad o consulta su stock disponible accediendo a su publicación.</p>
                                                            @elseif (Arr::get($failed, 'reason') === 'productSoldByMyself')
                                                                <b>¡Parece que este producto lo estás vendiendo tú!</b>
                                                                <p>Elimina este producto de tu carrito para poder realizar tu compra.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                        <div class="row justify-content-center">
                                            <div class="col-3 col-sm-3 col-md-2 col-lg-3">
                                                <img src="{{ asset($item->attributes->pathImage) }}" class="img-thumbnail" width="200" height="200" alt="imagen del producto">
                                            </div>
                                            <div class="col-9 col-sm-8 col-md-8 col-lg-7 ">
                                                <p>
                                                    {{-- <b><a href="/shop/{{ $item->attributes->slug }}">{{ $item->name }}Nombre</a></b><br> --}}
                                                    <form action="{{ route('verDesdeInicioController') }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" id="codProd" name="codProd" value="{{$item->id}}">
                                                            <div class="col-11 col-sm-12 col-md-12 col-lg-12 text-truncate">
                                                                <button class="btn btn-link p-0 mb-2" type="submit" value="Submit"><b>{{ $item->name }}</b></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <b>Precio: </b>{{ $item->price }}<br>
                                                    <b>Sub Total: </b>{{ \Cart::get($item->id)->getPriceSum() }}<br>
                                                    {{--                                <b>With Discount: </b>${{ \Cart::get($item->id)->getPriceSumWithConditions() }}--}}
                                                    <form action="{{ route('cart.update') }}" method="POST">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <input type="hidden" value="{{$item->id}}" id="id" name="id">
                                                            <input type="hidden" value="{{$item->name}}" id="name" name="name">
                                                            <label for="quantity" class="col-auto col-sm-auto col-md-auto col-lg-auto form-control-label m-0">
                                                                <strong>Cantidad:</strong>
                                                            </label>
                                                            <div class="col-4 col-sm-3 col-md-3 col-lg-3">
                                                                <input type="number" class="form-control form-control-sm" value="{{$item->quantity}}" id="quantity" name="quantity">
                                                            </div>
                                                            <div class="col-3 col-sm-auto col-md-auto col-lg-auto">
                                                                <button class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i>  Editar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </p>
                                            </div>
                                            <div class="col-auto col-sm-auto col-md-2 col-lg-2 p-0 ">
                                                <div class="row justify-content-center">
                                                    <div class="col-auto col-sm-auto col-md-auto col-lg-auto p-0 ">
                                                        <form action="{{ route('cart.remove') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" value="{{$item->id}}" id="id" name="id">
                                                            <div class="col-auto col-sm-auto col-md-auto col-lg-auto p-0 ">
                                                                <button class="btn btn-dark btn-sm" style="font-size: 12px"><i class="fa fa-trash "></i>  Eliminar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach

                                @if(\Cart::getTotalQuantity()>0)
                                    <form action="{{ route('cart.clear') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-secondary btn-md">Vaciar Carrito</button>
                                    </form>
                                    <br>
                                @endif
                            </div>
                            @if(\Cart::getTotalQuantity()>0)
                                <div class="col-lg-5">
                                    <div class="card">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><b>Total: </b>${{ \Cart::getTotal() }}</li>
                                        </ul>
                                    </div>
                                    <div class="row my-4 justify-content-center align-items-center">
                                        <div class="col-sm-auto mx-auto my-2">
                                            <a href="{{url('/principal')}}" class="btn btn-dark mx-auto">Seguir viendo Productos</a>
                                        </div>
                                        <div class="col-sm-auto mx-auto my-2">
                                            <form action="{{ route('iniciarCompra') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="products" id="products" value="{{\Cart::getContent()}}">

                                                <input type="hidden" name="totalPrice" id="totalPrice" value="{{\Cart::getTotal()}}">

                                                <input type="hidden" name="totalQuantity" id="totalQuantity" value="{{\Cart::getTotalQuantity()}}" />

                                                <input type="hidden" name="cartBool" id="cartBool" value="true" />

                                                <button type="submit" class="btn btn-success mx-auto">Comprar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <br><br>


                    </div>
{{-- --------------------------------------------------------------------------------------------------------- --}}
                </article>
            </div> <!-- card.// -->
        </div> {{-- container// --}}
        {{-- FOOTER --}}
        <div style="text-align: center; margin-top: 4%; margin-bottom: 2%;">
            <hr style="border: none; height: 1px; width: 80%; background-color: #82818190; ">
            <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
        </div>
        {{-- FIN FOOTER --}}

    </div>
@endsection
