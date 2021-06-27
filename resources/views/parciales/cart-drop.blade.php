<div class="row" style="margin: 0px;">
    <a class="btn btn-dark btn-sm btn-block" href="{{ route('cart.checkout') }}">
        IR AL CARRITO <i class="fa fa-arrow-right"></i>
    </a>
    {{-- <a class="btn btn-dark btn-sm btn-block" href="">
        COMPRAR <i class="fa fa-arrow-right"></i>
    </a> --}}
</div>
@if(count(\Cart::getContent()) > 0)
    <div style="max-height: 300px; overflow: auto;">
        @foreach(\Cart::getContent() as $item)
            <li class="list-group-item" style="background: #ffa500;">
                <div class="row">
                    <div class="col-lg-2 align-self-center">
                        <img src="{{asset($item->attributes->pathImage)}}"
                            class="img-fluid"
                            width="40"
                            height="40"
                        >
                    </div>
                    <div class="col-lg-7 text-truncate">
                        <b style="">{{$item->name}}</b>
                        <br><small style="">Cantidad: {{$item->quantity}}</small>
                    </div>
                    <div class="col-lg-3 text-center text-truncate p-0">                    
                        <p>${{ \Cart::get($item->id)->getPriceSum() }}</p>
                    </div>
                    <hr>
                </div>
            </li>
        @endforeach
    </div>
    <br>
    <li class="list-group-item" style="background: #ffe4b3;">
        <div class="row">
            <div class="col-lg-9 text-truncate">
                <b>Total: </b>${{ \Cart::getTotal() }}
            </div>
            <div class="col-lg-2">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button class="btn btn-secondary btn-sm" type="submit"><i class="fa fa-trash"></i>  Vaciar</button>
                </form>
            </div>
        </div>
    </li>
@else
    <li class="list-group-item text-center" style="background: #ffa500;"><strong>Aún no has agregado nada a tu carrito.</strong></li>
@endif
<br>
