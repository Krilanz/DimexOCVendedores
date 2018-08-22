<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

require_once 'podio-php/PodioAPI.php';

Podio::setup($client_id, $client_secret);
Podio::authenticate_with_app($appProductos_id, $appProductos_token);

$itemsProductos = PodioItem::filter($appProductos_id, array('limit' => 500));

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dimex - OC Vendedores</title>
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
      <div class="app-title">
        <div>
          <h1><i class="fa fa-product-hunt"></i> Productos</h1>
          <p></p>
        </div>
      </div>

      <?php include("modal/modal_AgregarProducto.php");?>
      <?php include("modal/modal_ModificarProducto.php");?>
      <?php include("modal/modal_EliminarProducto.php");?>

      <div class="form-group col-md-3">
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister"><i class='glyphicon glyphicon-plus'></i> Nuevo Producto</button>
      </div>

      <div class="row">
        <div class="row">
        <!--<div class="col-md-5">
            <div class="tile">
                <h3 class="tile-title">Agregar Nuevo Producto</h3>
                  <div class="tile-body">
                    <form method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label class="control-label">Título</label>
                        <input class="form-control" type="text" id="titulo" name="titulo" placeholder="Título" required>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción"></textarea>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Imagen</label>
                        <input class="form-control" id="imagen" name="imagen" type="file">
                      </div>
                      <div class="tile-footer">
                        <button class="btn btn-primary" name="submit" type="submit" value="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar</button>
                      </div>
                    </form>
                  </div>

          </div>
        </div>-->
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive" >
              <table class="table table-hover table-bordered" id="table">
                <thead>
                  <tr>
                    <th>Título</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                   <?php
                    foreach ($itemsProductos as $item) {


                    ?>
                      <tr>
                            <td><?php echo $item->fields["titulo"]  == null ? "" : $item->fields["titulo"]-> values ?></td>
                            <td><?php echo $item->fields["codigo"]  == null ? "" : $item->fields["codigo"]-> values ?></td>
                            <td><?php echo $item->fields["descripcion"]  == null ? "" : $item->fields["descripcion"]-> values ?></td>
                            <td>
                                <a type="button" class="btn btn-info" data-toggle="modal" data-target="#dataUpdate"
                                   data-itemId="<?php echo $item -> item_id ?>"
                                   data-titulo="<?php echo $item->fields["titulo"]  == null ? "" : $item->fields["titulo"]-> values ?>"
                                   data-codigo="<?php echo $item->fields["codigo"]  == null ? "" : $item->fields["codigo"]-> values  ?>"
                                   data-descripcion="<?php echo $item->fields["descripcion"]  == null ? "" : $item->fields["descripcion"]-> values  ?>"
                                >Modificar</a>
                                <a>  </a>
                                <a type="button" class="btn btn-danger" data-toggle="modal" data-target="#dataDelete" data-itemId="<?php echo $item -> app_item_id?>"  >Eliminar</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
              </table>
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
    <!-- The javascript plugin to display page loading on top-->
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://unpkg.com/promise-polyfill@7.1.0/dist/promise.min.js"></script>

    <script src="js/plugins/pace.min.js"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $('#table').DataTable({
                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "Ningún dato disponible en esta tabla",
                    "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":   "",
                    "sSearch":        "Buscar:",
                    "sUrl":           "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":    "Último",
                        "sNext":    "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });

        $('#dataUpdate').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Botón que activó el modal
            var id = button.data('itemid') // Extraer la información de atributos de datos
            var titulo = button.data('titulo') // Extraer la información de atributos de datos
            var codigo = button.data('codigo') // Extraer la información de atributos de datos
            var descripcion = button.data('descripcion') // Extraer la información de atributos de datos


            var modal = $(this)
            modal.find('.modal-body #itemId').val(id).change();
            modal.find('.modal-body #titulo').val(titulo).change();
            modal.find('.modal-body #codigo').val(codigo).change();
            modal.find('.modal-body #descripcion').val(descripcion).change();
            $('.alert').hide();//Oculto alert
        })

        $('#dataDelete').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Botón que activó el modal
          var id = button.data('itemid') // Extraer la información de atributos de datos
          var modal = $(this)
          modal.find('#itemId').val(id)
        })

        $(document).ready(function() {
            $(window).keydown(function(event){
              if(event.keyCode == 13) {
                event.preventDefault();
                return false;
              }
            });
          });

    </script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript" src="js/plugins/select2.min.js"></script>

  </body>
</html>


<?php

if ($_SESSION['NuevoProducto'] == 1) {
    $_SESSION['NuevoProducto'] = 0;
    echo '<script language="javascript">';
    echo 'swal("Nuevo Producto","Producto cargado con exito!","success")';
    echo '</script>';

}



if ($_SESSION['ProductoEliminado'] != null && $_SESSION['ProductoEliminado'] == 1) {
    $_SESSION['ProductoEliminado'] = 0;
    echo '<script language="javascript">';
    echo 'swal("Producto Eliminado","Producto eliminado con exito!","success")';
    echo '</script>';

}

if ($_SESSION['ProductoModificado'] != null && $_SESSION['ProductoModificado'] == 1) {
    $_SESSION['ProductoModificado'] = 0;
    echo '<script language="javascript">';
    echo 'swal("Producto Modificado","Producto modificado con exito!","success")';
    echo '</script>';

}

/*
if(isset($_POST['submit']))
{
    try {
      if($_FILES["imagen"]["name"]!= ""){
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if($check == false) {
            echo '<script language="javascript">';
            echo 'swal("Error","El archivo de imagen tiene que ser una imagen.","error")';
            echo '</script>';
            return;
        }

        // Check if file already exists

        // Check file size
        if ($_FILES["imagen"]["size"] > 500000) {
            echo '<script language="javascript">';
            echo 'swal("Error","El tamaño del archivo de imagen es demasiado grande.","error")';
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
            //echo "The file ". basename( $_FILES["comprobante"]["name"]). " has been uploaded.";
        } else {
            echo '<script language="javascript">';
            echo 'swal("Error","Hubo un error al subir la imagen","error")';
            echo '</script>';
            return;
        }

        require_once 'podio-php/PodioAPI.php';
        Podio::setup($client_id, $client_secret);
        Podio::authenticate_with_app($appProductos_id, $appProductos_token);

        $imagenUpload = PodioFile::upload( $target_file, $_FILES["imagen"]["name"] );



       //Creo el nuevo Producto
       PodioItem::create($appProductos_id, array('fields' => array(
            "titulo" => $_POST['titulo'],
            "descripcion" =>   $_POST['descripcion'] != "" ?  $_POST['descripcion'] : null ,
            "foto" => $imagenUpload -> file_id
        )));

       unlink($target_file);
    }else{
            //Creo el nuevo Producto
           PodioItem::create($appProductos_id, array('fields' => array(
                "titulo" => $_POST['titulo'],
                "descripcion" =>   $_POST['descripcion'] != "" ?  $_POST['descripcion'] : null
            )));
       }

    } catch (Exception $e) {
        echo '<script language="javascript">';
        echo 'swal("Error","Hubo un error al guardar el nuevo producto.","error")';
        echo '</script>';
        return;
    }


   $_SESSION['NuevoProducto'] = 1;
   echo '<script language="javascript">';
   echo ' window.location.href = "productos.php" ; ';
   echo '</script>';




}*/


?>
