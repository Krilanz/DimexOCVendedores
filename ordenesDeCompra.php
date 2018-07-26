<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}


require_once 'podio-php/PodioAPI.php';

Podio::setup($client_id, $client_secret);
Podio::authenticate_with_app($appOrdenDeCompra_id, $appOrdenDeCompra_token);

$itemsOrdenDeCompra = PodioItem::filter($appOrdenDeCompra_id, [
        'filters' => [
            'vendio-2' => $_SESSION['userId'],    
            'oc-status' => [1,2,3]
        ],
        'limit' => 500
    ]);



if(isset($_GET['descargarPDF']))
{
   
   //Descargar PDF 
   // Get the file object. Only necessary if you don't already have it!
    $file = PodioFile::get($_GET['descargarPDF']);

    // Download the file. This might take a while...
    $file_content = $file->get_raw();
    
   // $file_content = Podio::get($file->link . '/medium', array(), array('file_download' => true))->body;
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
          <h1><i class="fa fa-book"></i> Ordenes de Compra</h1>
          <p></p>
        </div>
      </div>
        
      <div class="form-group col-md-3">  
          <a class="btn btn-primary" target="_blank" href="http://dimex.innen.com.ar/OrdenesDeCompra/Index.php">Nueva Orden de Compra</a>   
      </div> 
       
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive" >
              <table class="table table-hover table-bordered" id="table">
                <thead>
                  <tr>
                    <th>ID Pedido</th>
                    <th>Número de OC</th>
                    <th>Fecha de Solicitud</th>
                    <th>Fecha de Primera Entrega</th>
                    <th>Cliente</th>
                    <th>Preparó</th>
                    <th>Porcentaje Entregado</th>
                    <th>Status</th>
                    <th>PDF</th>
                    <th>Link</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                   <?php
                    $i = 0;
                    $files = PodioFile::get_for_app( $appOrdenDeCompra_id , array('limit' => 100)) ;
                    foreach ($itemsOrdenDeCompra as $item) {
                        //$detalles = PodioItem::get_by_app_item_id( $appOrdenDeCompra_id , $item -> app_item_id) ;
                        $items['Articulos'] = array();
                        
                    ?>
                      <tr>                            
                            <td><?php echo $item->fields["id-pedido-3"] == null ? "" : intval($item->fields["id-pedido-3"]-> values) ?></td>
                            <td><?php echo $item->fields["numero-de-oc"] == null ? "" : $item->fields["numero-de-oc"]-> values ?></td>
                            <td><?php echo $item->fields["fecha"]-> values["start"] == null ? "" : $item->fields["fecha"]-> values["start"] -> format('Y/m/d') ?></td>
                            <td><?php echo $item->fields["fecha-de-primera-entrega"] == null ? "" : $item->fields["fecha-de-primera-entrega"]-> values["start"] -> format('Y/m/d') ?></td>
                            <td><?php echo $item->fields["cliente"] != null ? $item->fields["cliente"] -> values[0] -> title : ""   ?></td>
                            <td><?php echo $item->fields["preparo"] == null ? "" : $item->fields["preparo"]-> values ?></td>
                            <td><?php echo $item->fields["porcentaje-de-entrega"] == null ? "" : $item->fields["porcentaje-de-entrega"]-> values ?></td>
                            <td><?php echo $item->fields["oc-status"] == null ? "" : $item->fields["oc-status"]-> values[0]['text'] ?></td>
                            <td>
                               <select class="form-control selectpicker"  data-width="120px" name='selectPDF<?php echo $i ?>' id='selectPDF<?php echo $i ?>'  >
                                <?php
                                    foreach ($files  as $detalle) { 
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
                            <td><a target="_blank" href="<?php echo $item->fields["link-3"] == null ? "" : $item->fields["link-3"]-> values ?>" id="<?php $item -> item_id ?>">OC LINK</a></td>
                            <td>
                                <a href="historialDeEntregas.php?Id=<?php echo $item -> item_id ?>">Historial de Entregas</a>
                                <a> | </a>
                                <a href="ordenesDeCompra.php?archivar=<?php echo $item->fields["id-pedido-3"] == null ? "" : intval($item->fields["id-pedido-3"]-> values) ?>">Archivar</a>
                                <a> | </a>
                                <a href="ordenesDeCompra.php?anular2=<?php echo $item->fields["id-pedido-3"] == null ? "" : intval($item->fields["id-pedido-3"]-> values) ?>">Anular</a>
                            </td>
                        </tr>
                    <?php
                    $i++;
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
                
                window.location.href = 'ordenesDeCompra.php?descargarPDF=' + fileId;
                
                /*$.ajax({
                    type: 'GET',
                    url: 'ordenesDeCompra.php?descargarPDF',
                    data: ({Id: fileId}),
                    success: function(data) {
                        
                    },
                    async: false // <- this turns it into synchronous
                });*/
               /* var win = window.open('https://files.podio.com/' + fileId, '_blank');
                win.focus();*/
            }
           
    </script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    
  </body>
</html>

<?php

if(isset($_GET['NuevaFactura']) && $_SESSION['NuevaFactura'] != 0 ){
    $_SESSION['NuevaFactura'] = 0;
    echo '<script language="javascript">';
    echo 'swal( "Nueva Factura","La factura fue creada con exito!.","success" )';
    echo '</script>';
}

if ($_SESSION['OCAnulada'] == 1) {
    $_SESSION['OCAnulada'] = 0;
    echo '<script language="javascript">';
    echo 'swal( "OC Anulada","La orden de compra fue anulada correctamente.","success" )';
    echo '</script>';
}

if ($_SESSION['OCArchivada'] == 1) {
    $_SESSION['OCArchivada'] = 0;
    echo '<script language="javascript">';
    echo 'swal( "OC Archivada","La orden de compra fue archivada correctamente.","success" )';
    echo '</script>';
}


if (isset($_GET['anular2'])) {
    
    echo "
<script src='js/jquery-3.3.1.min.js'></script>    
<script language='javascript'>
    
           swal({
      title: 'Estas seguro?',
      text: 'No hay manera de revertir la anulación!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, anulalo!'
    }).then((result) => {
      if (result.value) {
      
          
         return window.location.href = 'ordenesDeCompra.php?anular=".$_GET['anular2']."' ;
      }else{
        return window.location.href = 'ordenesDeCompra.php';
      }
    })  </script>" ;

}


if (isset($_GET['anular'])) {

    

    $ordenVieja = PodioItem::get_by_app_item_id( $appOrdenDeCompra_id, intval($_GET['anular']));
    
    
    Podio::authenticate_with_app($appPedido_id, $appPedido_token);
     
     
    $detalles = PodioItem::filter($appPedido_id, [
        'filters' => [
            'oc' => $ordenVieja-> item_id
        ],
        'limit' => 500
    ]);
    foreach ($detalles as $detalle) {
        PodioItem::delete($detalle -> item_id);
    }
    
    Podio::authenticate_with_app($appOrdenDeCompra_id, $appOrdenDeCompra_token);
    
    PodioItem::delete($ordenVieja-> item_id);
    
   
    
    
    /*PodioItem::update($ordenVieja-> item_id, array('fields' => array(            
            "oc-status" => 4
        )));*/

   $_SESSION['OCAnulada'] = 1;
   echo '<script language="javascript">';
   echo ' window.location.href = "ordenesDeCompra.php" ; ';
   echo '</script>';
}


if (isset($_GET['archivar'])) {

    

    $ordenVieja = PodioItem::get_by_app_item_id( $appOrdenDeCompra_id, intval($_GET['archivar']));
    
    
    PodioItem::update($ordenVieja-> item_id, array('fields' => array(            
            "oc-status" => 5
        )));

    $_SESSION['OCArchivada'] = 1;
    echo '<script language="javascript">';
    echo ' window.location.href = "ordenesDeCompra.php" ; ';
    echo '</script>';
}

?>
