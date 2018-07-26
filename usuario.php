<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

require_once 'podio-php/PodioAPI.php';



Podio::setup($client_id, $client_secret);
Podio::authenticate_with_app($appVendedor_id, $appVendedor_token);

$userItem = PodioItem::get_basic($_SESSION['userId']); // Get item with item_id=123

//$userEmail = $userItem->fields["e-mail"] != null ? $userItem->fields["e-mail"] -> values : "";
$userNombre = $userItem->fields["nombre"] != null ? $userItem->fields["nombre"] -> values : ""; 
$userProfesion =   $userItem->fields["cargo"] != null ? $userItem->fields["cargo"] -> values : ""; 

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Perfil de Usuario</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class="app sidebar-mini rtl">
    
   <?php
    include('_layout.html');
   ?>
    <main class="app-content">
      <div class="row user">
        <div class="col-md-12">
          <div class="profile">
            <div class="info"><img class="user-img" src="<?php echo $_SESSION['userImagen'] ?>">
              <h4><?php print $_SESSION['userNombre'] ?></h4>
              <p><?php print $_SESSION['userProfesion'] ?></p>
            </div>
            <div class="cover-image"></div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="tile p-0">
            <ul class="nav flex-column nav-tabs user-tabs">
              <!--<li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Timeline</a></li>-->
              <li class="nav-item"><a class="nav-link active" href="#user-settings" data-toggle="tab">Datos Personales</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-9">
          <div class="tab-content">
            <!--<div class="tab-pane active" id="user-timeline">
              <div class="timeline-post">
                <div class="post-media"><a href="#"><img src="<?php echo $_SESSION['userImagen'] ?>"></a>
                  <div class="content">
                    <h5><a href="#"><?php print $_SESSION['userNombre'] ?></a></h5>
                    <p class="text-muted"><small>2 Enero 9:30</small></p>
                  </div>
                </div>
                <div class="post-content">
                  <p></p>
                </div>
                <ul class="post-utility">
                  <li class="likes"><a href="#"><i class="fa fa-fw fa-lg fa-thumbs-o-up"></i>Me Gusta</a></li>
                  <li class="shares"><a href="#"><i class="fa fa-fw fa-lg fa-share"></i>Compartir</a></li>
                  <li class="comments"><i class="fa fa-fw fa-lg fa-comment-o"></i> 5 Comentarios</li>
                </ul>
              </div>
              <div class="timeline-post">
                <div class="post-media"><a href="#"><img src="<?php echo $_SESSION['userImagen'] ?>"></a>
                  <div class="content">
                    <h5><a href="#"><?php print $_SESSION['userNombre'] ?></a></h5>
                    <p class="text-muted"><small>2 Enero 9:30</small></p>
                  </div>
                </div>
                <div class="post-content">
                  <p></p>
                </div>
                <ul class="post-utility">
                  <li class="likes"><a href="#"><i class="fa fa-fw fa-lg fa-thumbs-o-up"></i>Me Gusta</a></li>
                  <li class="shares"><a href="#"><i class="fa fa-fw fa-lg fa-share"></i>Compartir</a></li>
                  <li class="comments"><i class="fa fa-fw fa-lg fa-comment-o"></i> 5 Comentarios</li>
                </ul>
              </div>
            </div>-->
            <div class="tab-pane active" id="user-settings">
              <div class="tile user-settings">
                <h4 class="line-head">Datos Personales</h4>
                <form method="post"  enctype="multipart/form-data">
                  <div class="row mb-4">
                    <div class="col-md-4">
                      <label>Nombre</label>
                      <input class="form-control" id="nombre" name="nombre" value ="<?php echo $userNombre ?>"  type="text">
                    </div>
                    <!--<div class="col-md-4">
                      <label>Apellido</label>
                      <input class="form-control" type="text">
                    </div>-->
                  </div>
                  <div class="row">
                     <!--<div class="col-md-8 mb-4">
                      <label>Email</label>
                      <input class="form-control" id="email" name="email" value ="<?php echo $userEmail ?>" type="text">
                    </div>-->
                    <div class="clearfix"></div>
                    <div class="col-md-8 mb-4">
                      <label>Cargo</label>
                      <input class="form-control" id="profesion" name="profesion" value ="<?php echo $userProfesion ?>" type="text">
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-8 mb-4">
                        <label>Cambiar Foto</label>
                        <input class="form-control" id="imagen" name="imagen" type="file">
                    </div>
                  </div>
                  <div class="row mb-10">
                    <div class="col-md-12">
                      <button class="btn btn-primary" name="submit" type="submit" value="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Modificar</button>
                    </div>
                  </div>
               
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Essential javascripts for application to work-->
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
    <!-- Page specific javascripts-->
    <!-- Google analytics script-->
    <script type="text/javascript">
      if(document.location.hostname == 'pratikborsadiya.in') {
      	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      	ga('create', 'UA-72504830-1', 'auto');
      	ga('send', 'pageview');
      }
    </script>
  </body>
</html>

<?php


if(isset($_POST['submit']))
{
    if($_FILES["imagen"]["name"] != ""){
        
         $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if($check == false) {       
            echo '<script language="javascript">';
            echo 'swal("Error","El archivo de la imagen tiene que ser una imagen.","error")';
            echo '</script>';
            return;
        }

        // Check if file already exists
        /*if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }*/
        // Check file size
        if ($_FILES["imagen"]["size"] > 500000) {
            echo '<script language="javascript">';
            echo 'swal("Error","El tamaño del archivo de la imagen es demasiado grande.","error")';
            echo '</script>';
            return;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo '<script language="javascript">';
            echo 'swal("Error","Solo se permiten cargar imagenes JPG, JPEG, PNG & GIF.","error")';
            echo '</script>';
            return;
        }

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["imagen"]["name"]). " has been uploaded.";
        } else {
            echo '<script language="javascript">';
            echo 'swal("Error","Hubo un error al subir la imagen","error")';
            echo '</script>';
            return;
        }
        
        Podio::setup($client_id, $client_secret);
        Podio::authenticate_with_app($appVendedor_id, $appVendedor_token);

        $imagenUpload = PodioFile::upload( $target_file, $_FILES["imagen"]["name"] );
        
        
        PodioItem::update($_SESSION['userId'], array('fields' => array(
            "foto" =>  $imagenUpload -> file_id
        )));
        
        
        
        if( $imagenUpload == NULL){
            $_SESSION['userImagen']= "images/defaultavatar_large.png";
        }else{
            $_SESSION['userImagen']= $imagenUpload -> link;
        }
        
        unlink($target_file);
    }
   
    
   //Modifico datos del usuario
   PodioItem::update($_SESSION['userId'], array('fields' => array(
        "nombre" =>   $_POST['nombre'],
        "cargo" => $_POST['profesion']
    )));
       
   echo '<script language="javascript">';
   echo 'swal("Modificación","Usuario modificado con exito!","success")';
   echo '</script>';
   
   header("Location: usuario.php");
   

}

?>