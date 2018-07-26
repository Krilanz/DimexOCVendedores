<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" style="display: none;">
                <div class="modal-dialog" role="document" style ="max-width: 1000px; max-height: 1000px">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h4 class="modal-title" id="gridModalLabel">Historial de Entregas</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                                </div>
                                <div class="modal-body">
                                        <div class="tile-body table-responsive" >
                                            <table class="table table-hover table-bordered" id="table">
                                              <thead>
                                                <tr>
                                                  <th>ID Pedido</th>
                                                  <th>Número de OC</th>
                                                  <th>Fecha de Solicitud</th>
                                                  <th>Fecha de Primera Entrega</th>
                                                  <th>Cliente</th>
                                                  <th>Vendío</th>
                                                  <th>Preparó</th>
                                                  <th>Status</th>
                                                  <th>Link</th>
                                                  <th>Acciones</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                 <?php
                                                  foreach ($itemsOrdenDeCompra as $item) {
                                                      $detalles = PodioItem::get_by_app_item_id( $appOrdenDeCompra_id , $item -> app_item_id) ;
                                                      $items['Articulos'] = array();
                                                      foreach ($detalles -> files as $detalle) {                           
                                                          $fileId = $detalle -> file_id;
                                                      }

                                                  ?>
                                                    <tr>                            
                                                          <td><?php echo $item->fields["id-pedido-3"] == null ? "" : intval($item->fields["id-pedido-3"]-> values) ?></td>
                                                          <td><?php echo $item->fields["numero-de-oc"] == null ? "" : intval($item->fields["numero-de-oc"]-> values) ?></td>
                                                          <td><?php echo $item->fields["fecha"]-> values["start"] == null ? "" : $item->fields["fecha"]-> values["start"] -> format('Y/m/d') ?></td>
                                                          <td><?php echo $item->fields["fecha-de-primera-entrega"] == null ? "" : $item->fields["fecha-de-primera-entrega"]-> values["start"] -> format('Y/m/d') ?></td>
                                                          <td><?php echo $item->fields["cliente"] != null ? $item->fields["cliente"] -> values[0] -> title : ""   ?></td>
                                                          <td><?php echo $item->fields["vendio-2"] != null ? $item->fields["vendio-2"] -> values[0] -> title : "" ?></td>
                                                          <td><?php echo $item->fields["preparo"] == null ? "" : $item->fields["preparo"]-> values ?></td>
                                                          <td><?php echo $item->fields["oc-status"] == null ? "" : $item->fields["oc-status"]-> values[0]['text'] ?></td>
                                                          <td><a target="_blank" href="<?php echo $item->fields["link-3"] == null ? "" : $item->fields["link-3"]-> values ?>" id="<?php $item -> item_id ?>">OC LINK</a></td>
                                                          <td>
                                                              <a href="" data-toggle="modal" data-target="#gridSystemModal" >Historial de Entregas</a>
                                                              <a> | </a>
                                                              <td><a href="historialDeEntregas.php?descargar=<?php echo $fileId ?>">Imprimir</a></td>
                                                              <a> | </a>
                                                              <a href="ordenesDeCompra.php?anular=<?php echo $item->fields["id-pedido-3"] == null ? "" : intval($item->fields["id-pedido-3"]-> values) ?>">Anular</a>
                                                          </td>
                                                      </tr>
                                                  <?php
                                                  }
                                                  ?>

                                              </tbody>
                                            </table>
                                          </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                </div>
                        </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->