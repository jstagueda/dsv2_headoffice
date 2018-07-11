/*
  @package Ajax Connection.
  @author John Paul Pineda.
  @email paulpineda19@yahoo.com.
  @copyright 2012 John Paul Pineda.
  @version 1.0 September 4, 2011.

  @description Ajax Connection.
*/

//function GetXmlHttpObject()
function GetXmlHttpObject() {
	
  try {
  
    // code for Firefox, Chrome, Opera, Safari browsers.
    return new XMLHttpRequest();
  } catch(tryMicrosoft) {
  
    try {
    
      // code for Internet Explorer browsers.
      return new ActiveXObject("Msxml2.XMLHTTP");
    } catch(tryOtherMicrosoft) {
    
      try {
      
        // code for Internet Explorer browsers.
        return new ActiveXObject("Microsoft.XMLHTTP");	
      } catch(error) {
      
        return null;
      }
    }
  }
}