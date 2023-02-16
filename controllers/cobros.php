<?php

/*----------Insertar cobro Ajax----------*/
$app->post('/cobro/nuevo', function() use($app){

    require_once 'models/Cobros.php';
    require_once 'models/Deudas.php';

    $pay = new Cobros();
    $request = $app->request;

    $fecha=date_create($request->post('fecha'));
    $date = date_format($fecha,"Y/m/d");
    $pay->setFecha($date);

    $pay->setCobro(intval(preg_replace('/[^0-9]+/','', $request->post('cobro'))));
    $pay->setIdPedidos($request->post('idpedido'));
    $pay->setObs($request->post('obs'));

    $iddeuda = $request->post('iddeuda');
    if($iddeuda>0){
        $deuda = new Deudas();
        $deuda->setIdDeuda($iddeuda);
        $deuda->setPagado("Y");
        $deuda->updatePagado();
    }

    $insertado=$pay->insertCobros();
    echo $insertado;

})->name('insert-pay');

/*----------INSERT RECIBO DE DINERO AJAX----------*/
$app->post('/cobro/detalle-activo', function() use($app){

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
    $ingress->setTipoIngress("M");

    ///echo $ingress->getFecha()."/".$ingress->getNroFactura()."/".$ingress->getIdCliente()."/".$ingress->getEstado()."/".$ingress->getTipoIngress();
    $insertActivo=$ingress->insertActivoLastId();

    if($insertActivo) {
        $detailActivo = new DetalleActivo();
        $detailActivo->setIdActivo($insertActivo);
        $detailActivo->setCantidad(1);
        $detailActivo->setProducto(strtoupper($request->post('producto')));
        $detailActivo->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
        $total = $detailActivo->getCantidad()*$detailActivo->getCosto();
        $detailActivo->setTotal($total);
        //echo $detailActivo->getIdActivo()."/".$detailActivo->getCantidad()."/".$detailActivo->getCosto()."/".$detailActivo->getProducto()."/".$detailActivo->getTotal();
        $detailActivo->insertDetalleActivo();
    }
    
    echo $insertActivo;

})->name('insert-recibo-ajax');

/*----------Cancela Cobro----------*/
$app->get('/detalle-pedido/cancel-pay/:id-:idpedido', function($id,$idpedido) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Cobros.php';

        $pay = new Cobros();
        $pay->setIdCobro($id);
        echo $pay->getIdCobro()."/".$idpedido;
        $eliminado = $pay->cancelCobro();

        if($eliminado){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'Cobro Cancelado');
        }

        $app->redirect($app->urlFor('detalle-pedido',['id'=>$idpedido."#cobros"]));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('cancel-pay');

/*----------Cobrar Deuda----------*/
$app->post('/deuda/cobrar', function() use($app){

    require_once 'models/Deudas.php';
    require_once 'models/Cobros.php';

    $debt = new Deudas();
    $pay = new Cobros();

    $request = $app->request;
    
    $date = date("Y/m/d");
    $pay->setFecha($date);
    $pay->setCobro((preg_replace('/[^0-9]+/','', $request->post('cobrar'))));
    $pay->setIdPedidos($request->post('idpedidos'));
    $pay->setObs($request->post('obs-pay'));
    $insertado=$pay->insertCobros();
    
    $debt->setIdDeuda($request->post('iddeuda'));
    $debt->setPagado("Y");
    $debt->updatePagado();

   
    if($insertado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Pagado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido pagar');
    }

    $app->redirect($app->urlFor('detalle-pedido',['id'=>$pay->getIdPedidos()."#cobros"]));

})->name('pay-debt');