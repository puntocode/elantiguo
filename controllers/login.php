<?php



/*----------Pagina Login-----------*/

$app->get('/login', function() use($app){



    //redireccionar si hay session

    if(!empty($_SESSION['session'])){

        $app->redirect($app->urlFor('dashboard'));

    }else{

        //si no hay session dibujar la pagina de login

        $app->render('login.html.twig');

    }

})->name('login');





/*----------Form Login-----------*/

$app->post('/login', function() use($app){
    try{
        require_once 'models/Login.php';
        
        $request = $app->request;
        
        $pass = $request->post('password');
        $secret = "eaadmin";
        $passEncript = hash_hmac("sha512",$pass,$secret);
        
        $login = new login();
        $login->setUser($request->post('user'));
        $login->setPass($passEncript);
        $login->consult();
        
        //$arrayUser = $login->getResult();

        $app->redirect($app->urlFor('dashboard', ['mes'=>date("m"),'anho'=>date("Y")]) );
        
    }catch (Exception $e) {
        echo 'Exception: ' . $e->getMessage();
        exit;
    }

    /*if($login->check() == true){
        //si el login fue exitoso, ingresar redireccionando
        $app->redirect($app->urlFor('dashboard', ['mes'=>date("m"),'anho'=>date("Y")]) );
    }else{
        //si no volver a la pagina de login con un mensaje flash
        $app->flash('error', 'Usuario incorrecto');
        $app->redirect($app->urlFor('login'));
    }*/

})->name('login-form');





/*----------Cerrar Sesion-----------*/

$app->get('/logout', function() use($app){



    //Borro las cookies de session para no dejar ningun rastro

    //Nota: Esto destruira la session y no la informacion de la session!!

    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(session_name(), '', time() - 42000,

            $params["path"], $params["domain"],

            $params["secure"], $params["httponly"]

        );

    }



    //Finalmente destruyo la session

    session_destroy();



    //Redirect al login

    $app->redirect($app->urlFor('login'));



})->name('logout');