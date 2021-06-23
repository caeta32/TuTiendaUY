<!DOCTYPE html>
<html style="height: 100%; background-color: #F8F9FA">
<head>
    <title>TuTienda</title>
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/013a3a1db6.js" crossorigin="anonymous"></script>
    {{-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .noscrollbar::-webkit-scrollbar {
            display: none;
        }
    
        .noscrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    
</head>
<body style="background-color: #F8F9FA;">
    <div class="row justify-content-center mt-2">
        <div class="col-10 col-sm-8 col-md-8 col-lg-10 col-xl-15 text-center mb-5" style="box-shadow:0px 0px 30px #ccc; border-radius: 10px;">
                <div class="row justify-content-center mb-5 mx-5 mt-5">
                    <div class="col">
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
                            <div class="col-md-auto mb-3">
                                <a href="{{route('cart.checkout')}}" class="btn btn-primary">Ir al carrito</a>
                            </div>
                            <div class="col-md-auto">
                                <a href="{{url('/')}}" class="btn btn-success">Volver a la pantalla principal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</body>