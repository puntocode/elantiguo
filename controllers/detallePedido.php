<?php

/*-------------------------------Index Detalle Pedido-----------------------------------------*/
$app->get('/detalle-pedido/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/DetallePedido.php';
        require_once 'models/Pedidos.php';
        require_once 'models/Cobros.php';
        require_once 'models/Deudas.php';

        $detailOrder = new DetallePedido();
        $detailOrder->setIdPedidos($id);
        $result = $detailOrder->selectPedido();
        $sumTotal = $detailOrder->selectTotalProductos();

        $pedido = new Pedidos();
        $pedido->setIdPedidos($id);
        $resultPedido = $pedido->selectPedido();
        //var_dump($resultPedido);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $productos = $selector->uploadProducto();
        $customer = $selector->cargarClientes();

        $pay = new Cobros();
        $pay->setIdPedidos($id);
        $resultCobro = $pay->selectCobro();
        $sumCobro = $pay->sumCobros();

        $debt = new Deudas();
        $debt->setIdPedidos($id);
        $resultDeuda = $debt->selectDeuda();
        $sumDeuda = $debt->sumDeudas();

        $pedido->setAbonado($sumCobro["total"]);
        $pedido->updateAbonado();

        $saldo = $sumTotal["total"] - ($sumCobro["total"]+$resultPedido["senha"]);
        
        $totales = array(
            "total_productos" => $sumTotal["total"],
            "total_cobro" => $sumCobro["total"],
            "total_deuda" => $sumDeuda["total"],
            "saldo" => $saldo
        );

        $app->render('pedidos/detalle-pedido.html.twig', 
            array(
                'detallepedido'=>$result, 'totales'=>$totales, 'pedido'=>$resultPedido, 'productos'=>$productos,
                'clientes'=>$customer, 'cobro'=>$resultCobro, 'deuda'=>$resultDeuda, 'user' => $userAr
            )
        );

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('detalle-pedido');

/*----------Insertar Detalle Pedido----------
$app->post('/detalle-pedido/nuevo', function() use($app){

    require_once 'models/DetallePedido.php';
    $order = new DetallePedido();
    $request = $app->request;

    $order->setDetalle(strtoupper($request->post('producto')));
    $order->setCantidad($request->post('cantidad'));
    $order->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
    $order->setIdPedidos($request->post('idpedido'));

    $insertado=$order->insertDetailOrder();
    if($insertado<=0){
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Ya existe el cliente en la Base de Datos');
    }

    $app->redirect($app->urlFor('detalle-pedido',['id'=>$order->getIdPedidos()]));

})->name('insert-detail-order');*/

/*----------Insertar Detalle Pedido AJAX----------*/
$app->post('/detalle-pedido/productos', function() use($app){
    //si hay sesion abierta
    require_once 'models/DetallePedido.php';
    $order = new DetallePedido();
    $request = $app->request;

    $order->setDetalle(strtoupper($request->post('producto')));
    $order->setCantidad($request->post('cantidad'));
    $order->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
    $order->setIdPedidos($request->post('idpedido'));

    $insertado=$order->insertDetailOrder();
    echo $insertado;

})->name('insert-detail-order');

/*----------Elimina detalle pedido----------*/
$app->get('/detalle-pedido/delete/:id-:idpedido', function($id,$idpedido) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/DetallePedido.php';

        $order = new DetallePedido();
        $order->setIdDetallePedido($id);
        $eliminado = $order->deleteDetailOrder();

        if($eliminado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Producto Cancelado');
        }

        $app->redirect($app->urlFor('detalle-pedido',['id'=>$idpedido]));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-detalle-pedido');

/*----------Update Senha----------*/
$app->post('/detalle-pedido/cargar-senha', function() use($app){

    require_once 'models/Pedidos.php';
    $order = new Pedidos();
    $request = $app->request;

    $order->setIdPedidos($request->post('idpedido'));
    $order->setSenha(intval(preg_replace('/[^0-9]+/','', $request->post('sign'))));

    $actualizado=$order->updateSenha();
    if($actualizado){
        $app->flash('mensaje-senha', 'Seña cargada correctamente');
    }

    $app->redirect($app->urlFor('detalle-pedido',['id'=>$order->getIdPedidos()]));

})->name('update-senha');

/*----------Update Observacion----------*/
$app->post('/detalle-pedido/cargar-obs', function() use($app){

    require_once 'models/Pedidos.php';
    $order = new Pedidos();
    $request = $app->request;

    $order->setIdPedidos($request->post('idpedido'));
    $order->setObs($request->post('obs'));

    $actualizado=$order->updateObs();
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Observacion guardada correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido guardar');
    }

    $app->redirect($app->urlFor('detalle-pedido',['id'=>$order->getIdPedidos()]));

})->name('update-obs');

/*--------------------------------Ver Pedido Terminado---------------------------------------*/
$app->get('/ver-pedido/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/DetallePedido.php';
        require_once 'models/Pedidos.php';

        $detailOrder = new DetallePedido();
        $detailOrder->setIdPedidos($id);
        $result = $detailOrder->selectPedido();
        $sumTotal = $detailOrder->selectTotalProductos();

        $pedido = new Pedidos();
        $pedido->setIdPedidos($id);
        $resultPedido = $pedido->selectPedido();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $productos = $selector->uploadProducto();
        $customer = $selector->cargarClientes();

        $app->render('pedidos/pedido-terminado-detalle.html.twig', array(
            'order'=> $resultPedido, 'detailOrder'=> $result, 'total'=> $sumTotal, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('order-view');

/*--------------------------------Ver Pedido Incobrable--------------------------------------------------------*/
$app->get('/ver-pedido-incobrable/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/DetallePedido.php';
        require_once 'models/Pedidos.php';

        $detailOrder = new DetallePedido();
        $detailOrder->setIdPedidos($id);
        $result = $detailOrder->selectPedido();
        $sumTotal = $detailOrder->selectTotalProductos();

        $pedido = new Pedidos();
        $pedido->setIdPedidos($id);
        $resultPedido = $pedido->selectPedido();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $productos = $selector->uploadProducto();
        $customer = $selector->cargarClientes();

        $app->render('pedidos/pedidos-incobrables-detalle.html.twig', array(
            'order'=> $resultPedido, 'detailOrder'=> $result, 'total'=> $sumTotal, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('order-bad-view');