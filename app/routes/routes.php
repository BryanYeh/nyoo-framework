<?php

$routes->get('','App\Controllers\HomeController@indedx');
$routes->get('get-info/{id}','App\Controllers\HomeController@database');
$routes->get('create-info','App\Controllers\HomeController@create');