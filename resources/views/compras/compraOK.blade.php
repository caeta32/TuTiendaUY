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

    <div style="background: #EBEBEB; height: 100%; overflow: auto;">
        <br>
        <br>
        <br>
        <br>
        <div class="container" style="margin-top: 2%; max-width: 700px;">
            <div class="card bg-light" style="box-shadow:0px 0px 15px #777777;">
                <article class="card-body mx-auto" style="max-width: 1000px; " >
                    <div class="container">
                        <div class="row align-items-center justify-content-center mb-3">
                            <div class="col-md-auto">
                                <img src="{{ asset('img/logo-confetti/confetti.png') }}" alt="logo confeti" width="110" height="110">
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-auto">
                                <h2>¡Listo, ya has realizado tu compra!</h2>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-2">
                            <div class="col-md-auto">
                                <p><strong>¡Muchas gracias! Tu compra se ha efectuado con éxito.</strong></p>
                            </div>
                        </div>
                        <div class="row justify-content-center text-center mt-4">
                            <div class="col-md-10">
                                <p>Te hemos enviado un correo electrónico con todos los detalles de tu pedido y con los siguientes pasos a seguir.</p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                <hr>
                            </div>
                        </div>

                        <div class="row text-center mt-4">
                            <div class="col">
                                <div>¡Te invitamos a continuar echando un vistazo!</div>
                            </div>
                        </div>
                        <div class="row text-center mb-4 mt-2">
                            <div class="col">
                                <a href="/" class="btn btn-success">Continuar Viendo Productos</a>
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
