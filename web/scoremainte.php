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

$app['monolog']->addDebug('logging output.');

$utils = new DataUtils();

$memberlist = $utils->getMemberList();

$nowDate = date("Y-m-d");

//現在の日付から、月初と月末の日付を取得
$dateFrom = $_REQUEST['dateFrom'];
$dateTo = $_REQUEST['dateTo'];

$scorelist = $utils->getScoreList($dateFrom, $dateTo);

foreach ($scorelist as $key => $row) {
  $tmp_matchno[$key] = $row['matchno'];
}
array_multisort( $tmp_matchno, SORT_ASC, SORT_NUMERIC,
                 $scorelist );

echo $app['twig']->render('scoremainte.twig', array('dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'memberlist' => $memberlist, 'scorelist' => $scorelist ) );
