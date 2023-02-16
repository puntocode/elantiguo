<?php
/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 11/4/2018
 * Time: 15:27
 */

/*--------------------------------------------------Index SUELDOS-----------------------------------------------------*/
$app->get('/sueldos', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Sueldo.php';
        require_once 'models/Selectores.php';

        $sueldo = new Sueldo();
        $selector = new Selectores();

        $inicio = $selector->firstDay();
        $fin = $selector->lastDay();

        $pagos = $sueldo->verMes($inicio,$fin);
        $sum = $sueldo->sumSueldo($inicio,$fin);

        $userAr = $selector->returnRol();
        $empleado = $selector->cargarEmpleados();

        $mes=date("m");
        $anho =date("Y");
        $subtitle = "Sueldo del mes: ".$mes."/".$anho;

        $cabecera = array( "titulo" => "Sueldo del mes",
            "subtitulo" => $subtitle);

        $app->render('sueldo/sueldo.html.twig', array(
           'cabecera'=>$cabecera, 'employed'=>$empleado, 'pagos'=>$pagos, 'user' => $userAr, 'total'=>$sum));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('sueldos');

/*--------------------------------------------------Cargar SUELDOS AJAX-----------------------------------------------*/
$app->post('/insert-salary', function() use($app){

    require_once 'models/Sueldo.php';
    $salary = new Sueldo();
    $request = $app->request;

    $salary->setConcepto(strtoupper($request->post('concepto')));
    $salary->setIdEmpleado($request->post('empleado'));
    $salary->setTotal(intval(preg_replace('/[^0-9]+/','', $request->post('total'))));

    $fecha=date_create($request->post('fecha'));
    $date = date_format($fecha,"Y/m/d");
    $salary->setFecha($date);

    $insertado=$salary->insertSalary();
    echo $insertado;

    //echo $salary->getFecha()."/".$salary->getConcepto()."/".$salary->getTotal()."/".$salary->getIdEmpleado();

})->name('insert-salary');

/*--------------------------------------------------BUSQUEDA POR FECHA------------------------------------------------*/
$app->post('/search-salary', function() use($app){

    require_once 'models/Sueldo.php';
    require_once 'models/Selectores.php';

    $salary = new Sueldo();
    $request = $app->request;

    $selector = new Selectores();

    $beginDate=date_create($request->post('begin'));
    $inicio = date_format($beginDate,"Y/m/d");

    $endDate=date_create($request->post('end'));
    $fin = date_format($endDate,"Y/m/d");

    $pagos = $salary->verMes($inicio,$fin);
    $sum = $salary->sumSueldo($inicio,$fin);

    $userAr = $selector->returnRol();
    $empleado = $selector->cargarEmpleados();

    $subtitle = "Resultados de fecha: ".$inicio." - ".$fin;

    $cabecera = array( "titulo" => "Resultado de Busqueda",
        "subtitulo" => $subtitle);

    $app->render('sueldo/sueldo.html.twig', array(
        'cabecera'=>$cabecera, 'employed'=>$empleado, 'pagos'=>$pagos, 'user' => $userAr, 'total'=>$sum));

})->name('search-salary');

/*---------------------------------------DELETE SALARY----------------------------------------------------------------*/
$app->post('/delete-salary', function() use($app){

    require_once 'models/Sueldo.php';
    $salary = new Sueldo();
    $request = $app->request;

    $salary->setIdSueldo($request->post('id'));
    $delete=$salary->deleteSalary();
    echo $delete;

})->name('delete-salary');