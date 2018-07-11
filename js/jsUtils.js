// JavaScript Document

//debugger for js
function errWindow(errMsg, location, lineNum) {
	debugWin = window.open("","debugWin","height=200,width=300,resize=yes")
	debugWin.document.write("<H2>There was an error at line " + lineNum)
	debugWin.document.write("<BR>The error was: " + errMsg)
	debugWin.document.close()
	return true
}

function RedirectTo(page) {
	location.replace(page);
	return false;
}

function left(str, n)
{ 
	if (n <= 0) return ""; 
	else if (n > String(str).length) 
		return str; 
	else 
		return String(str).substring(0,n); 
}

//function to verify if value is whole number
//function isNumeric(passedVal) {
//	if (passedVal == "") {
//		return false
//	}
//	for (i=0; i<passedVal.length; i++) {
//		if (passedVal.charAt(i) < "0") {
//			return false
//		}
//		if (passedVal.charAt(i) > "9") {
//			return false
//		}
//	}
//	return true
//}

//function to verify if passed value is blank
function isBlank(passedVal,errMsg) {
	if (!errMsg) {
		errMsg = 'Field required.';
	}
	if (TrimAll(passedVal)=='') {
		alert(errMsg);
		return true;
	}
	return false;
}

//function to verify if passed value is a valid military time (length)
function isValidMilitaryTimeLength(passedVal) {
	if (passedVal.length > 4 ||  passedVal.length < 4) {
		return false;
	}
	return true
}

//function to verify if passed value is a valid military time (hours)
function isValidMilitaryTimeHrs(passedVal) {
	if (passedVal.substring(0, 2) > 23 || passedVal.substring(0, 2) < 0) {	
		return false;
	}
	return true
}

//function to verify if passed value is a valid military time (mins)
function isValidMilitaryTimeMins(passedVal) {
	if (passedVal.substring(2, 4) > 59 || passedVal.substring(2, 4) < 0) {	
		return false;
	}						
	return true
}
//function to verify if passed value is a valid military time (mins)
function isValidMilitaryTimeMins_DTR(passedVal) {
	if (passedVal.substring(0, 2) > 59 || passedVal.substring(0, 2) < 0) {	
		return false;
	}						
	return true
}

//function to verify deleting of record
function VerifyDelete(msg) {
	if (!msg) {
		msg = 'Are you sure you want to delete this record?';
	}	
	if (confirm(msg) == false) 
		return false;
	else	
		return true;
}

//function to verify removing of items
function VerifyRemove(msg) {
	if (!msg) {
		msg = 'Are you sure you want to remove the selected item(s)?';
	}	
	if (confirm(msg) == false) 
		return false;
	else	
		return true;
}

//function to verify confirming of record
function VerifyConfirm(msg) {
	if (!msg) {
		msg = 'Are you sure you want to confirm this record?';
	}	
	if (confirm(msg) == false) 
		return false;
	else	
		return true;
}

//function to verify saving of record
function VerifySave(msg) {
	if (!msg) {
		msg = 'Are you sure you want to save this record?';
	}	
	if (confirm(msg) == false) 
		return false;
	else	
		return true;
}

//START OF DATE VALIDATION SCRIPT
// Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

//function to check the date. This function would be the one used first hand for date validation
function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strMonth=dtStr.substring(0,pos1)
	var strDay=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		alert("The date format should be : mm/dd/yyyy")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date")
		return false
	}
return true
}

function Trim(s) 
{
  // Remove leading spaces and carriage returns
  
  while ((s.substring(0,1) == ' ') || (s.substring(0,1) == '\n') || (s.substring(0,1) == '\r'))
  {
    s = s.substring(1,s.length);
  }

  // Remove trailing spaces and carriage returns

  while ((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\n') || (s.substring(s.length-1,s.length) == '\r'))
  {
    s = s.substring(0,s.length-1);
  }
  return s;
}

//END OF DATE VALIDATION SCRIPT

function GetInList (list, index, delim)
{
	var flag = false, curr = 0;
	var posStart = "", posStop = "";

	// first, look for at least one occurance of the delimeter
	// if we can't find one, then just return the original
	if(list.indexOf(delim) == -1) return list;

	// alright, let's go through the string one character at a time
	for(x=0; x<list.length; x++)
	{
		/*/
		/ / We process if we find a delimeter, or we already found
		/ / a delimeter before and reached the end of the string.
		/*/
		if( (list.substr(x, 1) == delim) || (flag && (x == (list.length - 1))) )
		{
			// increment the current index if we need to
			if(index > 0) curr++;

			/*/ are we looking for the end or begining of the index? /*/
			if(flag)
			{	/*/ ending /*/

				/*/
				/ / Record the index for extraction later.  Remember, we want
				/ / the char before the delim, so we're done with this cycle.
				/ / But, we don't do this for the last index because there is
				/ / not delimeter for us to track, so we add one.
				/*/
				if(x == (list.length - 1))
					posStop = x + 1;
				else
					posStop = x;
				break;
			}
			else
			{	/*/ beginning /*/

				// did we find a match?
				if(curr == index)
				{
					/*/
					/ / We are on the index the caller wants.
					/ / So we record this for extraction later.
					/*/

					/*/ flag indicates we found the start /*/
					flag = true;

					/*/
					/ / Now, here's the tricky part.  If we're not on the first
					/ / index (0) we want posStart to be one greater than the
					/ / current iteration to pass up the delimeter; however,
					/ / if we are on the first index, we want posStart to be the
					/ / beginning and posStop to be what posStart was supposed to be.
					/*/
					if(curr == 0)
					{	/*/ zero /*/
						posStart = 0;
						posStop  = x;

						// we have the data we need
						break;
					}
					else
						/*/ non-zero /*/
						posStart = x + 1;
				}
			}
		}
	}

	/*/ if we made it here w/o flag being set, then we didn't find the index /*/
	if(!flag)
		return false;
	else
	{	/*/ we have what we need to extract the index /*/

		// return the data back to the caller
		return list.substring(posStart, posStop);
	}
}

/*/
/ / PURPOSE:
/ /		To replace/add an entry to a list.
/ /
/ / COMMENTS:
/ /		Use index to specify which item in the list.  If insert is
/ /		true then item is inserted, otherwise it item will replace
/ /		the current index.  Also, delim must match whatever the
/ /		delimeter is.  Returns false if the index is not found.
/*/

function SetInList (list, item, index, delim, insert)
{
	var flag = false, curr = 0;
	var posStart = "", posStop = "";

	// if list is empty then we just start a new list
	if((list == null) || (list == "")) return item;

	// first, look for at least one occurance of the delimeter
	// if we can't find one, then there's most likey only a
	// single item in the list, try to append or prepend
	if(list.indexOf(delim) == -1)
	{
		if((index == 0) && insert)  return item + delim + list;
		if((index == 0) && !insert) return item;
	}

	// alright, let's go through the string one character at a time
	for(x=0; x<list.length; x++)
	{
		/*/
		/ / We process if we find a delimeter, or we already found
		/ / a delimeter before and reached the end of the string.
		/*/
		if( (list.substr(x, 1) == delim) || (flag && (x == (list.length - 1))) )
		{
			// increment the current index if we need to
			if(index > 0) curr++;

			/*/ are we looking for the end or begining of the index? /*/
			if(flag)
			{	/*/ ending /*/

				/*/
				/ / Record the index for extraction later.  Remember, we want
				/ / the char before the delim, so we're done with this cycle.
				/ / But, we don't do this for the last index because there is
				/ / not delimeter for us to track, so we add one.
				/*/
				if(x == (list.length - 1))
					posStop = x + 1;
				else
					posStop = x;
				break;
			}
			else
			{	/*/ beginning /*/

				// did we find a match?
				if(curr == index)
				{
					/*/
					/ / We are on the index the caller wants.
					/ / So we record this for extraction later.
					/*/

					/*/ flag indicates we found the start /*/
					flag = true;

					/*/
					/ / Now, here's the tricky part.  If we're not on the first
					/ / index (0) we want posStart to be one greater than the
					/ / current iteration to pass up the delimeter; however,
					/ / if we are on the first index, we want posStart to be the
					/ / beginning and posStop to be what posStart was supposed to be.
					/*/
					if(curr == 0)
					{	/*/ zero /*/
						posStart = 0;
						posStop  = x;

						// we have the data we need
						break;
					}
					else
						/*/ non-zero /*/
						posStart = x + 1;
				}
			}
		}
	}

	/*/ if we made it here w/o flag being set, then we didn't find the index /*/
	if(!flag)
		return false;
	else
	{	/*/ we have what we need to include the index /*/

		/*/ return the data back to the caller /*/

		// do we replace or insert?
		if(insert)
			/*/ insert /*/
			return list.substring(0, posStart) + item + delim +
				list.substring(posStart, list.length);
		else
			/*/ replace /*/
			return list.substring(0, posStart) + item +
				list.substring(posStart + (posStop - posStart), list.length);
	}
}

function compareDates (value1, value2) {
   var date1, date2;
   var month1, month2;
   var year1, year2;

   month1 = value1.substring (0, value1.indexOf ("/"));
   date1 = value1.substring (value1.indexOf ("/")+1, value1.lastIndexOf ("/"));
   year1 = value1.substring (value1.lastIndexOf ("/")+1, value1.length);

   month2 = value2.substring (0, value2.indexOf ("/"));
   date2 = value2.substring (value2.indexOf ("/")+1, value2.lastIndexOf ("/"));
   year2 = value2.substring (value2.lastIndexOf ("/")+1, value2.length);

   if (year1 > year2) return 1;
   else if (year1 < year2) return -1;
   else if (month1 > month2) return 1;
   else if (month1 < month2) return -1;
   else if (date1 > date2) return 1;
   else if (date1 < date2) return -1;
   else return 0;
}

//-------------------------------------------------------------------
// Trim functions
//   Returns string with whitespace trimmed
//-------------------------------------------------------------------
function LTrim(str){
if (str==null){return null;}
for(var i=0;str.charAt(i)==" ";i++);
return str.substring(i,str.length);
}
function RTrim(str){
if (str==null){return null;}
for(var i=str.length-1;str.charAt(i)==" ";i--);
return str.substring(0,i+1);
}
function Trim(str){return LTrim(RTrim(str));}
function LTrimAll(str) {
if (str==null){return str;}
for (var i=0; str.charAt(i)==" " || str.charAt(i)=="\n" || str.charAt(i)=="\t"; i++);
return str.substring(i,str.length);
}
function RTrimAll(str) {
if (str==null){return str;}
for (var i=str.length-1; str.charAt(i)==" " || str.charAt(i)=="\n" || str.charAt(i)=="\t"; i--);
return str.substring(0,i+1);
}
function TrimAll(str) {
return LTrimAll(RTrimAll(str));
}
//-------------------------------------------------------------------
// isNull(value)
//   Returns true if value is null
//-------------------------------------------------------------------
function isNull(val){return(val==null);}

//-------------------------------------------------------------------
// isBlank(value)
//   Returns true if value only contains spaces
//-------------------------------------------------------------------
//function isBlank(val,errMsg){
//	if (!errMsg) {
//	errMsg = 'Field required.';
//	if (val==null)
//		{return true;}
//	for(var i=0;i<val.length;i++) {
//		if ((val.charAt(i)!=' ')&&(val.charAt(i)!="\t")&&(val.charAt(i)!="\n")&&(val.charAt(i)!="\r")) 	alert(errMsg);{return false;}
//	}
//	return true;
//}

//-------------------------------------------------------------------
// isInteger(value)
//   Returns true if value contains all digits
//-------------------------------------------------------------------
function isInteger(val){
if (isBlank(val)){return false;}
for(var i=0;i<val.length;i++){
if(!isDigit(val.charAt(i))){return false;}
}
return true;
}

//-------------------------------------------------------------------
// isNumeric(value)
//   Returns true if value contains a positive float value
//-------------------------------------------------------------------
function isNumeric(val){return(parseFloat(val,10)==(val*1));}

//-------------------------------------------------------------------
// isPercent(value)
//   Returns true if value contains a positive percent value
//-------------------------------------------------------------------
function isPercent(val){
	if (parseFloat(val,10)==(val*1)) {
		return true;
	} else {	
		if (val.indexOf('%') >= 0) {			
			ctr = val.indexOf('%');			
			tempstr = val.substring(ctr + 1, val.length);			
			len = tempstr.length;
			if (!len) //isNumeric(val.replace('%', ''))
				return true;
			else 
				return false;
		} else return false;
	}
}
//-------------------------------------------------------------------
// isArray(obj)
// Returns true if the object is an array, else false
//-------------------------------------------------------------------
function isArray(obj){return(typeof(obj.length)=="undefined")?false:true;}

//-------------------------------------------------------------------
// isDigit(value)
//   Returns true if value is a 1-character digit
//-------------------------------------------------------------------
function isDigit(num) {
if (num.length>1){return false;}
var string="1234567890";
if (string.indexOf(num)!=-1){return true;}
return false;
}

//-------------------------------------------------------------------
// isWholeNumber(value)
//   Returns true if value is a whole number
//-------------------------------------------------------------------

function isWholeNumber(number)
{
	var isDecimal = false;
	for(var i=0;i<number.length;i++)
	{
		if(number.charAt(i) == '.')
		{				
			isDecimal = true;
			break;
		}
	}
	
	if(!isNaN(number) && !isDecimal)
		return true;
	else
		return false;
}


//-------------------------------------------------------------------
// setNullIfBlank(input_object)
//   Sets a form field to "" if it isBlank()
//-------------------------------------------------------------------
function setNullIfBlank(obj){if(isBlank(obj.value)){obj.value="";}}

//-------------------------------------------------------------------
// setFieldsToUpperCase(input_object)
//   Sets value of form field toUpperCase() for all fields passed
//-------------------------------------------------------------------
function setFieldsToUpperCase(){
for(var i=0;i<arguments.length;i++) {
arguments[i].value = arguments[i].value.toUpperCase();
}
}

//-------------------------------------------------------------------
// disallowBlank(input_object[,message[,true]])
//   Checks a form field for a blank value. Optionally alerts if 
//   blank and focuses
//-------------------------------------------------------------------
function disallowBlank(obj){
var msg=(arguments.length>1)?arguments[1]:"";
var dofocus=(arguments.length>2)?arguments[2]:false;
if (isBlank(getInputValue(obj))){
if(!isBlank(msg)){alert(msg);}
if(dofocus){
if (isArray(obj) && (typeof(obj.type)=="undefined")) {obj=obj[0];}
if(obj.type=="text"||obj.type=="textarea"||obj.type=="password") { obj.select(); }
obj.focus();
}
return true;
}
return false;
}

//-------------------------------------------------------------------
// disallowModify(input_object[,message[,true]])
//   Checks a form field for a value different than defaultValue. 
//   Optionally alerts and focuses
//-------------------------------------------------------------------
function disallowModify(obj){
var msg=(arguments.length>1)?arguments[1]:"";
var dofocus=(arguments.length>2)?arguments[2]:false;
if (getInputValue(obj)!=getInputDefaultValue(obj)){
if(!isBlank(msg)){alert(msg);}
if(dofocus){
if (isArray(obj) && (typeof(obj.type)=="undefined")) {obj=obj[0];}
if(obj.type=="text"||obj.type=="textarea"||obj.type=="password") { obj.select(); }
obj.focus();
}
setInputValue(obj,getInputDefaultValue(obj));
return true;
}
return false;
}

//-------------------------------------------------------------------
// commifyArray(array[,delimiter])
//   Take an array of values and turn it into a comma-separated string
//   Pass an optional second argument to specify a delimiter other than
//   comma.
//-------------------------------------------------------------------
function commifyArray(obj,delimiter){
if (typeof(delimiter)=="undefined" || delimiter==null) {
delimiter = ",";
}
var s="";
if(obj==null||obj.length<=0){return s;}
for(var i=0;i<obj.length;i++){
s=s+((s=="")?"":delimiter)+obj[i].toString();
}
return s;
}

//-------------------------------------------------------------------
// getSingleInputValue(input_object,use_default,delimiter)
//   Utility function used by others
//-------------------------------------------------------------------
function getSingleInputValue(obj,use_default,delimiter) {
switch(obj.type){
case 'radio': case 'checkbox': return(((use_default)?obj.defaultChecked:obj.checked)?obj.value:null);
case 'text': case 'hidden': case 'textarea': return(use_default)?obj.defaultValue:obj.value;
case 'password': return((use_default)?null:obj.value);
case 'select-one':
if (obj.options==null) { return null; }
if(use_default){
var o=obj.options;
for(var i=0;i<o.length;i++){if(o[i].defaultSelected){return o[i].value;}}
return o[0].value;
}
if (obj.selectedIndex<0){return null;}
return(obj.options.length>0)?obj.options[obj.selectedIndex].value:null;
case 'select-multiple': 
if (obj.options==null) { return null; }
var values=new Array();
for(var i=0;i<obj.options.length;i++) {
if((use_default&&obj.options[i].defaultSelected)||(!use_default&&obj.options[i].selected)) {
values[values.length]=obj.options[i].value;
}
}
return (values.length==0)?null:commifyArray(values,delimiter);
}
alert("FATAL ERROR: Field type "+obj.type+" is not supported for this function");
return null;
}

//-------------------------------------------------------------------
// getSingleInputText(input_object,use_default,delimiter)
//   Utility function used by others
//-------------------------------------------------------------------
function getSingleInputText(obj,use_default,delimiter) {
switch(obj.type){
case 'radio': case 'checkbox': return "";
case 'text': case 'hidden': case 'textarea': return(use_default)?obj.defaultValue:obj.value;
case 'password': return((use_default)?null:obj.value);
case 'select-one':
if (obj.options==null) { return null; }
if(use_default){
var o=obj.options;
for(var i=0;i<o.length;i++){if(o[i].defaultSelected){return o[i].text;}}
return o[0].text;
}
if (obj.selectedIndex<0){return null;}
return(obj.options.length>0)?obj.options[obj.selectedIndex].text:null;
case 'select-multiple': 
if (obj.options==null) { return null; }
var values=new Array();
for(var i=0;i<obj.options.length;i++) {
if((use_default&&obj.options[i].defaultSelected)||(!use_default&&obj.options[i].selected)) {
values[values.length]=obj.options[i].text;
}
}
return (values.length==0)?null:commifyArray(values,delimiter);
}
alert("FATAL ERROR: Field type "+obj.type+" is not supported for this function");
return null;
}

//-------------------------------------------------------------------
// setSingleInputValue(input_object,value)
//   Utility function used by others
//-------------------------------------------------------------------
function setSingleInputValue(obj,value) {
switch(obj.type){
case 'radio': case 'checkbox': if(obj.value==value){obj.checked=true;return true;}else{obj.checked=false;return false;}
case 'text': case 'hidden': case 'textarea': case 'password': obj.value=value;return true;
case 'select-one': case 'select-multiple': 
var o=obj.options;
for(var i=0;i<o.length;i++){
if(o[i].value==value){o[i].selected=true;}
else{o[i].selected=false;}
}
return true;
}
alert("FATAL ERROR: Field type "+obj.type+" is not supported for this function");
return false;
}

//-------------------------------------------------------------------
// getInputValue(input_object[,delimiter])
//   Get the value of any form input field
//   Multiple-select fields are returned as comma-separated values, or
//   delmited by the optional second argument
//   (Doesn't support input types: button,file,reset,submit)
//-------------------------------------------------------------------
function getInputValue(obj,delimiter) {
var use_default=(arguments.length>2)?arguments[2]:false;
if (isArray(obj) && (typeof(obj.type)=="undefined")) {
var values=new Array();
for(var i=0;i<obj.length;i++){
var v=getSingleInputValue(obj[i],use_default,delimiter);
if(v!=null){values[values.length]=v;}
}
return commifyArray(values,delimiter);
}
return getSingleInputValue(obj,use_default,delimiter);
}

//-------------------------------------------------------------------
// getInputText(input_object[,delimiter])
//   Get the displayed text of any form input field
//   Multiple-select fields are returned as comma-separated values, or
//   delmited by the optional second argument
//   (Doesn't support input types: button,file,reset,submit)
//-------------------------------------------------------------------
function getInputText(obj,delimiter) {
var use_default=(arguments.length>2)?arguments[2]:false;
if (isArray(obj) && (typeof(obj.type)=="undefined")) {
var values=new Array();
for(var i=0;i<obj.length;i++){
var v=getSingleInputText(obj[i],use_default,delimiter);
if(v!=null){values[values.length]=v;}
}
return commifyArray(values,delimiter);
}
return getSingleInputText(obj,use_default,delimiter);
}

//-------------------------------------------------------------------
// getInputDefaultValue(input_object[,delimiter])
//   Get the default value of any form input field when it was created
//   Multiple-select fields are returned as comma-separated values, or
//   delmited by the optional second argument
//   (Doesn't support input types: button,file,password,reset,submit)
//-------------------------------------------------------------------
function getInputDefaultValue(obj,delimiter){return getInputValue(obj,delimiter,true);}

//-------------------------------------------------------------------
// isChanged(input_object)
//   Returns true if input object's value has changed since it was
//   created.
//-------------------------------------------------------------------
function isChanged(obj){return(getInputValue(obj)!=getInputDefaultValue(obj));}

//-------------------------------------------------------------------
// setInputValue(obj,value)
//   Set the value of any form field. In cases where no matching value
//   is available (select, radio, etc) then no option will be selected
//   (Doesn't support input types: button,file,password,reset,submit)
//-------------------------------------------------------------------
function setInputValue(obj,value) {
var use_default=(arguments.length>1)?arguments[1]:false;
if(isArray(obj)&&(typeof(obj.type)=="undefined")){
for(var i=0;i<obj.length;i++){setSingleInputValue(obj[i],value);}
}
else{setSingleInputValue(obj,value);}
}

//-------------------------------------------------------------------
// isFormModified(form_object,hidden_fields,ignore_fields)
//   Check to see if anything in a form has been changed. By default
//   it will check all visible form elements and ignore all hidden 
//   fields. 
//   You can pass a comma-separated list of field names to check in
//   addition to visible fields (for hiddens, etc).
//   You can also pass a comma-separated list of field names to be
//   ignored in the check.
//-------------------------------------------------------------------
function isFormModified(theform,hidden_fields,ignore_fields){
if(hidden_fields==null){hidden_fields="";}
if(ignore_fields==null){ignore_fields="";}
var hiddenFields=new Object();
var ignoreFields=new Object();
var i,field;
var hidden_fields_array=hidden_fields.split(',');
for (i=0;i<hidden_fields_array.length;i++) {
hiddenFields[Trim(hidden_fields_array[i])]=true;
}
var ignore_fields_array=ignore_fields.split(',');
for (i=0;i<ignore_fields_array.length;i++) {
ignoreFields[Trim(ignore_fields_array[i])]=true;
}
for (i=0;i<theform.elements.length;i++) {
var changed=false;
var name=theform.elements[i].name;
if(!isBlank(name)){
var type=theform[name].type;
if(!ignoreFields[name]){
if(type=="hidden"&&hiddenFields[name]){changed=isChanged(theform[name]);}
else if(type=="hidden"){changed=false;}
else {changed=isChanged(theform[name]);}
}
}
if(changed){return true;}
}
return false;
}


//Email validator
function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true					
	}
	
//Trim All Leading Zeros
function TrimZero(str)
{
	if (!str)
	{
		return false;
	}
	var len = str.length;
	ctr = 0;
	if (eval(len) > 1) { 	
		for (var i = 0; i < len; i++) {
			t = str.charAt(i);
			if (t != '0') {
				break;
			}		
			ctr++;
		}
	}
	str = str.substring (ctr, len);	
	return str;
}

//Limits a textbox or textarea (obj) to a certain number characters (limit)
function TextLimit(obj,limit) {
var strlen = obj.value.length;
	
	if ((obj.type.toLowerCase() == 'text') || (obj.type.toLowerCase() == 'textarea')) {
		if (strlen > limit) {
			alert('Character limit of field reached.')
			obj.value = obj.value.substring(0,limit);
			obj.focus();
			obj.select();
			return false;
		}
	}
}
//Adds comma separtor to numbers 
function CommaFormatted(amount)
{	
	var delimiter = ","; // replace comma if desired
	var a = amount.split('.',2)
	var d = a[1];

	var i = parseInt(a[0]);
	if(isNaN(i)) { return ''; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	var n = new String(i);
		
	var a = [];
	while(n.length > 3)
	{
		var nn = n.substr(n.length-3);
		
		a.unshift(nn);
		n = n.substr(0,n.length-3);
		
	}
	if(n.length > 0) { a.unshift(n); }
	n = a.join(delimiter);
	if(d.length < 1) { amount = n; }
	else { amount = n + '.' + d; }	
	amount = minus + amount;
	return amount;
}

function replaceAll( str, from, to ) {
    var idx = str.indexOf( from );


    while ( idx > -1 ) {
        str = str.replace( from, to );
        idx = str.indexOf( from );
    }

    return str;
}



