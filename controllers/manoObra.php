<?php

/*----------Insertar Mano de Obra----------*/
$app->post('/mano-obra/nuevo', function() use($app){

    require_once 'models/ManoObra.php';
    $manoObra = new ManoObra();
    $request = $app->request;

    $manoObra->setIdProducto($request->post('idproducto'));
    $manoObra->setManoObra(strtolower($request->post('mano-obra')));
    $manoObra->setCosto(intval(preg_replace('/[^0-9]+/','', $request->post('cost'))));

    $insertado = $manoObra->insertarManoObra();
    if($insertado){
        $app->redirect($app->urlFor('detail-machine',['id'=>$manoObra->getIdProducto()]));
    }
    //echo $product->getIdCategoria()."/".$product->getProducto()."/".$product->getValorVenta()."/".$product->getImgSmall()."/".$product->getImgBig();

})->name('insert-mano-obra');

/*----------Eliminar Mano Obra----------*/
$app->get('/delete/mano-obra/:id-:idproducto', function($id,$idproducto) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/ManoObra.php';

        $manoObra = new ManoObra();
        $manoObra->setIdManoObra($id);
        $eliminado = $manoObra->deleteManoObra();
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
})->name('delete-mano-obra');