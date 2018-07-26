
<form method="post" enctype="multipart/form-data">
  <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Modificar Cliente</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" class="form-control" id="itemId" name="itemId">
          <div class="form-group">
            <label class="control-label">Nombre Comercial</label>
            <input class="form-control" type="text" id="nombreComercial" name="nombreComercial" placeholder="" required>
          </div>
          <div class="form-group">
            <label class="control-label">Razón Social</label>
            <input class="form-control" type="text" id="razonSocial" name="razonSocial" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Cuit</label>
            <input class="form-control" type="number" id="cuit" name="cuit" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Telefono</label>
            <input class="form-control" type="text" id="telefono" name="telefono" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input class="form-control" type="email" id="email" name="email" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Dirección Fiscal</label>
            <input class="form-control" type="text" id="direccionFiscal" name="direccionFiscal" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Dirección Entrega</label>
            <input class="form-control" type="text" id="direccionEntrega" name="direccionEntrega" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Transporte</label>
            <input class="form-control" type="text" id="transporte" name="transporte" rows="3" placeholder="">
          </div>
          <div class="form-group">
            <label class="control-label">Forma De Pago</label>
            <input class="form-control" type="text" id="formaDePago" name="formaDePago" rows="3" placeholder="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button name="modificarCliente" value="modificarCliente" type="submit" class="btn btn-primary" >Guardar datos</button>
        </div>
      </div>
    </div>
  </div>
</form>



    
<?php


if(isset($_POST['modificarCliente']))
{
    try {       

       //Modificar Cliente
       PodioItem::update(intval($_POST['itemId']), array('fields' => array(
            "titulo" => $_POST['nombreComercial'],
            "razon-social" =>   $_POST['razonSocial'] == "" ? null : $_POST['razonSocial'] ,            
            "cuit" => $_POST['cuit'] == "" ? null : intval($_POST['cuit'])  ,
            "telefono" => $_POST['telefono'] == "" ? null :  array('type' => 'work'  , "value" => $_POST['telefono'])    ,
            "email" => $_POST['email'] == "" ? null :  array('type' => 'work'  , "value" => $_POST['email'])  ,
            "direccion" => $_POST['direccionFiscal'] == "" ? null : $_POST['direccionFiscal'] ,
            "donde-se-entrega" => $_POST['direccionEntrega'] == "" ? null :  $_POST['direccionEntrega'],
            "transporte" =>   $_POST['transporte'] == "" ? null : $_POST['transporte'] ,            
            "forma-de-pago" =>   $_POST['formaDePago'] == "" ? null : $_POST['formaDePago']     
        )));

    } catch (Exception $e) {
        echo '<script language="javascript">';
        echo 'swal("Error","Hubo un error al guardar el nuevo cliente.","error")';
        echo '</script>';
        return;
    }
 
   $_SESSION['ClienteModificado'] = 1;
   echo '<script language="javascript">';
   echo ' window.location.href = "clientes.php" ; ';
   echo '</script>';
   
   

}


?>
