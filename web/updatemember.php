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

$memberno = $_REQUEST['memberno'];

$membername = $_REQUEST['membername'];
$class = $_REQUEST['class'];
$sex = $_REQUEST['sex'];

$member = array( 'memberno' => $memberno
              , 'name' => $membername
              , 'class' => $class
              , 'sex' => $sex
          );

// var_dump($match);
$dtutils->updateMember($memberno, $member);

echo 'メンバーの修正を行いました：<br>';
echo 'メンバーNo：', $memberno, '<br>';
echo 'なまえ：', $membername, '<br>';
echo 'クラス：', $class, '<br>';
echo '性別：', $sex, '<br>';

echo '<p>';
echo '<a class="gotoMenu" href="/memberlist">前へ戻る</a>';
echo '</p>';

echo '<p>';
echo '<a class="gotoMenu" href="/maintenance">メニューへ</a>';
echo '</p>';

?>
