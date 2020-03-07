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
$score = array();

$dateFrom = $_REQUEST['dateFrom'];
$dateTo = $_REQUEST['dateTo'];

$latest = $dtutils->getLatestMatchdate();

$scorelist = $dtutils->getScoreList($dateFrom,$dateTo);
$rankinglist = $dtutils->getRankingList($dateFrom,$dateTo);
$rankingbase = $dtutils->aggregateRankingBase($rankinglist);
$ranking = $dtutils->aggregateRanking($rankingbase);

echo $app['twig']->render('ranking2.twig', array('dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'scorelist' => $scorelist, 'rankingbase' => $rankingbase, 'ranking' => $ranking, 'latest' => $latest) );
