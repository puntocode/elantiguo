<?php
/*----------Index Empleados----------*/
$app->get('/maquinarias', function() use($app){
    //si hay sesion abierta
    if(!empty($_SESSION['session'])){
        require_once 'models/Producto.php';
        require_once 'models/Selectores.php';

        $product = new Producto();
        $productTable = $product->selectProducto();

        $selector = new Selectores();
        $userAr = $selector->returnRol();
        $category = $selector->uploadCategory();

        $app->render('maquinarias/maquinarias.html.twig', array(
            'producto' => $productTable, 'user' => $userAr, 'categoria'=>$category));

    }else{
        //si no hay redirecciona al login
        $app->redirect($app->urlFor('login'));
    }
})->name('machine');

/*----------Insertar Maquinaria----------*/
$app->post('/maquinaria/nuevo', function() use($app){

    require_once 'models/Producto.php';
    $product = new Producto();
    $request = $app->request;

    $product->setIdCategoria($request->post('category'));
    $product->setProducto(strtolower($request->post('product')));
    $product->setValorVenta(intval(preg_replace('/[^0-9]+/','', $request->post('cost'))));

    $insertado = $product->insertarProducto();
    if($insertado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Insertado Correctamente');
    }
    //echo $product->getIdCategoria()."/".$product->getProducto()."/".$product->getValorVenta()."/".$product->getImgSmall()."/".$product->getImgBig();
    $app->redirect($app->urlFor('machine'));

})->name('insert-product');


/*----------Actualizar Categoria----------*/
$app->post('/maquinaria/update', function() use($app){

    require_once 'models/Producto.php';
    $product = new Producto();
    $request = $app->request;

    $product->setIdProducto($request->post('up-id-product'));
    $product->setIdCategoria($request->post('up-category'));
    $product->setProducto(strtolower($request->post('up-product')));
    $product->setValorVenta(intval(preg_replace('/[^0-9]+/','', $request->post('up-cost'))));
    echo $product->getIdProducto()."/".$product->getProducto()."/".$product->getIdCategoria()."/".$product->getValorVenta();
    $actualizado = $product->updateProducto();

    if($actualizado){
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Editado Correctamente');
    }else{
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'No se ha podido modificar');
    }

    $app->redirect($app->urlFor('machine'));

})->name('update-product');

/*----------Actualizar Imagen Maquinaria----------*/
$app->post('/maquinaria/updte-image', function() use($app){
    require_once 'models/Producto.php';
    $product = new Producto();
    $request = $app->request;

    $product->setIdProducto($request->post('up-id-product-img'));
    $lastId = $product->getIdProducto();
    //$lastIdFirst = $product->selectMaxId();

    /*if (!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])){
        $product->setImgSmall('no-image.png');
        $product->setImgBig('no-image.png');
    }else {*/

    $image = getimagesize($_FILES['image']['tmp_name']);
    $mime = $image['mime'];
    if ($mime != 'image/gif' and $mime != 'image/png' and $mime != 'image/jpeg') {
        $app->flash('content', 'alert-danger');
        $app->flash('mensaje', 'El tipo de archivo seleccionado no es una imagen');

    } else {
        //OBTENGO EL NOMBRE Y LA RUTA DE LA IMAGEN
        $image_name = $_FILES['image']['name'];
        $image_tmp_directory = $_FILES['image']['tmp_name'];
        //DIRECTORIO DONDE SE GUADARAN LAS IMAGENES
        $dir = "views/assets/images/productos/";

        if ($mime === 'image/png') {
            $imagen_original = imagecreatefrompng($image_tmp_directory);
            $name_img = ".png";
            $name_img_small = "small.png";
        } elseif ($mime === 'image/jpeg') {
            $imagen_original = imagecreatefromjpeg($image_tmp_directory);
            $name_img = ".jpeg";
            $name_img_small = "small.jpeg";
        } elseif ($mime === 'image/gif') {
            $imagen_original = imagecreatefromgif($image_tmp_directory);
            $name_img = ".gif";
            $name_img_small = "small.gif";
        }

        //Obtengo el ancho y  alto de la imagen que cargue
        $ancho_original = imagesx($imagen_original);
        $alto_original = imagesy($imagen_original);
        //Redimensiono
        $width_small = 100;
        $height_small = 75;
        $width = 800;
        $height = 600;
        $imagen_resized = imagecreatetruecolor($width, $height);
        $imagen_resized_small = imagecreatetruecolor($width_small, $height_small);
        imagecopyresampled($imagen_resized, $imagen_original, 0, 0, 0, 0, $width, $height, $ancho_original, $alto_original);
        imagecopyresampled($imagen_resized_small, $imagen_original, 0, 0, 0, 0, $width_small, $height_small, $ancho_original, $alto_original);


        $name_img = strtr($name_img, " ", "-");//reemplazo espacios por guion medio
        $name_img = strtr($name_img, "/", "-");//reemplazo barra diagonal por guion medio
        $name_img = strtolower($name_img);//convierto a minusculas

        $name_img_small = strtr($name_img_small, " ", "-");//reemplazo espacios por guion medio
        $name_img_small = strtr($name_img_small, "/", "-");//reemplazo barra diagonal por guion medio
        $name_img_small = strtolower($name_img_small);//convierto a minusculas

        $image = $dir . $lastId . "-" . $name_img;
        $image_small = $dir . $lastId . "-" . $name_img_small;

        $nameImage = $lastId . "-" . $name_img;
        $nameImageSmall = $lastId . "-" . $name_img_small;
       /* $arrayName = array(
            "small" => $nameImageSmall,
            "big" => $nameImage
        );*/

        if ($mime === 'image/png') {
            imagepng($imagen_resized, $image);
            imagepng($imagen_resized_small, $image_small);
        } elseif ($mime === 'image/jpeg') {
            imagejpeg($imagen_resized, $image);
            imagejpeg($imagen_resized_small, $image_small);
        } elseif ($mime === 'image/gif') {
            imagegif($imagen_resized, $image);
            imagegif($imagen_resized_small, $image_small);
        }

        imagedestroy($imagen_original);
        imagedestroy($imagen_resized);
        imagedestroy($imagen_resized_small);

        $product->setImgSmall($nameImageSmall);
        $product->setImgBig($nameImage);
        $product->updateImagen();
        $app->flash('content', 'alert-success');
        $app->flash('mensaje', 'Editado Correctamente');
    }

    $app->redirect($app->urlFor('machine'));

})->name('update-img-product');


/*function uploadImage($mime, $id){

    //Get the last insert id from photos
    $lastId = $id;
    //OBTENGO EL NOMBRE Y LA RUTA DE LA IMAGEN
    $image_name = $_FILES['image']['name'];
    $image_tmp_directory = $_FILES['image']['tmp_name'];
    //DIRECTORIO DONDE SE GUADARAN LAS IMAGENES
    $dir = "views/assets/images/productos/";

    if ($mime === 'image/png') {
        $imagen_original = imagecreatefrompng($image_tmp_directory);
        $name_img = ".png";
        $name_img_small = "small.png";
    } elseif ($mime === 'image/jpeg') {
        $imagen_original = imagecreatefromjpeg($image_tmp_directory);
        $name_img = ".jpeg";
        $name_img_small = "small.jpeg";
    } elseif ($mime === 'image/gif') {
        $imagen_original = imagecreatefromgif($image_tmp_directory);
        $name_img = ".gif";
        $name_img_small = "small.gif";
    }

    //Obtengo el ancho y  alto de la imagen que cargue
    $ancho_original = imagesx($imagen_original);
    $alto_original = imagesy($imagen_original);
    //Redimensiono
    $width_small = 100;
    $height_small = 75;
    $width = 800;
    $height = 600;
    $imagen_resized = imagecreatetruecolor($width, $height);
    $imagen_resized_small = imagecreatetruecolor($width_small, $height_small);
    imagecopyresampled($imagen_resized, $imagen_original, 0, 0, 0, 0, $width, $height, $ancho_original, $alto_original);
    imagecopyresampled($imagen_resized_small, $imagen_original, 0, 0, 0, 0, $width_small, $height_small, $ancho_original, $alto_original);


    $name_img = strtr($name_img, " ", "-");//reemplazo espacios por guion medio
    $name_img = strtr($name_img, "/", "-");//reemplazo barra diagonal por guion medio
    $name_img = strtolower($name_img);//convierto a minusculas

    $name_img_small = strtr($name_img_small, " ", "-");//reemplazo espacios por guion medio
    $name_img_small = strtr($name_img_small, "/", "-");//reemplazo barra diagonal por guion medio
    $name_img_small = strtolower($name_img_small);//convierto a minusculas

    $image = $dir . $lastId . "-" . $name_img;
    $image_small = $dir . $lastId . "-" . $name_img_small;

    $nameImage = $lastId . "-" . $name_img;
    $nameImageSmall = $lastId . "-" . $name_img_small;
    $arrayName = array(
        "small" => $nameImageSmall,
        "big" => $nameImage
    );

    if ($mime === 'image/png') {
        imagepng($imagen_resized, $image);
        imagepng($imagen_resized_small, $image_small);
    } elseif ($mime === 'image/jpeg') {
        imagejpeg($imagen_resized, $image);
        imagejpeg($imagen_resized_small, $image_small);
    } elseif ($mime === 'image/gif') {
        imagegif($imagen_resized, $image);
        imagegif($imagen_resized_small, $image_small);
    }

    imagedestroy($imagen_original);
    imagedestroy($imagen_resized);
    imagedestroy($imagen_resized_small);

    return $arrayName;
}*/