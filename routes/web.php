<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('welcome');
});

route::get('/principal', function () {
    return view('principal');
});

route::get('/administradores', function () {
    return view('principalAdmin');
});


Route::get('/login', function () {
    return view('login');
});

Route::get('/logout', function () {
    Session::forget('usuario');
    \Cart::clear();
    return redirect('/');
});

Route::get('/loginerror', function () {
    return view('loginerror');
});

Route::get('/registererrorpass', function () {
    return view('registererrorpass');
});

Route::get('/registererroremail', function () {
    return view('registererroremail');
});

Route::get('/registererrorusuario', function () {
    return view('registererrorusr');
});

Route::view('/confirmar', 'confirmaruser');


Route::get('/confirmarerror', function () {
    return view('confirmarerror');
});


Route::get('/datoscliente', function () {
    return view('datoscliente');
});

Route::get('/paneldecontrol', function () {
    return view('paneldecontrol');
});

Route::view('/registro', 'register');

Route::post("/login", [ClienteController::class, 'login']);
Route::post("/registro", [ClienteController::class, 'registro']);
Route::post("/confirmar", [ClienteController::class, 'confirmar']);

Route::get('/datospersonales', function () {
    return view('datospersonales');
});

Route::get('/invitado', function () {
    return view('invitado');
});

// Ventas ===============================================================
Route::view('/vender', 'ventas.formularioVender');

Route::post("/publicar", [ProductoController::class, 'publicar'])->name('publicarController');

Route::get('/publicar/{codigoProd}', function () {
    return view('ventas.publicarProducto');
})->name('ventas.publicar');

Route::get('/verVentas', [ClienteController::class, 'verVentasDeUsuario']);

Route::view('/verVentasView', 'ventas.verVentas');
// Fin ventas =============================================================

// Compras ===============================================================
Route::post('/iniciarCompra', [ProductoController::class, 'iniciarCompra'])->name('iniciarCompra');

Route::post('/comprar', [ProductoController::class, 'comprar'])->name('comprar');

Route::view('/compra-exitosa', 'compras.compraOK');
// Fin compras =============================================================

// Productos ===============================================================
Route::get('/productos', function () {
    return view('productos');
});

Route::post("/verproductos", [ProductoController::class, 'verTodos'])->name('verController');
Route::post("/verproductosadmin", [ProductoController::class, 'verGlobal'])->name('verGlobalController');
Route::post("/verproductosadmini", [ProductoController::class, 'verGlobalDesdeInicio'])->name('verGlobalIniController');



Route::post("/productoampliado", [ProductoController::class, 'verProductoDesdeInicio'])->name('verDesdeInicioController');

Route::post("/editarproducto", [ProductoController::class, 'editar'])->name('editarController');


Route::view('/verprod', 'productos.verproductos');

Route::view('/verprodadmin', 'productos.verproductosAdmin');

Route::view('/editarprod', 'productos.editarproducto');

Route::view('/prodeditado', 'productos.productoeditado');

Route::view('/detalleprod', 'productos.detalleproducto');

Route::view('/detalleprodiniad', 'productos.detalleproductoinicioAdmin');

Route::view('/detalleprodinicio', 'productos.detalleproductoinicio');

Route::view('/prodasc', 'productos.productosasc');

Route::view('/proddesc', 'productos.productosdesc');

Route::view('/prodascfec', 'productos.productosascfec');

Route::view('/proddescfec', 'productos.productosdescfec');

Route::post("/busquedaproducto", [ProductoController::class, 'verBuscados'])->name('buscarController');
Route::view('/encontrados', 'productos.productosbusqueda');

Route::post('/porcategoria', [ProductoController::class, 'verPorCategoria'])->name('categoriaController');

Route::delete('/eliminarProducto/{codigoProd}', [ProductoController::class, 'eliminar'])->name('productos.eliminarProd');
// Fin productos =============================================================

// Carrito =============================================================
Route::post('/cart-add', [CartController::class, 'add'])->name('cart.add');

Route::get('/cart-checkout', [CartController::class, 'cart'])->name('cart.checkout');

Route::post('/cart-remove', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/cart-update', [CartController::class, 'update'])->name('cart.update');

Route::post('/cart-clear', [CartController::class, 'clear'])->name('cart.clear');

Route::view('/cart', 'carrito.cart')->name('cart');
// Fin Carrito =============================================================

// Errores =====================================================================
Route::view('/error', 'errores.error');
// FIN Errores =====================================================================
