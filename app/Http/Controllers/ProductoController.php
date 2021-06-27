<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Monolog\Handler\IFTTTHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as ExceptionPHPMailer;
use Aws\S3\S3Client;

// Modelos
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Envio;
use App\Models\EnviosEnEspera;
use App\Models\PedidosContienenProd;

class ProductoController extends Controller
{
    // Validaciones manuales debido a que $request->validate()
    // no envía los mensajes de error en la respuesta y no se encontró la razón.

// Publicar =================================================================================================
    public function publicar(Request $request) {
        $datos = $this->requestProductoToArray($request); // Para posibles errores

        // Validación de la imagen y manejo de error en caso de que la validación no pase
        // con éxito.
        $errors = $this->validarImagen($request);
        if(!empty($errors)) {
            // Se cargan los datos para simular que el formulario recuerde los datos ingresados
            // EXCEPTO LA IMAGEN.
            foreach($datos as $d)
                $request->session()->push('datos', $d);
            // Se cargan los errores.
            foreach($errors as $e)
                $request->session()->push('errors', $e);

            return back();
        }

        if(!Producto::where('codigo', '=', $request->codigo)->exists()) {
            // Se obtiene usuario para saber su email.
            $usuario = $request->session()->get('usuario', 'default');
            // Se crea el producto.
            $producto = new Producto;
            $producto->codigo = $request->codigo;
            $producto->emailVendedor = $usuario->email;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->categoria = $request->categoria;
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
            foreach($datos as $d)
                $request->session()->push('datos', $d);
            // Se carga el error.
            $request->session()->push('errors', 'El código de Producto ingresado ya se encuentra registrado, es posible que ya haya publicado el producto o deba ingresar un código distinto. Inténtelo de nuevo y asegúrese de ingresar la imagen nuevamente.');
            return back();
        }
    }
// FIN Publicar =================================================================================================

// Validar Imagen =================================================================================================
    private function validarImagen(Request $request) {
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
// FIN Validar Imagen ============================================================================================

// Convertir un request de producto a array ==================================================================
    private function requestProductoToArray($request) {
        $datos = array();
        $datos['nombre'] = $request->nombre;
        $datos['descripcion'] = $request->descripcion;
        $datos['codigo'] = $request->codigo;
        $datos['precio'] = $request->precio;
        $datos['cantidadDisponible'] = $request->cantidadDisponible;
        return $datos;
    }
// FIN Convertir un request de producto a array ==================================================================

// Ver todos los productos de un cliente========================================================================
    public function verTodos(Request $request){
        $nombreprod = $request->get('productoselect');
        $mail = Session::get('usuario')['email'];
        $producto = DB::table('productos')->where([
            ['emailVendedor','=', $mail],
            ['nombre','=', $nombreprod]
        ])->first();
        Session::put('codProd', $producto->codigo);
        return view('productos.detalleproducto')->with('producto', $producto);
    }
// FIN Ver todos los productos de un cliente========================================================================

//==========================================================================================================
    public function verBuscados(Request $request)
    {
        $buscado = $request->get('buscarprod');
        $productos = DB::table('productos')
            ->where('nombre', 'like', '%' . $buscado . '%')
            ->get();
        return View::make("productos.productosbusqueda")->with(array('productos' => $productos));
    }
//==========================================================================================================

//==========================================================================================================
    public function verGlobal(Request $request)
    {
        $nombreprod = $request->get('productoselect');
        $producto = DB::table('productos')->where([
            ['nombre', '=', $nombreprod]
        ])->first();
        Session::put('codProd', $producto->codigo);
        return view('productos.detalleproducto')->with('producto', $producto);
    }
//==========================================================================================================

//==========================================================================================================
    public function verGlobalDesdeInicio(Request $request)
    {
        $codprod = $request->get('codProd');
        $producto = DB::table('productos')->where([
            ['codigo', '=', $codprod]
        ])->first();
        Session::put('codProd', $producto->codigo);
        return view('productos.detalleproductoinicioAdmin')->with('producto', $producto);
    }
//==========================================================================================================

//==========================================================================================================
    public function verProductoDesdeInicio(Request $request)
    {
        $codprod = $request->get('codProd');
        $producto = DB::table('productos')->where([
            ['codigo', '=', $codprod]
        ])->first();
        return view('productos.detalleproductoinicio')->with('producto', $producto);
    }
//==========================================================================================================

//Eliminar Producto ======================================================================================
    public function eliminar($codigo) {
        $mail = Session::get('usuario')['email'];
        if(Producto::where('codigo', '=', $codigo)->exists()) {
            $producto = Producto::find($codigo);
            $producto->delete();
            session()->put('message', 'success delete Producto');
            if($mail == "administradores@tutienda.com") {
                return view('productos.verproductosAdmin');
            } else {
                return view('productos.verproductos');
            }
        } else {
            session()->push('errors', 'ERROR INTERNO: No parece haber un producto registrado con el código recibido.');
            return back();
        }
    }
// FIN Eliminar Producto ======================================================================================

// Editar Producto ======================================================================================
    public function editar(Request $request) {
        $codigo = session()->get('codProd');
        $descprod = $request->get('descripcion');
        $precioprod = $request->get('precio');
        $stockprod = $request->get('cantidadDisponible');
        if(Producto::where('codigo', '=', $codigo)->exists()) {
            $producto = Producto::find($codigo);
            DB::table('productos')
                ->where('codigo', '=', $codigo)
                ->update(array("descripcion"=>$descprod, "precio"=>$precioprod, "cantidadDisponible"=>$stockprod));
            session::pull('codProd');
            return view('productos.productoeditado');
        } else {
            session()->push('errors', 'ERROR INTERNO: No parece haber un producto registrado con el código recibido.');
            return back();
        }
    }
// FIN Editar Producto ======================================================================================

// Ver por Categoria ======================================================================================
    public function verPorCategoria(Request $request) {
        $categoria = $request->get('mybutton');
        return view('productos.productoscategoria')->with('categoria', $categoria);
    }
// FIN Ver por Categoria ======================================================================================

// COMPRAS =====================================================================================================
// Iniciar Compra de Producto ======================================================================================
    /*
    * Para el caso de compra directa, debe llegar un objeto eloquent formateado como json en una string.
    * Para el caso de compra desde el carrito, debe llegar un array formateado a json en una string con los productos
    * dados por la función correspondiente del carrito.
    */
    public function iniciarCompra(Request $request) {
        // Sin precio de envío por el momento.
        $shippingPrice = 0;

        // Se obtiene la información sobre desde dónde se está comprando (carrito o compra directa de un producto)
        // Y el o los productos correspondientes.
        $cartBool = $request->cartBool;
        $productsReceived = json_decode($request->products);

        // array donde se almacenará cada producto como un array asociativo para poder ser leído
        // por la vista de confirmación.
        $products = array();
        try{
            // Mapeo de los productos recibidos.
            // Para el caso del carrito, el control sobre si el usuario intenta comprar
            // algo que él mismo vende, se hace desde el carrito.
            if ($cartBool === 'true') { // Compra desde el carrito
                foreach ($productsReceived as $prod) {
                    $productNew = array();
                    $productNew['id'] = $prod->id;
                    $productNew['quantity'] = $prod->quantity;
                    $productNew['pathImage'] = $prod->attributes->pathImage;
                    $productNew['name'] = $prod->name;
                    $productNew['price'] = $prod->price;
                    $productNew['priceSum'] = \Cart::get($prod->id)->getPriceSum();
                    $products[] = $productNew;
                }
                // Se agrega el precio desde el valor recibido totalPrice, sólo para el caso de carrito.
                $totalPrice = $request->totalPrice;

            } elseif ($cartBool === 'false') { // Compra directa de un producto
                // Consulta para saber si el producto que está intentando comprar
                // el usuario lo está vendiendo él mismo.
                if(
                    Producto::where('codigo', '=', $productsReceived->codigo)
                            ->where('emailVendedor', '=', Session::get('usuario')['email'])
                            ->exists()
                ) {
                    //ERROR
                    throw new Exception('productSoldByMyself');
                }

                $productNew = array();
                $productNew['id'] = $productsReceived->codigo;
                $productNew['quantity'] = $request->totalQuantity;
                $productNew['pathImage'] = $productsReceived->rutaImagen;
                $productNew['name'] = $productsReceived->nombre;
                $productNew['price'] = $productsReceived->precio;
                // Se calcula el subtotal del producto.
                $productNew['priceSum'] = $request->totalQuantity * $productsReceived->precio;
                $products[] = $productNew;
                // Para el caso de la compra directa, el subtotal del producto será el mismo que el precio total
                // del pedido.
                $totalPrice = $productNew['priceSum'];
            } else {
                // ERROR
                throw new Exception('El string cartBool debe contener "true" o "false" únicamente, el valor que tiene es: '.$cartBool.' . Verificar desde dónde inicia la compra para que aparezca este error y verificar el valor que se le está dando a cartBool.');
            }

            $totalQuantity = $request->totalQuantity;
            $userLocation = Session::get('usuario')['direccion'];

            return view('compras.comprar')->with('orderData', ['userLocation' => $userLocation, 'shippingPrice' => $shippingPrice, 'totalPrice' => $totalPrice, 'totalQuantity' => $totalQuantity, 'cartBool' => $cartBool, 'products' => $products]);
        } catch (Exception $e) {
            return view('errores.error')->with('error', $e->getMessage());
        }
    }
// FIN Iniciar Compra de Producto ======================================================================================

// Comprar Producto ======================================================================================
    public function comprar(Request $request) {
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
                if(! Producto::where('codigo', '=', $products[$cont]['id'])->exists()) {
                    // ERROR
                    throw new Exception('failed product');
                }
                // Se recupera el producto de la base.
                $productsDB[$cont] = Producto::find($products[$cont]['id']);

                // Comprobación sobre si hay stock disponible para efectuar la compra del producto.
                if(! ($productsDB[$cont]->cantidadDisponible > 0 && $productsDB[$cont]->cantidadDisponible >= intval($products[$cont]['quantity']))) {
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
            // En caso de sesr una compra desde el carrito, se vacía el mismo luego de
            // finalizado el proceso de compra.
            if($data['cartBool'] === 'true')
                \Cart::clear();
            // Se envía mail de compra y se controla en caso de que ocurra algún problema.
            if(! $this->enviarCorreoDeCompra($usuario->email, $pedido->id, $pedido->cantidadTotal, $pedido->precioTotal, $pedido->created_at)) {
                throw new Exception('failed mail compra');
            }
            return redirect('/compra-exitosa');
        } catch (Exception $e) {
            // Tratar excepción.
            DB::rollBack();
            return view('errores.error')->with('error', $e->getMessage());
        }
    }
// FIN Comprar Producto ======================================================================================

// Enviar mail de Compra ======================================================================================
public function enviarCorreoDeCompra($emailUsuario, $idPedido, $cantidadProductos, $precioTotal, $fechaRealizado) {
    try {
        $mail = new PHPMailer(true); // Creacion instancia PHPMailer, true para poder manejar excepciones

        // Configuración del servidor
        $mail->SMTPDebug = 0;
        $mail->IsSMTP(); //Se especifica protocolo

        // servidor smtp
        // $mail->Host = "smtp.office365.com";
        $mail->Host = "smtp.gmail.com";

        // $mail->Username = 'tutiendaury@outlook.com';
        // $mail->Password = 'tallerPHP';
        // $mail->From = "tutiendaury@outlook.com";

        $mail->Username = 'tutiendaury@gmail.com';
        $mail->Password = '4GkRbWW8eALXWzS';
        $mail->From = "tutiendaury@gmail.com";

        $mail->SMTPSecure = 'tls'; //seguridad
        $mail->Port = 587; //puerto
        $mail->SMTPAuth = true;

        // Info de envío y recepción
        // $mail->setFrom('tutiendaury@outlook.com', 'Mailer');
        $mail->FromName = "TuTienda";
        $mail->AddAddress($emailUsuario);

        // Attachments
        $mail->AddEmbeddedImage('img/logo.png', 'img1');
        $mail->AddEmbeddedImage('img/logo-email-confirmacion-compra/package.png', 'img2');

        // Contenido
        $mail->Subject = "Compra Realizada";
        $mail->Priority = 1;
        $mail->IsHTML(true);
        // $mail->AltBody = 'Parece que no se admiten correos en HTML';
// Contenido del correo electrónico
        $mail->Body = $this->msjConfirmacionCompra($idPedido, $cantidadProductos, $precioTotal, $fechaRealizado);
// FIN Contenido del correo electrónico

        // Se retorna booleano.
        if($mail->Send())
            return true;
        else
            return false;
    } catch (ExceptionPHPMailer $e) {
        throw new Exception('Mensaje de correo electrónico no pudo ser enviado porque ha ocurrido una excepción, Mailer error: '.$e->getMessage());
    } catch (Exception $e) {
        throw new Exception('Mensaje de correo electrónico no pudo ser enviado porque ha ocurrido una excepción, Mailer error: '.$e->getMessage());
    }
}
// FIN Enviar mail de Compra ======================================================================================

// Método que devuelve el mensaje de correo para confirmar compra.  ================================================
private function msjConfirmacionCompra($idPedido, $cantidadProductos, $precioTotal, $fechaRealizado) {
    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html
        xmlns="http://www.w3.org/1999/xhtml"
        xmlns:v="urn:schemas-microsoft-com:vml"
        xmlns:o="urn:schemas-microsoft-com:office:office"
    >
        <head>
            <!--[if gte mso 9]>
                <xml>
                    <o:OfficeDocumentSettings>
                        <o:AllowPNG />
                        <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                </xml>
            <![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta name="x-apple-disable-message-reformatting" />
            <!--[if !mso]><!-->
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <!--<![endif]-->
            <title></title>
    
            <style type="text/css">
                table,
                td {
                    color: #000000;
                }
    
                a {
                    color: #236fa1;
                    text-decoration: underline;
                }
    
                @media only screen and (min-width: 620px) {
                    .u-row {
                        width: 600px !important;
                    }
    
                    .u-row .u-col {
                        vertical-align: top;
                    }
    
                    .u-row .u-col-100 {
                        width: 600px !important;
                    }
                }
    
                @media (max-width: 620px) {
                    .u-row-container {
                        max-width: 100% !important;
                        padding-left: 0px !important;
                        padding-right: 0px !important;
                    }
    
                    .u-row .u-col {
                        min-width: 320px !important;
                        max-width: 100% !important;
                        display: block !important;
                    }
    
                    .u-row {
                        width: calc(100% - 40px) !important;
                    }
    
                    .u-col {
                        width: 100% !important;
                    }
    
                    .u-col > div {
                        margin: 0 auto;
                    }
                }
    
                body {
                    margin: 0;
                    padding: 0;
                }
    
                table,
                tr,
                td {
                    vertical-align: top;
                    border-collapse: collapse;
                }
    
                p {
                    margin: 0;
                }
    
                .ie-container table,
                .mso-container table {
                    table-layout: fixed;
                }
    
                * {
                    line-height: inherit;
                }
    
                a[x-apple-data-detectors="true"] {
                    color: inherit !important;
                    text-decoration: none !important;
                }
            </style>
    
            <!--[if !mso]><!-->
            <link
                href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap"
                rel="stylesheet"
                type="text/css"
            />
            <!--<![endif]-->
        </head>
    
        <body
            class="clean-body"
            style="
                margin: 0;
                padding: 0;
                -webkit-text-size-adjust: 100%;
                background-color: #f9f9f9;
                color: #000000;
            "
        >
            <!--[if IE]><div class="ie-container"><![endif]-->
            <!--[if mso]><div class="mso-container"><![endif]-->
            <table
                style="
                    border-collapse: collapse;
                    table-layout: fixed;
                    border-spacing: 0;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    vertical-align: top;
                    min-width: 320px;
                    margin: 0 auto;
                    background-color: #f9f9f9;
                    width: 100%;
                "
                cellpadding="0"
                cellspacing="0"
            >
                <tbody>
                    <tr style="vertical-align: top">
                        <td
                            style="
                                word-break: break-word;
                                border-collapse: collapse !important;
                                vertical-align: top;
                            "
                        >
                            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f9f9f9;"><![endif]-->
    
                            <div
                                class="u-row-container"
                                style="padding: 0px; background-color: transparent"
                            >
                                <div
                                    class="u-row"
                                    style="
                                        margin: 0 auto;
                                        min-width: 320px;
                                        max-width: 600px;
                                        overflow-wrap: break-word;
                                        word-wrap: break-word;
                                        word-break: break-word;
                                        background-color: #ffa500;
                                    "
                                >
                                    <div
                                        style="
                                            border-collapse: collapse;
                                            display: table;
                                            width: 100%;
                                            background-color: transparent;
                                        "
                                    >
                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffa500;"><![endif]-->
    
                                        <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                                        <div
                                            class="u-col u-col-100"
                                            style="
                                                max-width: 320px;
                                                min-width: 600px;
                                                display: table-cell;
                                                vertical-align: top;
                                            "
                                        >
                                            <div style="width: 100% !important">
                                                <!--[if (!mso)&(!IE)]><!-->
                                                <div
                                                    style="
                                                        padding: 0px;
                                                        border-top: 0px solid
                                                            transparent;
                                                        border-left: 0px solid
                                                            transparent;
                                                        border-right: 0px solid
                                                            transparent;
                                                        border-bottom: 0px solid
                                                            transparent;
                                                    "
                                                >
                                                    <!--<![endif]-->
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 17px
                                                                            0px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <table
                                                                        width="100%"
                                                                        cellpadding="0"
                                                                        cellspacing="0"
                                                                        border="0"
                                                                    >
                                                                        <tr>
                                                                            <td
                                                                                style="
                                                                                    padding-right: 0px;
                                                                                    padding-left: 0px;
                                                                                "
                                                                                align="center"
                                                                            >
                                                                                <img
                                                                                    align="center"
                                                                                    border="0"
                                                                                    src="cid:img1"
                                                                                    alt="TuTienda"
                                                                                    title="TuTienda"
                                                                                    style="
                                                                                        outline: none;
                                                                                        text-decoration: none;
                                                                                        -ms-interpolation-mode: bicubic;
                                                                                        clear: both;
                                                                                        display: inline-block !important;
                                                                                        border: none;
                                                                                        height: auto;
                                                                                        float: none;
                                                                                        width: 11%;
                                                                                        max-width: 66px;
                                                                                    "
                                                                                    width="66"
                                                                                />
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <!--[if (!mso)&(!IE)]><!-->
                                                </div>
                                                <!--<![endif]-->
                                            </div>
                                        </div>
                                        <!--[if (mso)|(IE)]></td><![endif]-->
                                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                                    </div>
                                </div>
                            </div>
    
                            <div
                                class="u-row-container"
                                style="padding: 0px; background-color: transparent"
                            >
                                <div
                                    class="u-row"
                                    style="
                                        margin: 0 auto;
                                        min-width: 320px;
                                        max-width: 600px;
                                        overflow-wrap: break-word;
                                        word-wrap: break-word;
                                        word-break: break-word;
                                        background-color: #f3e8d9;
                                    "
                                >
                                    <div
                                        style="
                                            border-collapse: collapse;
                                            display: table;
                                            width: 100%;
                                            background-color: transparent;
                                        "
                                    >
                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #e8eced;"><![endif]-->
    
                                        <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                                        <div
                                            class="u-col u-col-100"
                                            style="
                                                max-width: 320px;
                                                min-width: 600px;
                                                display: table-cell;
                                                vertical-align: top;
                                            "
                                        >
                                            <div style="width: 100% !important">
                                                <!--[if (!mso)&(!IE)]><!-->
                                                <div
                                                    style="
                                                        padding: 0px;
                                                        border-top: 0px solid
                                                            transparent;
                                                        border-left: 0px solid
                                                            transparent;
                                                        border-right: 0px solid
                                                            transparent;
                                                        border-bottom: 0px solid
                                                            transparent;
                                                    "
                                                >
                                                    <!--<![endif]-->
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 44px
                                                                            10px
                                                                            10px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <table
                                                                        width="100%"
                                                                        cellpadding="0"
                                                                        cellspacing="0"
                                                                        border="0"
                                                                    >
                                                                        <tr>
                                                                            <td
                                                                                style="
                                                                                    padding-right: 0px;
                                                                                    padding-left: 0px;
                                                                                "
                                                                                align="center"
                                                                            >
                                                                                <img
                                                                                    align="center"
                                                                                    border="0"
                                                                                    src="cid:img2"
                                                                                    alt="Image"
                                                                                    title="Image"
                                                                                    style="
                                                                                        outline: none;
                                                                                        text-decoration: none;
                                                                                        -ms-interpolation-mode: bicubic;
                                                                                        clear: both;
                                                                                        display: inline-block !important;
                                                                                        border: none;
                                                                                        height: auto;
                                                                                        float: none;
                                                                                        width: 26%;
                                                                                        max-width: 150.8px;
                                                                                    "
                                                                                    width="150.8"
                                                                                />
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 10px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <div
                                                                        style="
                                                                            color: #34495e;
                                                                            line-height: 140%;
                                                                            text-align: center;
                                                                            word-wrap: break-word;
                                                                        "
                                                                    >
                                                                        <p
                                                                            style="
                                                                                font-size: 14px;
                                                                                line-height: 140%;
                                                                            "
                                                                        >
                                                                            <span
                                                                                style="
                                                                                    font-size: 26px;
                                                                                    line-height: 36.4px;
                                                                                "
                                                                                ><strong
                                                                                    ><span
                                                                                        style="
                                                                                            line-height: 36.4px;
                                                                                            font-size: 26px;
                                                                                        "
                                                                                        >&iexcl;Tu compra se ha realizado con &eacute;xito!<br /></span></strong
                                                                            ></span>
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 10px
                                                                            40px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <div
                                                                        style="
                                                                            color: #686d6d;
                                                                            line-height: 210%;
                                                                            text-align: center;
                                                                            word-wrap: break-word;
                                                                        "
                                                                    >
                                                                        <p
                                                                            style="
                                                                                font-size: 17px;
                                                                                line-height: 210%;
                                                                                font-weight: bold;
                                                                            "
                                                                        >
                                                                            El siguiente paso es que realices el pago de tu pedido, puedes hacerlo a trav&eacute;s de cualquier medio de tu preferencia.
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <table
                                                    style=""
                                                    role="presentation"
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                    width="100%"
                                                    border="0"
                                                >
                                                    <tbody>
                                                        <tr>
                                                            <td
                                                                style="
                                                                    overflow-wrap: break-word;
                                                                    word-break: break-word;
                                                                    padding: 10px
                                                                        50px;
                                                                "
                                                            >
                                                                <div
                                                                    style="
                                                                        color: #686d6d;
                                                                        line-height: 210%;
                                                                        text-align: center;
                                                                        word-wrap: break-word;
                                                                    "
                                                                >
                                                                    <p
                                                                        style="
                                                                            font-size: 14px;
                                                                            line-height: 210%;
                                                                        "
                                                                    >
                                                                        <span
                                                                            style="
                                                                                font-size: 16px;
                                                                                line-height: 33.6px;
                                                                            "
                                                                            >&iexcl;Y listo!</span>
                                                                        <br>
                                                                        <p
                                                                            style="
                                                                                font-size: 14px;
                                                                                line-height: 33.6px;
                                                                            "
                                                                            >Te estaremos avisando a esta direcci&oacute;n de email sobre el despacho de tu pedido, &iexcl;para que no te tengas que preocupar de nada!</p>
                                                                            
                                                                            <!-- <hr style="width: 85%; background: #9d9d9da2; border: none; height: 1px;"> -->
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            <table
                                            style=""
                                            role="presentation"
                                            cellpadding="0"
                                            cellspacing="0"
                                            width="100%"
                                            border="0">
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            style="
                                                                overflow-wrap: break-word;
                                                                word-break: break-word;
                                                            "
                                                        >
                                                            <div
                                                                style="
                                                                    word-wrap: break-word;
                                                                "
                                                            >
                                                                <div
                                                                    style="
                                                                        display: flex;
                                                                    "
                                                                >
                                                                    <div style="
                                                                        background: #e6e6e6;
                                                                        border: 1px solid #c9c9c98a;
                                                                        border-radius: 10px;
                                                                        width:53%;
                                                                        max-width:60%;
                                                                        padding: 1.8rem 1.8rem 1.5rem 1.8rem;
                                                                        margin-bottom: 1rem;
                                                                        margin-top: 1.4rem;
                                                                        margin-right: auto;
                                                                        margin-left: auto;
                                                                        /* padding: 1.5rem 2.3rem 1.3rem 2.3rem; */
                                                                        text-align: center;
                                                                    ">
                                                                        <p style="
                                                                            color: #34495e;
                                                                            font-weight: bold;
                                                                            margin-bottom: 0.8rem;
                                                                            font-size: 16px;
                                                                        ">
                                                                            INFORMACI&Oacute;N DEL PEDIDO
                                                                        </p>
                                                                        <div style="font-size: 12px; color: #686d6d; line-height: 30.6px;">
                                                                            <span style="
                                                                            font-weight: bold;
                                                                            ">
                                                                            ID del Pedido:
                                                                            </span> '.$idPedido.'<br>
                                                                            <span style="
                                                                            font-weight: bold;
                                                                            ">
                                                                            Cantidad de Productos:
                                                                            </span> '.$cantidadProductos.'<br>
                                                                            <span style="
                                                                            font-weight: bold;
                                                                            ">
                                                                            Precio Total:
                                                                            </span> $ '.$precioTotal.'<br>
                                                                            <span style="
                                                                            font-weight: bold;
                                                                            ">
                                                                            Pedido Realizado:
                                                                            </span> <div>'.$fechaRealizado.'</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                                <table
                                                style=""
                                                role="presentation"
                                                cellpadding="0"
                                                cellspacing="0"
                                                width="100%"
                                                border="0"
                                            >
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            style="
                                                                overflow-wrap: break-word;
                                                                word-break: break-word;
                                                                padding: 10px
                                                                    33px;
                                                            "
                                                            align="left"
                                                        >
                                                            <div
                                                                style="
                                                                    color: #686d6d;
                                                                    line-height: 210%;
                                                                    text-align: center;
                                                                    word-wrap: break-word;
                                                                "
                                                            >
                                                                <p
                                                                    style="
                                                                        font-size: 14px;
                                                                        line-height: 210%;
                                                                    "
                                                                >
                                                                    <p
                                                                    style="
                                                                        font-size: 12px;
                                                                        
                                                                        color: #787e7e;
                                                                    "
                                                                    >Puedes consultar toda la informaci&oacute;n de tu pedido a trav&eacute;s de la secci&oacute;n de compras en el panel de control de tu cuenta, presionando sobre tu nombre en la barra superior del sitio.</p>
                                                                </p>
                                                                <p
                                                                    style="
                                                                        font-size: 14px;
                                                                        line-height: 210%;
                                                                        
                                                                    "
                                                                >
                                                                    
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
    
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 10px 33px;
                                                                    "
                                                                >
                                                                    <div
                                                                        style="
                                                                            color: #34495e;
                                                                            line-height: 140%;
                                                                            text-align: center;
                                                                            word-wrap: break-word;
                                                                        "
                                                                    >
                                                                        <p
                                                                            style="
                                                                                font-size: 14px;
                                                                                line-height: 140%;
                                                                            "
                                                                        >
                                                                            <span
                                                                                style="
                                                                                    font-size: 26px;
                                                                                    line-height: 36.4px;
                                                                                "
                                                                                ><strong
                                                                                    ><span
                                                                                        style="
                                                                                            line-height: 36.4px;
                                                                                            font-size: 21px;
                                                                                        "
                                                                            >&iexcl;Te invitamos a que continues echando un vistazo!</span>
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 22px
                                                                            10px
                                                                            44px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <div
                                                                        align="center"
                                                                    >
                                                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:49px; v-text-anchor:middle; width:158px;" arcsize="8%" stroke="f" fillcolor="#ffb200"><w:anchorlock/><center style="color:#FFFFFF;"><![endif]-->
                                                                        <a
                                                                            href="https://tutiendauy.herokuapp.com/"
                                                                            target="_blank"
                                                                            style="
                                                                                box-sizing: border-box;
                                                                                display: inline-block;
                                                                                text-decoration: none;
                                                                                -webkit-text-size-adjust: none;
                                                                                text-align: center;
                                                                                color: #ffffff;
                                                                                background-color: #ffb200;
                                                                                border-radius: 4px;
                                                                                -webkit-border-radius: 4px;
                                                                                -moz-border-radius: 4px;
                                                                                width: auto;
                                                                                max-width: 100%;
                                                                                overflow-wrap: break-word;
                                                                                word-break: break-word;
                                                                                word-wrap: break-word;
                                                                                mso-border-alt: none;
                                                                            "
                                                                        >
                                                                            <span
                                                                                style="
                                                                                    display: block;
                                                                                    padding: 15px
                                                                                        33px;
                                                                                    line-height: 120%;
                                                                                "
                                                                                ><span
                                                                                    style="
                                                                                        font-size: 16px;
                                                                                        line-height: 19.2px;
                                                                                    "
                                                                                    ><strong
                                                                                        ><span
                                                                                            style="
                                                                                                line-height: 19.2px;
                                                                                                font-size: 16px;
                                                                                            "
                                                                                            >Ir
                                                                                            a
                                                                                            TuTienda</span
                                                                                        ></strong
                                                                                    ></span
                                                                                ></span
                                                                            >
                                                                        </a>
                                                                        <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <!--[if (!mso)&(!IE)]><!-->
                                                </div>
                                                <!--<![endif]-->
                                            </div>
                                        </div>
                                        <!--[if (mso)|(IE)]></td><![endif]-->
                                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                                    </div>
                                </div>
                            </div>
    
                            <div
                                class="u-row-container"
                                style="padding: 0px; background-color: transparent"
                            >
                                <div
                                    class="u-row"
                                    style="
                                        margin: 0 auto;
                                        min-width: 320px;
                                        max-width: 600px;
                                        overflow-wrap: break-word;
                                        word-wrap: break-word;
                                        word-break: break-word;
                                        background-color: #ffa500;
                                    "
                                >
                                    <div
                                        style="
                                            border-collapse: collapse;
                                            display: table;
                                            width: 100%;
                                            background-color: transparent;
                                        "
                                    >
                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffa500;"><![endif]-->
    
                                        <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                                        <div
                                            class="u-col u-col-100"
                                            style="
                                                max-width: 320px;
                                                min-width: 600px;
                                                display: table-cell;
                                                vertical-align: top;
                                            "
                                        >
                                            <div style="width: 100% !important">
                                                <!--[if (!mso)&(!IE)]><!--><div
                                                    style="
                                                        padding: 0px;
                                                        border-top: 0px solid
                                                            transparent;
                                                        border-left: 0px solid
                                                            transparent;
                                                        border-right: 0px solid
                                                            transparent;
                                                        border-bottom: 0px solid
                                                            transparent;
                                                    "
                                                ><!--<![endif]-->
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 44px
                                                                            44px
                                                                            10px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <div
                                                                        style="
                                                                            color: #000000;
                                                                            line-height: 140%;
                                                                            text-align: center;
                                                                            word-wrap: break-word;
                                                                        "
                                                                    >
                                                                        <p
                                                                            style="
                                                                                font-size: 14px;
                                                                                line-height: 140%;
                                                                            "
                                                                        >
                                                                            <span
                                                                                style="
                                                                                    font-size: 24px;
                                                                                    line-height: 33.6px;
                                                                                "
                                                                                ><strong
                                                                                    ><span
                                                                                        style="
                                                                                            line-height: 33.6px;
                                                                                            font-size: 24px;
                                                                                        "
                                                                                        >&iquest;Por
                                                                                        qu&eacute;
                                                                                        no
                                                                                        nos
                                                                                        recomiendas
                                                                                        a
                                                                                        tus
                                                                                        amigos?<br /></span></strong
                                                                            ></span>
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 22px
                                                                            10px
                                                                            44px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <div
                                                                        align="center"
                                                                    >
                                                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:49px; v-text-anchor:middle; width:258px;" arcsize="8%" stroke="f" fillcolor="#0f7d81"><w:anchorlock/><center style="color:#c5d5d6;"><![endif]-->
                                                                        <a
                                                                            href=""
                                                                            target="_blank"
                                                                            style="
                                                                                box-sizing: border-box;
                                                                                display: inline-block;
                                                                                text-decoration: none;
                                                                                -webkit-text-size-adjust: none;
                                                                                text-align: center;
                                                                                color: #c5d5d6;
                                                                                background-color: #0f7d81;
                                                                                border-radius: 4px;
                                                                                -webkit-border-radius: 4px;
                                                                                -moz-border-radius: 4px;
                                                                                width: auto;
                                                                                max-width: 100%;
                                                                                overflow-wrap: break-word;
                                                                                word-break: break-word;
                                                                                word-wrap: break-word;
                                                                                mso-border-alt: none;
                                                                            "
                                                                        >
                                                                            <span
                                                                                style="
                                                                                    display: block;
                                                                                    padding: 15px
                                                                                        33px;
                                                                                    line-height: 120%;
                                                                                "
                                                                                ><span
                                                                                    style="
                                                                                        font-size: 16px;
                                                                                        line-height: 19.2px;
                                                                                    "
                                                                                    ><span
                                                                                        style="
                                                                                            line-height: 19.2px;
                                                                                            font-size: 16px;
                                                                                        "
                                                                                        >Recomienda
                                                                                        a
                                                                                        TuTienda</span
                                                                                    ></span
                                                                                ></span
                                                                            >
                                                                        </a>
                                                                        <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <!--[if (!mso)&(!IE)]><!-->
                                                </div>
                                                <!--<![endif]-->
                                            </div>
                                        </div>
                                        <!--[if (mso)|(IE)]></td><![endif]-->
                                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                                    </div>
                                </div>
                            </div>
    
                            <div
                                class="u-row-container"
                                style="
                                    padding: 0px 0px 4px;
                                    background-color: transparent;
                                "
                            >
                                <div
                                    class="u-row"
                                    style="
                                        margin: 0 auto;
                                        min-width: 320px;
                                        max-width: 600px;
                                        overflow-wrap: break-word;
                                        word-wrap: break-word;
                                        word-break: break-word;
                                        background-color: #ffffff;
                                    "
                                >
                                    <div
                                        style="
                                            border-collapse: collapse;
                                            display: table;
                                            width: 100%;
                                            background-color: transparent;
                                        "
                                    >
                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px 0px 4px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->
    
                                        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffa500;width: 600px;padding: 0px;border-top: 1px solid #000000;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                                        <div
                                            class="u-col u-col-100"
                                            style="
                                                max-width: 320px;
                                                min-width: 600px;
                                                display: table-cell;
                                                vertical-align: top;
                                            "
                                        >
                                            <div
                                                style="
                                                    background-color: #ffa500;
                                                    width: 100% !important;
                                                "
                                            >
                                                <!--[if (!mso)&(!IE)]><!--><div
                                                    style="
                                                        padding: 0px;
                                                        border-top: 1px solid
                                                            #000000;
                                                        border-left: 0px solid
                                                            transparent;
                                                        border-right: 0px solid
                                                            transparent;
                                                        border-bottom: 0px solid
                                                            transparent;
                                                    "
                                                ><!--<![endif]-->
                                                    <table
                                                        style=""
                                                        role="presentation"
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        border="0"
                                                    >
                                                        <tbody>
                                                            <tr>
                                                                <td
                                                                    style="
                                                                        overflow-wrap: break-word;
                                                                        word-break: break-word;
                                                                        padding: 22px
                                                                            44px;
                                                                    "
                                                                    align="left"
                                                                >
                                                                    <div
                                                                        style="
                                                                            color: #000000;
                                                                            line-height: 140%;
                                                                            text-align: center;
                                                                            word-wrap: break-word;
                                                                        "
                                                                    >
                                                                        <p
                                                                            style="
                                                                                font-size: 14px;
                                                                                line-height: 140%;
                                                                            "
                                                                        >
                                                                            &nbsp;Copyright
                                                                            &copy;
                                                                            2021
                                                                            TuTienda
                                                                            Uruguay,
                                                                            Inc.
                                                                            Todos
                                                                            los
                                                                            derechos
                                                                            reservados.
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
    
                                                    <!--[if (!mso)&(!IE)]><!-->
                                                </div>
                                                <!--<![endif]-->
                                            </div>
                                        </div>
                                        <!--[if (mso)|(IE)]></td><![endif]-->
                                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                                    </div>
                                </div>
                            </div>
    
                            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--[if mso]></div><![endif]-->
            <!--[if IE]></div><![endif]-->
        </body>
    </html>';
}
// FIN Método que devuelve el mensaje de correo para confirmar compra. ================================================
}
