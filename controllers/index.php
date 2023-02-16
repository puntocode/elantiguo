<?php
$app->get('/', function() use($app){

    $app->render('login.html.twig');

})->name('index');