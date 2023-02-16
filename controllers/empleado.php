<?php
/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 24/08/2016
 * Time: 03:18 PM
 */

/*----------Index Empleados----------*/
$app->get('/empleados', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Empleado.php';
        require_once 'models/Selectores.php';

        $empleado = new Empleado();
        $empleados = $empleado->vertodos();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('empleados/empleados.html.twig', array(
            'empleado' => $empleados, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('empleados');

/*----------Insertar Empleado----------*/
$app->post('/empleado/nuevo', function() use($app){

    require_once 'models/Empleado.php';
    $empleado = new Empleado();
    $request = $app->request;

    $empleado->setNombre(strtolower($request->post('nombre')));
    $date=date_create($request->post('nacimiento'));
    $empleado->setNacimiento(date_format($date,"Y-m-d"));
    $empleado->setCi($request->post('ci'));
    $empleado->setSueldo($request->post('sueldo'));
    $empleado->setTelefono($request->post('telefono'));
    $empleado->setEmail($request->post('email'));
    $empleado->setCiudad(strtolower($request->post('ciudad')));


    if($empleado->getNombre()) {
        $empleado->insertarEmpleado();
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
        //echo ($causaAcc->getCausaAccidente());
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido Registrar: Error de Nombre');
        //echo ($causaAcc->getCausaAccidente());
    }

    $app->redirect($app->urlFor('empleados'));


})->name('insert-empleado');


/*----------Actualizar Empleado----------*/
$app->get('/update/empleado/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Empleado.php';
        require_once 'models/Selectores.php';

        $empleado = new Empleado();
        $empleado->setIdempleado($id);
        $empleadoResul = $empleado->selectEmpleado();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('empleados/up-empleado.html.twig', array(
            'user' => $userAr, 'empleado' => $empleadoResul));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('up-empleado');

$app->post('/empleado/update', function() use($app){

    require_once 'models/Empleado.php';
    $empleado = new Empleado();
    $request = $app->request;

    $empleado->setIdempleado($request->post('id'));
    $empleado->setNombre(strtoupper($request->post('nombre')));
    $empleado->setCi($request->post('ci'));
    $date=date_create($request->post('nacimiento'));
    $empleado->setNacimiento(date_format($date,"Y-m-d"));
    $empleado->setTelefono($request->post('telefono'));
    $empleado->setEmail($request->post('email'));
    $empleado->setCiudad($request->post('ciudad'));
    $empleado->setSueldo($request->post('sueldo'));

    $actualizado = $empleado->updateEmpleado();
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Editado Correctamente');
    }

    $app->redirect($app->urlFor('empleados'));

})->name('update-empleado');

