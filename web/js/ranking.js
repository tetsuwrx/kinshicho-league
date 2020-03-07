 function displayChange( id )
 {
   var obj = document.getElementById( id );

   if ( obj.style.display == "" )
   {
     obj.style.display = "none";
   }else if ( obj.style.display == "none" ) {
     obj.style.display = "";
   }
 }
