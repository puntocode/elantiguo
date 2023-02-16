<?php
/*--------------------------------Index Detalle Presupuestos---------------------------------------*/
$app->get('/detalle-presupuesto/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Presupuesto.php';
        require_once 'models/DetallePresupuesto.php';

        $budget = new Presupuesto();
        $budget->setIdPresupuesto($id);
        $result = $budget->selectById();
        //var_dump($result);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $products = $selector->uploadProducto();
        //var_dump($customer);

        $detailBudget = new DetallePresupuesto();
        $detailBudget->setIdPresupuesto($id);
        $resultDetail = $detailBudget->selectByIdpresupuestoActive();
        $sumTotal = $detailBudget->selectSumTotalByIdPresupuesto();

        $app->render('presupuesto/budget-detail.html.twig', array(
            'productos' => $products, 'budget'=> $result, 'detailBudget'=> $resultDetail, 'total'=> $sumTotal, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('budget-detail');

/*--------------------------------INSERT Detalle Presupuesto-------------------------------------------------*/
$app->post('/insert-detalle-presupuesto', function() use($app){

    require_once 'models/DetallePresupuesto.php';
    $request = $app->request;

    $detailBudget = new DetallePresupuesto();
    $detailBudget->setIdPresupuesto($request->post('id-presupuesto'));
    $detailBudget->setCantidad(intval(preg_replace('/[^0-9]+/','', $request->post('cantidad'))));
    $detailBudget->setProducto(strtoupper($request->post('producto')));
    $detailBudget->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('costo'))));
    
    $total = $detailBudget->getCantidad()*$detailBudget->getCosto();

    $detailBudget->setEstado("1");
    //$detailActivo->setTotal($total);
    //echo $detailBudget->getIdPresupuesto()."/".$detailBudget->getCantidad()."/".$detailBudget->getCosto()."/".$detailBudget->getProducto()."/".$detailBudget->getEstado()."/".$total;
    $insert=$detailBudget->insertDetallePresupuesto();
    if($insert){
        $app->redirect($app->urlFor('budget-detail',['id'=>$detailBudget->getIdPresupuesto()])); 
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido insertar');
    }
    $app->redirect($app->urlFor('budget-detail',['id'=>$detailBudget->getIdPresupuesto()])); 

})->name('insert-detail-budget');

/*--------------------------------------Update Estadp----------------------------------------------------------*/
$app->get('/detalle-presupuesto/update-estado-delete/:id', function($id) use($app){

   //si hay sesion abierta
   if(!empty($_SESSION['session'])){
    require_once 'models/DetallePresupuesto.php';

    $detailBudget = new DetallePresupuesto();
    $detailBudget->setIdDetallePresupuesto($id);
    $detailBudget->setEstado("0");
    $update =  $detailBudget->updateEstado();
    if($update){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Eliminado Correctamente');

    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido insertar');
    }

    $resultidPresupuesto = $detailBudget->selectByIdDetallePresupuesto();
    //var_dump($resultidPresupuesto);
    $idPrespuesto = $resultidPresupuesto["id_presupuesto"];
    //echo $idPrespuesto;
    
    $app->redirect($app->urlFor('budget-detail',['id'=>$idPrespuesto])); 

}else{
    //si no hay redirecciona al login
    $app->redirect($app->urlFor('login'));
}

})->name('update-detail-budget-estado-delete');

/*--------------------------------Imprimir Presupuestos---------------------------------------*/
$app->get('/imprimir-presupuesto/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Presupuesto.php';
        require_once 'models/DetallePresupuesto.php';

        $budget = new Presupuesto();
        $budget->setIdPresupuesto($id);
        $result = $budget->selectById();
        //var_dump($result);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        //var_dump($customer);

        $detailBudget = new DetallePresupuesto();
        $detailBudget->setIdPresupuesto($id);
        $resultDetail = $detailBudget->selectByIdpresupuestoActive();
        $sumTotal = $detailBudget->selectSumTotalByIdPresupuesto();

        $app->render('presupuesto/budget-print.html.twig', array(
            'budget'=> $result, 'detailBudget'=> $resultDetail, 'total'=> $sumTotal, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('budget-print');