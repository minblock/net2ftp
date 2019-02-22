<?php

//   -------------------------------------------------------------------------------
//  |                  net2ftp: a web based FTP client                              |
//  |                Copyright (c) 2003 by David Gartner                            |
//  |                                                                               |
//  | This program is free software; you can redistribute it and/or                 |
//  | modify it under the terms of the GNU General Public License                   |
//  | as published by the Free Software Foundation; either version 2                |
//  | of the License, or (at your option) any later version.                        |
//  |                                                                               |
//   -------------------------------------------------------------------------------


// -------------------------------------------------------------------------
// Basic settings
// -------------------------------------------------------------------------
require_once("../settings.inc.php");

// Run the script to the end, even if the user hits the stop button
ignore_user_abort();

// Set the error reporting level
if ($error_reporting == "ALL")  { error_reporting(E_ALL); }
if ($error_reporting == "NONE") { error_reporting(0); }
else                            { error_reporting(E_ERROR | E_WARNING | E_PARSE); }


// -------------------------------------------------------------------------
// Functions
// -------------------------------------------------------------------------
require_once($application_rootdir . "/includes/authorizations.inc.php");
require_once($application_rootdir . "/includes/bookmark.inc.php");
require_once($application_rootdir . "/includes/browse.inc.php");
require_once($application_rootdir . "/includes/database.inc.php");
require_once($application_rootdir . "/includes/errorhandling.inc.php");
require_once($application_rootdir . "/includes/filesystem.inc.php");
require_once($application_rootdir . "/includes/html.inc.php");
require_once($application_rootdir . "/includes/languages.inc.php");
require_once($application_rootdir . "/includes/manage.inc.php");
require_once($application_rootdir . "/includes/skins.inc.php");
require_once($application_rootdir . "/includes/zip.lib.php");


// -------------------------------------------------------------------------
// Register global variables (POST, GET, GLOBAL, ...)
// -------------------------------------------------------------------------
require_once($application_rootdir . "/includes/registerglobals.inc.php");


// -------------------------------------------------------------------------
// Send HTTP headers
// -------------------------------------------------------------------------
require_once($application_rootdir . "/includes/httpheaders.inc.php");


// -------------------------------------------------------------------------
// Block the output to the browser and use compression if the browser supports it
// -------------------------------------------------------------------------
if ($compress_output == "yes") { ob_start("ob_gzhandler"); }


// -------------------------------------------------------------------------
// Begin HTML output
// -------------------------------------------------------------------------
HtmlBegin("net2ftp Admin", $state, $state2, $directory, $entry);



// ----------------------------------------------------------------------------------------------------
// Form
// ----------------------------------------------------------------------------------------------------

if ($formresult != "result") {

	echo "<table align=\"center\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -----------------------------------------------------
// Form to filter the logs
// -----------------------------------------------------

	echo "Select the criteria to <b>view</b> the logs:<br /><br />\n";
	echo "<form name=\"ViewForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"viewlogs\" />\n";

// Date
	$today = date("Y-m-d");
	$oneweekago = date("Y-m-d", time() - 3600*24*7);
	echo "Date from: <input type=\"text\" name=\"datefrom\" value=\"$oneweekago\" />  to: <input type=\"text\" name=\"dateto\" value=\"$today\" /><br />";
	echo "<br /><br /><div style=\"text-align: center;\"><input type=\"submit\" class=\"button\" value=\"View\" /></div>\n";
	echo "</form>\n";

	echo "<br /><br />\n";

// -----------------------------------------------------
// Form to empty the logs
// -----------------------------------------------------

	echo "Select the criteria to <b>empty</b> the logs:<br /><br />\n";
	echo "<form name=\"EmptyForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"emptylogs\" />\n";

// Date
	$today = date("Y-m-d");
	$oneweekago = date("Y-m-d", time() - 3600*24*7);
	echo "Date from: <input type=\"text\" name=\"datefrom\" value=\"$oneweekago\" />  to: <input type=\"text\" name=\"dateto\" value=\"$today\" /><br />";
	echo "<br /><br /><div style=\"text-align: center;\"><input type=\"submit\" class=\"button\" value=\"Empty\" /></div>\n";
	echo "</form>\n";

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";


} // End if

// ----------------------------------------------------------------------------------------------------
// Result: there are 2 possibilities, view logs or empty logs
// ----------------------------------------------------------------------------------------------------

// -----------------------------------------------------
// View logs
// -----------------------------------------------------

elseif ($formresult == "result" && $state == "viewlogs") {

	$resultArray = connect2db();
	$mydb = getResult($resultArray);
	if ($mydb == false)  { printErrorMessage($resultArray, "exit"); }

	$fields_net2ftp_logAccess = mysql_list_fields($dbname, "net2ftp_logAccess", $mydb);
	$columns_net2ftp_logAccess = mysql_num_fields($fields_net2ftp_logAccess);

	$fields_net2ftp_logLogin = mysql_list_fields($dbname, "net2ftp_logLogin", $mydb);
	$columns_net2ftp_logLogin = mysql_num_fields($fields_net2ftp_logLogin);

	$fields_net2ftp_logError= mysql_list_fields($dbname, "net2ftp_logError", $mydb);
	$columns_net2ftp_logError = mysql_num_fields($fields_net2ftp_logError);

// Query 1
	$sqlquery1 = "SELECT * FROM net2ftp_logAccess WHERE date BETWEEN '$datefrom' AND '$dateto' ORDER BY date DESC, time DESC;";
	$result1 = mysql_query("$sqlquery1") or die("Unable to execute SQL SELECT query (stats1) <br /> $sqlquery1");
	$nrofrows1 = mysql_num_rows($result1);

// Query 2
	$sqlquery2 = "SELECT * FROM net2ftp_logLogin WHERE date BETWEEN '$datefrom' AND '$dateto' ORDER BY date DESC, time DESC;";
	$result2 = mysql_query("$sqlquery2") or die("Unable to execute SQL SELECT query (stats2) <br /> $sqlquery2");
	$nrofrows2 = mysql_num_rows($result2);

// Query 3
	$sqlquery3 = "SELECT * FROM net2ftp_logError WHERE date BETWEEN '$datefrom' AND '$dateto' ORDER BY date DESC, time DESC;";
	$result3 = mysql_query("$sqlquery3") or die("Unable to execute SQL SELECT query (stats3) <br /> $sqlquery3");
	$nrofrows3 = mysql_num_rows($result3);

	printLogs($columns_net2ftp_logAccess, $nrofrows1, $sqlquery1, $fields_net2ftp_logAccess, $result1);
	printLogs($columns_net2ftp_logLogin, $nrofrows2, $sqlquery2, $fields_net2ftp_logLogin, $result2);
	printLogs($columns_net2ftp_logError, $nrofrows3, $sqlquery3, $fields_net2ftp_logError, $result3);

} // End elseif view


// -----------------------------------------------------
// Empty logs
// -----------------------------------------------------
elseif ($formresult == "result" && $state == "emptylogs") {

	$resultArray = connect2db();
	$mydb = getResult($resultArray);
	if ($mydb == false)  { printErrorMessage($resultArray, "exit"); }

	$tables[1] = "net2ftp_logAccess";
	$tables[2] = "net2ftp_logLogin";
	$tables[3] = "net2ftp_logError";

	for ($i=1; $i<=3; $i++) {
		$sqlquery_empty = "DELETE FROM $tables[$i] WHERE date BETWEEN '$datefrom' AND '$dateto';";
		$result_empty = mysql_query("$sqlquery_empty") or die("Unable to execute SQL SELECT query (empty) <br /> $sqlquery_empty");
		if ($result_empty == true) { echo "The table <b>$tables[$i]</b> was emptied successfully.<br />\n"; }
		else                       { echo "The table <b>$tables[$i]</b> could not be emptied.<br />\n"; }
		echo "<br />\n";
	}

} // End elseif empty



// -------------------------------------------------------------------------
// End HTML output
// -------------------------------------------------------------------------
HtmlEnd();

// -------------------------------------------------------------------------
// Send the output to the browser
// Note: in case there is an error and the script is exited, the ob_flush function is called by the error handler
// -------------------------------------------------------------------------
ob_end_flush();








// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printLogs($nrofcolumns, $nrofrows, $sqlquery, $fields, $result) {

// Call the function like this:
//    printLogs($columns_net2ftp_ftpserversBanned, $nrofrows5, $sqlquery5, $fields_net2ftp_ftpserversBanned, $result3)

$nrofcolumns_withindex = $nrofcolumns + 1;

	echo "<table border=\"1\">\n";
// ------------------------------------------------------------------------- 
// First row: title
// ------------------------------------------------------------------------- 
	echo "<tr><td colspan=\"$nrofcolumns_withindex\" class=\"tdheader1\" style=\"font-size: 120%;\">$sqlquery</td></tr>\n";
// ------------------------------------------------------------------------- 
// Second row: header
// ------------------------------------------------------------------------- 
	if ($nrofrows != 0) {
		echo "<tr>\n";
		echo "<td class=\"tdheader1\"><b>Index</b></td>\n";
		for ($i = 0; $i < $nrofcolumns; $i=$i+1) {
			$resultparametername = mysql_field_name($fields, $i);
			$resultparametervalue = mysql_result($result, 0, $resultparametername);
			echo "<td class=\"tdheader1\"><b>$resultparametername</b></td>\n";
		} // End for (row, loop on columns)
		echo "</tr>\n";
	}
// ------------------------------------------------------------------------- 
// Other rows: data
// ------------------------------------------------------------------------- 

// If there is data
	if ($nrofrows != 0) {
		for($j=0; $j<$nrofrows; $j=$j+1) {
			echo "<tr>\n";
			echo "<td class=\"tditem1\">$j</td>\n";
			for ($i = 0; $i < $nrofcolumns; $i=$i+1) {
				$resultparametername = mysql_field_name($fields, $i);
				$resultparametervalue = mysql_result($result, $j, $resultparametername);
				echo "<td class=\"tditem1\">$resultparametervalue</td>\n";
			} // End for (row, loop on columns)
			echo "</tr>\n";
		} // End for (show results, loop on rows)
	}

// If there is no data
	else { echo "<tr><td colspan=\"$nrofcolumns_withindex\">No data</td></tr>"; }

	echo "</table>\n";

	echo "<p> &nbsp; </p>\n\n\n";

} // End printLogs

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************


?>