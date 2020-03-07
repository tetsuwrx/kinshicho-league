<?php

class MatchRecord extends ModelBase
{
  // battledata.txtから全レコードを取得
  public function getMatchRecordAll()
  {
    $filename = "/library/battledata.txt"

    $fp=fopen($fileNm,'r');
    $n=0;
    while(!feof($fp)){
        $List[$n] = fgets($fp);
        $List[$n] = str_replace("\n","",$List[$n]);
        $Lists[$n] = explode(",",$List[$n]);
        $n++;
    }
    fclose($fp);

    return $Lists;
  }
}
 ?>
