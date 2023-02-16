<?php

/*----------Insertar Deudas----------
$app->post('/deuda/nuevo', function() use($app){

    require_once 'models/Deudas.php';
    $debt = new Deudas();
    $request = $app->request;

    $fecha=date_create($request->post('fecha'));
    $date = date_format($fecha,"Y/m/d");
    $debt->setFecha($date);

    $debt->setDeuda(intval(preg_replace('/[^0-9]+/','', $request->post('deuda'))));
    $debt->setIdPedidos($request->post('idpedido'));
    $debt->setObs($request->post('obs'));
    $debt->setPagado("N");

    $insertado=$debt->insertDeuda();
    if($insertado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertada Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido insertar');
    }

    $app->redirect($app->urlFor('detalle-pedido',['id'=>$debt->getIdPedidos()."#deudas"]));

})->name('insert-debt');*/

/*----------Insertar Deudas Ajax----------*/
$app->post('/deuda/nuevo', function() use($app){

    require_once 'models/Deudas.php';
    $debt = new Deudas();
    $request = $app->request;

    $fecha=date_create($request->post('fecha-deuda'));
    $date = date_format($fecha,"Y/m/d");
    $debt->setFecha($date);

    $debt->setDeuda(intval(preg_replace('/[^0-9]+/','', $request->post('monto-deuda'))));
    $debt->setIdPedidos($request->post('idpedido-deuda'));
    $debt->setObs($request->post('obs-deuda'));
    $debt->setPagado("N");

    $insertado=$debt->insertDeuda();
    echo $insertado;

})->name('insert-debt');

/*----------Eliminar Deuda----------*/
$app->get('/detalle-pedido/cancel-debt/:id-:idpedido', function($id,$idpedido) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Deudas.php';

        $debt = new Deudas();
        $debt->setIdDeuda($id);
        $eliminado = $debt->cancelDebt();

        if($eliminado){
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'Deuda Cancelada');
        }

        $app->redirect($app->urlFor('detalle-pedido',['id'=>$idpedido."#deudas"]));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('cancel-debt');

/*----------Calendario de Deudas----------*/
$app->get('/calendario-deudas', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Deudas.php';

        $debt = new Deudas();
        $result = $debt->selectDeudas();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('pedidos/calendar.html.twig', array(
            'deudas'=>$result, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('calendar');

/*----------Tabla de Deudas----------*/
$app->get('/tabla-deudas', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Selectores.php';
        require_once 'models/Deudas.php';

        $debt = new Deudas();
        $result = $debt->selectDeudas();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('pedidos/deudas.html.twig', array(
            'deudas'=>$result, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('deudas');

/*----------Datos Calendario----------*/
$app->get('/calendario-deudas/datos-calendario', function () use ($app) {
//render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Deudas.php';
        $calendario = new Deudas();

        $datos = $calendario->cargarCalendar();
        print json_encode($datos, JSON_NUMERIC_CHECK);
        //$app->redirect($app->urlFor('dashboard'));
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
});

/*----------ENVIAR POR CORREO----------*/
$app->post('/calendario-deudas/enviar-correo', function() use($app){
    require_once 'models/Deudas.php';
    $request = $app->request;

    $debt = new Deudas();

    $mes = $request->post('mes');
    $anho = $request->post('anho');
    $result = $debt->seletMesDeuda($mes, $anho);

    //Variables
    $para = 'deudas@elantiguoadm.com';
    $asunto = 'Deudas del mes: '.$mes."/".$anho;
    $remitente = "info@elantiguo.com.py"; //Aquí va la dirección de quien envía el email.


    //Cabecera de la funcion mail()
    $headers = "From: ".$remitente." \r\n";
    $headers .= "Reply-To: ".$remitente."\r\n"; //La dirección por defecto si se responde el email enviado.
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; //La codificación del email.

    //mensaje
    $count = 1;

    $mensaje = '<div style="background-color: #f8f8f8;padding: 10px 10px; font-family: calibri,serif;color: #888;">
                    <div style="padding:10px;display: inline-block">
                        <div style="width: 20%; display: inline-block; vertical-align: middle"><img src="http://www.elantiguoadm.com/views/assets/images/logo.png" style="width:100%;"></div>
                        <div style="width: 70%; display: inline-block; padding-left:15px"><h3>DEUDAS DEL MES DE '.$mes.'/'.$anho.'</h3></div>
                    </div>    
                    <div style="padding: 5px;">
                        <div style="background-color: #fff;font-size: 12px;padding: 10px;border: 1px solid #ddd;-webkit-border-radius: 5px;-o-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">
                            <table style="width: 100%;">
                                <thead>
                                    <tr style="text-align: left; padding: 5px">
                                        <th>#</th>
                                        <th>FECHA PAGO</th>
                                        <th>NRO. ORDEN</th>
                                        <th>CLIENTE</th>
                                        <th>DETALLE</th>
                                        <th>DEUDA</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                foreach($result as $item) {
                                    $mensaje .="<tr>
                                    <td style='color:gainsboro'>" . $count++ ."</td>
                                    <td>" . $item['fecha'] ."</td>
                                    <td>".$item['nro_presupuesto']."</td>
                                    <td>".$item['cliente']."</td>
                                    <td>".$item['obs']."</td>
                                    <td style='color:red'>".$item['deuda']."</td>
                                    </tr>";
                                }
                                $mensaje .= '</tbody>
                            </table>
                        </div>
                    </div>
                </div>';

    //Mandamos el email.
    if(mail($para, $asunto, $mensaje, $headers)){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Correo enviado correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido enviar el correo');
    };

    $app->redirect($app->urlFor('calendar'));

})->name('deuda-correo') ;


$app->post('/cantidad-deuda', function() use($app) {
    require_once 'models/Deudas.php';
    $debt = new Deudas();
    $cantidad = $debt->cantidadDeuda();
    //print json_encode($cantidad, JSON_NUMERIC_CHECK);
    echo count($cantidad);
});

/*---------------------------------------------------Deudas Json Noti----------------------------------------------------------------------*/
$app->get('/dias-deudas', function() use ($app) {
    //render cuando inicia sesion
    if (!empty($_SESSION['session'])) {
        require_once 'models/Deudas.php';
        $debt = new Deudas();
        $deudas = $debt->cantidadDeuda();
        print json_encode($deudas, JSON_NUMERIC_CHECK);
    } else {
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
});

/*---------------------------------------------------Deudas Enviar por correo Dia----------------------------------------------------------------------*/
$app->get('/dias-deudas/correo', function() use ($app) {
    require_once 'models/Deudas.php';
    $request = $app->request;

    $debt = new Deudas();
    $deudas = $debt->cantidadDeuda();

    //Variables
    $para = 'deudas@elantiguoadm.com';
    $asunto = 'Deudas del día';
    $remitente = "info@elantiguo.com.py"; //Aquí va la dirección de quien envía el email.


    //Cabecera de la funcion mail()
    $headers = "From: ".$remitente." \r\n";
    $headers .= "Reply-To: ".$remitente."\r\n"; //La dirección por defecto si se responde el email enviado.
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; //La codificación del email.

    //mensaje
    //$count = 1;
    $today = date("Y-m-d");
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $afterTomorrow = date('Y-m-d', strtotime('+2 days'));

    $debt->setFecha($today);
    $debToday=$debt->deudaDia();
    $debt->setFecha($tomorrow);
    $debtTomorrow=$debt->deudaDia();
    $debt->setFecha($afterTomorrow);
    $debtAfterTomorrow = $debt->deudaDia();


    $mensaje = '<div style="background-color: #f8f8f8;padding: 10px 10px; font-family: calibri,serif;color: #888;">
                    <div style="padding:10px;display: inline-block">
                        <div style="width: 20%; display: inline-block; vertical-align: middle"><img src="http://www.elantiguoadm.com/views/assets/images/logo.png" style="width:100%;"></div>
                        <div style="width: 70%; display: inline-block; padding-left:15px; font-size: 18px"><h3>DUEDAS A COBRAR</h3></div>
                    </div>    
                    <div style="padding: 5px;">
                        <div style="background-color: #fff;font-size: 12px;padding: 10px;border: 1px solid #ddd;-webkit-border-radius: 5px;-o-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">
                            <ul style="list-style:none;font-size: 16px;padding-left: 5px">';

                                    if($debToday) {
                                        $mensaje .='<li class="hoy">
                                        <div style="color: #fff; margin-top: 20px; background:#001432; padding: 3px;">HOY '.date("d-m-Y").'</div>';
                                        foreach($debToday as $item) {
                                            $mensaje .="<div style='border-bottom: 1px solid #e1e6ef; padding-top: 3px'>
                                                            <div style='color: #001432'>".$item['cliente']."</div>
                                                            <div>".$item['detalle']."</div>
                                                            <div style='color:#FA2A00'>Monto de Pago: ".$item['deuda']."</div>
                                                        </div>";

                                        }
                                        $mensaje .='</li>';
                                    }

                                    if($debtTomorrow) {
                                        $mensaje .='<li class="tomorrow">
                                        <div style="color: #fff; margin-top: 20px; background:#001432; padding: 3px;">MAÑANA '.date('d-m-Y', strtotime('+1 day')).'</div>';
                                        foreach($debtTomorrow as $item) {
                                            $mensaje .="<div style='border-bottom: 1px solid #e1e6ef; padding-top: 3px'>
                                                            <div><span class='fa fa-circle text-danger'></span>".$item['cliente']."</div>
                                                            <div>".$item['detalle']."</div>
                                                            <div style='color:#FA2A00'>Monto de Pago: ".$item['deuda']."</div>
                                                        </div>";

                                        }
                                        $mensaje .='</li>';
                                    }

                                    if($debtAfterTomorrow) {
                                        $mensaje .='<li class="after-tomorrow">
                                        <div style="color: #fff; margin-top: 20px; background:#001432; padding: 3px;">PASADO MAÑANA '.date('d-m-Y', strtotime('+2 days')).'</div>';
                                        foreach($debtAfterTomorrow as $item) {
                                            $mensaje .="<div style='border-bottom: 1px solid #e1e6ef; padding-top: 3px'>
                                                            <div><span class='fa fa-circle text-danger'></span>".$item['cliente']."</div>
                                                            <div>".$item['detalle']."</div>
                                                            <div style='color:#FA2A00'>Monto de Pago: ".$item['deuda']."</div>
                                                        </div>";

                                        }
                                        $mensaje .='</li>';
                                    }
                               $mensaje .= '</ul>
                        </div>
                    </div>
                </div>';

    //echo $mensaje;
    //Mandamos el email.
    $bandera = 0;
    if(mail($para, $asunto, $mensaje, $headers)){
        $bandera = 1;
    }

    echo $bandera;

})->name('dia-correo') ;