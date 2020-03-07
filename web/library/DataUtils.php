<?php
  require_once 'Utils.php';
  require_once 'DBUtils.php';

  /**
   * データ操作関連共通ライブラリ
   */
  class DataUtils extends Utils
  {

    function __construct()
    {
      // code...
    }

    /*
     * MemberList.xmlに任意のメンバーが存在するか確認
     */
    function checkMemberList($member)
    {

      $utils = new DBUtils();

      $stmt = $utils->getMemberNo($member);

      $memberno = (int)$stmt['memberno'];

      $result = 0;

      if ( $memberno > 0 )
      {
        $result = $memberno;
      }

      return $result;
    }

    /*
     * 対戦結果の最終登録日を取得する
     */
    function getLatestMatchdate()
    {

      $utils = new DBUtils();

      $stmt = $utils->getLatestMatchdate($member);

      $matchdate = $stmt['matchdate'];

      return $matchdate;
    }

    /*
     * MemberList.xmlにメンバーを登録
     */
    function registMember($member)
    {
      $utils = new DBUtils();

      // membernoの最大値を取得
      $sql = "select max(memberno) from members;";

      $stmt = $utils->getDataSet($sql);

      $rows = (int)$stmt->fetchColumn();

      $registDate = date("Y-m-d");

      $result = $utils->registMember($rows, $registDate, $member);

      return $result;
    }

    /*
     * メンバーリスト更新
     */
    function updateMember($memberno, $member)
    {
      $utils = new DBUtils();

      $result = $utils->updateMember($memberno, $member);

      return $result;
    }

    /*
     * メンバーのリストを取得
     */
    function getMemberList()
    {
      $utils = new DBUtils();

      // メンバーリストを取得
      $sql = "select memberno, name, class, score from members order by memberno;";

      $stmt = $utils->getDataSet($sql);

      $memberlist = array();

      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $memberlist[] = array('memberno' => $row['memberno'], 'name' => $row['name'], 'class' => $row['class'], 'score' => $row['score']);
      }

      $stmt = null;

      return $memberlist;

    }

    /*
     * メンバーのリスト(全カラム)を取得
     */
    function getAllMemberList()
    {
      $utils = new DBUtils();

      // メンバーリストを取得
      $sql = "select memberno, registdate, name, class, score, sex from members order by memberno;";

      $stmt = $utils->getDataSet($sql);

      $memberlist = array();

      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $memberlist[] = array( 'memberno' => $row['memberno']
                             , 'registdate' => $row['registdate']
                             , 'name' => $row['name']
                             , 'class' => $row['class']
                             , 'score' => $row['score']
                             , 'sex' => $row['sex']
                            );
      }

      $stmt = null;

      return $memberlist;

    }

    /*
     * スコアの結果を登録
     */
    function registScore($score)
    {
      $utils = new DBUtils();

      // membernoの最大値を取得
      $sql = "select max(matchno) from matchdata;";

      $stmt = $utils->getDataSet($sql);

      $rows = 0;

      try{
        $rows = (int)$stmt->fetchColumn();
      }catch ( Exception $ex ){
        $rows = 0;
      }

      $stmt = null;

      $utils->registScore($rows, $score);

    }

    /*
     * 対戦結果のリストを取得
     */
    function getScoreList($dateFrom, $dateTo)
    {
      $utils = new DBUtils();

      $stmt = $utils->getScoreList($dateFrom, $dateTo);

      $scorelist = array();

      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $scorelist[] = array('matchno' => $row['matchno'],
                             'matchdate' => $row['matchdate'],
                             'player1name' => $row['player1name'],
                             'player1score' => $row['player1score'],
                             'player1runout' => $row['player1runout'],
                             'player1win' => $row['player1win'],
                             'player2name' => $row['player2name'],
                             'player2score' => $row['player2score'],
                             'player2runout' => $row['player2runout'],
                             'player2win' => $row['player2win']
                           );
      }

      $stmt = null;

      return $scorelist;
    }

    /*
     * ランキングのベース情報を取得
     */
    function getMatchList($dateFrom, $dateTo, $memberno)
    {
      $utils = new DBUtils();

      $stmt = $utils->getMatchList($dateFrom, $dateTo, $memberno);

      $scorelist = array();

      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $scorelist[] = array('matchno' => $row['matchno'],
                             'matchdate' => $row['matchdate'],
                             'player1no' => $row['player1no'],
                             'player1name' => $row['player1name'],
                             'player1score' => $row['player1score'],
                             'player1runout' => $row['player1runout'],
                             'player1win' => $row['player1win'],
                             'player2no' => $row['player2no'],
                             'player2name' => $row['player2name'],
                             'player2score' => $row['player2score'],
                             'player2runout' => $row['player2runout'],
                             'player2win' => $row['player2win'],
                             'rack_count' => $row['rack_count']
                           );
      }

      $stmt = null;

      return $scorelist;
    }

    function getRankingList($dateFrom, $dateTo)
    {
      // メンバーのリストを取得
      $memberlist = $this->getMemberList();

      $rankingbase = array();
      foreach ($memberlist as $member) {
        // 試合結果を取得
        $scorelist = $this->getMatchList($dateFrom, $dateTo, $member['memberno']);

        foreach ($scorelist as $score) {
          // ランキングのもととなるデータを「<memberno>,<対戦相手>,<試合日>,<結果>」の形で整形する
          if( $member['memberno'] == $score['player1no'] )
          {
            $rankingbase[] = array('matchno' => $score['matchno'],
                                 'memberno' => $member['memberno'],
                                 'membername' => $score['player1name'],
                                 'opponentno' => $score['player2no'],
                                 'opponentname' => $score['player2name'],
                                 'matchdate' => $score['matchdate'],
                                 'score' => $score['player1score'],
                                 'runout' => $score['player1runout'],
                                 'result' => $score['player1win'],
                                 'rack_count' => $score['rack_count']
                               );
          }elseif ( $member['memberno'] == $score['player2no'] ) {
            $rankingbase[] = array('matchno' => $score['matchno'],
                                 'memberno' => $member['memberno'],
                                 'membername' => $score['player2name'],
                                 'opponentno' => $score['player1no'],
                                 'opponentname' => $score['player1name'],
                                 'matchdate' => $score['matchdate'],
                                 'score' => $score['player2score'],
                                 'runout' => $score['player2runout'],
                                 'result' => $score['player2win'],
                                 'rack_count' => $score['rack_count']
                               );
          }
        }
      }

      // メンバーNo、対戦相手No、試合番号順にソート
      foreach ($rankingbase as $key => $row) {
        $tmp_memberno[$key] = $row['memberno'];
        $tmp_opponentno[$key] = $row['opponentno'];
        $tmp_matchno[$key] = $row['matchno'];
      }
      array_multisort( $tmp_memberno,
                       $tmp_opponentno, SORT_ASC, SORT_NUMERIC,
                       $tmp_matchno, SORT_ASC,
                       $rankingbase);

      return $rankingbase;
    }

    // ランキングの集計
    function aggregateRankingBase( $scoreList )
    {
      $rankingbase = array();

      $tmp_memberno = -1;
      $tmp_opponentno = -1;
      $tmp_win_count = 0;
      $tmp_lose_count = 0;
      $tmp_match_count = 0;
      $tmp_rack_count = 0;
      $tmp_runout_count = 0;

      $point = 0;
      foreach ($scoreList as $score)
      {
        // メンバーNoが変わったら集計リセット
        if ( $tmp_memberno != $score['memberno'] )
        {
          if ( $tmp_memberno != -1 )
          {
            $rankingbase[] = array('memberno' => $tmp_memberno,
                               'membername' => $tmp_membername,
                               'opponentno' => $tmp_opponentno,
                               'opponentname' => $tmp_opponentname,
                               'match_count' => $tmp_match_count,
                               'win_count' => $tmp_win_count,
                               'lose_count' => $tmp_lose_count,
                               'rack_count' => $tmp_rack_count,
                               'runout_count' => $tmp_runout_count,
                               'point' => $point
                             );
          }
          $tmp_memberno = $score['memberno'];
          $tmp_membername = $score['membername'];
          $tmp_opponentno = -1;
          $tmp_win_count = 0;
          $tmp_lose_count = 0;
          $tmp_match_count = 0;
          $tmp_rack_count = 0;
          $tmp_runout_count = 0;
          $point = 0;
        }

        // 対戦者が変わった
        if ( $tmp_opponentno != $score['opponentno'] )
        {
          if ( $tmp_opponentno != -1 )
          {
            $rankingbase[] = array('memberno' => $tmp_memberno,
                               'membername' => $tmp_membername,
                               'opponentno' => $tmp_opponentno,
                               'opponentname' => $tmp_opponentname,
                               'match_count' => $tmp_match_count,
                               'win_count' => $tmp_win_count,
                               'lose_count' => $tmp_lose_count,
                               'rack_count' => $tmp_rack_count,
                               'runout_count' => $tmp_runout_count,
                               'point' => $point
                             );
          }

          $tmp_opponentno = $score['opponentno'];
          $tmp_opponentname = $score['opponentname'];
          $tmp_win_count = 0;
          $tmp_lose_count = 0;
          $tmp_match_count = 0;
          $tmp_rack_count = 0;
          $tmp_runout_count = 0;
          $point = 0;
        }

        // ここで集計
        if ( $score['result'] == 1 )
        {
          // 勝数をカウント
          $tmp_win_count++;
          if ( $tmp_match_count < 2 )
          {
            $point += 7;
          }else {
            $point++;
          }
        }else {
          // 負け数をカウント
          $tmp_lose_count++;

          if ( $tmp_match_count < 2 )
          {
            if ( $score['score'] < 2)
            {
              $point += $score['score'];
            }else {
              $point += 2;
            }

          }
        }
        $tmp_rack_count += $score['rack_count'];
        $tmp_runout_count += $score['runout'];
        $tmp_match_count++;
      }

      $rankingbase[] = array('memberno' => $tmp_memberno,
                         'membername' => $tmp_membername,
                         'opponentno' => $tmp_opponentno,
                         'opponentname' => $tmp_opponentname,
                         'match_count' => $tmp_match_count,
                         'win_count' => $tmp_win_count,
                         'lose_count' => $tmp_lose_count,
                         'rack_count' => $tmp_rack_count,
                         'runout_count' => $tmp_runout_count,
                         'point' => $point
                       );

      return $rankingbase;
    }

    function aggregateRanking( $rankingbase )
    {
      $ranking = array();
      $point = 0;

      // 一旦メンバーNoでソート
      foreach ($rankingbase as $key => $row) {
        $tmp_memberno[$key] = $row['memberno'];
      }
      array_multisort( $tmp_memberno, SORT_ASC, SORT_NUMERIC,
                       $rankingbase );

      $tmpmemberno = -1;

      foreach ($rankingbase as $base) {
        if ( $tmpmemberno != $base['memberno'] )
        {
          if ( $tmpmemberno != -1 )
          {
            $ranking[] = array('memberno' => $tmpmemberno,
                               'membername' => $tmpmembername,
                               'point' => $point
                             );
          }
          $tmpmemberno = $base['memberno'];
          $tmpmembername = $base['membername'];
          $point = 0;
        }

        $point += $base['point'];
      }

      $ranking[] = array('memberno' => $tmpmemberno,
                         'membername' => $tmpmembername,
                         'point' => $point
                       );

      // ポイントの降順でソート
      foreach ($ranking as $key => $row) {
        $tmp_point[$key] = $row['point'];
      }
      array_multisort( $tmp_point, SORT_DESC, SORT_NUMERIC,
                       $ranking );

      return $ranking;
    }

    function getMatchReport($dateFrom, $dateTo, $memberno)
    {
      $rankingbase = array();
      // 試合結果を取得
      $scorelist = $this->getMatchList($dateFrom, $dateTo, $memberno);
      foreach ($scorelist as $score) {
        // ランキングのもととなるデータを「<memberno>,<対戦相手>,<試合日>,<結果>」の形で整形する
        if( $memberno == $score['player1no'] )
        {
          $rankingbase[] = array('matchno' => $score['matchno'],
                               'memberno' => $member['memberno'],
                               'membername' => $score['player1name'],
                               'opponentno' => $score['player2no'],
                               'opponentname' => $score['player2name'],
                               'matchdate' => $score['matchdate'],
                               'score' => $score['player1score'],
                               'runout' => $score['player1runout'],
                               'result' => $score['player1win'],
                               'rack_count' => $score['rack_count']
                             );
        }elseif ( $memberno == $score['player2no'] ) {
          $rankingbase[] = array('matchno' => $score['matchno'],
                               'memberno' => $member['memberno'],
                               'membername' => $score['player2name'],
                               'opponentno' => $score['player1no'],
                               'opponentname' => $score['player1name'],
                               'matchdate' => $score['matchdate'],
                               'score' => $score['player2score'],
                               'runout' => $score['player2runout'],
                               'result' => $score['player2win'],
                               'rack_count' => $score['rack_count']
                             );
        }
      }

      // メンバーNo、対戦相手No、試合番号順にソート
      foreach ($rankingbase as $key => $row) {
        $tmp_opponentno[$key] = $row['opponentno'];
        $tmp_matchno[$key] = $row['matchno'];
      }
      array_multisort( $tmp_matchno, SORT_ASC,
                       $tmp_opponentno, SORT_ASC, SORT_NUMERIC,
                       $rankingbase);

      return $rankingbase;
    }

    // 対戦結果分析用集計
    function aggregateForReport($scorelist)
    {
      $tmp_win_count = 0;
      $tmp_lose_count = 0;
      $tmp_runout_rate = 0;
      $tmp_runout_count = 0;
      $tmp_match_count = 0;

      foreach ($scorelist as $score)
      {
        $tmp_win_count += $score['win_count'];
        $tmp_lose_count += $score['lose_count'];
        $tmp_match_count += $score['match_count'];
        $tmp_runout_count += $score['runout_count'];
        if ( $score['runout_count'] > 0 )
        {
          $tmp_runout_rate += $score['runout_count'] / $score['win_count'];
          $tmp_runout_rate = floor($tmp_runout_rate * 100) / 100;
        }
      }

      if ( $tmp_win_count > 0 )
      {
        $win_rate = ( $tmp_win_count / $tmp_match_count ) * 100;
        $win_rate = floor($win_rate * 100) / 100;
      }else {
        $win_rate = 0;
      }

      if ( $tmp_runout_rate > 0 )
      {
        $runout_rate = ( $tmp_runout_rate / $tmp_win_count ) * 100;
        $runout_rate = floor($runout_rate * 100) / 100;
      }else {
        $runout_rate = 0;
      }

      $result = array('win_count' => $tmp_win_count,
                      'lose_count' => $tmp_lose_count,
                      'win_rate' => $win_rate,
                      'runout_count' => $tmp_runout_count,
                      'runout_rate' => $runout_rate
                     );

      return $result;
    }

    // 一番勝った人を取得
    function getMostWin($scorelist)
    {
      // メンバーNo、対戦相手No、試合番号順にソート
      foreach ($scorelist as $key => $row) {
        $tmp_win_count[$key] = $row['win_count'];
      }
      array_multisort( $tmp_win_count, SORT_DESC, SORT_NUMERIC,
                       $scorelist);

      $wintarget = $scorelist[0]['opponentname'];

      return $wintarget;
    }

    // 一番負けた人を取得
    function getMostLose($scorelist)
    {
      // メンバーNo、対戦相手No、試合番号順にソート
      foreach ($scorelist as $key => $row) {
        $tmp_lose_count[$key] = $row['lose_count'];
      }
      array_multisort( $tmp_lose_count, SORT_DESC, SORT_NUMERIC,
                       $scorelist);

      $losetarget = $scorelist[0]['opponentname'];

      return $losetarget;
    }

    /*
     * メンバーリスト更新
     */
    function updateMatchScore($match)
    {
      $utils = new DBUtils();

      $result = $utils->updateMatchScore($match);

      return $result;
    }

  }

?>
