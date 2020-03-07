/*
function setClass1()
{
  var p1Name = document.entryForm.p1Name;
  var selInd = p1Name.selectedIndex;
  var nameVal = p1Name.options[selInd].value;
  if ( nameVal != "0" )
  {
    var classVal = nameVal.split(',');

    document.entryForm.p1Class.value = classVal[2];
    document.entryForm.p1ScoreWin.value = classVal[3];
  }else{
    document.entryForm.p1Class.value = "";
    document.entryForm.p1ScoreWin.value = "";
  }
}

function setClass2()
{
  var p2Name = document.entryForm.p2Name;
  var selInd = p2Name.selectedIndex;
  var nameVal = p2Name.options[selInd].value;
  if ( nameVal != "0" )
  {
    var classVal = nameVal.split(',');

    document.entryForm.p2Class.value = classVal[2];
    document.entryForm.p2ScoreWin.value = classVal[3];
  }else{
    document.entryForm.p2Class.value = "";
    document.entryForm.p2ScoreWin.value = "";
  }
}
*/

function setClass(nameObj, classObj, scoreObj, masuObj)
{
  var selInd = nameObj.selectedIndex;
  var nameVal = nameObj.options[selInd].value;
  if ( nameVal != "0" )
  {
    var classVal = nameVal.split(',');

    classObj.value = classVal[2];
    scoreObj.value = classVal[3];

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
    scoreObj.value = "";
  }
}

function scoreInput(player)
{
  if ( player == 'p1' )
  {
    var scoreVal = document.entryForm.p1Score.value;
    var scoreWin = document.entryForm.p1ScoreWin.value;

    if ( scoreVal == scoreWin )
    {
      document.getElementById("p1WinnerLabel").innerHTML = "Winner";
      document.getElementById("p1WinnerLabel").style.display = "block";
      document.getElementById("p1WinnerLabel").style.color = "red";
      document.getElementById("p1WinnerLabel").style.background = "linear-gradient(160deg, rgb(212, 181, 0), rgb(233, 255, 106))";
      document.entryForm.p1WinnerFlag.value = "1";

      document.getElementById("p2WinnerLabel").innerHTML = "Loser";
      document.getElementById("p2WinnerLabel").style.display = "block";
      document.getElementById("p2WinnerLabel").style.color = "blue";
      document.getElementById("p2WinnerLabel").style.background = "linear-gradient(160deg, rgb(0, 155, 0), rgb(170, 255, 170))";
      document.entryForm.p2WinnerFlag.value = "0";
    }
  }else {
    var scoreVal = document.entryForm.p2Score.value;
    var scoreWin = document.entryForm.p2ScoreWin.value;

    if ( scoreVal == scoreWin )
    {
      document.getElementById("p1WinnerLabel").innerHTML = "Loser";
      document.getElementById("p1WinnerLabel").style.display = "block";
      document.getElementById("p1WinnerLabel").style.color = "blue";
      document.getElementById("p1WinnerLabel").style.background = "linear-gradient(160deg, rgb(0, 155, 0), rgb(170, 255, 170))";
      document.entryForm.p1WinnerFlag.value = "0";

      document.getElementById("p2WinnerLabel").innerHTML = "Winner";
      document.getElementById("p2WinnerLabel").style.display = "block";
      document.getElementById("p2WinnerLabel").style.color = "red";
      document.getElementById("p2WinnerLabel").style.background = "linear-gradient(160deg, rgb(212, 181, 0), rgb(233, 255, 106))";
      document.entryForm.p2WinnerFlag.value = "1";
    }
  }
}

function refineMember(refineVal, selectObj, selectAllObj)
{
  var items = selectObj.options;
  var allitems = selectAllObj.options;
  var value = refineVal.value;

  const reg = new RegExp(".*" + value + ".*", "i");

  // 全てのアイテムを削除
  for ( i = items.length; i > 0; i-- ) {
    items[i-1] = null;
  }

  var itemindex = 0;
  for ( i = 0 ; i < allitems.length; i++) {
    if (allitems[i].textContent.match(reg)) {
      items[itemindex] = new Option(allitems[i].textContent, allitems[i].value);
      itemindex++;
    }
  }
}
