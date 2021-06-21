@extends('layouts.masterClienteNonProducts')

@section('sectionCliente')
<div style="background: #EBEBEB; height: 100%; margin: 0;">
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="container" style="margin-top: 0%;" style="text-align: center; ">
        <div class="card bg-light mb-auto" style="box-shadow:0px 0px 15px #777777;">
            <article class="card-body mx-auto" style="max-width: 1000px; display: inline; text-align: center">
                <h1>Panel de Control</h1>
                <div style="text-align: center">
                    <hr style=" width:75%; margin:0 auto;"> <a class="navbar-brand" href="{{ url('/datospersonales') }}" target="miiframe" style=" font-size: large;">Mis Datos</a> <a class="navbar-brand" href="#" target="miiframe" style=" font-size: large;">Compras</a> <a class="navbar-brand" href="#" target="miiframe" style=" font-size: large;">Ventas</a> <a class="navbar-brand" href="{{ url('/verprod') }}" target="miiframe" style=" font-size: large;">Productos</a> </div>
            </article>
            <iframe name="miiframe" scrolling="no" frameborder="0" style="
top: 0;
bottom: 0;
left: 0;
right: 0;
height: 100%;
width: 100%;"> </iframe>
        </div>
        <!-- card.// -->
    </div>
    <div style="text-align: center; background: #EBEBEB;">
        <br> <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small> </div>
</div>
@endsection
