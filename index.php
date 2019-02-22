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
//  | This program is distributed in the hope that it will be useful,               |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of                |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                 |
//  | GNU General Public License for more details.                                  |
//  |                                                                               |
//  | You should have received a copy of the GNU General Public License             |
//  | along with this program; if not, write to the Free Software                   |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA     |
//  |                                                                               |
//   -------------------------------------------------------------------------------


// -------------------------------------------------------------------------
// Basic settings
// -------------------------------------------------------------------------
require_once("settings.inc.php");

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
// Check authorizations
// -------------------------------------------------------------------------
if ($check_authorization == "yes" && $net2ftp_ftpserver != "") {
	$resultArray = checkAuthorization($net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username);
	$result = getResult($resultArray);
	if ($result == false) { printErrorMessage($resultArray, "exit"); }
}


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
HtmlBegin("net2ftp", $state, $state2, $directory, $entry);


// ------------------------------------------------------------------------
// Main switch; functions are in include files "functions_somename.inc.php"
// -------------------------------------------------------------------------
if (strlen($state) < 1) { $state= "printloginform"; }

switch ($state) {
	case "printloginform":
		printLoginForm();
	break;
	case "printdetails":
		printDetails();
	break;
	case "printscreenshots":
		printScreenshots();
	break;
	case "printdownload":
		printDownload();
	break;
	case "browse":
		browse($state2, $directory, $FormAndFieldName);
	break;
	case "directorytree":
		directorytree($directory);
	break;
	case "manage":
		manage($state2, $directory, $entry, $selectedEntries, $newNames, $dirorfile, $formresult, $chmodStrings, $targetDirectories, $copymovedelete, $text, $wysiwyg, $uploadedFilesArray, $uploadedArchivesArray, $use_folder_names, $command);
	break;
	case "bookmark":
		bookmark($directory, $url, $text);
	break;
	case "logout":
		printLoginForm();
	break;
	case "feedback":
		printFeedbackForm($formresult);
	break;
	default:
		$resultArray['message'] = "Unexpected state string. Exiting."; 
		printErrorMessage($resultArray, "exit");
	break;
} // End switch


// -------------------------------------------------------------------------
// End HTML output
// -------------------------------------------------------------------------
HtmlEnd();


// -------------------------------------------------------------------------
// Send the output to the browser
// -------------------------------------------------------------------------
if ($compress_output == "yes") { ob_end_flush(); }

?>