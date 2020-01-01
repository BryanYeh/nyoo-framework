<?php
$config['twig']['extension'] = 'twig';
$config['twig']['template_path'] = ROOT . DS . 'app' . DS . 'views' . DS.THEME;
$config['twig']['enviornment']['debug'] = TRUE;
$config['twig']['enviornment']['charset'] = 'utf-8';
$config['twig']['enviornment']['cache'] = FALSE;//ROOT . DS . 'app' . DS . 'storage' . DS . 'cache' . DS . 'views' . DS . THEME;
$config['twig']['enviornment']['autoescape'] = 'html';