<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

require_once 'podio-php/PodioAPI.php';

Podio::setup($client_id, $client_secret);
Podio::authenticate_with_app($appClientes_id, $appClientes_token);

$itemsClientes = PodioItem::filter($appClientes_id ,array('limit' => 500));

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
          <h1><i class="fa fa-user"></i> Clientes</h1>
          <p></p>
        </div>
      </div>
      <?php include("modal/modal_AgregarCliente.php");?> 
      <?php include("modal/modal_ModificarCliente.php");?> 
      <?php include("modal/modal_EliminarCliente.php");?> 
        
      <div class="form-group col-md-3">  
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister"><i class='glyphicon glyphicon-plus'></i> Nuevo Cliente</button>
      </div> 
        
      <div class="row">
        <div class="row">
        <!--<div class="col-md-4">
            <div class="tile">
                <h3 class="tile-title">Agregar Nuevo Cliente</h3>
                  <div class="tile-body">
                    <form method="post" enctype="multipart/form-data">
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
                    <th>Nombre Comercial</th>
                    <th>Razón Social</th>
                    <th>Cuit</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Dirección Fiscal</th>
                    <th>Dirección Entrega</th>
                    <th>Transporte</th>
                    <th>Forma de Pago</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                   <?php
                    foreach ($itemsClientes as $item) {
                        
                    ?>
                      <tr>                            
                            <td><?php echo $item->fields["titulo"]  == null ? "" : $item->fields["titulo"]-> values ?></td>
                            <td><?php echo $item->fields["razon-social"]  == null ? "" : $item->fields["razon-social"]-> values ?></td>    
                            <td><?php echo $item->fields["cuit"]  == null ? "" : intval($item->fields["cuit"]-> values) ?></td>    
                            <td><?php echo $item->fields["telefono"]  == null ? "" : $item->fields['telefono'] -> values[0]["value"] ?></td>    
                            <td><?php echo $item->fields["email"]  == null ? "" : $item->fields['email'] -> values[0]["value"] ?></td>     
                            <td><?php echo $item->fields["direccion"]  == null ? "" : $item->fields["direccion"]-> values["value"] ?></td>    
                            <td><?php echo $item->fields["donde-se-entrega"]  == null ? "" : $item->fields["donde-se-entrega"]-> values["value"] ?></td>  
                            <td><?php echo $item->fields["transporte"]  == null ? "" : $item->fields["transporte"]-> values ?></td>    
                            <td><?php echo $item->fields["forma-de-pago"]  == null ? "" : $item->fields["forma-de-pago"]-> values ?></td>    
                            <td>
                                <a type="button" class="btn btn-info" data-toggle="modal" data-target="#dataUpdate" 
                                   data-itemId="<?php echo $item -> item_id ?>" 
                                   data-nombreComercial="<?php echo $item->fields["titulo"]  == null ? "" : $item->fields["titulo"]-> values ?>" 
                                   data-razonSocial="<?php echo $item->fields["razon-social"]  == null ? "" : $item->fields["razon-social"]-> values ?>" 
                                   data-cuit="<?php echo $item->fields["cuit"]  == null ? "" : $item->fields["cuit"]-> values ?>" 
                                   data-telefono="<?php echo $item->fields["telefono"]  == null ? "" : $item->fields['telefono'] -> values[0]["value"]?>" 
                                   data-email="<?php echo $item->fields["email"]  == null ? "" : $item->fields['email'] -> values[0]["value"] ?>" 
                                   data-direccion="<?php echo  $item->fields["direccion"]  == null ? "" : $item->fields["direccion"]-> values["value"]  ?>" 
                                   data-dondeSeEntrega="<?php echo $item->fields["donde-se-entrega"]  == null ? "" : $item->fields["donde-se-entrega"]-> values["value"]  ?>" 
                                   data-transporte="<?php echo $item->fields["transporte"]  == null ? "" : $item->fields["transporte"]-> values ?>" 
                                   data-formaDePago="<?php echo $item->fields["forma-de-pago"]  == null ? "" : $item->fields["forma-de-pago"]-> values ?>" 
                                >Modificar</a>
                                <a>  </a>
                                <!--<a type="button" class="btn btn-danger" data-toggle="modal" data-target="#dataDelete" data-itemId="<?php echo $item -> app_item_id?>"  >Eliminar</a> -->
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
          
        //var placeSearch, autocomplete, geocoder;

        function initAutocomplete() {
          geocoder = new google.maps.Geocoder();
          autocomplete = new google.maps.places.Autocomplete(
              (document.getElementById('direccionFiscal')));

          autocomplete.addListener('place_changed', fillInAddress);
          
          autocomplete2 = new google.maps.places.Autocomplete(
              (document.getElementById('direccionEntrega')));

          autocomplete2.addListener('place_changed', fillInAddress);
          
          autocomplete3 = new google.maps.places.Autocomplete(
              (document.getElementById('direccionFiscal2')));

          autocomplete3.addListener('place_changed', fillInAddress);
          
          autocomplete4 = new google.maps.places.Autocomplete(
              (document.getElementById('direccionEntrega2')));

          autocomplete4.addListener('place_changed', fillInAddress);
        }
        
        function codeAddress(address) {
            geocoder.geocode( { 'address': address}, function(results, status) {
              if (status == 'OK') {
                //alert(results[0].geometry.location);
              } else {
                alert('Geocode was not successful for the following reason: ' + status);
              }
            });
          }

        function fillInAddress() {
          var place = autocomplete.getPlace();
          //alert(place.place_id);
          //codeAddress(document.getElementById('autocomplete').value);
        }
        
        $('#dataUpdate').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Botón que activó el modal
            var id = button.data('itemid') // Extraer la información de atributos de datos
            var nombreComercial = button.data('nombrecomercial') // Extraer la información de atributos de datos
            var razonSocial = button.data('razonsocial') // Extraer la información de atributos de datos
            var cuit = button.data('cuit') // Extraer la información de atributos de datos
            var telefono = button.data('telefono') // Extraer la información de atributos de datos
            var email = button.data('email') // Extraer la información de atributos de datos
            var direccion = button.data('direccion') // Extraer la información de atributos de datos    
            var dondeSeEntrega = button.data('dondeseentrega') // Extraer la información de atributos de datos    
            var transporte = button.data('transporte') // Extraer la información de atributos de datos    
            var formaDePago = button.data('formadepago') // Extraer la información de atributos de datos   

            var modal = $(this)            
            modal.find('.modal-body #itemId').val(id).change();
            modal.find('.modal-body #nombreComercial').val(nombreComercial).change();
            modal.find('.modal-body #razonSocial').val(razonSocial).change();
            modal.find('.modal-body #cuit').val(cuit).change();
            modal.find('.modal-body #telefono').val(telefono).change();
            modal.find('.modal-body #email').val(email).change();
            modal.find('.modal-body #direccionFiscal').val(direccion).change();
            modal.find('.modal-body #direccionEntrega').val(dondeSeEntrega).change();
            modal.find('.modal-body #transporte').val(transporte).change();
            modal.find('.modal-body #formaDePago').val(formaDePago).change();
            
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPpGUzmkPjLfYHzTTuDZ02jPNQGaKJBdU&libraries=places&callback=initAutocomplete" async defer></script>
    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    
  </body>
</html>

<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>

<?php

if ($_SESSION['NuevoCliente'] != null && $_SESSION['NuevoCliente'] == 1) {
    $_SESSION['NuevoCliente'] = 0;
    echo '<script language="javascript">';
    echo 'swal("Nuevo Cliente","Cliente cargado con exito!","success")';
    echo '</script>';
   
}


if ($_SESSION['ClienteEliminado'] != null && $_SESSION['ClienteEliminado'] == 1) {
    $_SESSION['ClienteEliminado'] = 0;
    echo '<script language="javascript">';
    echo 'swal("Cliente Eliminado","Cliente eliminado con exito!","success")';
    echo '</script>';
   
}

if ($_SESSION['ClienteModificado'] != null && $_SESSION['ClienteModificado'] == 1) {
    $_SESSION['ClienteModificado'] = 0;
    echo '<script language="javascript">';
    echo 'swal("Cliente Modificado","Cliente modificado con exito!","success")';
    echo '</script>';
   
}

/*
if(isset($_POST['submit']))
{
    try {       

       //Creo el nuevo Cliente
       PodioItem::create($appClientes_id, array('fields' => array(
            "titulo" => $_POST['nombreComercial'],
            "razon-social" =>   $_POST['razonSocial'] == "" ? null : $_POST['razonSocial'] ,            
            "cuit" => $_POST['cuit'] == "" ? null : intval($_POST['cuit'])  ,
            "telefono" => $_POST['telefono'] == "" ? null :  array('type' => 'work'  , "value" => $_POST['telefono'])    ,
            "email" => $_POST['email'] == "" ? null :  array('type' => 'work'  , "value" => $_POST['email'])  ,
            "direccion" => $_POST['direccionFiscal'] == "" ? null : $_POST['direccionFiscal'] ,
            "donde-se-entrega" => $_POST['direccionEntrega'] == "" ? null :  $_POST['direccionEntrega'] 
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
*/

?>


