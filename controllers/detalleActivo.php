<?php

/*----------Index Detalle Activo Factura----------*/
$app->get('/detalle-factura-ingreso/:id', function($id) use($app){
    //si hay sesion abierta
    if(empty($_SESSION['session'])) $app->redirect($app->urlFor('login'));

    require_once 'models/Activo.php';
    require_once 'models/DetalleActivo.php';
    require_once 'models/Selectores.php';

    $ingress = new Activo();
    $ingress->setIdActivo($id);
    $factura = $ingress->selectFactura();
    
    $detailActivo= new DetalleActivo();
    $detailActivo->setIdActivo($id);
    $detalleFactura = $detailActivo->selectDetalleFactura();
    $totalArr = array_column($detalleFactura, 'total');
    
    $selector = new Selectores();
    $userAr = $selector->returnRol();
    $products = $selector->uploadProducto();
    $customer = $selector->cargarClientes();
    
    $factura['total'] = array_sum($totalArr);

    $app->render('ingress/detail-ingress.html.twig', array(
        'factura' => $factura, 
        'detalle_factura'=>$detalleFactura, 
        'productos' => $products, 
        'cliente' => $customer, 
        'user' => $userAr));

     
})->name('detalle-factura-activo');

/*----------INSERT CABECERA FACTURA----------*/
$app->post('/ingress-factura/detalle-factura', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/DetalleActivo.php';
    $ingress = new Activo();

    $request = $app->request;

    $fecha=date_create($request->post('fecha'));
    $date = date_format($fecha,"Y/m/d");

    $ingress->setFecha($date);
    $ingress->setIdCliente($request->post('idcliente'));
    $ingress->setNroFactura($request->post('nrofactura'));
    $ingress->setEstado($request->post('estado'));
    $ingress->setTipoIngress($request->post('tipo_ingress'));
    //echo $ingress->getFecha()."/".$ingress->getNroFactura()."/".$ingress->getIdCliente()."/".$ingress->getEstado()."/".$ingress->getTipoIngress();
    $insertActivo=$ingress->insertActivo();

    if($insertActivo) {
        $codigo=$ingress->selectMaxIdActivo();
        
        $detailActivo = new DetalleActivo();
        $detailActivo->setIdActivo($codigo['codigo']);
        $detailActivo->setCantidad(intval(preg_replace('/[^0-9]+/','', $request->post('cantidad'))));
        $detailActivo->setProducto(strtoupper($request->post('producto')));
        $detailActivo->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
        $total = $detailActivo->getCantidad()*$detailActivo->getCosto();
        $detailActivo->setTotal($total);
        //echo $detailActivo->getIdActivo()."/".$detailActivo->getCantidad()."/".$detailActivo->getCosto()."/".$detailActivo->getProducto()."/".$detailActivo->getTotal();
        $detailActivo->insertDetalleActivo();
        $app->redirect($app->urlFor('detalle-factura-activo',['id'=>$codigo['codigo']]));
    }

})->name('insert-cabecera-facactivo');

/*----------INSERT PRODUCTOS FACTURA----------*/
$app->post('/ingress-factura/insert-detalle-factura', function() use($app){

    require_once 'models/DetalleActivo.php';
    $request = $app->request;

    $detailActivo = new DetalleActivo();
    $detailActivo->setIdActivo($request->post('idactivo'));
    $detailActivo->setCantidad(intval(preg_replace('/[^0-9]+/','', $request->post('cantidad'))));
    $detailActivo->setProducto(strtoupper($request->post('producto')));
    $detailActivo->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
    $total = $detailActivo->getCantidad()*$detailActivo->getCosto();
    $detailActivo->setTotal($total);
    //echo $detailActivo->getIdActivo()."/".$detailActivo->getCantidad()."/".$detailActivo->getCosto()."/".$detailActivo->getProducto()."/".$detailActivo->getTotal();
    $detailActivo->insertDetalleActivo();
    $app->redirect($app->urlFor('detalle-factura-activo',['id'=>$detailActivo->getIdActivo()]));

})->name('insert-producto-factura');

/*----------Cancela Producto----------*/
$app->get('/detalle-activo/eliminar-producto/:idactivo-:id', function($idactivo,$id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/DetalleActivo.php';

        $detailActivo = new DetalleActivo();
        $detailActivo->setIdDetalleActivo($id);
        $eliminado = $detailActivo->delDetalleActivo();

        if($eliminado){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'Producto Eliminado Correctamente');
        }

        $app->redirect($app->urlFor('detalle-factura-activo',['id'=>$idactivo]));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('del-detalle-activo');


/*----------INSERT RECIBO DE DINERO----------*/
$app->post('/ingress-recibo/detalle-activo', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/DetalleActivo.php';
    $ingress = new Activo();

    $request = $app->request;

    $fecha=date_create($request->post('fecha'));
    $date = date_format($fecha,"Y/m/d");

    $ingress->setFecha($date);
    $ingress->setIdCliente($request->post('customer'));
    $ingress->setNroFactura($request->post('nrofactura'));
    $ingress->setEstado($request->post('estado'));
    
    $tipoIngress = $request->post('switch');
    if($tipoIngress)
        $ingress->setTipoIngress("M");
    else        
        $ingress->setTipoIngress("D");
    
    ///echo $ingress->getFecha()."/".$ingress->getNroFactura()."/".$ingress->getIdCliente()."/".$ingress->getEstado()."/".$ingress->getTipoIngress();
    $insertActivo=$ingress->insertActivo();

    if($insertActivo) {
        $codigo=$ingress->selectMaxIdActivo();

        $detailActivo = new DetalleActivo();
        $detailActivo->setIdActivo($codigo['codigo']);
        $detailActivo->setCantidad(1);
        $detailActivo->setProducto(strtoupper($request->post('producto')));
        $detailActivo->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
        $total = $detailActivo->getCantidad()*$detailActivo->getCosto();
        $detailActivo->setTotal($total);
        //echo $detailActivo->getIdActivo()."/".$detailActivo->getCantidad()."/".$detailActivo->getCosto()."/".$detailActivo->getProducto()."/".$detailActivo->getTotal();
        $detailActivo->insertDetalleActivo();
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Recibo insertado correctamente!!');
        $app->redirect($app->urlFor('ingress_recibo'));
    }

})->name('insert-recibo');


/*----------Pagina Modificacion de Recibos----------*/
$app->get('/detalle-recibo-ingreso/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/DetalleActivo.php';
        require_once 'models/Selectores.php';

        $ingress = new Activo();
        $ingress->setIdActivo($id);
        $recibo = $ingress->selectRecibo();

        $detailActivo= new DetalleActivo();
        $detailActivo->setIdActivo($id);
        $detalleFactura = $detailActivo->selectDetalleFactura();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $customer = $selector->cargarClientes();

        $app->render('ingress/update-recibo.html.twig', array(
            'recibo' => $recibo, 'detalle_factura'=>$detalleFactura, 'cliente' => $customer, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('update-recibo');

$app->post('/ingress-recibo/update-detalle-activo', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/DetalleActivo.php';
    $ingress = new Activo();
    $request = $app->request;

    $ingress->setIdActivo($request->post('idactivo'));
    $fecha=date_create($request->post('fecha'));
    $date = date_format($fecha,"Y/m/d");

    $ingress->setFecha($date);
    $ingress->setIdCliente($request->post('customer'));
    $ingress->setNroFactura($request->post('nrofactura'));

    $tipoIngress = $request->post('switch');
    if($tipoIngress)
        $ingress->setTipoIngress("M");
    else
        $ingress->setTipoIngress("D");

    //echo $ingress->getFecha()."/".$ingress->getNroFactura()."/".$ingress->getIdCliente()."/".$ingress->getTipoIngress()."/".$tipoIngress;
    $update=$ingress->updateActivo();


    $detailActivo = new DetalleActivo();
    $detailActivo->setIdActivo($request->post('idactivo'));
    $detailActivo->setProducto(strtoupper($request->post('producto')));
    $detailActivo->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
    $detailActivo->setTotal(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
    //echo $detailActivo->getIdActivo()."/".$detailActivo->getCantidad()."/".$detailActivo->getCosto()."/".$detailActivo->getProducto()."/".$detailActivo->getTotal();
    $detailActivo->updateDetalleActivo();
    $app->flash('content', 'alert-success');
    $app->flash('mensaje', 'Recibo modificado correctamente!!');
    $app->redirect($app->urlFor('ingress_recibo'));
    

})->name('form-update-recibo');

/*----------Redirecciona el pedido por numero de factura--------------------------------------------------------------*/
$app->get('/detalle-factura-pedido/:nro-factura', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/DetalleActivo.php';
        require_once 'models/Selectores.php';

       /* $ingress = new Activo();
        $ingress->setIdActivo($id);
        $factura = $ingress->selectFactura();

        $detailActivo= new DetalleActivo();
        $detailActivo->setIdActivo($id);
        $detalleFactura = $detailActivo->selectDetalleFactura();*/

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $products = $selector->uploadProducto();
        $customer = $selector->cargarClientes();

        $app->render('pedidos/detalle-factura.html.twig', array(
            'user' => $userAr
            //'factura' => $factura, 'detalle_factura'=>$detalleFactura, 'productos' => $products, 'cliente' => $customer, 'user' => $userAr
        ));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('detalle-factura-pedidos');