<?php
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Session;

?>
@extends('layouts.masterCliente')

@section('sectionCliente')
    <!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> --}}

    {{-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->


</head>
<body style="background-color: #EBEBEB;">
<br>
<br>
<br>
<br>
<br>
<article class="card-body mx-auto" style="max-width: 1000px; max-height: 1500px; box-shadow:0px 0px 30px #ccc; border-radius: 10px">
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
                </div>
                <div class="container text-center mt-4 mb-3">
                    <hr style="width: 76%; margin-left: 12%; margin-bottom: 6%;">
                    <div class="row align-items-center justify-content-around mt-5">
                        <div class="col-sm-auto" style="margin-left: 0%">
                            {{-- DISPARA MODAL DE CONFIRMACIÓN --}}
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addToCart">Agregar al Carro</button>
                        </div>
                        <div class="col-sm-auto" style="margin-left: -30%">
                            {{-- DISPARA MODAL DE CONFIRMACIÓN --}}
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmacionCompra">Comprar</button>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <br>
        </div>
    </div>
</article>
{{-- ================================================================= --}}
{{-- MODAL CARRITO --}}
<div class="modal fade" id="addToCart" tabindex="-1" aria-labelledby="addToCart" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="margin-top: -60%">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Agregar el producto {{$producto->nombre}} al carrito.</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- FORMULARIO --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong>¡Estás a punto de agregar este producto al carrito!</strong></p>
                    {{-- ==================================================== --}}
                    {{-- Primer campo del form --}}
                    <label for="quantity">Indique la cantidad que desea agregar: </label>
                    <input type="number" min="1" max="{{$producto->cantidadDisponible}}" id="quantity" name="quantity" required>
                    {{-- ==================================================== --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {{-- ==================================================== --}}
                    {{-- Los otros campos del form y el submit correspondiente --}}
                    <input type="hidden" value="{{$producto->codigo}}" id="codigo" name="codigo">
                    <input type="hidden" value="{{$producto->nombre}}" id="nombre" name="nombre">
                    <input type="hidden" step="0.01" value="{{$producto->precio}}" id="precio" name="precio">
                    <input type="hidden" value="{{$producto->rutaImagen}}" id="rutaImagen" name="rutaImagen">
                    {{-- <input type="hidden" value="{{ $pro->slug }}" id="slug" name="slug"> --}}
                    <button type="submit" class="btn btn-primary" class="tooltip-test" title="add to cart">
                        <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                    </button>
                    {{-- ==================================================== --}}
                </div>
            </form>
        </div>
    </div>
</div>
{{-- FIN MODAL CARRITO --}}
{{-- ================================================================= --}}

{{-- ================================================================= --}}
{{-- MODAL COMPRAR --}}
<div class="modal fade" id="confirmacionCompra" tabindex="-1" aria-labelledby="confirmacionCompra" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="margin-top: -60%">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Comprar el producto: {{$producto->nombre}}.</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- FORMULARIO --}}
            {{-- En este caso, sólo necesita enviarse la cantidad pedida (totalQuantity),
            el objeto del modelo de producto como un objeto json formateado a string (products)
            y el string cartBool con el valor "false". El precio total es calculado por la
            función del controlador. --}}
            <form action="{{route('iniciarCompra')}}" method="post">
                @csrf
                <div class="modal-body">
                    <p><strong>¡Ya estás cerca de comprar este producto!</strong></p>
                    {{-- ==================================================== --}}
                    {{-- Primer campo del form --}}
                    <label for="quantity">Indique la cantidad que desea comprar: </label>
                    <input type="number" min="1" max="{{$producto->cantidadDisponible}}" id="totalQuantity" name="totalQuantity" required>
                    {{-- ==================================================== --}}
                </div>
                <div class="modal-footer">
                    {{-- ==================================================== --}}
                    {{-- Los otros campos del form, más el formateo de los datos del producto
                        y el submit correspondiente --}}
                    @php
                        // Se formatea el producto para ser enviado.
                        $productToSend = json_encode($producto);
                    @endphp
                    <input type="hidden" name="products" id="products" value="{{$productToSend}}" />
                    <input type="hidden" name="cartBool" id="cartBool" value="false" />
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary tooltip-test" title="comprar">
                        <i class="fas fa-dollar-sign"></i> Continuar
                    </button>
                    {{-- ==================================================== --}}
                </div>
            </form>
            {{-- FIN FORMULARIO --}}
        </div>
    </div>
</div>
{{-- FIN MODAL COMPRAR --}}
{{-- ================================================================= --}}
</body>
</html>
@endsection
