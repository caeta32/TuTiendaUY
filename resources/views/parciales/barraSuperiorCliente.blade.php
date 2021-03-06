<div style="width: 100%; position: fixed; z-index: 9999999;">
    <form id="formid" action="{{ route('buscarController') }}"  method="POST">
        @csrf
    <div style="text-align: center; background-color: #ff9900; width: 100%;">
        <a class="navbar-brand" href="{{ url('/principal') }}" style=" color: white; margin-left: 0%;"><img alt="Qries" src="{{ asset('img/logo.png') }}" width="58" height="48" style="border-right: 1px solid black; padding-right: 10px; margin-left: 4%"></a>
        <a class="navbar-brand" style="margin-left: -0.5%; font-size: medium; font-weight: bold; padding-right: 1%">TuTienda</a>

        <input type="text" name="buscarprod"; id="buscarprod" placeholder="Busca el producto que necesitas..." style="width:45%;border: 1px solid transparent; border-radius: 3px; box-shadow: 0 0 0 2px #f90;    outline: none;    padding: 0.1em; padding-left: 0.5% ;
            text-align: center; ">
        <button type="submit" style="background-color: #ff9900;text-align: center;border-width:0px; color: #ffffff; border-radius: 3px; box-shadow: 0 0 0 2px #f90; margin-left: 1%;padding: 0.15em; outline: none;">
            <i class="fa fa-search" aria-hidden="true"></i> Buscar
        </button>

        <input type="submit"
               style="position: absolute; left: -9999px; width: 1px; height: 1px;"
               tabindex="-1" />

        <a class="navbar-brand" href="{{ url('/datoscliente') }}" style=" color: white; font-size: medium; margin-left: 1%; "><i class="fa fa-user-circle-o fa-lg" aria-hidden="true" style="padding-right: 5%"></i><?php echo $nombre?></a>
    </form>

    {{-- Carrito --}}
        <div class="navbar-brand" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="color: #ffffff;">
                        <span class="badge badge-pill badge-light" style="color: #ffa500;">
                            <i class="fa fa-shopping-cart"></i> {{ \Cart::getTotalQuantity()}}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="width: 450px; padding: 0px; border-color: #9da0a285; background: #ffffffb9;">
                        <ul class="list-group" style="margin: 20px;">
                            @include('parciales.cart-drop')
                        </ul>

                    </div>
                </li>
            </ul>
        </div>

        {{--FIN Carrito --}}
        <a class="navbar-brand" href="{{ url('/logout') }}" style=" color: white; font-size: medium;">Salir</a>
    </div>
    <!-- Barra superior secundaria -->
    <div style="background: #ffa500; width: 100%; text-align: center; border-bottom: solid 2px black">
        <a class="navbar-brand" href="{{ url('vender') }}" target = "_parent" style=" color: white; font-size: 15px">
            <i class="fa fa-tag fa-lg" aria-hidden="true" style="padding-right: 5%; "></i>
            Vender
        </a>
    </div>

</div>
