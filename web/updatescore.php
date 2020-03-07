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

$matchno = $_REQUEST['matchno'];

$matchdate = $_REQUEST['matchdate'];

$player1no = $_REQUEST['player1no'];
$player1name = $_REQUEST['player1name'];
$player1class = $_REQUEST['player1class'];
$player1score = $_REQUEST['player1score'];
$player1win = $_REQUEST['player1win'];
$player1runout = $_REQUEST['player1runout'];
$player2no = $_REQUEST['player2no'];
$player2name = $_REQUEST['player2name'];
$player2class = $_REQUEST['player2class'];
$player2score = $_REQUEST['player2score'];
$player2win = $_REQUEST['player2win'];
$player2runout = $_REQUEST['player2runout'];

$match = array( 'matchno' => $matchno
              , 'matchdate' => $matchdate
              , 'player1no' => $player1no
              , 'player1score' => $player1score
              , 'player1win' => $player1win
              , 'player1runout' => $player1runout
              , 'player2no' => $player2no
              , 'player2score' => $player2score
              , 'player2win' => $player2win
              , 'player2runout' => $player2runout
          );

// var_dump($match);
$dtutils->updateMatchScore($match);

echo 'スコア修正を行いました：<br>';
echo '試合番号：', $matchno, '<br>';
echo '対戦日：', $matchdate, '<br>';
echo 'Player1no：', $player1no, '<br>';
echo 'なまえ１：', $player1name, '<br>';
echo 'クラス１：', $player1class, '<br>';
echo 'スコア１：', $player1score, '<br>';
echo 'マスワリ１：', $player1runout, '<br>';
echo 'なまえ２：', $player2name, '<br>';
echo 'クラス２：', $player2class, '<br>';
echo 'スコア２：', $player2score, '<br>';
echo 'マスワリ２：', $player2runout, '<br>';


echo '<p>';
echo '<a class="gotoMenu" href="/scoremainte">前へ戻る</a>';
echo '</p>';

echo '<p>';
echo '<a class="gotoMenu" href="/maintenance">メニューへ</a>';
echo '</p>';

?>
