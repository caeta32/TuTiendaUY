@extends('layouts.masterCliente')

@section('sectionCliente')
<div style="background: #EBEBEB; height: 100%; overflow: auto;">
    <br>
    <br>
    <br>
    <br>
    <div class="container" style="margin-top: 2%; max-width: 700px;">
        <div class="card bg-light" style="box-shadow:0px 0px 15px #777777;">
            <article class="card-body mx-auto" style="max-width: 1000px; " >
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-2 mr-3">
                            <div style="font-size: 6em; color: #389e0f;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-md-auto">
                            <h2>¡Producto publicado!</h2>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-auto">
                            <p class="text-muted">Su producto se ha publicado con éxito.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div style="col">
                            <hr style="width: 30rem">
                        </div>
                    </div>

                    <div class="row text-center mt-4">
                        <div class="col">
                            <div>Para ver la publicación presione click en el siguiente botón:</div>
                        </div>
                    </div>
                    <div class="row text-center mb-4 mt-4">
                        <div class="col">
                            <form action="{{ route('verDesdeInicioController') }}" method="POST">
                                @csrf
                                <input type="hidden" id="codProd" name="codProd" value="{{$codigoProd}}">
                                <button class="btn btn-outline-success" type="submit" value="Submit">Ver Publicación</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        </div> <!-- card.// -->
    </div>
    {{-- FOOTER --}}
    <div style="text-align: center; margin-top: 4%;">
        <hr style="border: none; height: 1px; width: 80%; background-color: #82818190; ">
        <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
    </div>
    {{-- FIN FOOTER --}}
</div>

@endsection
