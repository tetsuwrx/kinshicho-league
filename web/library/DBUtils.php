<?php
  /**
   * データ操作関連共通ライブラリ
   */
  class DBUtils
  {

    function __construct()
    {
      // code...
    }

    function getDataSet($sql)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $result = $pdo->query( $sql );

      $pdo = null;

      return $result;
    }

    function getMemberNo($member)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $sql = "select memberno from members where name = :name and sex = :sex";

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(":name", $member['name']);
      $stmt->bindValue(":sex", $member['sex']);

      $stmt->execute();

      $result = $stmt->fetch();

      $pdo = null;

      return $result;
    }

    function getLatestMatchdate()
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $sql = "select max(matchdate) as matchdate from matchdata";

      $stmt = $pdo->prepare($sql);

      $stmt->execute();

      $result = $stmt->fetch();

      $pdo = null;

      return $result;
    }

    function registMember($rows, $registDate, $member)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $sql = "insert into members values ( ?, ?, ?, ?, ?, ? )";

      $result = FALSE;
      try{
        $stmt = $pdo->prepare($sql);

        $score = $this->getScore( $member['class'] );

        $pdo->beginTransaction();

        $stmt->execute(array($rows + 1, $registDate, $member['name'], $member['class'], $score, $member['sex']));

        $pdo->commit();

        $result = TRUE;
      }catch(Exception $e){
        $pdo->rollBack();
        echo 'エラーメッセージ：', $e->getMessage(), "\n";
      }

      $pdo = null;
      $stmt = null;

      return $result;
    }

    function updateMember($memberno, $member)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $sql = "update members set name = :name, class = :class, score = :score, sex = :sex where memberno = :memberno";

      $stmt = $pdo->prepare($sql);

      $score = $this->getScore( $member['class'] );

      $stmt->bindValue(":name", $member['name']);
      $stmt->bindValue(":class", $member['class']);
      $stmt->bindValue(":score", $score);
      $stmt->bindValue(":sex", $member['sex']);
      $stmt->bindValue(":memberno", $memberno);

      $result = FALSE;

      try{
        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();

        $result = TRUE;

      }catch(Exception $e)
      {
        $pdo->rollBack();
        echo 'エラーメッセージ：', $e->getMessage(), "\n";
      }

      $pdo = null;
      $stmt = null;

      return $result;
    }

    function execDML($sql)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $result = $pdo->exec( $sql );

      $pdo = null;
    }

    function getScore($class)
    {
      $score = 0;

      switch ( $class )
      {
        case "UC":
          $score = 2;
          break;
        case "C":
          $score = 3;
          break;
        case "B":
          $score = 4;
          break;
        case "A":
          $score = 5;
          break;
        case "SA":
          $score = 6;
          break;
      }

      return $score;
    }

    function registScore($matchno, $score)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $sql = "insert into matchdata values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

      $stmt = $pdo->prepare($sql);

      try{
        $pdo->beginTransaction();
        $stmt->execute(array($matchno + 1, $score['entryDate'], $score['p1no'], $score['p1score'], $score['p1win'], $score['p1masu'], $score['p2no'], $score['p2score'], $score['p2win'], $score['p2masu']));
        $pdo->commit();
      }catch(Exception $e)
      {
        $pdo->rollBack();
        echo 'エラーメッセージ：', $e->getMessage(), "\n";
      }

      $pdo = null;
      $stmt = null;
    }

    function getScoreList($dateFrom, $dateTo)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      // スコアリストを取得
      $sql = "select matchno
                   , matchdate
                   , player1name
                   , player1score
                   , player1runout
                   , player1win
                   , player2name
                   , player2score
                   , player2runout
                   , player2win
                from v_matchdata
               where matchdate >= :dateFrom
                 and matchdate <= :dateTo
               order by matchdate
              ;";

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(":dateFrom", $dateFrom);
      $stmt->bindValue(":dateTo", $dateTo);

      $stmt->execute();

      $pdo = null;

      return $stmt;
    }

    /*
     * 引数で指定されたメンバーの対戦結果を取得する
     */
    function getMatchList($dateFrom, $dateTo, $memberno)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      // スコアリストを取得
      $sql = "select a.matchno
                   , a.matchdate
                   , a.player1no
                   , b.name as player1name
                   , a.player1score
                   , a.player1win
                   , a.player1runout
                   , a.player2no
                   , c.name as player2name
                   , a.player2score
                   , a.player2win
                   , a.player2runout
                   , ( a.player1score + a.player2score ) as rack_count
                from matchdata a
               inner join members b
                  on a.player1no = b.memberno
               inner join members c
                  on a.player2no = c.memberno
               where a.matchdate >= :dateFrom
                 and a.matchdate <= :dateTo
                 and ( a.player1no = :memberno or a.player2no = :memberno )
               order by matchdate
              ;";

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(":dateFrom", $dateFrom);
      $stmt->bindValue(":dateTo", $dateTo);
      $stmt->bindValue(":memberno", $memberno);

      $stmt->execute();

      $pdo = null;

      return $stmt;
    }

    /*
    * スコアの更新
    */
    function updateMatchScore($match)
    {
      $url = parse_url(getenv('DATABASE_URL'));

      $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'],1));

      $pdo = new PDO($dsn, $url['user'], $url['pass']);

      $sql = "update matchdata
                 set matchdate = :matchdate
                   , player1no = :player1no
                   , player1score = :player1score
                   , player1win = :player1win
                   , player1runout = :player1runout
                   , player2no = :player2no
                   , player2score = :player2score
                   , player2win = :player2win
                   , player2runout = :player2runout
               where matchno = :matchno";

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(":matchdate", $match['matchdate']);
      $stmt->bindValue(":player1no", $match['player1no']);
      $stmt->bindValue(":player1score", $match['player1score']);
      $stmt->bindValue(":player1win", $match['player1win']);
      $stmt->bindValue(":player1runout", $match['player1runout']);
      $stmt->bindValue(":player2no", $match['player2no']);
      $stmt->bindValue(":player2score", $match['player2score']);
      $stmt->bindValue(":player2win", $match['player2win']);
      $stmt->bindValue(":player2runout", $match['player2runout']);

      $stmt->bindValue(":matchno", $match['matchno']);

      $result = FALSE;

      try{
        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();

        $result = TRUE;

      }catch(Exception $e)
      {
        $pdo->rollBack();
        echo 'エラーメッセージ：', $e->getMessage(), "\n";
      }

      $pdo = null;
      $stmt = null;

      return $result;
    }

  }

?>
