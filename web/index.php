<?php

require('../vendor/autoload.php');
require('library/DataUtils.php');

use Symfony\Component\HttpFoundation\Request;

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

/*
$dbopts = parse_url(getenv('DATABASE_URL'));
$app->register(new Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider('pdo'),
               array(
                'pdo.server' => array(
                   'driver'   => 'pgsql',
                   'user' => $dbopts["user"],
                   'password' => $dbopts["pass"],
                   'host' => $dbopts["host"],
                   'port' => $dbopts["port"],
                   'dbname' => ltrim($dbopts["path"],'/')
                   )
               )
);
*/
// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/regist', function(Request $request) use($app) {
  $app['monolog']->addDebug('logging output.');
  $member = array();
  $member['no'] = $request->get('memberno');
  $member['name'] = $request->get('membername');
  $member['sex'] = $request->get('membersex');
  $member['class'] = $request->get('memberclass');
  $member['result'] = '';
  return $app['twig']->render('regist.twig', $member);
});

$app->get('/score', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $utils = new DataUtils();

  $memberlist = $utils->getMemberList();

  $entryDate = date("Y-m-d");

  return $app['twig']->render('score.twig', array('entryDate' => $entryDate, 'memberlist' => $memberlist) );
});

$app->get('/scorelist', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $utils = new DataUtils();

  $dateFrom = date("Y-m-d");
  $dateTo = date("Y-m-d");
  $scorelist = array();
  $memberlist = $utils->getMemberList();
  $membername = "";
  $scoreresult = array();
  $prevresult = array();
  $nowresult = array();
  $mostWinnerLoser = array();

  $param = array( 'dateFrom' => $dateFrom
                , 'dateTo' => $dateTo
                , 'scorelist' => $scorelist
                , 'memberlist' => $memberlist
                , 'membername' => $membername
                , 'scoreresult' => $scoreresult
                , 'prevresult' => $prevresult
                , 'nowresult' => $nowresult
                , 'mostWinnerLoser' => $mostWinnerLoser
                );

  return $app['twig']->render('scorelist.twig', $param );
});

$app->get('/scorelist2', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $utils = new DataUtils();

  $dateFrom = date("Y-m-d");
  $dateTo = date("Y-m-d");
  $scorelist = array();
  $memberlist = $utils->getMemberList();
  $membername = "";
  $scoreresult = array();
  $prevresult = array();
  $nowresult = array();
  $mostWinnerLoser = array();

  $param = array( 'dateFrom' => $dateFrom
                , 'dateTo' => $dateTo
                , 'scorelist' => $scorelist
                , 'memberlist' => $memberlist
                , 'membername' => $membername
                , 'scoreresult' => $scoreresult
                , 'prevresult' => $prevresult
                , 'nowresult' => $nowresult
                , 'mostWinnerLoser' => $mostWinnerLoser
                );

  return $app['twig']->render('scorelist2.twig', $param );
});

$app->get('/ranking', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $dateFrom = date("Y-m-d");
  $dateTo = date("Y-m-d");
  $latest = "";
  $ranking = array();
  $rankingbase = array();
  $scorelist = array();

  return $app['twig']->render('ranking.twig', array('dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'scorelist' => $scorelist, 'rankingbase' => $rankingbase, 'ranking' => $ranking, 'latest' => $latest) );
});

$app->get('/ranking2', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $dateFrom = date("Y-m-d");
  $dateTo = date("Y-m-d");
  $latest = "";
  $ranking = array();
  $rankingbase = array();
  $scorelist = array();

  return $app['twig']->render('ranking2.twig', array('dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'scorelist' => $scorelist, 'rankingbase' => $rankingbase, 'ranking' => $ranking, 'latest' => $latest) );
});

$app->get('/memberlist', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $utils = new DataUtils();

  $memberlist = $utils->getAllMemberList();

  return $app['twig']->render('memberlist.twig', array('memberlist' => $memberlist) );
});

$app->get('/scoreregist', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $utils = new DataUtils();

  $memberlist = $utils->getMemberList();
  foreach ($memberlist as $key => $row) {
    $tmp_name[$key] = $row['name'];
  }
  array_multisort( $tmp_name, SORT_ASC,
                   $memberlist );

  $entryDate = date("Y-m-d");

  return $app['twig']->render('scoreregist.twig', array('entryDate' => $entryDate, 'memberlist' => $memberlist) );
});

$app->get('/scoremainte', function() use($app) {
  $app['monolog']->addDebug('logging output.');

  $utils = new DataUtils();

  $memberlist = $utils->getMemberList();
  foreach ($memberlist as $key => $row) {
    $tmp_name[$key] = $row['name'];
  }
  array_multisort( $tmp_name, SORT_ASC,
                   $memberlist );

  $nowDate = date("Y-m-d");

  //現在の月を取得
  $nowMonth = date('Y-n');
  //現在の日付から、月初と月末の日付を取得
  $dateFrom = date('Y-m-d', strtotime('first day of ' . $nowMonth));
  $dateTo = date("Y-m-d");

  $scorelist = $utils->getScoreList($dateFrom, $dateTo);

  foreach ($scorelist as $key => $row) {
    $tmp_matchno[$key] = $row['matchno'];
  }
  array_multisort( $tmp_matchno, SORT_ASC, SORT_NUMERIC,
                   $scorelist );

  return $app['twig']->render('scoremainte.twig', array('dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'memberlist' => $memberlist, 'scorelist' => $scorelist ) );
});

$app->get('/bowlards', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('bowlard.twig');
});

$app->get('/bowlards_score', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('bowlard_score.twig');
});

$app->get('/admin', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('auth.twig');
});

$app->get('/maintenance', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('maintenance.twig');
});

$app->run();
