<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
$mail = Session::get('usuario')['email']
?>

@extends($mail == "administradores@tutienda.com" ? 'layouts.layoutAdmin' : 'layouts.masterCliente')


@section('sectionCliente')


<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="./css/styles.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /> {{--
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body style="background-color: #EBEBEB">
<br>
<br>

<?php if($mail!="administradores@tutienda.com") {
?><br><br>
<?php ;
};?>
<br>

<div>
    <h5 style="margin-left: 1%; position: absolute; margin-top: 0%;">&nbsp;Ordenar Por</h5>
    <hr style="margin-left: 1%; width: 11%; border-color: black; position: absolute; margin-top: 1.5%;">
    <ul class="list-group list-group-flush" style=" margin-left: 1%; width: 11%; margin-top: 2%; position: absolute">
        <a href="{{ url('proddesc') }}" class="list-group-item list-group-item-action">Mayor Precio</a>
        <a href="{{ url('prodasc') }}" class="list-group-item list-group-item-action">Menor Precio</a>
        <a href="{{ url('proddescfec') }}" class="list-group-item list-group-item-action">Mas Reciente</a>
        <a href="{{ url('prodascfec') }}" class="list-group-item list-group-item-action">Menos Reciente</a>
    </ul>
</div>
<div>
    <h5 style="margin-left: 1%; position: absolute; margin-top: 13%;">&nbsp;Categorias</h5>
    <hr style="margin-left: 1%; width: 11%; border-color: black; position: absolute; margin-top: 14.5%;">
    <ul class="list-group list-group-flush" style=" margin-left: 1%; width: 11%; margin-top: 15%; position: absolute">
        <a href="#" class="list-group-item list-group-item-action">Tecnologia</a>
        <a href="#" class="list-group-item list-group-item-action">Deportes</a>
        <a href="#" class="list-group-item list-group-item-action">Alimentos</a>
        <a href="#" class="list-group-item list-group-item-action">Vestimenta</a>
        <a href="#" class="list-group-item list-group-item-action">Muebles</a>
        <a href="#" class="list-group-item list-group-item-action">Electrodomesticos</a>
        <a href="#" class="list-group-item list-group-item-action">Juguetes</a>
        <a href="#" class="list-group-item list-group-item-action">Herramientas</a>
    </ul>
</div>
<div class="container" style="max-width: 75%">
    <div class="row">
        <?php foreach ($productos as $producto) : ?>
        <div class="col-md-3 col-sm-6" >
            <div class="product-grid3" style="background-color: #FFFFFF;border-radius: 10px; box-shadow:0px 0px 15px #777777; margin-bottom: 50px">
                <div class="product-image3" style="max-height: 600px; max-width: 600px; ">
                    <?php if($mail == "administradores@tutienda.com") {
                    ?> <form id="formid" action="{{ route('verGlobalIniController') }}" target="_parent" method="POST">
                        <?php ;
                        } else {
                        ?> <form id="formid" action="{{ route('verDesdeInicioController') }}" target="_parent" method="POST">
                            <?php ;
                            }?>
                            <input type="image" class="pic-1" name="prodSelect" id="prodSelect"  style="border-top-left-radius: 10px;  border-top-right-radius: 10px;" src={{asset($producto->rutaImagen)}}>
                            <input type="hidden" id="codProd" name="codProd" value=<?php echo $producto->codigo ?>>
                            @csrf
                            <ul class="social">
                                <button type="submit" value="Submit"><li><i class="fa fa-shopping-bag"></i></li></button>
                                {{-- Agregar al carrito --}}
                                {{-- Dispara un Modal --}}
                                <li>
                                    <a
                                        role="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addToCart"
                                        data-bs-codigo="{{ $producto->codigo }}"
                                        data-bs-nombre="{{ $producto->nombre }}"
                                        data-bs-precio="{{ $producto->precio }}"
                                        data-bs-rutaImagen="{{ $producto->rutaImagen }}"
                                        data-bs-cantidadDisponible="{{ $producto->cantidadDisponible }}"
                                    >
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                </li>
                                {{-- FIN Agregar al carrito --}}
                            </ul>
                        </form>
                </div>
                <div class="product-content">
                    <h3 class="title"><a href="#"><?php echo $producto->nombre?></a></h3>
                    <div class="price">
                        <?php echo "$".$producto->precio?>
                    </div>
                    <br>
                    <div class="price" style="font-weight: normal">
                        <?php echo "Stock: ".$producto->cantidadDisponible." Unidades"?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>
<hr>
<div style="text-align: center; margin-top: 4%;">
    <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
</div>
{{-- ================================================================= --}}
{{-- MODAL --}}
<div class="modal fade" id="addToCart" tabindex="-1" aria-labelledby="addToCart" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- FORMULARIO --}}
            <form action="{{ route('cart.add') }}" target="_parent" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong>¡Estás a punto de agregar este producto al carrito!</strong></p>
                    {{-- ==================================================== --}}
                    {{-- Primer campo del form --}}
                    <label for="quantity">Indique la cantidad que desea agregar: </label>
                    <input type="number" min="1" max="{{--Se settea en el script js--}}" id="quantity" name="quantity" required>
                    {{-- ==================================================== --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                    {{-- ==================================================== --}}
                    {{-- Los otros campos del form y el submit correspondiente --}}
                    <input type="hidden" value="{{--Se settea en el script js--}}" id="codigo" name="codigo">
                    <input type="hidden" value="{{--Se settea en el script js--}}" id="nombre" name="nombre">
                    <input type="hidden" step="0.01" value="{{--Se settea en el script js--}}" id="precio" name="precio">
                    <input type="hidden" value="{{--Se settea en el script js--}}" id="rutaImagen" name="rutaImagen">
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
{{-- FIN MODAL --}}
{{-- ================================================================= --}}

{{-- ================================================================= --}}
{{-- Script JS para obtener datos para el modal del añadir al carrito --}}
<script type="text/javascript">
    var modalAddToCart = document.getElementById('addToCart');
    modalAddToCart.addEventListener('show.bs.modal', function(event) {
        // elemento que dispara el modal
        var trigger = event.relatedTarget;
        // Extraer la informacion de los elementos con los atributos data-bs-*
        var codigo = trigger.getAttribute('data-bs-codigo');
        var nombre = trigger.getAttribute('data-bs-nombre');
        var precio = trigger.getAttribute('data-bs-precio');
        var rutaImagen = trigger.getAttribute('data-bs-rutaImagen');
        var cantidadDisponible = trigger.getAttribute('data-bs-cantidadDisponible');
        console.log(codigo);
        // Si es necesario, se puede iniciar un request AJAX en este punto
        // y luego hacer la actualización en un callback.
        //
        // Actualizar el contenido del modal.

        // Se obtienen los elementos del DOM.
        var modalTitle = modalAddToCart.querySelector('.modal-title');
        // Los siguientes se obtienen por id.
        var fieldQuantity = modalAddToCart.querySelector('#quantity');
        var fieldCodigo = modalAddToCart.querySelector('#codigo');
        var fieldNombre = modalAddToCart.querySelector('#nombre');
        var fieldPrecio = modalAddToCart.querySelector('#precio');
        var fieldRutaImagen = modalAddToCart.querySelector('#rutaImagen');

        // Se actualiza el contenido de cada elemento
        modalTitle.textContent = 'Agregar el producto ' + nombre + ' al carrito.';
        fieldQuantity.setAttribute('max', parseInt(cantidadDisponible));
        fieldCodigo.value = codigo;
        fieldNombre.value = nombre;
        fieldPrecio.value = parseFloat(precio);
        fieldRutaImagen.value = rutaImagen;
    });
</script>
{{-- FIN - Script JS para obtener datos para el modal del añadir al carrito --}}
{{-- ================================================================= --}}
</body>
@endsection
