<?php
/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 24/08/2016
 * Time: 03:18 PM
 */

/*----------Index stock----------*/
$app->get('/materiales', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Stock.php';
        require_once 'models/Selectores.php';

        $stock = new Stock();
        $stockTable = $stock->verStock(50);

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('materiales/materiales.html.twig', array(
            'stock' => $stockTable, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('materiales');

/*----------Insertar Stock----------*/
$app->post('/stock/new', function() use($app){

    require_once 'models/Stock.php';
    $stock = new Stock();
    $request = $app->request;

    $stock->setCodigo('100001001');
    $stock->setProducto(strtolower($request->post('producto')));
    $stock->setUbicacion(strtoupper($request->post('ubicacion')));
    $stock->setUnidad(strtoupper($request->post('unidad')));
    $stock->setCantidad($request->post('cantidad'));
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
    $app->redirect($app->urlFor('materiales'));

})->name('insert-stock');

/*----------Buscar Material----------*/
$app->post('/busqueda-material', function() use($app){

    require_once 'models/Selectores.php';
    require_once 'models/Stock.php';
    $request = $app->request;

    $selector = new Selectores();
    $userAr = $selector->returnRol();

    $stock = new Stock();
    $stock->setProducto(strtolower($request->post('buscar')));
    $stockTable = $stock->searchStock();

    $app->render('materiales/materiales.html.twig', array('stock' => $stockTable, 'user' => $userAr));

})->name('search-stock');

/*----------Actualizar Stock----------*/
$app->get('/update/stock/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Stock.php';
        require_once 'models/Selectores.php';

        $stock = new Stock();
        $stock->setIdstock($id);
        $stockResul = $stock->selectStockId();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('materiales/update-stock.html.twig', array(
            'user' => $userAr, 'stock' => $stockResul));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('up-stock');

$app->post('/stock/update', function() use($app){

    require_once 'models/Stock.php';
    require_once 'models/Abm.php';

    $stock = new Stock();
    $request = $app->request;

    $stock->setIdstock($request->post('id'));
    $stock->setCodigo($request->post('codigo'));
    $stock->setProducto(strtolower($request->post('producto')));
    $stock->setUbicacion(strtoupper($request->post('ubicacion')));
    $stock->setUnidad(strtoupper($request->post('unidad')));
    $stock->setStockMin($request->post('minimo'));
    $stock->setCosto($request->post('costo'));
    $stock->setCantidad($request->post('cantidad'));

    $modificado = $stock->updateStock();
    if($modificado){
        $abm = new Abm();
        $abm->setTabla('stock');
        $abm->setCampo('modificado');
        $abm->setMotivo(strtolower($request->post('comentario')));
        $abm->setIduser($request->post('user'));
        $abm->insertarAbm();
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Actualizado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido actualizar: Producto existente');
    }

    $app->redirect($app->urlFor('materiales'));

})->name('update-stock');


/*----------Eliminar Stock----------*/
$app->get('/delete/stock/:id-:material-:iduser', function($id,$material,$iduser) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Stock.php';
        require_once 'models/Abm.php';

        $stock = new Stock();
        $stock->setIdstock($id);
       // echo $id."/".$material."/".$iduser;
        $eliminado = $stock->deleteStock();
        if($eliminado){
            $abm = new Abm();
            $abm->setIduser($iduser);
            $abm->setTabla('stock');
            $abm->setCampo('eliminado');
            $abm->setMotivo($material);
            $abm->insertarAbm();
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Eliminado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido eliminar, ya ha sido utilizado en la BD');
        }

        $app->redirect($app->urlFor('materiales'));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-stock');

/*----------Index stock----------*/
$app->get('/reporte-materiales', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Stock.php';
        require_once 'models/Selectores.php';

        $stock = new Stock();
        $stockTable = $stock->verStock(2000);

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('materiales/stock-report.html.twig', array(
            'stock' => $stockTable, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('stock-report');

/*----------Insertar Stock Ajax----------*/
$app->post('/stock/new-ajax', function() use($app){

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
        $idstock=$stock->retornaMaxIdStock();
        echo $idstock["codigo"];
    }else{
        echo 0;
    }

})->name('insert-stock-ajax');