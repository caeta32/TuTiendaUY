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
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card bg-light" style="box-shadow:0px 0px 15px #777777;">
                    <article class="card-body px-5">
                        <div class="row justify-content-center my-4">
                            <div class="col-lg-7">
                                <div class="row justify-content-center">
                                    <img src="{{ asset('img/logo-emoji-pensando/thinking.png') }}" alt="emoji pensando" width="130" height="130">
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <h3>¡Vaya! Encontramos un problema.</h3>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <hr>
                                    </div>
                                </div>
                                @if ($error === 'failed stock')
                                    <div class="row justify-content-center text-center mb-4">
                                        <h5>Uno o más productos de los que intentas comprar superan el stock disponible.</h5>
                                    </div>
                                    <div class="row justify-content-center text-center mt-4">
                                        <p><strong>¡No te preocupes! Intenta comprar lo mismo pero en menos cantidad o consultar el stock disponible desde la publicación correspondiente.</strong></p>
                                    </div>
                                @elseif ($error === 'failed product')
                                    <div class="row justify-content-center text-center mb-4">
                                        <h5>Uno o más productos de los que intentas comprar ya no se encuentran registrados.</h5>
                                    </div>
                                    <div class="row justify-content-center text-center mt-4">
                                        <p><strong>¡Lamentamos el inconveniente! Te invitamos a que continúes hechando un vistazo.</strong></p>
                                    </div>
                                @elseif ($error === 'productSoldByMyself')
                                    <div class="row justify-content-center text-center mb-4">
                                        <h5>¡Parece que estás intentando comprar un producto que vendes tú mismo!</h5>
                                    </div>
                                    <div class="row justify-content-center text-center mt-4">
                                        <p><strong>Esto no se puede realizar, ¡pero te invitamos a que continues hechando un vistazo!</strong></p>
                                    </div>
                                @else
                                    <div class="row justify-content-center text-center mb-4">
                                        <p><strong>Ha ocurrido un error no controlado, por favor, ponte en contacto con el administrador, a continuación se presenta información sobre el error, ¡lamentamos lo ocurrido!</strong></p>
                                    </div>
                                    <div class="row justify-content-center text-center mt-4">
                                        <p>{{ $error }}</p>
                                    </div>
                                @endif
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-auto">
                                        <a href="{{route('cart.checkout')}}" class="btn btn-primary">Ir al carrito</a>
                                    </div>
                                    <div class="col-md-auto">
                                        <a href="{{url('/')}}" class="btn btn-success">Volver a la pantalla principal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div> <!-- card.// -->
            </div>
        </div>
        {{-- FOOTER --}}
        <div style="text-align: center; margin-top: 4%; margin-bottom: 2%;">
            <hr style="border: none; height: 1px; width: 80%; background-color: #82818190; ">
            <small>Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados.</small>
        </div>
        {{-- FIN FOOTER --}}

    </div>
@endsection
