<?php
/**
 * Created by PhpStorm.
 * User: FedeXavier
 * Date: 24/08/2016
 * Time: 03:18 PM
 */

/*----------Index Usuarios----------*/
$app->get('/users', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/User.php';
        require_once 'models/Selectores.php';

        $usuario = new User();
        $usuarios = $usuario->verUser();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('user/user.html.twig', array(
            'usuarios' => $usuarios, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('usuarios');



/*----------Registrar Usuario-----------*/
$app->post('/registro-usuarios', function() use($app){

    require_once 'models/User.php';
    $registro = new User();

    $request = $app->request;

    $registro->setUser(strtolower($request->post('usuario')));
    $pass = $request->post('pass');
    $repeatpass = $request->post('pass2');
    $registro->setIdrol($request->post('rol'));
    //echo $user.$pass.$repeatpass.$nombre;
    if($pass == $repeatpass){
        $secret = "eaadmin";
        $passEncript = hash_hmac("sha512",$pass,$secret);
        $registro->setPassword($passEncript);
        $registro->insertarUser();

        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Usuario creado Correctamente!');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Contraseñas Incorrectas');
    }

    //echo ($registro->getUser().$registro->getPassword()." / ".$registro->getIdrol());
    $app->redirect($app->urlFor('usuarios'));

})->name('new-user');

/*----------Modificar Usuario----------
$app->get('/admin/update/user/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/User.php';
        require_once 'models/Selectores.php';

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $usuarios = new User();
        $usuarios->setIduser($id);
        $usuario = $usuarios->selectUser();

        $app->render('admin/update/up-user.html.twig', array( 'usuario' => $usuario,  'user' => $userAr));
    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('up-usuario');


$app->post('/admin/user/update', function() use($app){

    require_once 'models/User.php';
    $usuario = new User();
    $request = $app->request;

    $usuario->setIduser($request->post('id'));
    $usuario->setUsuario(strtolower($request->post('usuario')));
    $usuario->setNombre(strtoupper($request->post('nombre')));
    $pass = $request->post('pass');
    $repeatpass = $request->post('pass2');
    //$usuario->setAvatar("fisio.png");

    if($pass == $repeatpass){
        $secret = "fisioweb";
        $passEncript = hash_hmac("sha512",$pass,$secret);
        $usuario->setContrasena($passEncript);
        //echo $pass."/".$repeatpass."/".$passEncript;
        $resul = $usuario->updateUser();
        if($resul>0){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Usuario Actualizado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido actualizar el Usuario');
        }
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'Contraseñas Incorrectas');
    }

    $app->redirect($app->urlFor('usuarios'));


})->name('update-user');*/