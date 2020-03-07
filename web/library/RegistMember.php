<?php
class RegistMember
{
  // メンバー登録クラス

  function __construct(){
    // DB接続とか
  }

  /*
    メンバー登録
    $no … 登録No
    $name … メンバー名
    $level … クラス
    $registDate … 登録日
    $upDate … 更新日
  */
  function memberRegist($no, $name, $level, $registDate, $upDate )
  {
    // 1.同じ名前での登録があるかどうかのチェック
    // 2.無ければ新規登録
    // 3.あれば更新
  }
}
?>
