<?php
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Session;

$prods = DB::table('pedidos')->get();

?>
    <!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> {{--
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css"> </head>

<body style="background-color: #F8F9FA"> @if (session('message')) @if (session()->pull('message', 'default') === 'success delete Producto')
    <div class="container">
        <div class="row justify-content-center align-items-center my-auto">
            <div class="col-md-auto">
                <div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>¡El Producto ha sido eliminado con éxito!</strong>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
            </div>
        </div>
    </div> @endif @endif
<form enctype="multipart/form-data" action="{{ route('verPedidosAdminController') }}" method="POST"> @csrf
    <article class="card-body mx-auto" style="max-width: 1000px; margin-top: -3%;">
        <h4 class="card-title mt-3 text-center">Pedidos</h4>
        <div class="form-group input-group">
            <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-tag" aria-hidden="true"></i>
 </span> </div>
            <select name="pedidoselect" type="text" class="form-control">
                <?php
                if(is_string($prods)) {
                    echo "<option value ='$prods''>" . $prods . "</option>";
                } else {
                    foreach ($prods as $pedido) {
                        echo "<option value ='$pedido->idEnvio'>" . $pedido->idEnvio . ' - '. $pedido->emailComprador . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <button id="btnFetch" type="submit" class="btn btn-primary btn-block"> Buscar </button>
        </div>
    </article>
</form>
</body>

</html>
