<?php

function readConfig($configFile)
{
	
    $rCconf_file = $configFile;
    $fp = fopen($rCconf_file, "r");
    if ($fp)
    {
        $contents = fread ($fp, filesize("$rCconf_file"));
        fclose ($fp);

        $lines = explode ("\n", $contents);
        $i=0;
        foreach($lines as $lnsep)
        {
		  if ( $lnsep == "" or substr($lnsep, 0, 1) == ";" ) { 
		  	continue;
		  }
		  
          $tmp1 = explode("##", $lnsep);
          foreach($tmp1 as $ln)
          {
             $tmp2 = explode("=", $ln);
             $rCconfig[$i][$tmp2[0]] = trim($tmp2[1]);
		 $tmp2 = NULL;
          }
	    $tmp1 = NULL;	
          $i++;
        }
        return $rCconfig;
    }
    else
    {
      die ("Error reading the configuration file...");
    }
}


function saveConfig($configFile,$rCconfig,$rCServerId)
{
    global $documentRoot, $pathData;
    $rCconf_file = $configFile;
    $fp = fopen($rCconf_file, "wb");

    if ($fp)
    {
       for ( $j = 0; $j < sizeof ( $rCconfig ); $j++ )
       {
           $content = "";
           $i = 1;
    	     reset($rCconfig[$j]);
         
           foreach($rCconfig[$j] as $cf_key => $cf)
           {
               if ($cf_key == "Find")
               {
               	continue;
               }
               else
               {
                  if ( $cf_key == "Replace" )
			{
                     if ( $cf != "[SERVERNAME]" )
                     {
               	      $content .= "Find=" . $cf;
                     }
                     else
			   {
			   	$content .= "Find=" . $rCServerId . "##";
                        $content .= "Replace=[SERVERNAME]";
                     }
                  }
			else
			{
			   $content .= $cf_key . "=" . $cf;
                  }
               	if ($i < sizeof($rCconfig[$j])-1)
               	{
                  	$content .= "##";
                  	$i++;
               	}
		   }
           }
           if ( !$rCconfig[$j]["Replace"] )
	     {
			$content .= "##Find=" . $documentRoot . $pathData;
           }
	     $content .= "\n";
           $rt = fwrite($fp, $content);
           if (!$rt)
           {
              fclose ($fp);
              die("01 Error writing file...");
           }
        }
 
        fclose ($fp);
    }
    else
    {
        die("02 Error opening file...");
    }
    return TRUE;
}


function CreateIso2Mst($lcDir,$lcName, $lcMst)
{
	global $documentRoot;
	global $pathData;
	
	if ( eregi(".id",$lcName) ){
		//$lcExecLine = $documentRoot . $pathData . "install/id2i " . $lcDir . $lcName . " create=" . $lcDir . $lcMst;
		$lcExecLine = '"'.$documentRoot . $pathData . 'install/id2i' . '" "' . $lcDir . $lcName .'" "'. 'create=' . $lcDir . $lcMst . '"';	
	}else{
		$mxpar = ( eregi(".iso",$lcName) ?  "iso=" : "seq=" );
		//$lcExecLine = $documentRoot . $pathData . "install/mx " . $mxpar . $lcDir . $lcName . " create=" . $lcDir . $lcMst . " -all now";
		$lcExecLine = '"'. $documentRoot . $pathData . 'install/mx' . '" "'. $mxpar . $lcDir . $lcName . '" "'. ' create=' . $lcDir . $lcMst . '" ' .  ' -all now';
	}

	echo "\n\n<!-- command line: $lcExecLine -->\n\n";
	
	$mySuccess[0] = "CreatingMaster";
	exec($lcExecLine,$mySuccess);
	if (! $mySuccess )
	{
		die("01 Execution error...");
	}
	else
	{
		if (substr(php_uname(), 0, 7) !== "Windows")
		{
			$lcMyPermissions = 0666;
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".mst";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".xrf";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
		}
	}
      return TRUE;
}

function CreateInverted($lcDir,$lcName,$lcInverted)
{
	global $documentRoot;
	global $pathData;

	$lcExecLine = '"'. $documentRoot . $pathData . 'install/mx"' . ' "' . $lcDir . $lcName . '" "fst=@';
	$lcExecLine .= $lcDir . $lcInverted . '" "fullinv/ansi=';
	$lcExecLine .= $lcDir . $lcName . '" now -all';
	// print $lcExecLine . "<br>";
	$mySuccess[0] = "CreatingInverted";
      exec($lcExecLine,$mySuccess);
	if (! $mySuccess )
	{
		die("01 Execution error...");
	}
	else
	{
		if (substr(php_uname(), 0, 7) !== "Windows")
		{
			$lcMyPermissions = 0666;
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".ifp";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".l01";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".l02";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".n01";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".n02";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
			reset($lcExecLine);
			$lcExecLine = $lcDir . $lcName . ".cnt";
			$mySuccess = chmod($lcExecLine,$lcMyPermissions);
		}
	}
      return TRUE;
}

?>
