<?php
	require_once(dirname(__FILE__)."/old2new.inc");
 include ("classLogDatabaseQueryScieloIssue.php");

 $scieloLogQueryIssue = new LogDatabaseQueryScieloIssue("scielo.def");

 $scieloLogQueryIssue->mostRequested_ISSN($pid,$dti,$dtf,$access,$cpage,$nlines,$tpages,$maccess);
 $scieloLogQueryIssue->destroy();
 $scieloLogQueryIssue = null;

?>