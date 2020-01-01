<?php

$routes->get('','App\Controllers\HomeController@index');
$routes->get('get-info/{id}','App\Controllers\HomeController@database');
$routes->get('create-info','App\Controllers\HomeController@create');