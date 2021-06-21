<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request) {
        \Cart::add(array(
            'id' => $request->codigo,
            'name' => $request->nombre,
            'price' => $request->precio,
            'quantity' => $request->quantity,
            'attributes' => array(
                'pathImage' => $request->rutaImagen
            )
        ));
        session()->put('message', '¡Producto añadido correctamente!');
        return redirect()->route('cart.checkout');
    }

    public function cart() {
        $cartCollection = \Cart::getContent();
        $failedStockInCart = array();

        foreach($cartCollection as $item) {
            if(Producto::where('codigo','=',$item->id)->exists()) {
                $producto = Producto::find($item->id);
                if ($producto->cantidadDisponible === 0) {
                    $failedStockInCart[] = [
                        'codigo' => strval($item->id),
                        'reason' => 'emptyStock'
                    ];
                } elseif ($producto->cantidadDisponible < $item->quantity) {
                    $failedStockInCart[] = [
                        'codigo' => strval($item->id),
                        'reason' => 'exceededStock'
                    ];
                }
            }
        }

        if(!empty($failedStockInCart))
            session()->put(['failedStockInCart' => $failedStockInCart]);
            session()->put(['cartCollection' => $cartCollection]);
        return redirect()->route('cart');
    }

    public function remove(Request $request) {
        \Cart::remove($request->id);
        session()->put('message', 'Producto eliminado correctamente.');
        return redirect()->route('cart.checkout');
    }

    public function update(Request $request) {
        // Con 'relative' => false se actualiza la cantidad remplazando a la actual.
        \Cart::update($request->id,
            array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $request->quantity
                )
        ));
        $updatedProduct = \Cart::get($request->id);
        $updatedProductData = [
            'name' => $updatedProduct['name'],
            'quantity' => $updatedProduct['quantity']
        ];
        session()->put('message', 'updateOK');
        session()->put('updatedProductData', $updatedProductData);
        return redirect()->route('cart.checkout');
    }

    public function clear(Request $request) {
        \Cart::clear();
        session()->put('message', 'Carrito vaciado correctamente.');
        return redirect()->route('cart.checkout');
    }
}
