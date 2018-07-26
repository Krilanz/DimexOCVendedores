<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.1.1/animate.css">
    <title>Login - Dimex OC Vendedores </title>
  </head>
  <body>

    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Dimex - OC Vendedores</h1>
      </div>
      <div class="login-box">
        <form class="login-form" method="post">
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>LOG IN</h3>
          
          
         <?php if (isset($errMSG)) { ?>
                <div class="form-group">
                    <div class="alert alert-danger">
                        <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                    </div>
                </div>
         <?php } ?>

          
          <div class="form-group">
            <label class="control-label">USUARIO</label>
            <input id="usuario" name="usuario" class="form-control" type="text" placeholder="Usuario" required autofocus>
          </div>
          <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input id="password"  name="password" class="form-control" type="password" placeholder="Password" required>
          </div>
          <div class="form-group">
            <div class="utility">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox"><span class="label-text">Recordarme</span>
                </label>
              </div>
              <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Olvide mi Contraseña</a></p>
            </div>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block" type="submit" name="login"><i class="fa fa-sign-in fa-lg fa-fw"></i>LOG IN</button>
          </div>
          

            
        </form>
        <form class="forget-form" action="login.php">
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Olvide mi Contraseña</h3>
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input class="form-control" type="text" placeholder="Email">
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
          </div>
          <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Volver al Login</a></p>
          </div>
        </form>
      </div>
    </section>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    
    <!-- Essential javascripts for application to work-->
    <script src="js/sweetalert2.all.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://unpkg.com/promise-polyfill@7.1.0/dist/promise.min.js"></script>


    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    
    <script type="text/javascript">
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>

<?php
session_start();
require_once 'config.php';
// if session is set direct to index
if (isset($_SESSION['userId'])) {
    header("Location: ordenesDeCompra.php");
    exit;
}
    
if (isset($_POST['login'])) {

    //App Vendedores
    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appVendedor_id, $appVendedor_token);

    // Output the title of each item
    
    $logincheck = PodioItem::filter($appVendedor_id, [
        'filters' => [
            // replace 123456 with field ID for your field with email type
            'usuario' => $_POST['usuario'], 
            'contrasena-2' => $_POST['password'],
                
        ]
    ]);
    
    IF($logincheck -> filtered > 0)
    {

        foreach ($logincheck as $item) {
            $_SESSION['userId'] = $item->item_id ;
            $_SESSION['userNombre'] = $item->fields["nombre"]-> values;
            $_SESSION['userProfesion']= $item->fields["cargo"] != null ? $item->fields["cargo"] -> values : "";

            if( $item->fields["foto"] == NULL){
                $_SESSION['userImagen']= "images/defaultavatar_large.png";
            }else{
                $_SESSION['userImagen']= $item->fields["foto"] -> values[0]-> link;
            }
            break;
        }
        header("Location: ordenesDeCompra.php");
        exit;
    }else{
        echo '<script language="javascript">';
        echo 'swal("Error!","Usuario o contraseña incorrectos.","error")';
        echo '</script>';
        //$errMSG = "Email o contraseña incorrectos.";ar
    }
}


?>