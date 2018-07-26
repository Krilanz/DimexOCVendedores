
<form method="post" enctype="multipart/form-data">
  <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Modificar Producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" class="form-control" id="itemId" name="itemId">
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button name="modificarProducto" value="modificarProducto" type="submit" class="btn btn-primary" >Guardar datos</button>
        </div>
      </div>
    </div>
  </div>
</form>



    
<?php


if(isset($_POST['modificarProducto']))
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
        /*if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }*/
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
       PodioItem::update(intval($_POST['itemId']), array('fields' => array(
            "titulo" => $_POST['titulo'],
            "descripcion" =>   $_POST['descripcion'] != "" ?  $_POST['descripcion'] : null ,           
            "foto" => $imagenUpload -> file_id 
        )));

       unlink($target_file);
    }else{
            //Creo el nuevo Producto
           PodioItem::update(intval($_POST['itemId']), array('fields' => array(
                "titulo" => $_POST['titulo'],
                "descripcion" =>   $_POST['descripcion'] != "" ?  $_POST['descripcion'] : null 
            )));
       }
       
    } catch (Exception $e) {
        echo '<script language="javascript">';
        echo 'swal("Error","Hubo un error al modificar el producto.","error")';
        echo '</script>';
        return;
    }
   
   
   $_SESSION['ProductoModificado'] = 1;
   echo '<script language="javascript">';
   echo ' window.location.href = "productos.php" ; ';
   echo '</script>';
   
   
   

}


?>
