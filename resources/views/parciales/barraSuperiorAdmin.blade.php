<div style="width: 100%; position: fixed; z-index: 9999999;">
    <form id="formid" action="{{ route('buscarController') }}"  method="POST">
        @csrf
        <div style="text-align: center; background-color: #ff9900; width: 100%; border-bottom: 2px solid #000000">
            <a class="navbar-brand" href="{{ url('/') }}" style=" color: white; margin-left: 0%;"><img alt="Qries" src="{{ asset('img/logo.png') }}" width="58" height="48" style="border-right: 1px solid black; padding-right: 10px; margin-left: 4%"></a>
            <a class="navbar-brand" style="margin-left: -0.5%; font-size: medium; font-weight: bold; padding-right: 1%">TuTienda</a>

            <input type="text" name="buscarprod"; id="buscarprod" placeholder="Busca el producto que necesitas..." style="width:45%;border: 1px solid transparent; border-radius: 3px; box-shadow: 0 0 0 2px #f90;    outline: none;    padding: 0.1em; padding-left: 0.5% ;
            text-align: center; ">
            <button type="submit" style="background-color: #ff9900;text-align: center;border-width:0px; color: #ffffff; border-radius: 3px; box-shadow: 0 0 0 2px #f90; margin-left: 1%;padding: 0.15em; outline: none;">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>

            <input type="submit"
                   style="position: absolute; left: -9999px; width: 1px; height: 1px;"
                   tabindex="-1" />

            <a class="navbar-brand" style=" color: white; font-size: medium; margin-left: 1%; "><i class="fa fa-user-circle-o fa-lg" aria-hidden="true" style="padding-right: 5%"></i><?php echo $nombre?></a>
            <a class="navbar-brand" href="{{ url('/paneldecontrol') }}" style=" color: white; font-size: medium;">Panel de Control</a>

    </form>

    <a class="navbar-brand" href="{{ url('/logout') }}" style=" color: white; font-size: medium;">Salir</a>
</div>