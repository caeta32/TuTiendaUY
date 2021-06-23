<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Cliente;
use App\Models\Confirmacion;
use App\Models\Pedido;
use App\Models\Usuario;
use Exception;

class ClienteController extends Controller
{
    public function verVentasDeUsuario() {
      try {
        // Consulta SQL para los pedidos cuyo estado de envío es en espera y están asociados 
        // al usuario de la sesión con el rol de comprador
        $pedidosEnEspera = DB::table('pedidos_contienen_prods as pedidos_prods')
                      ->join('pedidos as p', 'pedidos_prods.idPedido', '=', 'p.id')
                      ->join('productos as prods', function ($join) {
                          // Se obtiene usuario para saber su email.
                          $usuario = session()->get('usuario', 'default');

                          $join->on('pedidos_prods.codigoProducto', '=', 'prods.codigo')
                              ->where('prods.emailVendedor', '=', $usuario->email);
                      })
                      ->join('clientes as c', 'c.email', '=', 'p.emailComprador')
                      ->join('envios_en_esperas as e', 'e.idEnvio', '=', 'p.idEnvio')
                      ->whereNotIn('p.idEnvio', function ($query) {
                        $query->select('env_desp.idEnvio')->from('envio_despachados as env_desp');
                      })
                      ->select(
                        'pedidos_prods.cantidadPedida',
                        'p.id as idPedido',
                        'p.created_at as fechaPedido',
                        'p.emailComprador',                       
                        'c.nombre as nombreUsuario',
                        'e.idEnvio as idEnvio',
                        'e.created_at as fechaEnvio',
                        'prods.nombre as nombreProducto', 
                        'prods.precio', 
                        'prods.rutaImagen'
                      )
                      ->get();
        // Consulta SQL para los pedidos cuyo estado de envío es despachado y están asociados 
        // al usuario de la sesión con el rol de comprador
        $pedidosDespachados = DB::table('pedidos_contienen_prods as pedidos_prods')
                        ->join('pedidos as p', 'pedidos_prods.idPedido', '=', 'p.id')
                        ->join('productos as prods', function ($join) {
                            // Se obtiene usuario para saber su email.
                            $usuario = session()->get('usuario', 'default');

                            $join->on('pedidos_prods.codigoProducto', '=', 'prods.codigo')
                                ->where('prods.emailVendedor', '=', $usuario->email);
                        })
                        ->join('envio_despachados as e', 'e.idEnvio', '=', 'p.idEnvio')
                        ->join('clientes as c', 'c.email', '=', 'p.emailComprador')
                        ->select(
                          'pedidos_prods.cantidadPedida',
                          'p.id as idPedido',
                          'p.created_at as fechaPedido',
                          'p.emailComprador',
                          'c.nombre as nombreUsuario',
                          'e.idEnvio as idEnvio',
                          'e.created_at as fechaEnvio',
                          'prods.nombre as nombreProducto', 
                          'prods.precio', 
                          'prods.rutaImagen'
                        )
                        ->get();
        return view('ventas.verVentas')->with('data', ['pedidosEnEspera' => $pedidosEnEspera, 'pedidosDespachados' => $pedidosDespachados]);
      } catch (Exception $e) {
        // Tratar excepción
        return view('errores.errorIframe')->with('error', $e->getMessage());
      }
    }
    
    public function login(Request $request)
    {
        $usuario = Cliente::where(['email' => $request->email])->first();
        // Chequea si el usuario existe, su password y si esta confirmado.
        if (!$usuario || !Hash::check($request->pass, $usuario->pass) || $usuario->confirmado == 0) {
            return redirect('/loginerror');
        } else {
            if ($usuario->email == "administradores@tutienda.com") {
                $request->session()->put('usuario', $usuario);
                return redirect('/administradores');
            } else {
                $request->session()->put('usuario', $usuario);
                return redirect('/principal');
            }
        }
    }

    public function registro(Request $request)
    {
        // Creación Usuario
        $usu = new Usuario;
        $usu->email = $request->email;
        $usu->contrasenia = $request->pass;
        $usu->apellido = $request->apellido;
        $usu->nombre = $request->nombre;
        $usu->confirmado = 0;

        //Creación Cliente
        $usuario = new Cliente;
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->fecha = $request->fecha;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->postal = $request->postal;
        $usuario->pass = $request->pass;
        $passconf = $request->passconf;
        $usuario->confirmado = 0;

        // Chequea si el usuario existe
        if ($usuario->pass == $passconf) {
            $usuarioExiste = Cliente::where(['email' => $request->email])->first();
            if ($usuarioExiste) {
                return redirect('/registererroremail');
            } else {
                $usu->contrasenia = Hash::make($request->pass);
                $usuario->pass = Hash::make($request->pass);
                $usu->save();
                $usuario->save();

                // Creacion instancia PHPMailer, para mandar confirmacion al usuario registrado
                $mail = new PHPMailer();
                $mail->IsSMTP();

                //Configuracion servidor mail
                $mail->From = "tutiendaury@gmail.com"; //remitente
                $mail->FromName = "TuTienda";
                $mail->Priority = 1;
                $mail->SMTPAuth = true;
                $mail->IsHTML(true);
                $mail->SMTPSecure = 'tls'; //seguridad
                $mail->Host = "smtp.gmail.com"; // servidor smtp
                $mail->Port = 587; //puerto
                $mail->Username = 'tutiendaury@gmail.com'; //nombre usuario
                $mail->Password = '4GkRbWW8eALXWzS'; //contraseña
                $mail->AddAddress($usuario->email);
                $mail->Subject = "Cuenta Creada";
                $mail->AddEmbeddedImage('img/logo.png', 'img1');
                $mail->AddEmbeddedImage('img/checked.png', 'img2');

                //Contenido del mail enviado
                $mail->Body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<!--[if gte mso 9]>
<xml>
  <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
  </o:OfficeDocumentSettings>
</xml>
<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
  <title></title>
  
    <style type="text/css">
      table, td { color: #000000; } a { color: #236fa1; text-decoration: underline; }
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
  
  

<!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" type="text/css"><!--<![endif]-->

</head>

<body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #f9f9f9;color: #000000">
  <!--[if IE]><div class="ie-container"><![endif]-->
  <!--[if mso]><div class="mso-container"><![endif]-->
  <table style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #f9f9f9;width:100%" cellpadding="0" cellspacing="0">
  <tbody>
  <tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f9f9f9;"><![endif]-->
    

<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffa500;">
    <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
      <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffa500;"><![endif]-->
      
<!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
<div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
  <div style="width: 100% !important;">
  <!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
  
<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:17px 0px;" align="left">
        
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding-right: 0px;padding-left: 0px;" align="center">
      
      <img align="center" border="0" src="cid:img1" alt="TuTienda" title="TuTienda" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 11%;max-width: 66px;" width="66"/>
      
    </td>
  </tr>
</table>

      </td>
    </tr>
  </tbody>
</table>

  <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
  </div>
</div>
<!--[if (mso)|(IE)]></td><![endif]-->
      <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
    </div>
  </div>
</div>



<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #e8eced;">
    <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
      <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #e8eced;"><![endif]-->
      
<!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
<div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
  <div style="width: 100% !important;">
  <!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
  
<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:44px 10px 10px;" align="left">
        
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding-right: 0px;padding-left: 0px;" align="center">
      
      <img align="center" border="0" src="cid:img2" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 26%;max-width: 150.8px;" width="150.8"/>
      
    </td>
  </tr>
</table>

      </td>
    </tr>
  </tbody>
</table>

<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;" align="left">
        
  <div style="color: #34495e; line-height: 140%; text-align: center; word-wrap: break-word;">
    <p style="font-size: 14px; line-height: 140%;"><span style="font-size: 26px; line-height: 36.4px;"><strong><span style="line-height: 36.4px; font-size: 26px;">&iexcl;Tu cuenta ha sido creada!<br /></span></strong></span></p>
  </div>

      </td>
    </tr>
  </tbody>
</table>

<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px 33px;" align="left">
        
  <div style="color: #686d6d; line-height: 210%; text-align: center; word-wrap: break-word;">
    <p style="font-size: 14px; line-height: 210%;"><span style="font-size: 16px; line-height: 33.6px;">Estimado usuario,</span></p>
<p style="font-size: 14px; line-height: 210%;">Para confirmar tu cuenta, ingresa este codigo en la pagina de inicio:</p>
  </div>

      </td>
    </tr>
  </tbody>
</table>
<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;" align="left">
        
  <div style="color: #34495e; line-height: 140%; text-align: center; word-wrap: break-word;">
    <p style="font-size: 14px; line-height: 140%;"><span style="font-size: 26px; line-height: 36.4px;"><strong><span style="line-height: 36.4px; font-size: 26px;">'
                ?><?php
                $codigorand = mt_rand(0, 999999);
                $mail->Body .= $codigorand . '<br /></span></strong></span></p>
  </div>

      </td>
    </tr>
  </tbody>
</table>
<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:22px 10px 44px;" align="left">
        
<div align="center">
  <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:49px; v-text-anchor:middle; width:158px;" arcsize="8%" stroke="f" fillcolor="#ffb200"><w:anchorlock/><center style="color:#FFFFFF;"><![endif]-->
    <a href="" target="_blank" style="box-sizing: border-box;display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #ffb200; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
      <span style="display:block;padding:15px 33px;line-height:120%;"><span style="font-size: 16px; line-height: 19.2px;"><strong><span style="line-height: 19.2px; font-size: 16px;">Ir a TuTienda</span></strong></span></span>
    </a>
  <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
</div>

      </td>
    </tr>
  </tbody>
</table>

  <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
  </div>
</div>
<!--[if (mso)|(IE)]></td><![endif]-->
      <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
    </div>
  </div>
</div>



<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffa500;">
    <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
      <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffa500;"><![endif]-->
      
<!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
<div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
  <div style="width: 100% !important;">
  <!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
  
<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:44px 44px 10px;" align="left">
        
  <div style="color: #000000; line-height: 140%; text-align: center; word-wrap: break-word;">
    <p style="font-size: 14px; line-height: 140%;"><span style="font-size: 24px; line-height: 33.6px;"><strong><span style="line-height: 33.6px; font-size: 24px;">¿Por que no nos recomiendas a tus amigos?<br /></span></strong></span></p>
  </div>

      </td>
    </tr>
  </tbody>
</table>

<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:22px 10px 44px;" align="left">
        
<div align="center">
  <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:49px; v-text-anchor:middle; width:258px;" arcsize="8%" stroke="f" fillcolor="#0f7d81"><w:anchorlock/><center style="color:#c5d5d6;"><![endif]-->
    <a href="" target="_blank" style="box-sizing: border-box;display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #c5d5d6; background-color: #0f7d81; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
      <span style="display:block;padding:15px 33px;line-height:120%;"><span style="font-size: 16px; line-height: 19.2px;"><span style="line-height: 19.2px; font-size: 16px;">Recomienda a TuTienda</span></span></span>
    </a>
  <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
</div>

      </td>
    </tr>
  </tbody>
</table>

  <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
  </div>
</div>
<!--[if (mso)|(IE)]></td><![endif]-->
      <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
    </div>
  </div>
</div>



<div class="u-row-container" style="padding: 0px 0px 4px;background-color: transparent">
  <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
    <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
      <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px 0px 4px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->
      
<!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffa500;width: 600px;padding: 0px;border-top: 1px solid #000000;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
<div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
  <div style="background-color: #ffa500;width: 100% !important;">
  <!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 1px solid #000000;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
  
<table style="" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:22px 44px;" align="left">
        
  <div style="color: #000000; line-height: 140%; text-align: center; word-wrap: break-word;">
    <p style="font-size: 14px; line-height: 140%;">&nbsp;Copyright &copy; 2021 TuTienda Uruguay, Inc. Todos los derechos reservados. </p>
  </div>

      </td>
    </tr>
  </tbody>
</table>

  <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
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

                if ($mail->Send()) {
                    echo '<script type="text/javascript">
                    alert("Cuenta Creada Correctamente");
                    </script>';
                } else {
                    echo '<script type="text/javascript">
                    alert("Tuvimos un problema al encontrar tu mail, prueba nuevamente");
                    </script>';
                }

                $userEnEspera = new Confirmacion();
                $userEnEspera->email = $usuario->email;
                $userEnEspera->codigo = $codigorand;
                $userEnEspera->save();
                return redirect('/confirmar');
            }
        } else {
            return redirect('/registererrorpass');
        }
    }

    //Manda el usuario creado a espera, hasta que se confirme el mismo
    public function confirmar(Request $request)
    {
        $confirmacion = Confirmacion::where(['codigo' => $request->codigo])->first();
        if ($confirmacion) {
            $cliente = Cliente::where(['email' => $confirmacion->email])->first();
            $usuario = Usuario::where(['email' => $confirmacion->email])->first();
            $cliente->confirmado = 1;
            $cliente->save();
            $usuario->confirmado = 1;
            $usuario->save();
            DB::table('confirmacion')->where('email', '=', $confirmacion->email)->delete();
            return redirect('/login');
        } else {
            return redirect('/confirmarerror');
        }
    }
}
