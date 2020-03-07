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

// $memberlist = $utils->getMemberList();

// $entryDate = date("Y-m-d");

// echo $app['twig']->render('scoreregist.twig', array('entryDate' => $entryDate, 'memberlist' => $memberlist) );

$cnt = 0;

$scorelist = array();

for ($i = 1; $i <= 20; $i++) {

  $key = 'entryDate_'.$i;
  $entryDate = $_REQUEST[$key];

  $key = 'player1no_'.$i;
  $player1no = $_REQUEST[$key];

  $key = 'class1_'.$i;
  $class1 = $_REQUEST[$key];

  $key = 'score1_'.$i;
  $score1 = $_REQUEST[$key];

  $key = 'p1win_'.$i;
  $p1win = $_REQUEST[$key];

  $key = 'p1Masu_'.$i;
  $p1masu = $_REQUEST[$key];

  $key = 'player2no_'.$i;
  $player2no = $_REQUEST[$key];

  $key = 'class2_'.$i;
  $class2 = $_REQUEST[$key];

  $key = 'score2_'.$i;
  $score2 = $_REQUEST[$key];

  $key = 'p2win_'.$i;
  $p2win = $_REQUEST[$key];

  $key = 'p2Masu_'.$i;
  $p2masu = $_REQUEST[$key];

  if ( $score1 != null && $score2 != null )
  {
    $cnt++;
    $scorelist[] = array( 'entryDate' => $entryDate
                    , 'p1no' => $player1no
                    , 'p1score' => $score1
                    , 'p1win' => $p1win
                    , 'p1masu' => $p1masu
                    , 'p2no' => $player2no
                    , 'p2score' => $score2
                    , 'p2win' => $p2win
                    , 'p2masu' => $p2masu
               );
  }

}

foreach ( $scorelist as $score )
{
  $dtutils->registScore($score);
}

echo '登録件数：', $cnt, '件完了しました。';

echo '<p>';
echo '<a class="gotoMenu" href="/scoreregist">前へ戻る</a>';
echo '</p>';

echo '<p>';
echo '<a class="gotoMenu" href="/maintenance">メニューへ</a>';
echo '</p>';

?>
