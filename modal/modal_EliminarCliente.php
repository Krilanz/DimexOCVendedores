<form method="post" id="eliminarDatos">
<div class="modal fade" id="dataDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <input type="hidden" id="itemId" name="itemId">
      <h2 class="text-center text-muted">Estas seguro?</h2>
	  <p class="lead text-muted text-center" style="display: block;margin:10px">Esta acción eliminará de forma permanente el Cliente. Deseas continuar?</p>
      <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Cancelar</button>
        <button name="eliminarCliente" value="eliminarCliente" type="submit" class="btn btn-lg btn-primary">Aceptar</button>
      </div>
    </div>
  </div>
</div>
</form>



<?php


if(isset($_POST['eliminarCliente']))
{
    $cliente = PodioItem::get_by_app_item_id( $appClientes_id, intval($_POST['itemId']));
    
    PodioItem::delete($cliente-> item_id);

   $_SESSION['ClienteEliminado'] = 1;
   
   echo '<script language="javascript">';
   echo ' window.location.href = "clientes.php" ; ';
   echo '</script>';
}


?>

