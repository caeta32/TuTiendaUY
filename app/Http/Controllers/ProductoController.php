<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\IFTTTHandler;

use App\Models\Pedido;
use App\Models\Envio;
use App\Models\EnviosEnEspera;
use App\Models\PedidosContienenProd;
use Aws\S3\S3Client;



class ProductoController extends Controller
{
    // Validaciones manuales debido a que $request->validate()
    // no envía los mensajes de error en la respuesta y no se encontró la razón.

    public function publicar(Request $request)
    {
        $datos = $this->requestProductoToArray($request); // Para posibles errores

        // Validación de la imagen y manejo de error en caso de que la validación no pase
        // con éxito.
        $errors = $this->validarImagen($request);
        if (!empty($errors)) {
            // Se cargan los datos para simular que el formulario recuerde los datos ingresados
            // EXCEPTO LA IMAGEN.
            foreach ($datos as $d)
                $request->session()->push('datos', $d);
            // Se cargan los errores.
            foreach ($errors as $e)
                $request->session()->push('errors', $e);

            return back();
        }

        if (!Producto::where('codigo', '=', $request->codigo)->exists()) {
            // Se obtiene usuario para saber su email.
            $usuario = $request->session()->get('usuario', 'default');
            // Se crea el producto.
            $producto = new Producto;
            $producto->codigo = $request->codigo;
            $producto->emailVendedor = $usuario->email;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->cantidadDisponible = $request->cantidadDisponible;
            // Se crea nombre del archivo.
            $fileName = $request->file('imagen')->getClientOriginalName();
            // Se almacena el archivo.
            $filePath = $request->file('imagen')->storeAs("storage/images", $fileName, 'public');
            $s3 = new S3Client([
                'version' => 'latest',
                'region'  => 'us-east-2',
                'credentials' => [
                    'key'    => 'AKIATYWMB2PSQ4MPMOPD',
                    'secret' => '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'
                ]
            ]);


            $bucketName = 'tutiendaimagenes';
            $key = basename($filePath);

// Upload a publicly accessible file. The file size and type are determined by the SDK.
            try {
                $result = $s3->putObject([
                    'Bucket' => $bucketName,
                    'Key'    => $key,
                    'Body'   => fopen($_FILES['imagen']['tmp_name'], 'rb'),
                    'ACL'    => 'public-read',
                ]);
                echo "Image uploaded successfully. Image path is: ". $result->get('ObjectURL');
            } catch (Aws\S3\Exception\S3Exception $e) {
                echo "There was an error uploading the file.\n";
                echo $e->getMessage();
            }
            //Se guarda la ruta en el producto.
            $producto->rutaImagen = $result->get('ObjectURL');
            // Se persiste en base de datos.
            $producto->save();
            return redirect()
                ->route('ventas.publicar', ['codigoProd' => $producto->codigo]);
        } else {
            // Se cargan los datos para simular que el formulario recuerde los datos ingresados
            // EXCEPTO LA IMAGEN.
            foreach ($datos as $d)
                $request->session()->push('datos', $d);
            // Se carga el error.
            $request->session()->push('errors', 'El código de Producto ingresado ya se encuentra registrado, es posible que ya haya publicado el producto o deba ingresar un código distinto. Inténtelo de nuevo y asegúrese de ingresar la imagen nuevamente.');
            return back();
        }
    }

    private function validarImagen(Request $request)
    {
        $validatorReqImg = Validator::make($request->all(), [
            'imagen' => 'required|image'
        ]);
        $validatorMimes = Validator::make($request->all(), [
            'imagen' => 'mimes:jpg,jpeg,png'
        ]);
        $validatorMaxSize = Validator::make($request->all(), [
            'imagen' => 'max:2048'
        ]);

        $errors = array();

        if ($validatorReqImg->fails()) {
            $errors[] = 'Debe ingresar una imagen.';
        }
        if ($validatorMimes->fails()) {
            $errors[] = 'El formato de la imagen debe ser .jpg, .jpeg o .png';
        }
        if ($validatorMaxSize->fails()) {
            $errors[] = 'El tamaño de la imagen no debe ser superior a 2 MB.';
        }

        return $errors;
    }

    private function requestProductoToArray($request)
    {
        $datos = array();
        $datos['nombre'] = $request->nombre;
        $datos['descripcion'] = $request->descripcion;
        $datos['codigo'] = $request->codigo;
        $datos['precio'] = $request->precio;
        $datos['cantidadDisponible'] = $request->cantidadDisponible;
        return $datos;
    }

    public function verTodos(Request $request)
    {
        $nombreprod = $request->get('productoselect');
        $mail = Session::get('usuario')['email'];
        $producto = DB::table('productos')->where([
            ['emailVendedor', '=', $mail],
            ['nombre', '=', $nombreprod]
        ])->first();
        Session::put('codProd', $producto->codigo);
        return view('productos.detalleproducto')->with('producto', $producto);
    }

    public function verBuscados(Request $request)
    {
        $buscado = $request->get('buscarprod');
        $productos = DB::table('productos')
            ->where('nombre', 'like', '%' . $buscado . '%')
            ->get();
        return View::make("productos.productosbusqueda")->with(array('productos' => $productos));
    }

    public function verGlobal(Request $request)
    {
        $nombreprod = $request->get('productoselect');
        $producto = DB::table('productos')->where([
            ['nombre', '=', $nombreprod]
        ])->first();
        Session::put('codProd', $producto->codigo);
        return view('productos.detalleproducto')->with('producto', $producto);
    }

    public function verGlobalDesdeInicio(Request $request)
    {
        $codprod = $request->get('codProd');
        $producto = DB::table('productos')->where([
            ['codigo', '=', $codprod]
        ])->first();
        Session::put('codProd', $producto->codigo);
        return view('productos.detalleproductoinicioAdmin')->with('producto', $producto);
    }


    public function verProductoDesdeInicio(Request $request)
    {
        $codprod = $request->get('codProd');
        $producto = DB::table('productos')->where([
            ['codigo', '=', $codprod]
        ])->first();
        return view('productos.detalleproductoinicio')->with('producto', $producto);
    }


    public function eliminar($codigo)
    {
        $mail = Session::get('usuario')['email'];
        if (Producto::where('codigo', '=', $codigo)->exists()) {
            $producto = Producto::find($codigo);
            $producto->delete();
            session()->put('message', 'success delete Producto');
            if ($mail == "administradores@tutienda.com") {
                return view('productos.verproductosAdmin');
            } else {
                return view('productos.verproductos');
            }
        } else {
            session()->push('errors', 'ERROR INTERNO: No parece haber un producto registrado con el código recibido.');
            return back();
        }
    }

    public function editar(Request $request)
    {
        $codigo = session()->get('codProd');
        $descprod = $request->get('descripcion');
        $precioprod = $request->get('precio');
        $stockprod = $request->get('cantidadDisponible');
        if (Producto::where('codigo', '=', $codigo)->exists()) {
            $producto = Producto::find($codigo);
            DB::table('productos')
                ->where('codigo', '=', $codigo)
                ->update(array("descripcion" => $descprod, "precio" => $precioprod, "cantidadDisponible" => $stockprod));
            session::pull('codProd');
            return view('productos.productoeditado');
        } else {
            session()->push('errors', 'ERROR INTERNO: No parece haber un producto registrado con el código recibido.');
            return back();
        }
    }


// COMPRAS =====================================================================================================
// Iniciar Compra de Producto ======================================================================================
    public function iniciarCompra(Request $request)
    {
        // Sin precio de envío por el momento.
        $shippingPrice = 0;
        $products = json_decode($request->products);
        $totalPrice = $request->totalPrice;
        $totalQuantity = $request->totalQuantity;
        $cartBool = $request->cartBool;
        $userLocation = Session::get('usuario')['direccion'];

        return view('compras.comprar')->with('orderData', ['userLocation' => $userLocation, 'shippingPrice' => $shippingPrice, 'totalPrice' => $totalPrice, 'totalQuantity' => $totalQuantity, 'cartBool' => $cartBool, 'products' => $products]);
    }
// FIN Iniciar Compra de Producto ======================================================================================

// Comprar Producto ======================================================================================
    public function comprar(Request $request)
    {
        // Donde se almacenarán los productos recuperados de la base.
        $productsDB = array();

        //Se reciben los datos.
        $receivedProducts = explode("|", $request->products);
        $data = json_decode($request->data, true);

        // Se lee cada dato de producto recibido.
        // Se realizan las comprobaciones necesarias y se reduce el stock disponible de cada producto registrado.
        $products = array();
        $cont = 0;
        try {
            // Se inicia transacción
            DB::beginTransaction();
            foreach ($receivedProducts as $item) {

                $products[$cont] = json_decode($item, true);
                // Verificación de si existe el producto.
                if (!Producto::where('codigo', '=', $products[$cont]['id'])->exists()) {
                    // ERROR
                    throw new Exception('failed product');
                }
                // Se recupera el producto de la base.
                $productsDB[$cont] = Producto::find($products[$cont]['id']);

                // Comprobación sobre si hay stock disponible para efectuar la compra del producto.
                if (!($productsDB[$cont]->cantidadDisponible > 0 && $productsDB[$cont]->cantidadDisponible >= intval($products[$cont]['quantity']))) {
                    // ERROR
                    throw new Exception('failed stock');
                }
                // Se reduce el stock del producto comprado y se almacena en base de datos.
                $productsDB[$cont]->cantidadDisponible = $productsDB[$cont]->cantidadDisponible - intval($products[$cont]['quantity']);
                $productsDB[$cont]->save();

                // Aumenta contador.
                $cont++;
            }

            $envio = new Envio;
            $envio->save();

            $envioEnEspera = new EnviosEnEspera;
            $envioEnEspera->idEnvio = $envio->id;
            $envioEnEspera->save();

            // Se obtiene usuario para saber su email.
            $usuario = $request->session()->get('usuario', 'default');

            $pedido = new Pedido;
            $pedido->emailComprador = $usuario->email;
            $pedido->idEnvio = $envio->id;
            $pedido->cantidadTotal = $data['totalQuantity'];
            $pedido->precioTotal = $data['totalPrice'];
            $pedido->save();

            // Se crea la relación del pedido con los productos.
            foreach ($products as $prod) {
                $ProductosEnPedido = new PedidosContienenProd;
                $ProductosEnPedido->idPedido = $pedido->id;
                $ProductosEnPedido->codigoProducto = $prod['id'];
                $ProductosEnPedido->cantidadPedida = $prod['quantity'];
                $ProductosEnPedido->save();
            }

            // Finalización exitosa.
            DB::commit();
            return view('compras.compraOK');

        } catch (Exception $e) {
            // Tratar excepción.
            DB::rollBack();
            return view('compras.errorCompra')->with('error', $e->getMessage());
        }


    }
}
// FIN Comprar Producto ======================================================================================

