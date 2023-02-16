<?php
/*----------Index Dashboard----------*/
$app->get('/dashboard/:mes-:anho', function($mes, $anho) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';
        require_once 'models/Sueldo.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(10);
        
        $dash->setMovimiento("S");
        $salida = $dash->cantidadInventario();

        $dash->setMovimiento("E");
        $entrada = $dash->cantidadInventario();

        $egreso = $dash->sumEgreso();
        $exentos = $dash->returnGastosExentos();
        $impuestos = $dash->returnGastosIva();

        $dash->setEstado("F");
        $dash->setTipoIngress("M");
        $ingressFM = $dash->sumIngress();
        $dash->setTipoIngress("D");
        $ingressFD = $dash->sumIngress();

        $dash->setEstado("R");
        $dash->setTipoIngress("M");
        $ingressRM = $dash->sumIngress();
        $dash->setTipoIngress("D");
        $ingressRD = $dash->sumIngress();

        //Pagos en sueldo del mes
        $sueldo = new Sueldo();
        $inicio = $anho."-".$mes."-01";
        $fin = date("Y-m-t", strtotime($inicio));
        $sum = $sueldo->sumSueldo($inicio,$fin);

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho()
        );

        $letreros = array(
            "egreso" => $egreso["egreso"],
            "total_exento"=>$exentos["exento"],
            "total_iva"=>$impuestos["impuesto"],
            "ingreso_factura_money" => $ingressFM["total"],
            "ingress_factura_doc" => $ingressFD["total"],
            "ingreso_recibo_money" => $ingressRM["total"],
            "ingress_recibo_doc" => $ingressRD["total"],
            "sueldo" => $sum["total"],
            "ganancia" => (($ingressFM["total"]+$ingressRM["total"])-($egreso["egreso"]+$sum["total"]))
        );

        $app->render('dashboard/dashboard.html.twig', array('user' => $userAr, 'tableIn' => $entrada,
            'tableOut' => $salida, 'fecha' => $fecha, 'letrero' => $letreros));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('dashboard');

/*----------Tablas Dashboard----------*/
$app->get('/dashboard/tabla-movimiento/:movimiento-:mes-:anho', function($movimiento,$mes,$anho) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(1000);
        $dash->setMovimiento($movimiento);
        $salida = $dash->cantidadInventario();
        
        if($dash->getMovimiento()=="S"){
            $mov="Salida";
        }else{
            $mov="Entrada";
        }

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho(),
            "movimiento" => $mov
        );

        $app->render('dashboard/table-dash.html.twig', array('user' => $userAr, 'table' => $salida, 'fecha' => $fecha));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('table-movimiento');

/*----------Chart Egreso----------*/
$app->get('/dashboard/:mes-:anho/egreso', function($mes, $anho) use ($app) {
    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        $dash = new Dashboard();

        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(8);
        $data = $dash->chartProveedores();
        print json_encode($data, JSON_NUMERIC_CHECK);
        
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
});


/*----------Tablas Proveedores----------*/
$app->get('/dashboard/tabla-egreso/:mes-:anho', function($mes,$anho) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(1000);
        //$dash->setMovimiento($movimiento);
        $egress = $dash->chartProveedores();

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho(),
        );

        $app->render('dashboard/proveedores/table-provider.html.twig', array('user' => $userAr, 'table' => $egress, 'fecha' => $fecha));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('table-egress');

$app->get('/dashboard/tabla-detail-provider/:mes-:anho/:id', function($mes,$anho,$id) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setIdProveedor($id);
        //$dash->setMovimiento($movimiento);
        $egress = $dash->detailProvider();
        $provider = $dash->sumProvider();

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho(),
            "proveedor" => $provider["proveedor"],
            "total" => $provider["total"]
        );

        $app->render('dashboard/proveedores/detail-provider.html.twig', array('user' => $userAr, 'table' => $egress, 'fecha' => $fecha));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('table-detail-provider');
/*----------Tablas Proveedores----------*/


/*---------------------------------------------------Clientes----------------------------------------------------------------------*/
$app->get('/dashboard/:mes-:anho/clientes', function($mes, $anho) use ($app) {
    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        $dash = new Dashboard();

        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(8);
        $data = $dash->chartCliente();
        print json_encode($data, JSON_NUMERIC_CHECK);

    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
});

$app->get('/dashboard/tabla-cliente/:mes-:anho', function($mes,$anho) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(1000);
        //$dash->setMovimiento($movimiento);
        $egress = $dash->chartCliente();

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho(),
        );

        $app->render('dashboard/clientes/table-clientes.html.twig', array('user' => $userAr, 'table' => $egress, 'fecha' => $fecha));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('table-customer');

$app->get('/dashboard/tabla-detail-customer/:mes-:anho/:id', function($mes,$anho,$id) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setIdCliente($id);
        //$dash->setMovimiento($movimiento);
        $egress = $dash->detailCustomer();
        $provider = $dash->sumCustomer();

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho(),
            "cliente" => $provider["cliente"],
            "total" => $provider["total"]
        );

        $app->render('dashboard/clientes/detail-customer.html.twig', array('user' => $userAr, 'table' => $egress, 'fecha' => $fecha));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('table-detail-customer');
/*---------------------------------------------------Clientes----------------------------------------------------------------------*/




/*---------------------------------------------------PRODUCTOS----------------------------------------------------------------------*/
$app->get('/dashboard/:mes-:anho/productos', function($mes, $anho) use ($app) {
    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        $dash = new Dashboard();

        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(8);
        $data = $dash->chartProductos();
        print json_encode($data, JSON_NUMERIC_CHECK);

    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
});

$app->get('/dashboard/tabla-productos/:mes-:anho', function($mes,$anho) use($app){

    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Dashboard.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $dash = new Dashboard();
        $dash->setMes($mes);
        $dash->setAnho($anho);
        $dash->setLimit(1000);
        //$dash->setMovimiento($movimiento);
        $product = $dash->chartProductos();

        $fecha=array(
            "mes" => $dash->getMes(),
            "anho" => $dash->getAnho(),
        );

        $app->render('dashboard/productos/table-product.html.twig', array('user' => $userAr, 'table' => $product, 'fecha' => $fecha));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }

})->name('table-product');
/*---------------------------------------------------PRODUCTOS----------------------------------------------------------------------*/




/*-------------Balances-----------------*/
$app->get('/dashboard/:mes-:anho/balance', function($mes, $anho) use ($app) {
    require_once 'models/Dashboard.php';
    $dash = new Dashboard();
    $dash->setAnho($anho);
    $ingreso = $dash->sumIngressAnual();
    $egreso = $dash->sumEgressAnual();

    $balance=array(
        0=>array(
            "label"=>"Enero",
            "Ingreso"=>0,
            "Egreso"=>0
        ),
        1=>array(
            "label"=>"Febrero",
            "Ingreso"=>0,
            "Egreso"=>0
        ),
        2=>array(
            "label"=>"Marzo",
            "Ingreso"=>0,
            "Egreso"=>0
        ),
        3=>array(
            "label"=>"Abril",
            "Ingreso"=>0,
            "Egreso"=>0
        ),
        4=>array(
            "label"=>"Mayo",
            "Ingreso"=>0,
            "Egreso"=>0,
            ),
        5=>array(
            "label"=>"Junio",
            "Ingreso"=>0,
           "Egreso"=>0,
           ),
        6=>array(
            "label"=>"Julio",
            "Ingreso"=>0,
            "Egreso"=>0,
            ),
        7=>array(
            "label"=>"Agosto",
            "Ingreso"=>0,
            "Egreso"=>0,
            ),
        8=>array(
            "label"=>"Septiembre",
            "Ingreso"=>0,
            "Egreso"=>0,
          ),
        9=>array(
            "label"=>"Octubre",
            "Ingreso"=>0,
            "Egreso"=>0,
         ),
        10=>array(
            "label"=>"Noviembre",
            "Ingreso"=>0,
            "Egreso"=>0,
        ),
        11=>array(
            "label"=>"Diciembre",
            "Ingreso"=>0,
            "Egreso"=>0,
        )
    );


    foreach($egreso as $item) {
        if ($item["mes"] == "01") {
            $balance[0]["Egreso"]=$item["total"];
        } elseif ($item["mes"] == "02") {
            $balance[1]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "03") {
            $balance[2]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "04") {
            $balance[3]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "05") {
            $balance[4]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "06") {
            $balance[5]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "07") {
            $balance[6]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "08") {
            $balance[7]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "09") {
            $balance[8]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "10") {
            $balance[9]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "11") {
            $balance[10]["Egreso"]=$item["total"];
        }elseif ($item["mes"] == "12") {
            $balance[11]["Egreso"]=$item["total"];
        }
    }

    foreach($ingreso as $item) {
        if ($item["mes"] == "01") {
            $balance[0]["Ingreso"]=$item["total"];
        } elseif ($item["mes"] == "02") {
            $balance[1]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "03") {
            $balance[2]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "04") {
            $balance[3]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "05") {
            $balance[4]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "06") {
            $balance[5]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "07") {
            $balance[6]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "08") {
            $balance[7]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "09") {
            $balance[8]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "10") {
            $balance[9]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "11") {
            $balance[10]["Ingreso"]=$item["total"];
        }elseif ($item["mes"] == "12") {
            $balance[11]["Ingreso"]=$item["total"];
        }
    }

    //var_dump($balance);
     print json_encode($balance, JSON_NUMERIC_CHECK);
});