<?php
$password = $_REQUEST['password'];

if ( $password == '8804' )
{
  header('Location:/maintenance', true, 301);
}else{
  echo '<p>パスワードが間違ってます</p>';
}


?>
