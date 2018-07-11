// JavaScript Document

	function RemoveInvalidChars(strString)
	{
	   var iChars = "/[$\\@\\\#%\^\&\#\^\~\`\!\=\:\;\"\'\,\?\.\(<)\(>)\+\*\(\)\(/)\[\]\+\_\{\}\`\~\=\|]\/";
	   	   
	   var strtovalidate = strString.value;
	   var strlength = strtovalidate.length;
	   var strChar;
	   var ctr = 0;
	   var newStr = '';
	   if (strlength == 0)
	   {
	   	return false;
	   }

		for (i = 0; i < strlength; i++)
		{
			strChar = strtovalidate.charAt(i);
				if 	(iChars.indexOf(strChar) == -1)
				{
					newStr = newStr + strChar;
				}
		}
		strString.value = newStr;
	}
	
	function RemoveInvalidLetters(strString)
		{
		    var iChars = "1234567890\-";
			   
		   var strtovalidate = strString.value;
		   var strlength = strtovalidate.length;
		   var strChar;
		   var ctr = 0;
		   var newStr = '';
		   if (strlength == 0)
		   {
			return false;
		   }
	
			for (i = 0; i < strlength; i++)
			{
				strChar = strtovalidate.charAt(i);
					if 	(!(iChars.indexOf(strChar) == -1))
					{
						newStr = newStr + strChar;
					}
			}
			strString.value = newStr;
		}