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
$member = array();
$member['no'] = $_REQUEST['memberno'];
$member['name'] = $_REQUEST['membername'];
$member['sex'] = $_REQUEST['membersex'];
$member['class'] = $_REQUEST['memberclass'];
$member['result'] = 'failed';

$result = $dtutils->checkMemberList($member);

if ( !isset($member['name']) )
{
  echo "なまえが未入力です";
}

if ( !isset($member['sex']) )
{
  echo "性別が未入力です";
}

if ( !isset($member['class']) )
{
  echo "クラスが未入力です";
}

if ( $result < 1 )
{
  $return = $dtutils->registMember($member);
}else {
  $member['no'] = $result;

  $return = $dtutils->updateMember($result, $member);
}
$member['result'] = 'success';

echo $app['twig']->render('regist.twig', $member);
