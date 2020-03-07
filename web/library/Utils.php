<?php
  // 共通ライブラリ
  class Utils
  {
    public $matchRecordHeader = 'library/BattleDataHeader.xml';
    public $memberListXML = 'library/MemberList.xml';

    // ファイルオープン
    public function FileOpen($fileName)
    {
      $fp=fopen($fileNm,'r');

      return $fp;
    }

  }
?>
