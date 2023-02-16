<?php
/*----------Index Gastos----------*/
$app->get('/pedidos', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Pedidos.php';

        $pedidos = new Pedidos();
        $pedidos->setPagado('N');
        $result = $pedidos->vertodos();
        //var_dump($result);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $customer = $selector->cargarClientes();

        $app->render('pedidos/pedidos.html.twig', array(
            'pedidos'=>$result,  'clientes'=>$customer, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('pedidos');

/*----------New Pedido----------*/
$app->get('/pedido-nuevo', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Pedidos.php';
        
        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $customer = $selector->cargarClientes();
        $productos = $selector->uploadProducto();

        $app->render('pedidos/new-pedido.html.twig', array(
             'clientes'=>$customer, 'productos'=>$productos, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('new-pedido');

/*----------Pedidos Terminados----------*/
$app->get('/pedidos-terminados', function() use($app){

    if(empty($_SESSION['session'])) $app->redirect($app->urlFor('login'));
    //si hay sesion abierta
  
    require_once 'models/Selectores.php';
    require_once 'models/Pedidos.php';

    $pedidos = new Pedidos();
    $pedidos->setPagado("Y");
    $result = $pedidos->selectPedidosByPagado();
 
    $selector = new Selectores();
    $userAr = $selector->returnRol();

    $app->render('pedidos/pedidos-terminados.html.twig', array(
        'pedidos'=>$result, 'user' => $userAr));

    
})->name('pedidos-terminados');

/*----------------------------------- Pagina Pedidos Incobrables-----------------------------------------------*/
$app->get('/pedidos-incobrables', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Pedidos.php';

        $pedidos = new Pedidos();
        $pedidos->setPagado("I");
        $result = $pedidos->selectPedidosByPagado();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('pedidos/pedidos-incobrables.html.twig', array(
            'pedidos'=>$result, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('pedidos-incobrables');


/*----------Insertar Pedido----------*/
$app->post('/pedido/nuevo', function() use($app){

    require_once 'models/Pedidos.php';
    require_once 'models/DetallePedido.php';

    $order = new Pedidos();
    $request = $app->request;

    $order->setNroPresupuesto(strtoupper($request->post('nro-order')));

    $fecha=date_create($request->post('date'));
    $dateOrder = date_format($fecha,"Y/m/d");
    $order->setFecha($dateOrder);

    /*$fechaEntrega=date_create($request->post('date-delivery'));
    $dateDelivery = date_format($fechaEntrega,"Y/m/d");
    $order->setEntregaEstimada($dateDelivery);*/

    $order->setIdCliente($request->post('customer'));
    $order->setFacturado('N');
    $order->setPagado('N');
    $order->setSenha(0);

    $idInsertado=$order->insertPedidos();
    //echo $order->getFecha()."/".$order->getIdCliente()."/".$order->getNroPresupuesto()."/".$insertado;/*
    if($idInsertado>0){
        
       /* $detailOrder = new DetallePedido();
        $detailOrder->setIdPedidos($idInsertado);
        $detailOrder->setDetalle(strtoupper($request->post('producto')));
        $detailOrder->setCantidad($request->post('cantidad'));
        $detailOrder->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
        //echo $detailOrder->getIdPedidos()."/".$detailOrder->getCantidad()."/".$detailOrder->getCosto()."/".$detailOrder->getDetalle();
        $detailOrder->insertDetailOrder();*/
        $app->redirect($app->urlFor('detalle-pedido',['id'=>$idInsertado]));

    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Ya existe el pedido en la Base de Datos');
        $app->redirect($app->urlFor('new-pedido'));
    }


})->name('insert-order');


/*----------Cancelar Pedido----------*/
$app->get('/cancel-order/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Pedidos.php';
        $order = new Pedidos();
        $order->setIdPedidos($id);
        $order->setPagado("C");

        $cancelado = $order->cancelarPedido();
        if($cancelado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Pedido Cancelado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido cancelar');
        }

        $app->redirect($app->urlFor('pedidos'));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('cancel-order');

/*----------Update Entrega----------*/
$app->post('/pedido/entregar', function() use($app){

    require_once 'models/Pedidos.php';
    $order = new Pedidos();
    $request = $app->request;

    $order->setIdPedidos($request->post('idpedidos'));
    $fecha=date_create($request->post('entrega'));
    $dateOrder = date_format($fecha,"Y/m/d");
    $order->setEntrega($dateOrder);

    $actualizado=$order->updateEntrega();
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Entregado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido entregar');
    }

    $app->redirect($app->urlFor('pedidos'));

})->name('update-entrega');

/*----------Terminar Pedido----------*/
$app->get('/terminar-pedido/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Pedidos.php';
        $order = new Pedidos();

        $order->setIdPedidos($id);
        $order->setPagado('Y');
        $actualizado=$order->cancelarPedido();
        
        if($actualizado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Pedido Terminado Correctamente');
        }

        $app->redirect($app->urlFor('pedidos-terminados'));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('end-order');


/*----------Update Cabecera Pedido----------*/
$app->post('/pedido/update-cabecera', function() use($app){

    require_once 'models/Pedidos.php';
    $order = new Pedidos();
    $request = $app->request;

    $order->setIdPedidos($request->post('idpedido'));

    $fecha=date_create($request->post('date'));
    $dateOrder = date_format($fecha,"Y/m/d");
    $order->setFecha($dateOrder);

    $order->setIdCliente($request->post('customer'));
    $order->setNroPresupuesto(strtoupper($request->post('number-order')));
    $order->setFactura(strtoupper($request->post('number-facture')));


    $actualizado=$order->updatePedido();
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Actualizado correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido actualizar');
    }

    $app->redirect($app->urlFor('detalle-pedido',['id'=>$order->getIdPedidos()]));

})->name('update-pedido');

