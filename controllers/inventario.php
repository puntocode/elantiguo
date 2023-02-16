<?php

/*----------INDEX INVENTARIO----------*/
$app->get('/inventario', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Inventario.php';
        require_once 'models/Selectores.php';

        $inventario = new Inventario();
        $inventarioTable = $inventario->inventarioMes();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $selectEmpleado = $selector->cargarEmpleados();
        $selectStock = $selector->cargarStock();

        $app->render('inventario/inventario.html.twig', array(
            'user' => $userAr,'empleados' => $selectEmpleado, 'productos' => $selectStock,'inventario'=> $inventarioTable));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('inventario');

/*----------Nuevo Material Inventario----------*/
$app->get('/inventario/nuevo-material', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('inventario/new-stock.html.twig', array('user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('inventario-new-stock');

$app->post('/inventario/stock/new', function() use($app){

    require_once 'models/Stock.php';
    $stock = new Stock();
    $request = $app->request;

    $stock->setCodigo('100001001');
    $stock->setProducto(strtolower($request->post('producto')));
    $stock->setUbicacion(strtoupper($request->post('ubicacion')));
    $stock->setUnidad(strtoupper($request->post('unidad')));
    $stock->setCantidad(0);
    $stock->setStockMin($request->post('minimo'));
    $stock->setCosto($request->post('costo'));

    $insertado=$stock->insertarStock();

    if($insertado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Ya existe el producto en la Base de Datos');
    }
    $app->redirect($app->urlFor('inventario'));

})->name('inventario-insert-stock');


/*----------INSERTAR INVENTARIO----------*/
$app->post('/reg-inventario/new', function() use($app){

    require_once 'models/Inventario.php';
    require_once 'models/Abm.php';
    $inventario = new Inventario();
    $abm = new Abm();
    $request = $app->request;
    $bandera = 1;

    $date=date_create($request->post('fecha'));
    $inventario->setFecha(date_format($date,"Y/m/d"));
    $inventario->setIdstock($request->post('stock'));
    $inventario->setIdempleado($request->post('empleado'));
    $inventario->setCantidad($request->post('cantidad'));
    $inventario->setIduser($request->post('user'));
    $inventario->setMovimiento(strtoupper($request->post('movimiento')));
    $inventario->setMotivo(strtolower($request->post('motivo')));
    $inventario->setPendiente(strtolower($request->post('pendiente')));

    $sentencia = "SELECT cantidad,stock_min FROM stock WHERE idstock=".$inventario->getIdstock();
    $arrayStock = $abm->sentencias($sentencia);
    $existencia = $arrayStock['cantidad'];

    if($inventario->getMovimiento()=='E'){
        $inventario->registrarInventario();
        $stockNew = $existencia + $inventario->getCantidad();
        $inventario->setCantidad($stockNew);
        $inventario->updateStock();
        $inventario->setIdinventario($request->post('idinventario'));
        $inventario->updateInventario();
    }

    if($inventario->getMovimiento()=='S'){
        if($existencia<$inventario->getCantidad()){
            $bandera = 0;
        }else{
            $inventario->registrarInventario();
            $stockNew = $existencia - $inventario->getCantidad();
            $inventario->setCantidad($stockNew);
            $inventario->updateStock();
        }
    }

    echo $bandera;
})->name('insert-inventario');

/*----------INSERTAR INVENTARIO----------
$app->post('/reg-inventario/new', function() use($app){

    require_once 'models/Inventario.php';
    require_once 'models/Abm.php';
    $inventario = new Inventario();
    $abm = new Abm();
    $request = $app->request;

    $date=date_create($request->post('fecha'));
    $inventario->setFecha(date_format($date,"Y/m/d"));
    $inventario->setIdstock($request->post('stock'));
    $inventario->setIdempleado($request->post('empleado'));
    $inventario->setCantidad($request->post('cantidad'));
    $inventario->setIduser($request->post('user'));
    $inventario->setMovimiento(strtoupper($request->post('movimiento')));
    $inventario->setMotivo(strtolower($request->post('motivo')));
    $inventario->setPendiente(strtolower($request->post('pendiente')));

    $sentencia = "SELECT cantidad,stock_min FROM stock WHERE idstock=".$inventario->getIdstock();
    $arrayStock = $abm->sentencias($sentencia);
    $existencia = $arrayStock['cantidad'];

    if($inventario->getMovimiento()=='E'){
        $inventario->registrarInventario();
        $stockNew = $existencia + $inventario->getCantidad();
        $inventario->setCantidad($stockNew);
        $inventario->updateStock();
        $inventario->setIdinventario($request->post('idinventario'));
        $inventario->updateInventario();
    }

    if($inventario->getMovimiento()=='S'){
        if($existencia<$inventario->getCantidad()){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'La cantidad es mayor de lo existente');
            $app->redirect($app->urlFor('inventario'));
        }else{
            $inventario->registrarInventario();
            $stockNew = $existencia - $inventario->getCantidad();
            $inventario->setCantidad($stockNew);
            $inventario->updateStock();
        }
    }

    $app->flash('content', 'alert-success');
    $app->flash('mensaje', 'Insertado Correctamente');
    $app->redirect($app->urlFor('inventario'));

})->name('insert-inventario');*/

/*------------Eliminar Inventario-------------*/
$app->post('/inventario/delete', function() use($app){

    require_once 'models/Inventario.php';
    require_once 'models/Abm.php';

    $inventario = new Inventario();
    $request = $app->request;
    $inventario->setIdinventario($request->post('codigo-inventario'));
    $modificado = updateStock($inventario);
    $eliminado = 0;

    if($modificado){
        $eliminado = $inventario->deleteInventario();
    }

    if($eliminado > 0 ){
        $abm = new Abm();
        $abm->setTabla(strtolower('inventario'));
        $abm->setCampo("ELIMINADO");
        $motivo = $request->post('producto').": ".strtolower($request->post('comentario'));
        $abm->setMotivo($motivo);
        $abm->setIduser($request->post('user'));
        $abm->insertarAbm();

        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Eliminado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Error: no se puede modificar!');
    }

    $app->redirect($app->urlFor('inventario'));

})->name('delete-inventario');

//FUNCION MODIFICAR STOCK
function updateStock($inventario){
    require_once 'models/Stock.php';
    $stock = new Stock();

    $inventarioArr = $inventario->selectInventario();
    $stock->setIdStock($inventarioArr['idstock']);
    $arrStock = $stock->selectStockId();
    $cantidadStock = $arrStock['cantidad'];
    $cantidadMovimiento = $inventarioArr['cantidad'];

    if($inventarioArr['movimiento'] == 'S'){
        $cantidadNew = $cantidadStock+$cantidadMovimiento;
    }else{
        $cantidadNew = $cantidadStock-$cantidadMovimiento;
    }

    $stock->setCantidad($cantidadNew);
    echo $stock->getCantidad()."/".$stock->getIdstock();
    $modificado=$stock->updateMovimiento();

    if($modificado>0){
        return true;
    }else{
        return false;
    }
}


/*---------------------------Porcesos-----------------------------*/
$app->get('/inventario/proceso-bruto', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Inventario.php';

        $inventario= new Inventario();
        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $selectProducto = $selector->cargarBruto();
        $selectEmpleado = $selector->cargarEmpleados();
        $selectStock = $selector->cargarStock();

        $tablaPendiente = $inventario->pendientes();

        $app->render('inventario/bruto.html.twig', array(
            'user' => $userAr, 'empleado' => $selectEmpleado, 'stock' => $selectProducto, 'materiales' => $selectStock, 'pendiente' => $tablaPendiente));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('procesos');

/*----------Insertar Procesos----------*/
$app->post('/inventario/reg-bruto/new', function() use($app){

    require_once 'models/Inventario.php';
    require_once 'models/Stock.php';
    $request = $app->request;

    $inventario = new Inventario();
    $date=date_create($request->post('fecha'));
    $inventario->setFecha(date_format($date,"Y/m/d"));
    $inventario->setIdstock($request->post('stock'));
    $inventario->setIdempleado($request->post('empleado'));
    $inventario->setCantidad($request->post('cantidad'));
    $inventario->setIduser($request->post('user'));
    $inventario->setMovimiento(strtoupper($request->post('movimiento')));
    $inventario->setMotivo(strtolower($request->post('motivo')));
    $inventario->setPendiente(strtolower($request->post('pendiente')));

    $stock = new Stock();
    $stock->setIdStock($inventario->getIdstock());
    $arrayStock = $stock->selectStockId();
    $existencia = $arrayStock['cantidad'];

    if($inventario->getMovimiento()=='E'){
        $cantAnterior=$request->post('cant-anterior');
        $total = $cantAnterior-$inventario->getCantidad();

        if($total<0){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'La cantidad es mayor de lo existente');
            $app->redirect($app->urlFor('procesos'));
        }else{
            $inventario->registrarInventario();
            //actualiza el stock
            $stockNew = $existencia + $inventario->getCantidad();
            $inventario->setCantidad($stockNew);
            $inventario->updateStock();
            //actualiza el inventario
            $inventario->setIdinventario($request->post('idinventario'));
            $inventario->setCantidad($total);
            $inventario->updateCantInventario();
        }

        if($total==0){
            $inventario->setIdinventario($request->post('idinventario'));
            $inventario->updatePendiente();
        }

    }

    if($inventario->getMovimiento()=='S'){
        if($existencia<$inventario->getCantidad()){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'La cantidad es mayor de lo existente');
            $app->redirect($app->urlFor('procesos'));
        }else{
            $inventario->registrarInventario();
            $stockNew = $existencia - $inventario->getCantidad();
            $inventario->setCantidad($stockNew);
            $inventario->updateStock();
        }
    }

    $app->flash('content', 'alert-success');
    $app->flash('mensaje', 'Registrado Correctamente');
    $app->redirect($app->urlFor('procesos'));

})->name('insert-bruto');


/*---------------------------Reportes Inventario-----------------------------*/
$app->get('/inventario/reporte', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Inventario.php';
        require_once 'models/Selectores.php';

        $inventario = new Inventario();
        $inventarioTable = $inventario->inventarioMes();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        //var_dump($userAr);
        $app->render('inventario/reportes.html.twig', array('inventario'=> $inventarioTable, 'user'=>$userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('reporte-inventario');

/*----------BUSQUEDA INVENTARIO----------*/
$app->post('/busqueda-inventario', function() use($app){

    require_once 'models/Inventario.php';
    require_once 'models/Selectores.php';

    $selector = new Selectores();
    $inventario = new Inventario();
    $request = $app->request;

    $desde=date_create($request->post('desde'));
    $desdeSen = date_format($desde,"Y/m/d");
    $hasta=date_create($request->post('hasta'));
    $hastaSen = date_format($hasta,"Y/m/d");

    $sentencia = "SELECT i.movimiento, i.idinventario, i.fecha, s.producto, s.unidad, i.cantidad, i.motivo, e.nombre
                  FROM inventario i, stock s, empleado e
                  WHERE i.idstock = s.idstock and i.idempleado = e.idempleado AND i.fecha BETWEEN '$desdeSen' AND '$hastaSen'
                  ORDER BY i.fecha ASC;";
    $inventarioTable = $selector->cargarFetchAll($sentencia);
    $userAr = $selector->returnRol();

    $app->render('inventario/reportes.html.twig', array('inventario'=> $inventarioTable, 'user'=>$userAr));

})->name('busqueda-inventario');
