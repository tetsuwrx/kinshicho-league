<?php

require('../vendor/autoload.php');
require('library/DataUtils.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$dtutils = new DataUtils();

$memberlist = $utils->getMemberList();

echo $app['twig']->render('memberlist.twig', array('memberlist' => $memberlist) );
