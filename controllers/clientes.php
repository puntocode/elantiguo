<?php

/*----------Index Proveedor----------*/
$app->get('/clientes', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Clientes.php';
        require_once 'models/Selectores.php';

        $customer = new Clientes();
        $customerAll = $customer->selectAll();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('clientes/clientes.html.twig', array(
            'customer' => $customerAll, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('clientes');

/*----------Insertar Cliente----------*/
$app->post('/cliente/nuevo', function() use($app){

    require_once 'models/Clientes.php';
    $customer = new Clientes();
    $request = $app->request;

    $customer->setCliente(strtoupper($request->post('customer')));
    $customer->setDireccion($request->post('address'));
    $customer->setContacto(strtoupper($request->post('contact')));
    $customer->setTelefono($request->post('phone'));
    $customer->setEmail($request->post('mail'));
    $customer->setCelular($request->post('cellphone'));
    $customer->setRuc($request->post('ruc'));
    $customer->setLocalidad(strtoupper($request->post('city')));

    $insertado=$customer->insertCustomer();
    if($insertado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Ya existe el cliente en la Base de Datos');
    }

    $app->redirect($app->urlFor('clientes'));

})->name('insert-customer');

/*----------Buscar Cliente----------*/
$app->post('/busqueda-cliente', function() use($app){

    require_once 'models/Selectores.php';
    require_once 'models/Clientes.php';
    $request = $app->request;

    $selector = new Selectores();
    $userAr = $selector->returnRol();

    $customer = new Clientes();
    $customer->setCliente(strtolower($request->post('buscar')));
    $customerResult = $customer->searchCustomer();

    $app->render('clientes/clientes.html.twig', array('customer' => $customerResult, 'user' => $userAr));

})->name('search-customer');

/*----------Actualizar Cliente----------*/
$app->get('/modificar/cliente/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Clientes.php';
        require_once 'models/Selectores.php';

        $clientes = new Clientes();
        $clientes->setIdCliente($id);
        $customerResul = $clientes->selectCustomerId();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('clientes/update-clientes.html.twig', array(
            'user' => $userAr, 'customer' => $customerResul));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('up-customer');

$app->post('/customer/update', function() use($app){

    require_once 'models/Clientes.php';

    $clientes = new Clientes();
    $request = $app->request;

    $clientes->setIdCliente($request->post('id'));
    $clientes->setCliente(strtoupper($request->post('customer')));
    $clientes->setDireccion(strtolower($request->post('address')));
    $clientes->setContacto(strtoupper($request->post('contact')));
    $clientes->setTelefono($request->post('phone'));
    $clientes->setCelular($request->post('cellphone'));
    $clientes->setEmail($request->post('mail'));
    $clientes->setRuc($request->post('ruc'));
    $clientes->setLocalidad(strtoupper($request->post('city')));

    $modificado = $clientes->updateCustomer();
    if($modificado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Actualizado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido actualizar: Producto existente');
    }

    $app->redirect($app->urlFor('clientes'));

})->name('update-customer');


/*----------Eliminar Proveedor----------*/
$app->get('/delete/proveedor/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Clientes.php';
        require_once 'models/Abm.php';

        $customer = new Clientes();
        $customer->setIdCliente($id);
        $eliminado = $customer->deleteCustomer();
        if($eliminado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Eliminado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido eliminar, ya ha sido utilizado en la BD');
        }

        $app->redirect($app->urlFor('clientes'));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-customer');


/*----------Insertar Proveedor AJAX----------*/
$app->post('/cliente/nuevo-ajax', function() use($app){

    require_once 'models/Clientes.php';
    $customer = new Clientes();
    $request = $app->request;

    $customer->setCliente(strtoupper($request->post('customer')));
    $customer->setDireccion($request->post('address'));
    $customer->setContacto(strtoupper($request->post('contact')));
    $customer->setTelefono($request->post('phone'));
    $customer->setEmail($request->post('mail'));
    $customer->setCelular($request->post('cellphone'));
    $customer->setRuc($request->post('ruc'));
    $customer->setLocalidad(strtoupper($request->post('city')));

    $insertado=$customer->insertCustomer();
    if($insertado){
        $idCustomer = $customer->selectCustomerMaxId();
        echo $idCustomer['codigo'];
    }else{
        echo 0;
    }

})->name('insert-customer-ajax');