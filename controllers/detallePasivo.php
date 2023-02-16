<?php

/*----------INSERT DETALLE FACTURA AJAX----------*/
$app->post('/cargar-gastos/detalle', function() use($app){

    require_once 'models/DetallePasivo.php';
    require_once 'models/Stock.php';
    require_once 'models/Inventario.php';
    $egress = new DetallePasivo();
    $stock = new Stock();
    $inventario = new Inventario();

    $request = $app->request;

    $egress->setIdPasivo($request->post('idpasivo'));
    $egress->setCantidad($request->post('quantity'));
    $egress->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('cost'))));
    $egress->setProducto($request->post('stock'));
    $total=$egress->getCantidad()*$egress->getCosto();
    $egress->setTotal($total);
    $egress->setIdInventario(0);

    $tax = $request->post('tax');
    if($tax == 1){
        $impuesto = $total/11;
    }elseif ($tax == 2){
        $impuesto = $total/21;
    }else{
        $impuesto = 0;
    }
    $egress->setImpuesto($impuesto);

    $stock->setProducto($egress->getProducto());
    $idstock = $stock->retornaPorNombre();
    if($idstock) {
        $stockCosto = $idstock["costo"];
        $stockCantidad = $idstock["cantidad"];
        $stockCodigo = $idstock["idstock"];
        $stock->setIdStock($stockCodigo);

        $date=date_create();
        $inventario->setFecha(date_format($date,"Y/m/d"));
        $inventario->setIdstock($stockCodigo);
        $inventario->setIdempleado(1);
        $inventario->setCantidad($egress->getCantidad());
        $inventario->setIduser(2);
        $inventario->setMovimiento('E');
        $inventario->setMotivo('entrada');
        $inventario->setPendiente('N');

        $inventario->registrarInventario();
        $registroInventario = $inventario->selectMaxId();
        $codigoInventario = $registroInventario['codigo'];
        $egress->setIdInventario($codigoInventario);

        if ($stockCosto < $egress->getCosto()) {
            $stock->setCosto($egress->getCosto());
            $stock->updateCosto();
        }

        $stock->setCantidad($stockCantidad + $egress->getCantidad());
        $stock->updateMovimiento();
    }

    //Inserta en la BD tabla detalle_pasivo
    $insert=$egress->insertDetallePasivo();
    if($insert) {
        $codigo=$egress->returnMaxId();
        $id=$codigo['codigo'];
    }else{
        $id=0;
    }
    echo $id;

})->name('insert-detalle-pasivo');


/*----------Cargar Total Detalle Factura AJAX----------*/
$app->post('/cargar-gastos/total-factura', function() use($app){
    require_once 'models/DetallePasivo.php';
    $egress = new DetallePasivo();
    $request = $app->request;

    $egress->setIdPasivo($request->post('idpasivo'));
    $total = $egress->returnSumTotalImp();
    $totalFormato = number_format($total["total"]);
    echo $totalFormato;

});

/*----------Elimina detalle factura AJAX----------*/
$app->get('/cargar-gastos/delete-detalle-pasivo/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/DetallePasivo.php';

        $egress = new DetallePasivo();
        $egress->setIdDetallePasivo($id);
        $eliminado = $egress->deleteDetallePasivo();

        if($eliminado){
            echo "true";
        }else{
            echo "false";
        }
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-detalle-pasivo');


/*----------Actualizar Detalle Pasivo----------*/
$app->get('/update-detalle-factura/:id', function($id) use($app){
    if(empty($_SESSION['session'])) $app->redirect($app->urlFor('login'));

    require_once 'models/DetallePasivo.php';
    require_once 'models/Selectores.php';
    
    $detailEgress = new DetallePasivo();
    $detailEgress->setIdPasivo($id);
    $facture = $detailEgress->returnFacture();
    $cabecera = $detailEgress->returnCabecera();
    $total = $detailEgress->returnSumTotalImp();
    
    $selector = new Selectores();
    $userAr = $selector->returnRol();
    $stock = $selector->cargarStock();
    $app->render('egress/update-egress.html.twig', array(
        'user' => $userAr, 
        'factura' => $facture, 
        'cabecera'=>$cabecera, 
        'total'=>$total, 
        'stock' => $stock
    ));
})->name('up-detalle-pasivo');

/*----------Elimina detalle factura en Update-Egress----------*/
$app->get('/update-egress/delete-detalle-pasivo/:id-:idpasivo-:idinventario', function($id,$idpasivo,$idinventario) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/DetallePasivo.php';
        require_once  'models/Inventario.php';
        require_once  'models/Stock.php';
        
        $inventario = new Inventario();
        $stock = new Stock();

        $inventario->setIdinventario($idinventario);
        $movimiento = $inventario->selectInventario();
        $cantidad=$movimiento['cantidad'];
        $idStock = $movimiento['idstock'];

        $stock->setIdStock($idStock);
        $producto = $stock->selectStockId();
        $cantidadProducto = $producto['cantidad'];
        $cantidadNueva = $cantidadProducto-$cantidad;
        $stock->setCantidad($cantidadNueva);
        $stock->updateMovimiento();

        $inventario->deleteInventario();

        $egress = new DetallePasivo();
        $egress->setIdDetallePasivo($id);
        $eliminado = $egress->deleteDetallePasivo();

        if($eliminado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Eliminado Correctamente');
        }

        $app->redirect($app->urlFor('up-detalle-pasivo',['id'=>$idpasivo]));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-det-pasivo');

/*----------INSERT DETALLE FACTURA Update-Egress----------*/
$app->post('/update-egress/insert-detalle-pasivo', function() use($app){

    require_once 'models/DetallePasivo.php';
    require_once 'models/Stock.php';
    require_once 'models/Inventario.php';
    $egress = new DetallePasivo();
    $stock = new Stock();
    $inventario = new Inventario();

    $request = $app->request;

    $egress->setIdPasivo($request->post('idpasivo'));
    $egress->setCantidad($request->post('quantity'));
    $egress->setCosto($request->post('cost'));
    $egress->setProducto($request->post('stock'));
    $total=$egress->getCantidad()*$egress->getCosto();
    $egress->setTotal($total);
    $egress->setIdInventario(0);

    $tax = $request->post('tax');
    if($tax == 1){
        $impuesto = $total/11;
    }elseif ($tax == 2){
        $impuesto = $total/21;
    }else{
        $impuesto = 0;
    }
    $egress->setImpuesto($impuesto);

    $stock->setProducto($egress->getProducto());
    $idstock = $stock->retornaPorNombre();
    if($idstock) {
        $stockCosto = $idstock["costo"];
        $stockCantidad = $idstock["cantidad"];
        $stockCodigo = $idstock["idstock"];
        $stock->setIdStock($stockCodigo);

        $date=date_create();
        $inventario->setFecha(date_format($date,"Y/m/d"));
        $inventario->setIdstock($stockCodigo);
        $inventario->setIdempleado(1);
        $inventario->setCantidad($egress->getCantidad());
        $inventario->setIduser(2);
        $inventario->setMovimiento('E');
        $inventario->setMotivo('entrada');
        $inventario->setPendiente('N');
        
        $inventario->registrarInventario();
        $registroInventario = $inventario->selectMaxId();
        $codigoInventario = $registroInventario['codigo'];
        $egress->setIdInventario($codigoInventario);

        if ($stockCosto < $egress->getCosto()) {
            $stock->setCosto($egress->getCosto());
            $stock->updateCosto();
        }

        $stock->setCantidad($stockCantidad + $egress->getCantidad());
        $stock->updateMovimiento();
    }

    //Inserta en la BD tabla detalle_pasivo
    $insert=$egress->insertDetallePasivo();
    if($insert) {
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido Insertar!');
    }

    $app->redirect($app->urlFor('up-detalle-pasivo',['id'=>$egress->getIdPasivo()]));


})->name('ue-insert-detalle-pasivo');

/*----------Pagina de Gastos de Materiales----------*/
$app->get('/gastos-materiales', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/DetallePasivo.php';
        require_once 'models/Selectores.php';

        $egress = new DetallePasivo();
        $egressStock = $egress->egressStock();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('egress/egress-provider.html.twig', array(
            'user' => $userAr, 'egress' => $egressStock));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('egress-stock');

/*----------Pagina de Gastos de Materiales Busqueda----------*/
$app->post('/gastos-materiales/resultado-busqueda', function() use($app){    
    
    require_once 'models/DetallePasivo.php';
    require_once 'models/Selectores.php';
    $request = $app->request;

    $egress = new DetallePasivo();
    $egress->setProducto($request->post('search'));
    $egressStock = $egress->searchEgressStock();

    $selector = new Selectores();
    $userAr = $selector->returnRol();

    $app->render('egress/egress-provider.html.twig', array(
    'user' => $userAr, 'egress' => $egressStock));

})->name('egress-stock-search');

/*------------------------------------Modificar Descuento-----------------------------------------------------------------*/
$app->post('/modificar-descuento', function() use($app){

    require_once 'models/Pasivo.php';
    $egress = new Pasivo();
    $request = $app->request;

    $descuento=$request->post('descuento');
    $egress->setDescuento(intval(preg_replace('/[^0-9]+/','', $descuento)));
    $idPasivo= $request->post('id');
    $egress->setIdpasivo($idPasivo);

    $insertado=$egress->insertDescuento();
    if($insertado) {
        echo 1;
    }

});