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
                            <div class="col-lg-7">
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
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <img src="{{ asset($item->attributes->pathImage) }}" class="img-thumbnail" width="200" height="200" alt="imagen del producto">
                                            </div>
                                            <div class="col-lg-7">
                                                <p>
                                                    {{-- <b><a href="/shop/{{ $item->attributes->slug }}">{{ $item->name }}Nombre</a></b><br> --}}
                                                    <b>{{ $item->name }}</b><br>
                                                    <b>Precio: </b>{{ $item->price }}<br>
                                                    <b>Sub Total: </b>{{ \Cart::get($item->id)->getPriceSum() }}<br>
                                                {{--                                <b>With Discount: </b>${{ \Cart::get($item->id)->getPriceSumWithConditions() }}--}}
                                                <form action="{{ route('cart.update') }}" method="POST">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <input type="hidden" value="{{$item->id}}" id="id" name="id">
                                                        <input type="hidden" value="{{$item->name}}" id="name" name="name">
                                                        <label for="quantity" class="col-sm-auto form-control-label">
                                                            <strong>Cantidad:</strong>
                                                        </label>
                                                        <input type="number" class="form-control form-control-sm mr-0" value="{{$item->quantity}}" id="quantity" name="quantity" style="width: 70px; margin-right: 10px;">
                                                        <div class="col-sm">
                                                            <button class="btn btn-secondary btn-sm" style="margin-right: 25px;"><i class="fa fa-edit"></i>  Editar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                </p>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="row justify-content-end">
                                                    <div class="col-sm-auto">
                                                        <form action="{{ route('cart.remove') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" value="{{$item->id}}" id="id" name="id">
                                                            <div class="col">
                                                                <button class="btn btn-dark btn-sm" style="margin-right: 10px;"><i class="fa fa-trash"></i>  Eliminar</button>
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
                                                {{-- Se pasa como un objeto JSON en formato string --}}
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
