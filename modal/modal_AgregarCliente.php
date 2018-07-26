
<form method="post" enctype="multipart/form-data">
  <div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Agregar Cliente</h4>
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
          <div id="locationField" class="form-group">
            <label class="control-label">Dirección Fiscal</label>
            <input class="form-control" type="text" id="direccionFiscal2" name="direccionFiscal2" rows="3" placeholder="">
          </div>
          <div id="locationField" class="form-group">
            <label class="control-label">Dirección Entrega</label>
            <input class="form-control" type="text" id="direccionEntrega2" name="direccionEntrega2" rows="3" placeholder="">
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
          <button name="agregarCliente" value="agregarCliente"  type="submit" class="btn btn-primary" >Guardar datos</button>
        </div>
      </div>
    </div>
  </div>
</form>


<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>

    
<?php


if(isset($_POST['agregarCliente']))
{
    try {       

       //Creo el nuevo Cliente
       PodioItem::create($appClientes_id, array('fields' => array(
            "titulo" => $_POST['nombreComercial'],
            "razon-social" =>   $_POST['razonSocial'] == "" ? null : $_POST['razonSocial'] ,            
            "cuit" => $_POST['cuit'] == "" ? null : intval($_POST['cuit'])  ,
            "telefono" => $_POST['telefono'] == "" ? null :  array('type' => 'work'  , "value" => $_POST['telefono'])    ,
            "email" => $_POST['email'] == "" ? null :  array('type' => 'work'  , "value" => $_POST['email'])  ,
            "direccion" => $_POST['direccionFiscal2'] == "" ? null : $_POST['direccionFiscal2'] ,
            "donde-se-entrega" => $_POST['direccionEntrega2'] == "" ? null :  $_POST['direccionEntrega2'],
            "transporte" =>   $_POST['transporte'] == "" ? null : $_POST['transporte'] ,            
            "forma-de-pago" =>   $_POST['formaDePago'] == "" ? null : $_POST['formaDePago']             
        )));

    } catch (Exception $e) {
        echo '<script language="javascript">';
        echo 'swal("Error","Hubo un error al guardar el nuevo cliente.","error")';
        echo '</script>';
        return;
    }
 
   $_SESSION['NuevoCliente'] = 1;
   echo '<script language="javascript">';
   echo ' window.location.href = "clientes.php" ; ';
   echo '</script>';
   
   

}


?>

