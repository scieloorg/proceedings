<?
include_once("old2new.inc");
include("setup-run.inc");

if ( !$lang )
{
	$lang = "es";
}
$message = $messageArray[$lang];

?>

<html>
<head>
	<title><? print_r($message["title"]) ?></title>
</head>

<body>

<table border="1" width="580" cellspacing="1" cellpadding="5" align="center" bgcolor="#B3CEEC" >		
	<TR>
            <TD width="100%">
		<table border="0" width="100%" cellpadding="1" cellspacing="0"><tr>
		<TD align="left" bgcolor="#666699" valign="center">
			<img src="logo_bireme.gif">
		</TD>
		<TD align="right" bgcolor="#666699" valign="center">
			<font face="Verdana" size="2" color="white"><b><? print_r($message["title"]) ?></b></font>		
		</TD></tr>
            </table>
		<!-- <font face="Verdana" size="2" color="white"><? print_r($message["title"]) ?></font> -->
		</td>		
	</TR>
	<TR>
		<TD bgcolor="#D9E4EC" width="100%">		
		<font face="Verdana" size="2">
		<br>
		<p><b>&raquo; <? print_r($message["msg1"]) ?></b></p>
          
<?php

include('this2that-functions.php');
include('setup-functions.php');

$myConfigVars = readConfig("setup.ini");


$iniFind 	= array("(\[PATH_DATABASE\])", "(\[PATH_DATA\])", "(\[PATH_CGI-BIN\])", "(\[SERVERNAME\])");
$iniReplace = array($pathDbase, $documentRoot . $pathData, $pathCgi, $serverId);


foreach($myConfigVars as $myVarsArray)
{

	$dir  = preg_replace ($iniFind, $iniReplace, $myVarsArray["Directory"]);
	$find = $myVarsArray["Find"];
	
	$replace = preg_replace ($iniFind, $iniReplace, $myVarsArray["Replace"]);
	
	if ( $myVarsArray["Replace"] == false ) { 
		$replace = "$documentRoot$pathData";
	}

	$file = $myVarsArray["FileType"];

	$options = array("-ifn","-R");
	//$options = array("-t","-R");
	echo "\n<!-- this2that \nfind=" .  $find . "\nreplace=" . $replace . "\ndirectory=" . $dir . "\nfiles= " . $file . " \n-->\n\n";
	$result = this2that ( $dir, $file, $find, $replace, $options );

	print "<p> ";
      print_r($message["msg2"] . $dir . "<br>" . $message["msg3"] . $file);
	print "</p>";
/*		
	print "<table border='0'><tr><td bgcolor='#bdbcc5'>&nbsp;</td><td>";
	print( "<font face='arial' size='2'>" .  nl2br($result) . "</font>");
	print "</td></tr></table>"; 
*/
	$resultLines = split("\n", $result);

	foreach ( $resultLines as $line )
	{
		if ( !ereg ("^rw", $line) )
		{
			$line_error .= $line;
		}
	}

	if ( $line_error !== "" )
	{	
		print "<p><font color='#ff3300'>" . $message["error"] . "</font></p>";
		break;
	}
}

if ( $line_error == ""){

	$myNewConfig = saveConfig("setup.out",$myConfigVars,$serverId);	
	$myIAdminVars = readConfig("bases.ini");
	
	$ini2Find = array("(\[PATH_DATABASE\])", "(\[PATH_DATA\])", "(\[PATH_CGI-BIN\])", "(\[SERVERNAME\])");
	$ini2Replace = array($pathDbase, $documentRoot . $pathData, $pathCgi, $serverId);
	
	foreach($myIAdminVars as $myIAVars)
	{
		$IAdir  = preg_replace ($ini2Find, $ini2Replace, $myIAVars["Directory"]);
	
		$IAfile = $myIAVars["FileName"];
		$IAmst  = $myIAVars["Master"];
	      if ( !$myIAVars["Invert"] )
	      {
	         $IAfst = false;
	      }
	      else
	      {
		   $IAfst = $myIAVars["Invert"];
	      }
		print "<br>Base: " . $IAdir . $IAmst;
		$IAresult = CreateIso2Mst($IAdir, $IAfile, $IAmst);	
	      if ( $IAfst )
	      {
	         $IAresult = CreateInverted($IAdir, $IAmst, $IAfst);
		     print "  IFn: " . $IAmst . "<br>";
	      }
	      
	}
	
		if ( $line_error == "" )
		{
			print "<p><font color='#0066cc'>" . $message["end"] . "</font></p>";
		}
}		
?>		

		<div align="center">
			<? 
				if ( $line_error == ""){
					print '<a href="' . $pathData . 'index.php">' . $message["home"] . '</a>';
				}else{
					print '<a href="javascript:history.back();">' . $message["back"] . '</a>';
				}	
			 ?>			
		</div><br>	
		</TD>
	</TR>
</table>	

</body>
</html>
