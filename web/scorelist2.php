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

// $dateFrom = $_REQUEST['dateFrom'];
// $dateTo = $_REQUEST['dateTo'];

$p1no = explode(",", $_REQUEST['p1No']);
$memberno = $p1no[0];
$membername = $p1no[1];

//現在の月を取得
$nowMonth = date('Y-n');
//現在の日付から、月初と月末の日付を取得
$fromDate = date('Y-m-d', strtotime('first day of ' . $nowMonth));
$toDate = date('Y-m-d', strtotime('last day of ' . $nowMonth));
$nowDate = date('Y-m-d');
//先月の月初と月末の日付を取得
$prevFromDate = date('Y-m-d', strtotime($nowDate . 'first day of previous month'));
$prevToDate = date('Y-m-d', strtotime($nowDate . 'last day of previous month'));

//全試合結果を取得
$scorelistall = $dtutils->getMatchReport($prevFromDate,$toDate,$memberno);
$scorelist = $dtutils->aggregateRankingBase($scorelistall);
$scoreresult = $dtutils->aggregateForReport($scorelist);
$allMostWin = $dtutils->getMostWin($scorelist);
$allMostLose = $dtutils->getMostLose($scorelist);

//先月の結果を集計
$listPrev = $dtutils->getMatchReport($prevFromDate,$prevToDate,$memberno);
$basePrev = $dtutils->aggregateRankingBase($listPrev);
$prevresult = $dtutils->aggregateForReport($basePrev);
$prevMostWin = $dtutils->getMostWin($basePrev);
$prevMostLose = $dtutils->getMostLose($basePrev);

//今月の結果を集計
$listNow = $dtutils->getMatchReport($fromDate,$toDate,$memberno);
$baseNow = $dtutils->aggregateRankingBase($listNow);
$nowresult = $dtutils->aggregateForReport($baseNow);
$nowMostWin = $dtutils->getMostWin($baseNow);
$nowMostLose = $dtutils->getMostLose($baseNow);

$mostWinnerLoser = array('allMostWin' => $allMostWin
                        , 'allMostLose' => $allMostLose
                        , 'prevMostWin' => $prevMostWin
                        , 'prevMostLose' => $prevMostLose
                        , 'nowMostWin' => $nowMostWin
                        , 'nowMostLose' => $nowMostLose
                      );

// ドロップダウン用のメンバーリスト取得
$memberlist = $dtutils->getMemberList();

$param = array( 'dateFrom' => $prevFromDate
              , 'dateTo' => $toDate
              , 'scorelist' => $scorelist
              , 'memberlist' => $memberlist
              , 'membername' => $membername
              , 'scoreresult' => $scoreresult
              , 'prevresult' => $prevresult
              , 'nowresult' => $nowresult
              , 'mostWinnerLoser' => $mostWinnerLoser
              );
echo $app['twig']->render('scorelist2.twig',  $param);
