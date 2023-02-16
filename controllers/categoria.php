<?php
/*----------Index Empleados----------*/
$app->get('/categorias', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Categoria.php';
        require_once 'models/Selectores.php';

        $category = new Categoria();
        $categoryTable = $category->vertodos();

        $selector = new Selectores();
        $userAr = $selector->returnRol();

        $app->render('categorias/categorias.html.twig', array(
            'category' => $categoryTable, 'user' => $userAr));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('category');

/*----------Insertar Empleado----------*/
$app->post('/categoria/nuevo', function() use($app){

    require_once 'models/Categoria.php';
    $category = new Categoria();
    $request = $app->request;

    $category->setCategoria(strtolower($request->post('category')));

    if($category->getCategoria()) {
        $category->insertarCategoria();
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
        //echo ($causaAcc->getCausaAccidente());
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido Registrar: Error de Categoria');
        //echo ($causaAcc->getCausaAccidente());
    }
    $app->redirect($app->urlFor('category'));
    
})->name('insert-category');

/*----------Actualizar Categoria----------*/
$app->post('/categoria/update', function() use($app){

    require_once 'models/Categoria.php';
    $category = new Categoria();
    $request = $app->request;

    $category->setCategoria(strtolower($request->post('category-up')));
    $category->setIdcategoria(strtoupper($request->post('id-category')));
    $actualizado = $category->updateCategoria();
    
    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Editado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido modificar');
    }

    $app->redirect($app->urlFor('category'));

})->name('update-category');


/*----------Eliminar Categoria----------*/
$app->get('/delete/categoria/:id', function($id) use($app){
    if(!empty($_SESSION['session'])){
        require_once 'models/Categoria.php';
        $category = new Categoria();
        $category->setIdcategoria($id);
        
        $eliminado = $category->deleteCategoria();
        if($eliminado){
            $app->flash('content', 'alert-success');
            $app->flash('mensaje', 'Eliminado Correctamente');
        }else{
            $app->flash('content', 'alert-danger');
            $app->flash('mensaje', 'No se ha podido eliminar, ya ha sido utilizado en la BD');
        }

        $app->redirect($app->urlFor('category'));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('delete-category');

