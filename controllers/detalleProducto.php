<?php

/*----------Index Cargar Maquina----------*/
$app->get('/detalle-maquinarias/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/DetalleProducto.php';
        require_once 'models/ManoObra.php';
        require_once 'models/Selectores.php';

        $product = new DetalleProducto();
        $product->setIdProducto($id);
        $materiales = $product->selectDetalleProducto();
        $totalProducto = $product->sumDetailProduc();

        $manoObra = new ManoObra();
        $manoObra->setIdProducto($id);
        $mdo = $manoObra->selectManoDeObra();
        $totalManoObra = $manoObra->sumManoObra();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $stock = $selector->cargarStock();
        $empleados = $selector->cargarEmpleados();

        $codigo = array("codigo"=>$id);
        $app->render('maquinarias/upload-machine.html.twig', array(
            'materiales'=>$materiales, 'mano_obra'=>$mdo, 'user' => $userAr, 'stock'=>$stock, 'empleados'=>$empleados,
            'sum_detalle'=>$totalProducto, 'sum_mdo'=>$totalManoObra, 'idproducto'=>$codigo));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('detail-machine');


/*----------Insertar Detalle Producto----------*/
$app->post('/detalle-producto/nuevo', function() use($app){

    require_once 'models/DetalleProducto.php';
    $detalle = new DetalleProducto();
    $request = $app->request;

    $detalle->setIdProducto($request->post('idproducto'));
    $detalle->setIdStock($request->post('stock'));
    $detalle->setCantidad($request->post('quantity'));

    $insertado = $detalle->insertarDetalleProducto();
    if($insertado){
        $app->redirect($app->urlFor('detail-machine',['id'=>$detalle->getIdProducto()]));
    }
    echo $detalle->getIdProducto()."/".$detalle->getIdStock()."/".$detalle->getCantidad();

})->name('insert-detail-machine');

/*----------Eliminar Detalle Producto----------*/
$app->get('/delete/detalle-producto/:id-:idproducto', function($id,$idproducto) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/DetalleProducto.php';

        $producto = new DetalleProducto();
        $producto->setIdDetalleProducto($id);
        $eliminado = $producto->deleteDetalleProducto();
        if($eliminado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Eliminado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido eliminar, ya ha sido utilizado en la BD');
        }
        $app->redirect($app->urlFor('detail-machine',['id'=>$idproducto]));
    }else{
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-detalle-producto');

/*----------Imprimir Producto----------*/
$app->get('/print-maquinarias/:id', function($id) use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/DetalleProducto.php';
        require_once 'models/ManoObra.php';
        require_once 'models/Selectores.php';

        $product = new DetalleProducto();
        $product->setIdProducto($id);
        $materiales = $product->selectDetalleProducto();
        $totalProducto = $product->sumDetailProduc();

        $manoObra = new ManoObra();
        $manoObra->setIdProducto($id);
        $mdo = $manoObra->selectManoDeObra();
        $totalManoObra = $manoObra->sumManoObra();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $stock = $selector->cargarStock();
        $empleados = $selector->cargarEmpleados();
        $imgProducto=$selector->cargarFetch("SELECT img_big FROM producto WHERE idproducto=$id");

        $app->render('maquinarias/print.html.twig', array(
            'materiales'=>$materiales, 'mano_obra'=>$mdo, 'user' => $userAr, 'stock'=>$stock, 'empleados'=>$empleados,
            'sum_detalle'=>$totalProducto, 'sum_mdo'=>$totalManoObra, 'img'=>$imgProducto));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('print-machine');