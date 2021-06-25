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
        <div class="col-10 col-sm-8 col-md-8 col-lg-8 col-xl-15 text-center mb-5" style="box-shadow:0px 0px 30px #ccc; border-radius: 10px;">
                <div class="row justify-content-center mb-5 mx-5 mt-5">
                    <div class="col">
                        <div class="row justify-content-center">
                            <h3>¡Aún no has comprado nigún producto!</h3>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <hr>
                            </div>
                        </div>
                        <div class="row justify-content-center text-center mt-4">
                            <p>Aquí aparecerá toda la información de los pedidos que realices, ¡Anímate a realizar tu primera compra!</p>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-md-auto">
                                <a href="{{url('/')}}" target="_parent" class="btn btn-success" style="font-size: 17px;">Seguir viendo productos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</body>