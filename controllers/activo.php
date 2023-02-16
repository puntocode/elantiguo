<?php

/*----------Index Gastos----------*/
$app->get('/ingresos', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/Selectores.php';

        $mes=date("m");
        $anho=date("Y");
        $ingress = new Activo();
        $ingress->setEstado("F");
        $ingresMonth = $ingress->ingressMonth($mes, $anho);

        $ingress->setTipoIngress("M");
        $totalMoney = $ingress->sumporTipoIngres($mes,$anho);
        $totalM = $totalMoney["total"];

        $ingress->setTipoIngress("D");
        $totalDocument = $ingress->sumporTipoIngres($mes,$anho);
        $totalD = $totalDocument["total"];

        $ingress->setTipoIngress("A");
        $totalNull = $ingress->sumporTipoIngres($mes,$anho);
        $totalA = $totalNull["total"];

        $sumTotal = $totalM+$totalD;

        $total = array(
            "money"  => $totalM,
            "document" => $totalD,
            "null" => $totalA,
            "total" => $sumTotal
        );


        $fechas = array(
            "mes"  => $mes,
            "anho" => $anho,
        );

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('ingress/ingress.html.twig', array(
            'ingress' => $ingresMonth, 'total' => $total, 'fechas' => $fechas, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('ingress');


/*----------Busqueda Mensual Ingresos----------*/
$app->post('/busqueda-ingreso', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $request = $app->request;
    $mes=$request->post('mes');
    $anho=$request->post('anho');

    $ingress = new Activo();
    $ingress->setEstado("F");
    $ingresMonth = $ingress->ingressMonth($mes, $anho);

    $ingress->setTipoIngress("M");
    $totalMoney = $ingress->sumporTipoIngres($mes,$anho);
    $totalM = $totalMoney["total"];

    $ingress->setTipoIngress("D");
    $totalDocument = $ingress->sumporTipoIngres($mes,$anho);
    $totalD = $totalDocument["total"];

    $ingress->setTipoIngress("A");
    $totalNull = $ingress->sumporTipoIngres($mes,$anho);
    $totalA = $totalNull["total"];

    $sumTotal = $totalM+$totalD;

    $total = array(
        "money"  => $totalM,
        "document" => $totalD,
        "null" => $totalA,
        "total" => $sumTotal
    );

    $fechas = array(
        "mes"  => $mes,
        "anho" => $anho,
    );

    $userAr = $selector->returnRol();

    $app->render('ingress/ingress.html.twig', array(
        'ingress' => $ingresMonth, 'total' => $total, 'fechas' => $fechas, 'user' => $userAr));

})->name('busqueda-ingress');

/*----------Cargar Facturas----------*/
$app->get('/ingresos-factura', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $client = $selector->cargarClientes();
        $products = $selector->uploadProducto();

        $app->render('ingress/ingress-up.html.twig', array(
            'client' => $client, 'productos' => $products, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('ingress-facture');

/*----------Actualizar Activo----------*/
$app->post('/ingresos-factura/update', function() use($app){

    require_once 'models/Activo.php';
    $ingress = new Activo();
    $request = $app->request;

    $ingress->setIdActivo($request->post('idactivoup'));
    $ingress->setNroFactura($request->post('nro-factura'));
    $ingress->setIdCliente($request->post('cliente'));

    $fecha=date_create($request->post('fecha-factura'));
    $date = date_format($fecha,"Y/m/d");
    $ingress->setFecha($date);

    $on=$request->post('switch');
    if($on)
        $ingress->setTipoIngress("M");
    else
        $ingress->setTipoIngress("D");

    //echo $ingress->getFecha()."/".$ingress->getIdActivo()."/".$ingress->getNroFactura()."/".$ingress->getIdCliente()."/".$ingress->getEstado();
    
    $actualizado = $ingress->updateActivo();
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Editado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido modificar');
    }

    $app->redirect($app->urlFor('detalle-factura-activo',['id'=>$ingress->getIdActivo()]));

})->name('update-activo-factura');


/*----------Index RECIBO----------*/
$app->get('/ingresos-recibos', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/Selectores.php';

        $mes=date("m");
        $anho=date("Y");
        $ingress = new Activo();
        $ingress->setEstado("R");
        $ingresMonth = $ingress->recibosMes($mes, $anho);

        $ingress->setTipoIngress("M");
        $totalMoney = $ingress->sumporTipoIngres($mes,$anho);
        $totalM = $totalMoney["total"];

        $ingress->setTipoIngress("D");
        $totalDocument = $ingress->sumporTipoIngres($mes,$anho);
        $totalD = $totalDocument["total"];

        $ingress->setTipoIngress("A");
        $totalNull = $ingress->sumporTipoIngres($mes,$anho);
        $totalA = $totalNull["total"];

        $sumTotal = $totalM+$totalD;

        $total = array(
            "money"  => $totalM,
            "document" => $totalD,
            "null" => $totalA,
            "total" => $sumTotal
        );

        $fechas = array(
            "mes"  => $mes,
            "anho" => $anho,
        );

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('ingress/recibo.html.twig', array(
            'ingress' => $ingresMonth, 'total' => $total, 'fechas' => $fechas, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('ingress_recibo');

/*----------Cargar Recibo----------*/
$app->get('/ingresos-cargar-recibo', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $client = $selector->cargarClientes();
        $products = $selector->uploadProducto();

        $app->render('ingress/cargar-recibo.html.twig', array(
            'client' => $client, 'productos' => $products, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('ingress-recibo-upload');

/*----------Busqueda Mensual Recibos----------*/
$app->post('/busqueda-recibos', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $ingress = new Activo();
    $request = $app->request;

    $mes=$request->post('mes');
    $anho=$request->post('anho');
    $ingress->setEstado("R");
    $ingresMonth = $ingress->recibosMes($mes, $anho);

    $ingress->setTipoIngress("M");
    $totalMoney = $ingress->sumporTipoIngres($mes,$anho);
    $totalM = $totalMoney["total"];

    $ingress->setTipoIngress("D");
    $totalDocument = $ingress->sumporTipoIngres($mes,$anho);
    $totalD = $totalDocument["total"];

    $ingress->setTipoIngress("A");
    $totalNull = $ingress->sumporTipoIngres($mes,$anho);
    $totalA = $totalNull["total"];

    $sumTotal = $totalM+$totalD;

    $total = array(
        "money"  => $totalM,
        "document" => $totalD,
        "null" => $totalA,
        "total" => $sumTotal
    );

    $fechas = array(
        "mes"  => $mes,
        "anho" => $anho,
    );

    $userAr = $selector->returnRol();

    $app->render('ingress/recibo.html.twig', array(
        'ingress' => $ingresMonth, 'total' => $total, 'fechas' => $fechas, 'user' => $userAr));

})->name('busqueda-recibos');

/*----------ANULAR ACTIVO----------*/
$app->get('/ingresos/anular/:id-:pagina', function($id,$redirect) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';

        $ingress = new Activo();
        $ingress->setIdActivo($id);
        $ingress->setTipoIngress("A");
        $anulado = $ingress->anularActivo();

        if($anulado){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'ANULADO CORRECTAMENTE');
        }

        $app->redirect($app->urlFor($redirect));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('null-activo');



/*---------------------------------------------INDEX REPORTE INGRESO--------------------------------------------------*/
$app->get('/reporte-ingreso', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Activo.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        //$client = $selector->cargarClientes();
        //$products = $selector->uploadProducto();

        $app->render('ingress/report-ingress.html.twig', array(
            'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('report-ingress');

/*-----------------------------------------------BUSQUEDA CLIENTE INGRESO---------------------------------------------*/
$app->post('/busqueda-ingreso-cliente', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $ingress = new Activo();
    $request = $app->request;

    $ingress->setEstado($request->post('customer'));
    $result = $ingress->searchCustomer();
    $userAr = $selector->returnRol();

    $app->render('ingress/report-ingress.html.twig', array(
        'ingress' => $result, 'user' => $userAr));

})->name('search-customer-ingress');

/*-----------------------------------------------BUSQUEDA CLIENTE INGRESO---------------------------------------------*/
$app->post('/busqueda-ingreso-producto', function() use($app){

    require_once 'models/Activo.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $ingress = new Activo();
    $request = $app->request;

    $ingress->setEstado($request->post('product'));
    $result = $ingress->searchProduct();
    $userAr = $selector->returnRol();

    $app->render('ingress/report-ingress.html.twig', array(
        'ingress' => $result, 'user' => $userAr));

})->name('search-product-ingress');