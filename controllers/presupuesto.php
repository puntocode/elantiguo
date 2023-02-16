<?php
/*--------------------------------Index Presupuestos---------------------------------------*/
$app->get('/presupuesto', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Presupuesto.php';

        $budget = new Presupuesto();
        $budget->setEstado('0');
        $result = $budget->selectEstado();
        //var_dump($result);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $customer = $selector->cargarClientes();
        //var_dump($customer);

        $app->render('presupuesto/budget.html.twig', array(
            'customer' => $customer, 'budget'=> $result, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('budget');

/*---------------------------------Insert Presupuestos---------------------------------------*/
$app->post('/presupuesto/nuevo', function() use($app){

    require_once 'models/Presupuesto.php';
    $budget = new Presupuesto();
    $request = $app->request;

    $budget->setCliente($request->post('input-customer'));
    $budget->setDescripcion($request->post('text-area-description'));
    
    $fecha=date_create($request->post('input-date'));
    $dateBudget = date_format($fecha,"Y/m/d");
    $budget->setFecha($dateBudget);

    $budget->setEstado(0);
    $budget->setSustantivo($request->post('select-noun'));

    $resultMaxid = $budget->selectMaxId();
    $maxId = $resultMaxid["id"]+1;
    $budget->setNumero("EA-".$maxId);

    //echo $budget->getCliente() ."/". $budget->getFecha()."/". $budget->getDescripcion()."/".$budget->getNumero();

    $insert=$budget->insertBudget();
    if($insert){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Nuevo Presupuesto');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido insertar');
    }

    $app->redirect($app->urlFor('budget-detail',['id'=>$maxId])); 

})->name('insert-budget');

/*--------------------------------------Update Estadp----------------------------------------------------------*/
$app->get('/presupuesto/update-estado-delete/:id-:estado', function($id,$state) use($app){

    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
     require_once 'models/Presupuesto.php';
 
     $budget = new Presupuesto();
     $budget->setIdPresupuesto($id);
     $budget->setEstado($state);
     $update =  $budget->updateEstado();
     if($update){
         $app->flash('content', 'alert-success');
         $app->flash('mensaje', 'UPDATE');
 
     }else{
         $app->flash('content', 'alert-danger');
         $app->flash('mensaje', 'No update');
     }
     
     $app->redirect($app->urlFor('budget',['id'=>$budget->getIdPresupuesto()])); 
 
 }else{
     //si no hay redirecciona al login
     $app->redirect($app->urlFor('login'));
 }
 
 })->name('update-state-budget');

 /*--------------------------------Presupuestos Aceptados---------------------------------------*/
$app->get('/presupuestos-aceptados', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Presupuesto.php';

        $budget = new Presupuesto();
        $budget->setEstado('1');
        $result = $budget->selectEstado();
        //var_dump($result);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $customer = $selector->cargarClientes();
        //var_dump($customer);

        $app->render('presupuesto/budget-accept.html.twig', array(
            'customer' => $customer, 'budget'=> $result, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('budget-accept');

/*--------------------------------Presupuestos Rechazados---------------------------------------*/
$app->get('/presupuestos-rechazados', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Presupuesto.php';

        $budget = new Presupuesto();
        $budget->setEstado('2');
        $result = $budget->selectEstado();
        //var_dump($result);

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $customer = $selector->cargarClientes();
        //var_dump($customer);

        $app->render('presupuesto/budget-rejected.html.twig', array(
            'customer' => $customer, 'budget'=> $result, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('budget-rejected');


/*---------------------------------Update Presupuestos---------------------------------------*/
$app->post('/presupuesto/modificar', function() use($app){

    require_once 'models/Presupuesto.php';
    $budget = new Presupuesto();
    $request = $app->request;

    $budget->setIdPresupuesto($request->post('id'));
    $budget->setCliente($request->post('input-customer'));
    $budget->setDescripcion($request->post('text-area-description'));
    
    $fecha=date_create($request->post('input-date'));
    $dateBudget = date_format($fecha,"Y/m/d");
    $budget->setFecha($dateBudget);

    $budget->setSustantivo($request->post('select-noun'));

    //echo $budget->getCliente() ."/". $budget->getFecha()."/". $budget->getDescripcion()."/".$budget->getSustantivo();

    $update=$budget->updateBudget();
    if($update){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Actualizado correctamente!!');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido actualizar');
    }

    $app->redirect($app->urlFor('budget-detail',['id'=>$budget->getIdPresupuesto()])); 

})->name('update-budget');

/*--------------------------------Ver Presupuestos---------------------------------------*/
$app->get('/ver-presupuesto/:id', function($id) use($app){
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

        $app->render('presupuesto/budget-view.html.twig', array(
            'budget'=> $result, 'detailBudget'=> $resultDetail, 'total'=> $sumTotal, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('budget-view');