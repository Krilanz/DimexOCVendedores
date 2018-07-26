<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}
require_once 'podio-php/PodioAPI.php';

Podio::setup($client_id, $client_secret);
    
if(isset($_GET['Id'])){
    
    Podio::authenticate_with_app($appFacturas_id, $appFacturas_token);

    $itemsFacturas = PodioItem::filter($appFacturas_id, [
        'filters' => [
            'oc' => intval($_GET['Id'])
        ],
        'limit' => 500
    ]);
}else{
    
    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appOrdenDeCompra_id, $appOrdenDeCompra_token);

    $itemsOrdenDeCompra = PodioItem::filter($appOrdenDeCompra_id, [
            'filters' => [
                'vendio-2' => $_SESSION['userId'],    
                'oc-status' => [1,2,3]
            ],
            'limit' => 500
        ]);
    
    $ocIds = [0];
    foreach ($itemsOrdenDeCompra as $item) {
        $ocIds[] =  $item-> item_id;
    }
    
    if($ocIds != []){
        Podio::authenticate_with_app($appFacturas_id, $appFacturas_token);
    
        $itemsFacturas = PodioItem::filter($appFacturas_id, [
            'filters' => [
                'oc' => $ocIds
            ],
            'limit' => 500
        ]);
    }
    
}


if(isset($_GET['descargarPDF']))
{
   
   //Descargar documentación 
   // Get the file object. Only necessary if you don't already have it!
    $file = PodioFile::get($_GET['descargarPDF']);

    // Download the file. This might take a while...
    $file_content = $file->get_raw();
    
    //$file_content = Podio::get($file->link . '/medium', array(), array('file_download' => true))->body;
    // Store the file on local disk
    header("Content-Description: File Transfer"); 
    header("Content-Type: application/pdf");  
    header("Content-Disposition: attachment; filename='" . $file -> name . "'"); 
    file_put_contents("downloads/" . $file -> name, $file_content);

    readfile("downloads/" . $file -> name);
    unlink("downloads/" . $file -> name);
}
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
          <h1><i class="fa fa-history"></i> Historial de Entregas</h1>
          <p></p>
        </div>
      </div>
       
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive" >
              <table class="table table-hover table-bordered" id="table">
                <thead>
                  <tr>
                    <th>Número de OC</th>
                    <th>Número de Factura</th>
                    <th>Fecha de Entrega</th>
                    <th>Cantidad Entregada</th>
                    <th>Producto</th>
                    <th>Cliente</th>
                    <th>PDF</th>                   
                  </tr>
                </thead>
                <tbody>
                   <?php
                    $i = 0;
                    $files = PodioFile::get_for_app( $appFacturas_id,array('limit' => 100)) ;             
                    foreach ($itemsFacturas as $item) {
                        //$detalles = PodioFile::get_for_app( $appFacturas_id , $item -> app_item_id) ;                        
                        
                    ?>
                      <tr>                            
                            <td><?php echo $item->fields["oc"] != null ? $item->fields["oc"] -> values[0] -> title : ""   ?></td>
                            <td><?php echo $item->fields["numero"]  == null ? "" : intval($item->fields["numero"]-> values) ?></td>
                            <td><?php echo $item->fields["fecha-de-entrega-2"] == null ? "" : $item->fields["fecha-de-entrega-2"]-> values["start"] -> format('Y-m-d H:i:s') ?></td>                                                       
                            <td><?php echo $item->fields["cantidad-entregada"] == null ? "" : round($item->fields["cantidad-entregada"]-> values,2) ?></td>
                            <td><?php echo $item->fields["pedido-de-producto"] != null ? $item->fields["pedido-de-producto"] -> values[0] -> title : "" ?></td>
                            <td><?php echo $item->fields["cliente"] != null ? $item->fields["cliente"] -> values[0] -> title : ""   ?></td>                                                        
                            <td>
                               <select class="form-control selectpicker"  data-width="120px" name='selectPDF<?php echo $i ?>' id='selectPDF<?php echo $i ?>'  >
                                <?php
                                    foreach ($files as $detalle) { 
                                        if($detalle -> context["id"] == $item -> item_id){
                                        ?>                          
                                         <option id="<?php echo $detalle -> file_id ?>" value="<?php echo $detalle -> file_id ?>" > <?php echo $detalle -> name ?> </option> 
                                        <?php
                                        }
                                    }
                                ?>
                              </select>
                              <a href="javascript:void(0);" id="<?php echo $i ?>" onclick="DescargarPDF(this.id)">Descargar</a>
                            </td>
                        </tr>
                    <?php
                    $i ++;
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
            function DescargarPDF(id) {
                var e = document.getElementById("selectPDF" + id);
                var fileId = e.options[e.selectedIndex].value;
                window.location.href = 'historialDeEntregas.php?descargarPDF=' + fileId;
                
                /*var win = window.open('https://files.podio.com/' + fileId, '_blank');
                win.focus();*/
            }
    </script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    
  </body>
</html>

<?php

if (isset($_GET['anular'])) {

    echo "
<script src='js/jquery-3.3.1.min.js'></script>    
<script language='javascript'>
    
           swal({
      title: 'Estas seguro?',
      text: 'La unica manera de revertir la anulación es contactando con el administrador!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, anulalo!'
    }).then((result) => {
      if (result.value) {
           $.ajax({
                type: 'POST',
                url: 'ordenesDeCompra.php?anular2=220',
                success: function (data) {
                            swal(
                                'Anulado!',
                                'La orden de compra fue anulada.',
                                'success'
                              )
                            return window.location.href = 'ordenesDeCompra.php';    
                        }
            });
        
      }else{
        return window.location.href = 'ordenesDeCompra.php';
      }
    })  </script>" ;

}


if (isset($_GET['anular2'])) {

    $ordenVieja = PodioItem::get_by_app_item_id( $appOrdenDeCompra_id, intval($_GET['anular2']));

    PodioItem::update($ordenVieja-> item_id, array('fields' => array(            
            "oc-status" => 4
        )));

    header("Location: ordenesDeCompra.php");
}
  
?>
