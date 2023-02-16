<?php

/*----------Index Proveedor----------*/
$app->get('/proveedores', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Proveedor.php';
        require_once 'models/Selectores.php';

        $provider = new Proveedor();
        $provider = $provider->selectAll();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('proveedor/proveedor.html.twig', array(
            'provider' => $provider, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('provider');

/*----------Insertar Proveedor----------*/
$app->post('/proveedor/nuevo', function() use($app){

    require_once 'models/Proveedor.php';
    $provider = new Proveedor();
    $request = $app->request;

    $provider->setProveedor(strtoupper($request->post('provider')));
    $provider->setDireccion($request->post('address'));
    $provider->setContacto(strtoupper($request->post('contact')));
    $provider->setTelefono($request->post('phone'));
    $provider->setEmail($request->post('mail'));
    $provider->setCelular($request->post('cellphone'));
    $provider->setRuc($request->post('ruc'));
    $provider->setCiudad(strtoupper($request->post('city')));

    $insertado=$provider->insertProvider();
    if($insertado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Ya existe el producto en la Base de Datos');
    }

    $app->redirect($app->urlFor('provider'));

})->name('insert-provider');

/*----------Buscar Proveedor----------*/
$app->post('/busqueda-proveedor', function() use($app){

    require_once 'models/Selectores.php';
    require_once 'models/Proveedor.php';
    $request = $app->request;

    $selector = new Selectores();
    $userAr = $selector->returnRol();

    $provider = new Proveedor();
    $provider->setProveedor(strtolower($request->post('buscar')));
    $providerResult = $provider->searchProvider();

    $app->render('proveedor/proveedor.html.twig', array('provider' => $providerResult, 'user' => $userAr));

})->name('search-provider');

/*----------Actualizar Proveedor----------*/
$app->get('/modificar/proveedor/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Proveedor.php';
        require_once 'models/Selectores.php';

        $provider = new Proveedor();
        $provider->setIdproveedor($id);
        $providerResul = $provider->selectProviderId();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('proveedor/update-proveedor.html.twig', array(
            'user' => $userAr, 'provider' => $providerResul));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('up-provider');

$app->post('/provider/update', function() use($app){

    require_once 'models/Proveedor.php';
    require_once 'models/Abm.php';

    $provider = new Proveedor();
    $request = $app->request;

    $provider->setIdproveedor($request->post('id'));
    $provider->setProveedor(strtoupper($request->post('provider')));
    $provider->setDireccion(strtolower($request->post('address')));
    $provider->setContacto(strtoupper($request->post('contact')));
    $provider->setTelefono($request->post('phone'));
    $provider->setCelular($request->post('cellphone'));
    $provider->setEmail($request->post('mail'));
    $provider->setRuc($request->post('ruc'));
    $provider->setCiudad(strtoupper($request->post('city')));

    $modificado = $provider->updateProvider();
    if($modificado){
        $abm = new Abm();
        $abm->setTabla('proveedor');
        $abm->setCampo('modificado');
        $motivo = $provider->getProveedor() .": ".$request->post('comentario');
        $abm->setMotivo($motivo);
        $abm->setIduser($request->post('user'));
        $abm->insertarAbm();
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Actualizado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido actualizar: Producto existente');
    }

    $app->redirect($app->urlFor('provider'));

})->name('update-provider');


/*----------Eliminar Proveedor----------*/
$app->get('/delete/proveedor/:id-:proveedor-:iduser', function($id,$proveedor,$iduser) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Proveedor.php';
        require_once 'models/Abm.php';

        $provider = new Proveedor();
        $provider->setIdproveedor($id);
        $eliminado = $provider->deleteProvider();
        if($eliminado){
            $abm = new Abm();
            $abm->setIduser($iduser);
            $abm->setTabla('proveedor');
            $abm->setCampo('eliminado');
            $abm->setMotivo($proveedor);
            $abm->insertarAbm();
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Eliminado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido eliminar, ya ha sido utilizado en la BD');
        }

        $app->redirect($app->urlFor('provider'));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-provider');


/*----------Insertar Proveedor AJAX----------*/
$app->post('/proveedor/nuevo-ajax', function() use($app){

    require_once 'models/Proveedor.php';
    $provider = new Proveedor();
    $request = $app->request;

    $provider->setProveedor(strtoupper($request->post('provider')));
    $provider->setDireccion($request->post('address'));
    $provider->setContacto(strtoupper($request->post('contact')));
    $provider->setTelefono($request->post('phone'));
    $provider->setEmail($request->post('mail'));
    $provider->setCelular($request->post('cellphone'));
    $provider->setRuc($request->post('ruc'));
    $provider->setCiudad(strtoupper($request->post('city')));

    $insertado=$provider->insertProvider();
    if($insertado){
        $idproveedor = $provider->selectProviderMaxId();
        echo $idproveedor['codigo'];
    }else{
        echo 0;
    }

})->name('insert-provider-ajax');