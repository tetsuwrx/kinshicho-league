function setClass( classObj, rowno )
{
  var scoreVal = "";

  switch(classObj.value)
  {
    case 'UC':
      scoreVal = 2;
      break;
    case 'C':
      scoreVal = 3;
      break;
    case 'B':
      scoreVal = 4;
      break;
    case 'A':
      scoreVal = 5;
      break;
    case 'SA':
      scoreVal = 6;
      break;
  }

  var scoreLabel = document.getElementById('score_' + rowno);

  scoreLabel.innerText = scoreVal;

}

function updateMember( rowno )
{
  var formObj = document.createElement('form');

  formObj.action = 'updatemember.php';
  formObj.method = 'post';

  var obj = document.createElement('input');
  obj.name = 'memberno';
  obj.value = document.getElementById('no_' + rowno).innerText;
  formObj.appendChild(obj);

  var obj = document.createElement('input');
  obj.name = 'membername';
  obj.value = document.getElementById('name_' + rowno).value;
  formObj.appendChild(obj);

  var obj = document.createElement('input');
  obj.name = 'class';
  obj.value = document.getElementById('class_' + rowno).value;
  formObj.appendChild(obj);

  var obj = document.createElement('input');
  obj.name = 'sex';
  obj.value = document.getElementById('sex_' + rowno).value;
  formObj.appendChild(obj);

  document.body.appendChild(formObj);

  formObj.submit();

  document.body.removeChild(formObj);
}
