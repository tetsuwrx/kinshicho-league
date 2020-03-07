function scoreInput( scoreVal )
{
  frameObj = getEmptyCell();

  switch( scoreVal.value ) {
    case 'S':
      //ストライク
      frameObj.innerText = "ST";
      frameObj = getEmptyTd();
      frameObj.style.backgroundImage = "linear-gradient(-45deg, transparent 49%, black 49%, black 51%, transparent 51%, transparent)";
      frameObj = getEmptyCell();
      frameObj.innerText = "-";
      frameObj.style.display = "none";
      break;
    case 'SP':
      //スペア
      frameObj.innerText = "SP";
      break;
    case 'G':
      var idval = frameObj.id;
      if ( idval.substr(-1,1) == '1' )
      {
        frameObj.innerText = "G";
      }else {
        frameObj = getEmptyCell();
        frameObj.innerText = "-";
      }

    default:
      //普通にスコア入れる
      frameObj.innerText = scoreVal.value;
      break;
  }
  // スコア計算
  calcScore();

  // ボタンの値を変更
  var idval = frameObj.id;
  if ( idval.substr(-1,1) == '1' )
  {
    changeAllBtnVal( scoreVal.value );
  }else {
    resetAllBtnVal();
  }

}

function getEmptyCell()
{
  for ( var i = 1; i <= 10; i++ ){
    var idkey1 = 'frame' + i + '-1';
    var idkey2 = 'frame' + i + '-2';

    var frameObj = document.getElementById(idkey1);
    if ( frameObj.innerText == "" )
    {
      return frameObj;
    }

    var frameObj = document.getElementById(idkey2);
    if ( frameObj.innerText == "" )
    {
      return frameObj;
    }

    if ( i == 10 )
    {
      var idkey3 = 'frame10-3';

      var frameObj = document.getElementById(idkey3);
      if ( frameObj.innerText == "" )
      {
        return frameObj;
      }
    }
  }
}

function getEmptyTd()
{
  for ( var i = 1; i <= 10; i++ ){
    var idkey1 = 'frame' + i + '-1';
    var idkey2 = 'frame' + i + '-2';

    var tdkey1 = 'td' + i + '-1';
    var tdkey2 = 'td' + i + '-2';

    var frameObj = document.getElementById(idkey1);
    if ( frameObj.innerText == "" )
    {
      var tdObj = document.getElementById(tdkey1);
      return tdObj;
    }

    var frameObj = document.getElementById(idkey2);
    if ( frameObj.innerText == "" )
    {
      var tdObj = document.getElementById(tdkey2);
      return tdObj;
    }

    if ( i == 10 )
    {
      var idkey3 = 'frame10-3';
      var tdkey3 = 'td10-3';

      var frameObj = document.getElementById(idkey3);
      if ( frameObj.innerText == "" )
      {
        var tdObj = document.getElementById(tdkey3);
        return tdObj;
      }
    }
  }
}

// スコア計算
function calcScore()
{
  var currentScore = 0;

  for ( var i = 1; i <= 10; i++ ){
    var idkey1 = 'frame' + i + '-1';
    var idkey2 = 'frame' + i + '-2';

    // １投目の計算
    var frameObj = document.getElementById(idkey1);
    if ( frameObj.innerText != "" )
    {
      // 数字だったら単純に足す
      if ( isNan(frameObj.innerText) )
      {
        currentScore += frameObj.innerText;
      }
    }

    // ２投目の計算
    var frameObj = document.getElementById(idkey2);
    if ( frameObj.innerText != "" )
    {
      // 数字だったら単純に足す
      if ( isNan(frameObj.innerText) )
      {
        currentScore += frameObj.innerText;
      }

      // スコアの表示
      var idkey = 'frame' + i;
      var frameObj = document.getElementById(idkey);
      frameObj.innerText = currentScore;
    }
  }
}

// ボタンの値をリセット
function resetAllBtnVal()
{
  document.getElementById('btnS').value = "S";
  document.getElementById('btnS').disabled = false;
  document.getElementById('btnG').value = "G";
  document.getElementById('btnG').disabled = false;

  for ( var i = 1; i < 10; i++ )
  {
    var key = 'btn' + i;

    document.getElementById(key).value = i;
    document.getElementById(key).disabled = false;
  }
}
