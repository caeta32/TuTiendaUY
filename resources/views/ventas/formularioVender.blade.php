@extends('layouts.masterCliente')

@section('sectionCliente')
<div style="background: #EBEBEB; height: 100%; overflow: auto;">
<br>
<br>
<br>
<br>
    <div class="container" style="margin-top: 2%; max-width: 1000px;">
        <div class="card bg-light" style="border-radius: 10px; box-shadow:0px 0px 15px #777777;">
            <article class="card-body mx-auto" style="max-width: 605px; display: inline; ">
                <h1 style="text-align: center">Vender Producto</h1>
                <hr style=" width:75%; margin: auto;">
                <br>
                {{-- Manejo de errores --}}
                @if (session('errors'))
                    <div class="container">
                        <div class="row align-items-center justify-content-center">
                            <div class="col mb-4">
                                <div class="alert alert-danger" style="">
                                    <ul>
                                        @foreach(session()->pull('errors') as $message)
                                        <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Fin manejo de errores --}}

                {{-- Manejo de datos para simular que el formulario recuerda lo ingresado por el usuario --}}
                @php
                    $datos = array();
                @endphp
                @if(session('datos'))
                    @foreach(session()->pull('datos') as $d)
                        @php
                            $datos[] = $d;
                        @endphp
                    @endforeach
                @endif
                {{-- Fin manejo de datos para simular que el formulario recuerda lo ingresado por el usuario --}}
                <form enctype="multipart/form-data" action="{{ route('publicarController') }}" method="POST">
                    @csrf
                    <div class="form-group row mb-5">
                        <label for="imagen" class="col-md-auto col-form-label">Importar imagen:</label>
                        <div class="col-sm">
                            <input name="imagen" id="imagen" class="form-control-file" type="file">
                        </div>
                        <small id="descripcionHelp" class="form-text text-muted mx-auto mt-2">La imagen debe tener formato .jpg, .jpeg o .png y su peso no debe superar los 2MB.</small>
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="control-label">Ingrese un nombre para su producto:</label>
                        <input name="nombre" id="nombre" class="form-control" placeholder="Nombre" type="text" maxlength="100"  value="@if(!empty($datos)){{$datos[0]}}@endif" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="control-label">Redacte una descripción del producto:</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" placeholder="" rows="3" maxlength="600">@if(!empty($datos)){{$datos[1]}}@endif</textarea>
                        <small id="descripcionHelp" class="form-text text-muted">600 carácteres como máximo.</small>
                        <small id="descripcionHelp" class="form-text text-muted">No incluya información personal o de contacto.</small>
                    </div>
                    <div class="form-group">
                        <label for="codigo" class="control-label">Escriba el código del producto:</label>
                        <input name="codigo" id="codigo" class="form-control" placeholder="" type="text" maxlength="100" value="@if(!empty($datos)){{$datos[2]}}@endif" required>
                    </div>
                    <div class="form-group">
                        <label for="precio" class="control-label">Defina el precio:</label>
                        <input name="precio" id="precio" class="form-control" placeholder="0.00" type="number" step="0.01" min="0" value="@if(!empty($datos))@php echo floatval($datos[3]); @endphp@endif"" required>
                        <small id="descripcionHelp" class="form-text text-muted">En pesos Uruguayos (UYU).</small>
                    </div>
                    <div class="form-group">
                        <label for="cantidadDisponible" class="control-label">Indique la cantidad disponible del producto:</label>
                        <input name="cantidadDisponible" id="cantidadDisponible" class="form-control" type="number" min="1" value="@if(!empty($datos))@php echo intval($datos[4]); @endphp@endif" required>
                    </div>
                    <div class="form-group mt-5">
                        <button  id="btnFetch" type="submit" class="btn btn-primary btn-block"> Publicar </button>
                    </div> <!-- form-group// -->
                    <p class="text-center text-muted">Verifique los datos antes de publicar.</p>
                </form>
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
@endSection


