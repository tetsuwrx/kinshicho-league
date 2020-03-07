function setClass(nameObj, classID, masuID)
{
  var selInd = nameObj.selectedIndex;
  var nameVal = nameObj.options[selInd].value;
  var classObj = document.getElementById(classID);
  var masuObj = document.getElementById(masuID);

  if ( nameVal != "0" )
  {
    var classVal = nameVal.split(',');

    classObj.value = classVal[2];

    var items = masuObj.options;

    // 全てのアイテムを削除
    for ( i = items.length; i > 0; i-- ) {
      items[i-1] = null;
    }

    var itemindex = 0;
    for ( i = 0 ; i <= classVal[3]; i++) {
      items[i] = new Option("◎-" + i, i);
    }

  }else{
    classObj.value = "";
  }
}

function registScore()
{
  if ( checkEntry() == false )
  {
    return false;
  }

  var formObj = document.createElement('form');

  formObj.action = 'scoreregist.php';
  formObj.method = 'post';

  for ( i = 1; i < scorelist.rows.length; i++ )
  {
    var entryDate = document.getElementById('entryDate_' + i);

    var obj = document.createElement('input');
    obj.value = entryDate.value;
    obj.name = entryDate.id;
    formObj.appendChild(obj);

    var player1Obj = document.getElementById('player1name_' + i);
    var p1val = player1Obj.value.split(',');
    var p1no = p1val[0];
    var p1win = p1val[3];
    var obj = document.createElement('input');
    obj.value = p1no;
    obj.name = 'player1no_' + i;
    formObj.appendChild(obj);

    var score1Obj = document.getElementById('score1_' + i);
    var obj = document.createElement('input');
    obj.value = score1Obj.value;
    obj.name = score1Obj.id;
    formObj.appendChild(obj);

    var p1winflg = -1;
    if ( score1Obj.value == p1win )
    {
      p1winflg = 1;
    }else {
      p1winflg = 0;
    }
    var obj = document.createElement('input');
    obj.value = p1winflg;
    obj.name = 'p1win_' + i;
    formObj.appendChild(obj);

    var masu1Obj = document.getElementById('p1Masu_' + i);
    var obj = document.createElement('input');
    obj.value = masu1Obj.value;
    obj.name = masu1Obj.id;
    formObj.appendChild(obj);

    var player2Obj = document.getElementById('player2name_' + i);
    var p2val = player2Obj.value.split(',');
    var p2no = p2val[0];
    var p2win = p2val[3];
    var obj = document.createElement('input');
    obj.value = p2no;
    obj.name = 'player2no_' + i;
    formObj.appendChild(obj);

    var score2Obj = document.getElementById('score2_' + i);
    var obj = document.createElement('input');
    obj.value = score2Obj.value;
    obj.name = score2Obj.id;
    formObj.appendChild(obj);

    var p2winflg = -1;
    if ( score2Obj.value == p2win )
    {
      p2winflg = 1;
    }else {
      p2winflg = 0;
    }
    var obj = document.createElement('input');
    obj.value = p2winflg;
    obj.name = 'p2win_' + i;
    formObj.appendChild(obj);

    var masu2Obj = document.getElementById('p2Masu_' + i);
    var obj = document.createElement('input');
    obj.value = masu2Obj.value;
    obj.name = masu2Obj.id;
    formObj.appendChild(obj);
  }

  document.body.appendChild(formObj);

  formObj.submit();

  document.body.removeChild(formObj);
}

function checkEntry()
{
  var errMsg = "";

  for ( i = 1; i < scorelist.rows.length; i++ )
  {
    var player1Obj = document.getElementById('player1name_' + i);
    var p1val = player1Obj.value.split(',');
    var p1no = p1val[0];
    var p1win = p1val[3];

    var class1val = document.getElementById('class1_' + i).value;
    var score1val = document.getElementById('score1_' + i).value;
    var p1masu = document.getElementById('p1Masu_' + i).value;

    var player2Obj = document.getElementById('player2name_' + i);
    var p2val = player2Obj.value.split(',');
    var p2no = p2val[0];
    var p2win = p1val[3];

    var class2val = document.getElementById('class2_' + i).value;
    var score2val = document.getElementById('score2_' + i).value;
    var p2masu = document.getElementById('p2Masu_' + i).value;

    var check = false;

    if ( !(class1val != "" && score1val != "" && class2val != "" && score2val != "" ) )
    {
      if ( score1val == "" && score2val != "" )
      {
        errMsg += i + "行目:スコア1が未入力です\r\n";
        check = true;
      }else if ( score1val != "" && score2val == "" )
      {
        errMsg += i + "行目:スコア2が未入力です\r\n";
        check = true;
      }
    }else {
      if ( score1val > p1win )
      {
        errMsg += i + "行目:スコア1が勝利数よりも多いです\r\n";
        check = true;
      }else if ( score2val > p2win )
      {
        errMsg += i + "行目:スコア2が勝利数よりも多いです\r\n";
        check = true;
      }

      if ( score1val < p1masu )
      {
        errMsg += i + "行目:マスワリ1がスコア1よりも多いです\r\n";
        check = true;
      }else if ( score2val < p2masu )
      {
        errMsg += i + "行目:マスワリ2がスコア2よりも多いです\r\n";
        check = true;
      }

      if ( p1no == p2no )
      {
        errMsg += i + "行目:なまえ1となまえ2が同じです\r\n";
        check = true;
      }
    }

    if ( check == true )
    {
      for ( j = 0; j < scorelist.rows[i].cells.length; j++)
      {
        scorelist.rows[i].cells[j].style.backgroundColor = 'red';
      }
    }
  }
  if ( errMsg != "" )
  {
    window.alert(errMsg);
    return false;
  }else {
    return true;
  }

}

function setEntryDate( dateObj, rowno )
{
  for ( i = rowno; i < scorelist.rows.length; i++ )
  {
    var entryDate = document.getElementById('entryDate_' + i);

    entryDate.value = dateObj.value;
  }
}

function copyScore( rowno )
{
  if ( rowno == 20 )
  {
    return;
  }

  var nextrow = rowno + 1;

  document.getElementById('entryDate_' + nextrow).value = document.getElementById('entryDate_' + rowno).value;
  document.getElementById('player1name_' + nextrow).value = document.getElementById('player1name_' + rowno).value;
  document.getElementById('class1_' + nextrow).value = document.getElementById('class1_' + rowno).value;
  document.getElementById('score1_' + nextrow).value = document.getElementById('score1_' + rowno).value;
  document.getElementById('p1Masu_' + nextrow).value = document.getElementById('p1Masu_' + rowno).value;
  document.getElementById('player2name_' + nextrow).value = document.getElementById('player2name_' + rowno).value;
  document.getElementById('class2_' + nextrow).value = document.getElementById('class2_' + rowno).value;
  document.getElementById('score2_' + nextrow).value = document.getElementById('score2_' + rowno).value;
  document.getElementById('p2Masu_' + nextrow).value = document.getElementById('p2Masu_' + rowno).value;
}
