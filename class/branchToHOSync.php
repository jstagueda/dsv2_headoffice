<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 27, 2013
 * @description: Sync file processor from Branch to HO database table sync.
 */
	// Turn off all error reporting
	ini_set('display_errors', '1');
    include('../initialize.php');
    include('../class/DataSyncTables.php');
    include('../class/DataSync.php');
    include('SyncRequestFunctions.php');
    ini_set('memory_limit', '-1');
	
	//$my_file = $_POST['y'];
	//$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
	//exec("chmod -R 777 ".$my_file);
    //
	//fwrite($handle, base64_decode($_POST['x']));	
	//die();
	
	$action 			= $_POST['action'];
	$sync_option 		= $_POST['sync_opt'];
	$BranchID 			= $_POST['branchID'];
	$BranchCode 		= $_POST['branchCode'];
	$BranchURL 			= $_POST['branchURL'];
	$SyncFiles		 	= str_replace(" ","+",$_POST['SyncFiles']);
	$ZipSyncFileName 	= $_POST['ZipSyncFileName'];
	$NOW 				= date('Ymd'); //Date for today...
	$stop				= false;
	
	//Sample parameters..
	//$action 			= 'START_SYNC_FROM_BRANCH';
	//$sync_option 		= 'IC';
	//$BranchID 			= 46;
	//$BranchCode 		= 'TND';
	////$BranchURL 		= $_POST['branchURL'];
	//$SyncFiles		 	= str_replace(" ","+",'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il5DAAAAAAIAAAAAAAAAIwAAAGRhdGEvc2FsZXNvcmRlcmRldGFpbHMtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACUAAABkYXRhL3NhbGVzaW52b2ljZWRldGFpbHMtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACEAAABkYXRhL2N1bXVsYXRpdmVzYWxlcy0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAGwAAAGRhdGEvYmlyc2VyaWVzLTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAhAAAAZGF0YS9kZWxpdmVyeXJlY2VpcHQtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACgAAABkYXRhL2RlbGl2ZXJ5cmVjZWlwdGRldGFpbHMtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAABYAAABkYXRhL2RtY20tMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAAB0AAABkYXRhL2RtY21kZXRhaWxzLTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAcAAAAZGF0YS90cGlfY3JlZGl0LTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAoAAAAZGF0YS90cGlfY3JlZGl0bGltaXRkZXRhaWxzLTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAkAAAAZGF0YS90cGlfZGVhbGVyd3JpdGVvZmYtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACUAAABkYXRhL3RwaV9kZWFsZXJwcm9tb3Rpb24tMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACQAAABkYXRhL3RwaV9kZWFsZXJ0cmFuc2Zlci0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DflCQ79IKAADzZgAALAAAAGRhdGEvdHBpX2JyYW5jaGNvbGxlY3Rpb25yYXRpbmct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WHSlhPrfS1CNeXbWcWQTz4tF5AnzTlZO4taWdZ3KVS6cxj14CPu8ykW2Ee4FwVnZX3967C1jTfvrOrqjZw+n2fKhdZesPTTTr4E7XJjC/cf/AVBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAIQAAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0LTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAlAAAAZGF0YS9vZmZpY2lhbHJlY2VpcHRjYXNoLTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAmAAAAZGF0YS9vZmZpY2lhbHJlY2VpcHRjaGVjay0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAKwAAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0Y29tbWlzc2lvbi0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAKAAAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0ZGVwb3NpdC0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAKAAAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0ZGV0YWlscy0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAPwAAAGRhdGEvYXBwbGllZF9zaW5nbGVfbGluZV9wcm9tb19lbnRpdGxlbWVudF9kZXRhaWxzLTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAA+AAAAZGF0YS9hcHBsaWVkX211bHRpX2xpbmVfcHJvbW9fZW50aXRsZW1lbnRfZGV0YWlscy0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAOwAAAGRhdGEvYXBwbGllZF9vdmVybGF5X3Byb21vX2VudGl0bGVtZW50X2RldGFpbHMtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAADcAAABkYXRhL2FwcGxpZWRfaW5jZW50aXZlX2VudGl0bGVtZW50X2RldGFpbHMtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACsAAABkYXRhL2F2YWlsZWRfYXBwbGljYWJsZV9wcm9tb3MtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAAC8AAABkYXRhL2F2YWlsZWRfYXBwbGljYWJsZV9pbmNlbnRpdmVzLTIwMTMxMDMwLnR4dAMAUEsDBBQAAAAIADKKXkMAAAAAAgAAAAAAAAAdAAAAZGF0YS9zZm1fbWFuYWdlci0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAJgAAAGRhdGEvc2ZtX21hbmFnZXJfbmV0d29ya3MtMjAxMzEwMzAudHh0AwBQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAACEAAABkYXRhL3Byb2R1Y3RleGNoYW5nZS0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAKAAAAGRhdGEvcHJvZHVjdGV4Y2hhbmdlZGV0YWlscy0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5Dx5S5CaMCAAAXFgAAIAAAAGRhdGEvdHBpX3NmYXN1bW1hcnktMjAxMzEwMzAudHh07dhbb9owFADgv1LxXCwf381bCVOHVAZqeZmmPWTglkiQTCEgVdP++xxKi5M4odK4SAiJh+YcH9tNPvkQfvxpfR3em9ik4bzfa3VaBNpMtG5b2wv7VzcN48lsc43tZRAufofRSxwkU2NDw2AM1AkPkjib2Ti4Y7+bMM1j+cB+d7CZiwrC7OWDWZu3lfO1gtUySxYmfQsQpW1snGThvHf/9BTOzTIPAwbE8XumH6+TaGLuFskqzmxaK4qYes8O43G0MLZ6FL4uzGYARhgX09sp/EMKtf61d/ni4sHT6GNbwIRwZg1G/Y9UdUPd4LEUr0a+rRa/TDp8fjSTdBVly+3jKeTuJlm0Nt7UaLi95V/iNJnP8/33wix/ogQDbQNuY36DcWfzyZ9Mamx62n3dlj2Ey2yQTKPnyExLhRTfgOzYD+V54SyMX8x0U/b3tsKNuNzI8bjJZmuUKOaxlj9PyeqoKakQZUei1gZQCPNaavZOc8Sw1xoofLVWtUZda/RU1kTZmrxCu3BozIXGTthDK9a2I6499JK5cZcbPyI3uZm9qY1K4vvKZv8rJHmdtmLWo803oMKmOKihfk+lC44I/d/eAF+gOOGKEycUxzziuEccBS2lc98r5qQWDAE0qSNKaSTYHnegOZOIg1+e4JhUt+Euklfv9uHqY0xoWuOPYC4/TZDYWyHUQQmy8xuUrkF53lOPCl+TxQCiwaDSWCBGGwiWZ6ghWJqocYp9xcV+i5WsAci1PuMRSJv4ATsFP+XyU2fm53ulAAGKIVLbdoEyqpBu9Ee4BLT7FabuCMSMakRrAPq34Qwo7aPQgEme8wtU/PMCD34A6vMD1C5AfWaAygPQdjbecPxxBtUWdrC3DNx05hVeWh1uUml9gPPupF/4QBwe289/UEsDBBQAAAAIADKKXkMAJmeh5AIAAGEuAAAkAAAAZGF0YS90cGlfc2Zhc3VtbWFyeV9wbWctMjAxMzEwMzAudHh07ZlNb9pAEIb/SsQ5oP2eXW7FtBQpFKRwqaoerOAGJLAjQiKhqv+9awJkSnAaGzCDAPnAzu6ODY/e2d3XP35XvnZbURxNw3G7WalXDK8qU7muLBv+W2MaxnfDRZv5ZhBOHsLRfRwkg8iHukGfSxTuJPFs6OMcj/0ehdM0lg5sNzqLXNII5Zs30XP0cuf0XsHT4yyZRNOXgLDOx3qdVn/+kN4r+NL37X4y888aPyeju+jTJHmKZ+mT1Rhb9TVbt+s4B9wT3Pa2zgh67axMvXA+if5N9bnbaYaz9IGY/1QXVxqOp8l4nA5e9grGZZX7Xn3FWH1xpT9xGvnuQWO+/Mk34eOskwxGv0bRYGOiZFcc6v6SOp04DOP7aLCY9uf6DTeBuQlS3L4VASes3Ru5dS6a6CRGJ2lJrtfOS25v2EjLTWFm6nDM4H1gUli1a40Eq/eFrOpUTdCFpjE0TQdakQLJzkJmBhMzhGSWvy5Kp88CGWBkUBYys4kMLmXxo8QsJmapELsoLIuXw7wcEV6XNSwDFzCEC1iJ2/s3xJYjLifqD3HDTghwWtwux7LtzLALAoIUs4sL8j467ILAIV0QEPo/u30QOxuP57G0YRcEFClmReTm/4Ma6G3YhHF5wa2T0USHvRDQtOSWf3U7D7lhNwRMiczUFmZ61xIpmeFZBzbFmcyvOM0VaX7YGgGgxS+/5gTTkIFvs+vklYctErCkyBXaV/rdaQY6brnJvbVcp6NJDxsm4I671kmza910XIma3g5PcJd7yXvNl5ceVyXQs9g/sYwUvSLacyAz6TmbG94qG0122EOxnJby8q942rmMqrnRc8D1rhxu2Eex4sjcdn6Vwz0eyBCd4FrlZocS0uSHzRQrSfErtF+xJutgXgDeKhlNdNhTsYqW9ArYzjqrZG70nHzJxIaKPbKhIu2uJVMryDqbg2Rl2SrclEEO2yrWkCJ3im9Vy4GGvRQLtOR2ojvLA3D7+RdQSwMEFAAAAAgAMopeQwAAAAACAAAAAAAAAB4AAABkYXRhL2FmZmxpYXRlZGlibS0yMDEzMTAzMC50eHQDAFBLAwQUAAAACAAyil5DAAAAAAIAAAAAAAAAIQAAAGRhdGEvYmFua2RlcG9zaXRzbGlwLTIwMTMxMDMwLnR4dAMAUEsBAgAAFAAAAAgAMYpeQwAAAAACAAAAAAAAABoAAAAAAAAAAAAAAAAAAAAAAGRhdGEvY3VzdG9tZXItMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMYpeQwAAAAACAAAAAAAAACEAAAAAAAAAAAAAAAAAOgAAAGRhdGEvY3VzdG9tZXJkZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAsAAAAAAAAAAAAAAAAAHsAAABkYXRhL2N1c3RvbWVyYWNjb3VudHNyZWNlaXZhYmxlLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAhAAAAAAAAAAAAAAAAAMcAAABkYXRhL2N1c3RvbWVycGVuYWx0eS0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAxil5DAAAAAAIAAAAAAAAAJQAAAAAAAAAAAAAAAAAIAQAAZGF0YS90cGlfY3VzdG9tZXJkZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAiAAAAAAAAAAAAAAAAAE0BAABkYXRhL3RwaV9yY3VzdG9tZXJpYm0tMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMYpeQwAAAAACAAAAAAAAACUAAAAAAAAAAAAAAAAAjwEAAGRhdGEvdHBpX3JjdXN0b21lcnN0YXR1cy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAxil5DAAAAAAIAAAAAAAAAJQAAAAAAAAAAAAAAAADUAQAAZGF0YS90cGlfcmN1c3RvbWVyYnJhbmNoLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAiAAAAAAAAAAAAAAAAABkCAABkYXRhL3RwaV9yY3VzdG9tZXJwZGEtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMYpeQwAAAAACAAAAAAAAACMAAAAAAAAAAAAAAAAAWwIAAGRhdGEvdHBpX2N1c3RvbWVyc3RhdHMtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMYpeQwAAAAACAAAAAAAAACQAAAAAAAAAAAAAAAAAngIAAGRhdGEvY3VzdG9tZXJjb21taXNzaW9uLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkPkOAsu8AAAAC4DAAAsAAAAAAAAAAAAAAAAAOICAABkYXRhL2N1c3RvbWVyY29tbWlzc2lvbl9zdW1tYXJ5LTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAbAAAAAAAAAAAAAAAAABwEAABkYXRhL2ludmVudG9yeS0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAxil5DAAAAAAIAAAAAAAAAJQAAAAAAAAAAAAAAAABXBAAAZGF0YS9pbnZlbnRvcnlhZGp1c3RtZW50LTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAgAAAAAAAAAAAAAAAAAJwEAABkYXRhL2ludmVudG9yeWNvdW50LTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAgAAAAAAAAAAAAAAAAANwEAABkYXRhL2ludmVudG9yeWlub3V0LTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAjAAAAAAAAAAAAAAAAABwFAABkYXRhL2ludmVudG9yeXRyYW5zZmVyLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAsAAAAAAAAAAAAAAAAAF8FAABkYXRhL2ludmVudG9yeWFkanVzdG1lbnRkZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAnAAAAAAAAAAAAAAAAAKsFAABkYXRhL2ludmVudG9yeWNvdW50ZGV0YWlscy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAxil5DAAAAAAIAAAAAAAAAJwAAAAAAAAAAAAAAAADyBQAAZGF0YS9pbnZlbnRvcnlpbm91dGRldGFpbHMtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMYpeQwAAAAACAAAAAAAAACoAAAAAAAAAAAAAAAAAOQYAAGRhdGEvaW52ZW50b3J5dHJhbnNmZXJkZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADGKXkMAAAAAAgAAAAAAAAAcAAAAAAAAAAAAAAAAAIMGAABkYXRhL3NhbGVzb3JkZXItMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAAB4AAAAAAAAAAAAAAAAAvwYAAGRhdGEvc2FsZXNpbnZvaWNlLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAjAAAAAAAAAAAAAAAAAP0GAABkYXRhL3NhbGVzb3JkZXJkZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAlAAAAAAAAAAAAAAAAAEAHAABkYXRhL3NhbGVzaW52b2ljZWRldGFpbHMtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACEAAAAAAAAAAAAAAAAAhQcAAGRhdGEvY3VtdWxhdGl2ZXNhbGVzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAbAAAAAAAAAAAAAAAAAMYHAABkYXRhL2JpcnNlcmllcy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAIQAAAAAAAAAAAAAAAAABCAAAZGF0YS9kZWxpdmVyeXJlY2VpcHQtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACgAAAAAAAAAAAAAAAAAQggAAGRhdGEvZGVsaXZlcnlyZWNlaXB0ZGV0YWlscy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAFgAAAAAAAAAAAAAAAACKCAAAZGF0YS9kbWNtLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAdAAAAAAAAAAAAAAAAAMAIAABkYXRhL2RtY21kZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAcAAAAAAAAAAAAAAAAAP0IAABkYXRhL3RwaV9jcmVkaXQtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACgAAAAAAAAAAAAAAAAAOQkAAGRhdGEvdHBpX2NyZWRpdGxpbWl0ZGV0YWlscy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAJAAAAAAAAAAAAAAAAACBCQAAZGF0YS90cGlfZGVhbGVyd3JpdGVvZmYtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACUAAAAAAAAAAAAAAAAAxQkAAGRhdGEvdHBpX2RlYWxlcnByb21vdGlvbi0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAJAAAAAAAAAAAAAAAAAAKCgAAZGF0YS90cGlfZGVhbGVydHJhbnNmZXItMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQ35QkO/SCgAA82YAACwAAAAAAAAAAAAAAAAATgoAAGRhdGEvdHBpX2JyYW5jaGNvbGxlY3Rpb25yYXRpbmctMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACEAAAAAAAAAAAAAAAAAahUAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0LTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAlAAAAAAAAAAAAAAAAAKsVAABkYXRhL29mZmljaWFscmVjZWlwdGNhc2gtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACYAAAAAAAAAAAAAAAAA8BUAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0Y2hlY2stMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACsAAAAAAAAAAAAAAAAANhYAAGRhdGEvb2ZmaWNpYWxyZWNlaXB0Y29tbWlzc2lvbi0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAKAAAAAAAAAAAAAAAAACBFgAAZGF0YS9vZmZpY2lhbHJlY2VpcHRkZXBvc2l0LTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAoAAAAAAAAAAAAAAAAAMkWAABkYXRhL29mZmljaWFscmVjZWlwdGRldGFpbHMtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAAD8AAAAAAAAAAAAAAAAAERcAAGRhdGEvYXBwbGllZF9zaW5nbGVfbGluZV9wcm9tb19lbnRpdGxlbWVudF9kZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAA+AAAAAAAAAAAAAAAAAHAXAABkYXRhL2FwcGxpZWRfbXVsdGlfbGluZV9wcm9tb19lbnRpdGxlbWVudF9kZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAA7AAAAAAAAAAAAAAAAAM4XAABkYXRhL2FwcGxpZWRfb3ZlcmxheV9wcm9tb19lbnRpdGxlbWVudF9kZXRhaWxzLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAA3AAAAAAAAAAAAAAAAACkYAABkYXRhL2FwcGxpZWRfaW5jZW50aXZlX2VudGl0bGVtZW50X2RldGFpbHMtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACsAAAAAAAAAAAAAAAAAgBgAAGRhdGEvYXZhaWxlZF9hcHBsaWNhYmxlX3Byb21vcy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAALwAAAAAAAAAAAAAAAADLGAAAZGF0YS9hdmFpbGVkX2FwcGxpY2FibGVfaW5jZW50aXZlcy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAHQAAAAAAAAAAAAAAAAAaGQAAZGF0YS9zZm1fbWFuYWdlci0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAJgAAAAAAAAAAAAAAAABXGQAAZGF0YS9zZm1fbWFuYWdlcl9uZXR3b3Jrcy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DAAAAAAIAAAAAAAAAIQAAAAAAAAAAAAAAAACdGQAAZGF0YS9wcm9kdWN0ZXhjaGFuZ2UtMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAACgAAAAAAAAAAAAAAAAA3hkAAGRhdGEvcHJvZHVjdGV4Y2hhbmdlZGV0YWlscy0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5Dx5S5CaMCAAAXFgAAIAAAAAAAAAAAAAAAAAAmGgAAZGF0YS90cGlfc2Zhc3VtbWFyeS0yMDEzMTAzMC50eHRQSwECAAAUAAAACAAyil5DACZnoeQCAABhLgAAJAAAAAAAAAAAAAAAAAAHHQAAZGF0YS90cGlfc2Zhc3VtbWFyeV9wbWctMjAxMzEwMzAudHh0UEsBAgAAFAAAAAgAMopeQwAAAAACAAAAAAAAAB4AAAAAAAAAAAAAAAAALSAAAGRhdGEvYWZmbGlhdGVkaWJtLTIwMTMxMDMwLnR4dFBLAQIAABQAAAAIADKKXkMAAAAAAgAAAAAAAAAhAAAAAAAAAAAAAAAAAGsgAABkYXRhL2JhbmtkZXBvc2l0c2xpcC0yMDEzMTAzMC50eHRQSwUGAAAAADkAOQCJEgAArCAAAAAA');
	//$ZipSyncFileName 	= 'sync_files-46-20131030.zip';
	//$fetchEODPrev 		= (isset($_POST['fetchEODPrev']) ? $_POST['fetchEODPrev'] : false);
	//$NOW 				= '20131030'; //Date for today...
    
	$returns 			= array();

    $DSClass 			= new DataSync; //Initialize class methods for syncing...
    $DSClass->cURL();
    
	$database->execute("insert into syncdata (SyncFiles,xdate) values ('".$SyncFiles."',now())");
	
	
    //Trigger check when sync was about to start as the Branch requested...
    if($sync_option && $action == 'START_SYNC_FROM_BRANCH'){
        $mysqli->autocommit(FALSE);
        try{
            //Internet Connection
            if($sync_option == 'IC'){
				
				if(!is_dir($BranchCode.'-ZipFilesData')):
					mkdir($BranchCode.'-ZipFilesData', 0777, true);
				endif;
				
					$my_file = $BranchCode.'-ZipFilesData/'.$ZipSyncFileName;
					
					//$content = fread($fhandle,filesize($fname));
					$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
					exec("chmod -R 777 ".$my_file); //change file permission..
					fwrite($handle, base64_decode($SyncFiles));
					//unzip files..
					unzip($my_file, false, true, true);
				
				
					if(!$stop){
						$DSClass->setDBForeignKeyChecks(0);
						// $YesterDate = date('Ymd',strtotime('yesterday')); //date yesterday...
						$YesterDate = date('Ymd'); //date yesterday...
						
						
						if($fetchEODPrev){
							$TableCDFile = "$YesterDate/[TABLE_NAME]-$YesterDate.txt";
							$DSClass->PrevTransDate = "$YesterDate";
							
						}else{
							
							$TableCDFile = "[TABLE_NAME]-$NOW.txt";
							$DSClass->PrevTransDate = '';
						}
						
						foreach($HODBTblToReadInBranch as $tableName):
							$tableNameFile = str_replace('[TABLE_NAME]',$tableName,$TableCDFile);
							// $JSONEncode = $DSClass->get("/cron_jobs/data/$tableNameFile"); 
							$filename = str_replace(".zip","",$my_file)."/data/".$tableNameFile;
							if ( filesize($filename) > 0 ) {
								$JSONEncode = fread(fopen($filename, "r"),filesize(str_replace(".zip","",$my_file)."/data/".$tableNameFile));
								//echo $JSONEncode;
							}
							
							$JSONDecode = json_decode($JSONEncode);
							$DSClass->syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName);
							$affectedRows = $DSClass->doProcessSync($tableName,$JSONDecode);
							$DSClass->syncStartEndDateTimeLog($BranchID,$BranchCode,$tableName,'END',$affectedRows);
							unlink(str_replace(".zip","",$my_file)."/data/".$tableNameFile);
						endforeach;
							rmdir(str_replace(".zip","",$my_file)."/data");
							rmdir(str_replace(".zip","",$my_file));
						$DSClass->setDBForeignKeyChecks();
		
						//Update HO database tables from Branch...
						$DSClass->syncUpdateChangeField($HOTblNeedToUpdate);
						
						//rmdir(str_replace(".zip","",$my_file));
						
						$returns['status'] = 'success';
						$returns['message'] = 'Sync to HO done.';
					}
			}

            $mysqli->commit();
            $mysqli->autocommit(TRUE);
        }catch(Exception $e){
            $mysqli->rollback();
            $mysqli->autocommit(TRUE);

            $returns['status'] = 'error';
            $returns['message'] = 'Sync to HO fail, kindly check the error: '.$e->getMessage();
        }

        tpi_JSONencode($returns);
    }
    
    //Trigger to update "Changed" fields of HO specific database tables...
    if($action == 'TRIGGER_SYNC_CHANGE_UPDATE'){
        
    }
    
    //Process that will check if EOD of previous day was successfully process...
    //Used in SOD process of specific branch...
    if($action == 'SYNC_SOD_IF_EOD_SUCCESS'){
        try{
           $YesterDate = date('Ymd',strtotime('yesterday')); //date yesterday... 
           //Check method for table system sync log if there are previous/yesterday records of EOD...
           if(!$DSClass->syncCheckSODIfSuccessfulPrevTrans($BranchID,$YesterDate)){
                $returns['status'] = 'success';
                $returns['SODPrevCheck'] = false;
           }else{
               $returns['status'] = 'success';
               $returns['SODPrevCheck'] = true;
           } 
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
    
    //Process that will check if Mid Day Sync access key exists for an specific branch set in HO.
    //Used in Mid Day Sync procedure of branch.
    if($action == 'MDS_CHECK_IF_ACCESS_RIGHT'){
        $AccessKey = $_GET['AccessKey'];
        error_log($BranchID.' '.$AccessKey);
        try{
           if(!$DSClass->syncValidateMDSBranchAccessKey($BranchID,$AccessKey)){
                $returns['status'] = 'success';
                $returns['AccessKeyCheck'] = false;
           }else{
               $returns['status'] = 'success';
               $returns['AccessKeyCheck'] = true;
           } 
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }
        
        tpi_JSONencode($returns);
    }
	
	
?>
