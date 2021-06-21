
    <!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

</head>
<body style="background-color: #F8F9FA">
<div class="container" style="margin-top: 2%; max-width: 1000px;">
    <div class="card bg-light">
        <article class="card-body mx-auto" style="max-width: 605px; display: inline;">
            <h1 style="text-align: center">Edicion de Producto</h1>
            <hr style=" width:75%; margin: auto;">
            <br>
            <form enctype="multipart/form-data" action="{{ route('editarController') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="descripcion" class="control-label">Redacte una nueva descripción del producto:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" placeholder="" rows="3" maxlength="600">@if(!empty($datos)){{$datos[1]}}@endif</textarea>
                    <small id="descripcionHelp" class="form-text text-muted">600 carácteres como máximo.</small>
                    <small id="descripcionHelp" class="form-text text-muted">No incluya información personal o de contacto.</small>
                </div>
                <div class="form-group">
                    <label for="precio" class="control-label">Defina el nuevo precio:</label>
                    <input name="precio" id="precio" class="form-control" placeholder="0.00" type="number" step="0.01" min="0" value="@if(!empty($datos))@php echo floatval($datos[3]); @endphp@endif"" required>
                    <small id="descripcionHelp" class="form-text text-muted">En pesos Uruguayos (UYU).</small>
                </div>
                <div class="form-group">
                    <label for="cantidadDisponible" class="control-label">Indique la nueva cantidad disponible del producto:</label>
                    <input name="cantidadDisponible" id="cantidadDisponible" class="form-control" type="number" min="1" value="@if(!empty($datos))@php echo intval($datos[4]); @endphp@endif" required>
                </div>
                <div class="form-group mt-5">
                    <button  id="btnFetch" type="submit" class="btn btn-primary btn-block"> Aplicar Cambios </button>
                </div> <!-- form-group// -->
                <p class="text-center text-muted">Verifique los datos antes de editar.</p>

            </form>
        </article>

    </div>
    <br>
    <br><!-- card.// -->
</div> {{-- container// --}}
{{-- FOOTER --}}

</body>
</html>
