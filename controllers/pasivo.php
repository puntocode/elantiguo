<?php

/*----------Index Gastos----------*/
$app->get('/gastos', function() use($app){

    //if(empty($_SESSION['session'])) $app->redirect($app->urlFor('login'));
    
    require_once 'models/Pasivo.php';
    require_once 'models/Selectores.php';

    $mes = date("m");
    $anho=date("Y");
    $egress = new Pasivo();
    $egressMonth = $egress->egrressMonth($mes, $anho);
    $egressSum = $egress->sumTotal($mes, $anho);

    $selector = new Selectores();
    $userAr = $selector->returnRol();
    $provider = $selector->uploadProvider();

    $app->render('egress/egress.html.twig', array(
        'egress' => $egressMonth, 
        'provider' => $provider,
        'suma' => $egressSum, 
        'user' => $userAr
    ));

})->name('egress');

/*----------Cargar Gastos----------*/
$app->get('/cargar-gastos', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $provider = $selector->uploadProvider();
        $stock = $selector->cargarStock();

        $app->render('egress/upload-egress.html.twig', array(
            'provider' => $provider, 'stock' => $stock, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('upload-egress');

/*----------INSERT CABECERA FACTURA----------*/
$app->post('/cargar-gastos/cabecera', function() use($app){
    
    require_once 'models/Pasivo.php';
    $egress = new Pasivo();
    $request = $app->request;

    $fecha = date_create($request->post('date'));
    $date = date_format($fecha,"Y/m/d");

    $egress->setFecha($date);
    $egress->setIdproveedor($request->post('provider'));
    $egress->setNroFactura($request->post('fac-number'));
    $egress->setEstado('C');

    $egress->insertEgress();
    echo true;

})->name('insert-pasivo');

$app->post('/egress/insert', function() use($app){
    try{
        require_once 'models/Pasivo.php';
        require_once 'models/DetallePasivo.php';
        //$request = $app->request;
        $request = file_get_contents("php://input");
        $data = (object) json_decode( $request, true );

        $egress = new Pasivo();
        $egress->setFecha($data->fecha);
        $egress->setNroFactura($data->nro_factura);
        $egress->setIdproveedor($data->idproveedor);
        $egress->setEstado('C');
        
        $lastInsert = $egress->insertEgress();

        foreach ($data->detallePasivo as $detail) {
            $detailObj = (object) $detail;

            $detailEgress = new DetallePasivo();
            $detailEgress->setIdPasivo($lastInsert);
            $detailEgress->setCantidad($detailObj->cantidad);
            $detailEgress->setCosto($detailObj->costo);
            $detailEgress->setProducto($detailObj->producto);
            $detailEgress->setImpuesto($detailObj->impuesto);
            $detailEgress->setTotal($detailObj->total);
            $detailEgress->setIdInventario(0);

            $detailEgress->insertDetallePasivo();
        }

        echo $lastInsert;
    }catch(Exception $e){
        echo $e->getMessage();  
    }


    
    // if($result){

    // }
    // echo json_encode($result);

});



/*------------------------------------INSERT Descuento-----------------------------------------------------------------*/
$app->post('/cargar-descuento', function() use($app){

    require_once 'models/Pasivo.php';
    $egress = new Pasivo();
    $request = $app->request;
    
    $descuento=$request->post('descuento');
    $egress->setDescuento(intval(preg_replace('/[^0-9]+/','', $descuento)));
    $idPasivo= $egress->selectMaxId();
    $egress->setIdpasivo($idPasivo['codigo']);
    
    $insertado=$egress->insertDescuento();
    if($insertado) {
        $descontado= "Descuento: ".$descuento." Gs.";
    }else{
        $descontado= "Descuento: 0 Gs.";
    }

    echo $descontado;

})->name('insert-descuento');

/*------------ANULAR FACTURA-------------*/
$app->get('/anular-factura/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Pasivo.php';
        $egress = new Pasivo();
        $egress->setIdpasivo($id);
        $anulado = $egress->nullFacture();

        if($anulado > 0 ){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Anulado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido anular!!');
        }

        $app->redirect($app->urlFor('egress'));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('null-facture');

/*------------ACTUALIZAR FACTURA-------------*/
$app->post('/update-pasivo', function() use($app){

    require_once 'models/Pasivo.php';
    $egress = new Pasivo();
    $request = $app->request;

    $egress->setIdpasivo($request->post('id-egress'));
    $egress->setIdproveedor($request->post('provider'));
    $date=date_create($request->post('fecha-factura'));
    $egress->setFecha(date_format($date,"Y-m-d"));
    $egress->setNroFactura($request->post('nro-factura'));

    echo $egress->getIdpasivo()."/".$egress->getFecha()."/".$egress->getNroFactura()."/".$egress->getIdproveedor();

    $actualizado = $egress->updateFacture();
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Editado Correctamente');
    }

    $app->redirect($app->urlFor('egress'));

})->name('update-pasivo');


/*---------------------------Reportes Inventario-----------------------------*/
$app->get('/facturas/reporte', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Pasivo.php';
        require_once 'models/Selectores.php';

        $mes=date("m");
        $anho=date("Y");
        $egress = new Pasivo();
        $egressMonth = $egress->egrressMonth($mes, $anho);
        $egressSum = $egress->sumTotal($mes,$anho);

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('egress/reportes.html.twig', array('egress'=> $egressMonth, 'total'=>$egressSum, 'user'=>$userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('report-egress');

/*----------BUSQUEDA INVENTARIO----------*/
$app->post('/search-egress', function() use($app){

    require_once 'models/Pasivo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $request = $app->request;

    $desde=date_create($request->post('desde'));
    $desdeSen = date_format($desde,"Y/m/d");
    $hasta=date_create($request->post('hasta'));
    $hastaSen = date_format($hasta,"Y/m/d");

    $sentencia = "SELECT p.idpasivo, p.fecha, v.proveedor, p.nro_factura, SUM(d.total) total, SUM(d.impuesto) impuesto, p.estado
                  FROM pasivo p, detalle_pasivo d, proveedor v
                  WHERE p.idpasivo = d.idpasivo and p.idproveedor=v.idproveedor AND p.fecha BETWEEN '$desdeSen' AND '$hastaSen'
                  GROUP BY idpasivo
                  ORDER BY p.fecha;";
    $egressMonth = $selector->cargarFetchAll($sentencia);

    $sentenciaSum = "SELECT SUM(d.total) total
                     FROM pasivo p, detalle_pasivo d
                     WHERE p.idpasivo=d.idpasivo
                     AND p.fecha BETWEEN '$desdeSen' AND '$hastaSen'
                     AND p.estado='C';";
    $egressSum = $selector->cargarFetch($sentenciaSum);

    $userAr = $selector->returnRol();
    $app->render('egress/reportes.html.twig', array('egress'=> $egressMonth, 'total'=>$egressSum, 'user'=>$userAr));

})->name('search-egress');


/*---------------------------------------------INDEX REPORTE EGRESO---------------------------------------------------*/
$app->get('/reporte-egreso', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Pasivo.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('egress/report-egress.html.twig', array(
            'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('report-egress-search');

/*-----------------------------------------------BUSQUEDA CLIENTE INGRESO---------------------------------------------*/
$app->post('/busqueda-egreso-proveedor', function() use($app){

    require_once 'models/Pasivo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $egress = new Pasivo();
    $request = $app->request;

    $egress->setEstado($request->post('provider'));
    $result = $egress->searchProvider();
    $userAr = $selector->returnRol();

    $app->render('egress/report-egress.html.twig', array(
        'egress' => $result, 'user' => $userAr));

})->name('search-provider-egress');

/*-----------------------------------------------BUSQUEDA CLIENTE INGRESO---------------------------------------------*/
$app->post('/busqueda-egreso-producto', function() use($app){

    require_once 'models/Pasivo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $egress = new Pasivo();
    $request = $app->request;

    $egress->setEstado($request->post('product'));
    $result = $egress->searchProduct();
    $userAr = $selector->returnRol();

    $app->render('egress/report-egress.html.twig', array(
        'egress' => $result, 'user' => $userAr));

})->name('search-product-egress');