<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<cffunction name="outputJavascriptForRoot">

	<cfquery datasource="GEMSKampi" name="rsRoot">
		select distinct ID, Code, Name, COATypeID, ParentID 
		from COA
		where COATypeID = 0
		order by Code
	</cfquery>
	
	<cfscript>
		outputJavascriptForSubFolder(rsRoot.ID,rsRoot.Name,"fSub1");
	</cfscript>

	<cfset out10 = "foldersTree = fSub1"	>
	
	<cfoutput>#out10#</cfoutput>

</cffunction> 


<cffunction name="outputJavascriptForSubFolder">
	<cfargument name="folderId" required="yes">
	<cfargument name="nodeName" required="yes">
	<cfargument name="fName" required="yes">
	<cfdump var="#arguments#">

	<cfquery datasource="GEMSKampi" name="rsHits">
		select ID, Code, Name, Detailed, ParentID from COA where ParentID = #arguments.folderId# and Detailed = 0 order by Lt
	</cfquery>


	<cfset fi = 1>
	<cfoutput>
	<cfloop query="rsHits">
		<cfscript>
			outputJavascriptForSubFolder(rsHits.ID,rsHits.Name,arguments.fName & "Sub" & fi);
			fi = fi + 1;
		</cfscript>
	</cfloop>
	</cfoutput>
	
	<cfoutput>
		<cfset out1 = arguments.fName & " = " & "gFld('" & arguments.nodeName & "', 'javascript:parent.op();')" & Chr(13)>
		<cfset out2 = arguments.fName & " = " & ".xID = " & arguments.folderId & Chr(13)>
		<cfset out3 = arguments.fName & ".addChildren([">
		#out1#
		#out2#
		#out3#
		<cfset fi = 1>
		<cfloop query="rsHits">
			<cfif fi gt 1><cfset outTemp = ", ">#outTemp#</cfif>			
			<cfset out4 = arguments.fName & "Sub" & fi>
			#out4#
			<cfset fi = fi + 1>		
		</cfloop>
		<cfset subFolders = fi - 1>
	
	<cfquery datasource="GEMSKampi" name="rsHitsChild">
		select ID, Code, Name, Detailed, ParentID from COA where ParentID = #arguments.folderId# and Detailed = 1 order by Lt
	</cfquery>
	
	<cfset di = 1>
	<cfloop query="rsHitsChild">
		<cfif di gt 1 or subFolders gt 0><cfset haha = ", ">#haha#</cfif>
		<cfset out5 = "['" & rsHitsChild.Name & "', 'http://192.168.0.19/GEMSKampi']">
		#out5#
		<cfset di = di + 1>
	</cfloop>
	
	<cfset out6 = "])" & Chr(13)>
	#out6#	
	
	<cfset di = subFolders>
	<cfloop query="rsHitsChild">
		<cfset out7 = arguments.fName & ".children[" & di & "].xID = " & rsHitsChild.ID & Chr(13)>
		#out7#
		<cfset di = di + 1>
	</cfloop>
	
	</cfoutput>

</cffunction>


<script src="ua.js"></script>

<!-- Infrastructure code for the tree -->
<script src="ftiens4.js"></script>

<!-- Execution of the code that actually builds the specific tree.
     The variable foldersTree creates its structure with calls to
	 gFld, insFld, and insDoc -->
	 

<script language="javascript">
USETEXTLINKS = 1
STARTALLOPEN = 0
PRESERVESTATE = 1
ICONPATH = '' 
HIGHLIGHT = 1

<cfscript>
	outputJavascriptForRoot();
</cfscript>	 






// Load a page as if a node on the tree was clicked (synchronize frames)
// (Highlights selection if highlight is available.)
function loadSynchPage(xID) 
{
	var folderObj;
	docObj = parent.treeframe.findObj(xID);
	docObj.forceOpeningOfAncestorFolders();
	parent.treeframe.clickOnLink(xID,docObj.link,'basefrm'); 

    //Scroll the tree window to show the selected node
    //Other code in these functions needs to be changed to work with
    //frameless pages, but this code should, I think, simply be removed
    if (typeof parent.treeframe.document.body != "undefined") //scroll doesn work with NS4, for example
        parent.treeframe.document.body.scrollTop=docObj.navObj.offsetTop
} 
</script>
</head>

<body topmargin=16 marginheight=16>

<!-- By removing the follwoing code you are violating your user agreement.
     Corporate users or any others that want to remove the link should check 
	 the online FAQ for instructions on how to obtain a version without the link -->
<!-- Removing this link will make the script stop from working -->
<div style="position:absolute; top:0; left:0; "><table border=0><tr><td><font size=-2><a style="font-size:7pt;text-decoration:none;color:silver" href="http://www.treemenu.net/" target=_blank>JavaScript Tree Menu</a></font></td></tr></table></div>

<!-- Build the browser's objects and display default view of the 
     tree. -->
<script language="javascript">
initializeDocument()
//Click the Parakeet link
loadSynchPage(506027036)
</script>
<noscript>
A tree for site navigation will open here if you enable JavaScript in your browser.
</noscript>

</body>
</html>
