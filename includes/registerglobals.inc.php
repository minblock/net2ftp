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
// The purpose of this file is to register all global variables explicitely.
// This way, register_global can be set to off, which is safer.
// (Note: register_global can also be set to on.)
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
// When a variable is submitted, quotes ' are replaced by backslash-quotes \'
// This function removes the extra backslash that is added
// -------------------------------------------------------------------------
remove_magic_quotes(&$HTTP_GET_VARS);
remove_magic_quotes(&$HTTP_POST_VARS);
remove_magic_quotes(&$HTTP_COOKIE_VARS);
// Do not add remove_magic_quotes for $GLOBALS because this would call the same
// function a second time, replacing \' by ' and \" by "


// -------------------------------------------------------------------------
// POST variables (from forms)
// -------------------------------------------------------------------------
$input_ftpserver =             $HTTP_POST_VARS['input_ftpserver'];
$input_ftpserverport =         $HTTP_POST_VARS['input_ftpserverport'];
$input_username =              $HTTP_POST_VARS['input_username'];
$input_password =              $HTTP_POST_VARS['input_password'];
$input_language =              $HTTP_POST_VARS['input_language'];
$input_skin =                  $HTTP_POST_VARS['input_skin'];

$input_ftpserver2 =            $HTTP_POST_VARS['input_ftpserver2'];
$input_ftpserverport2 =        $HTTP_POST_VARS['input_ftpserverport2'];
$input_username2 =             $HTTP_POST_VARS['input_username2'];
$input_password2 =             $HTTP_POST_VARS['input_password2'];

$net2ftp_ftpserver =           $HTTP_POST_VARS['net2ftp_ftpserver'];
$net2ftp_ftpserverport =       $HTTP_POST_VARS['net2ftp_ftpserverport'];
$net2ftp_username =            $HTTP_POST_VARS['net2ftp_username'];
$net2ftp_password_encrypted =  $HTTP_POST_VARS['net2ftp_password_encrypted'];
$net2ftp_language =            $HTTP_POST_VARS['net2ftp_language'];
$net2ftp_skin =                $HTTP_POST_VARS['net2ftp_skin'];

$net2ftpcookie_ftpserver =     $HTTP_COOKIE_VARS['net2ftpcookie_ftpserver'];
$net2ftpcookie_ftpserverport = $HTTP_COOKIE_VARS['net2ftpcookie_ftpserverport'];
$net2ftpcookie_username =      $HTTP_COOKIE_VARS['net2ftpcookie_username'];
$net2ftpcookie_directory =     $HTTP_COOKIE_VARS['net2ftpcookie_directory'];
$net2ftpcookie_language =      $HTTP_COOKIE_VARS['net2ftpcookie_language'];
$net2ftpcookie_skin =          $HTTP_COOKIE_VARS['net2ftpcookie_skin'];


// Different state variables
$state =             $HTTP_POST_VARS['state'];
$state2 =            $HTTP_POST_VARS['state2'];
$directory =         $HTTP_POST_VARS['directory'];
$cookiesetonlogin =  $HTTP_POST_VARS['cookiesetonlogin'];
$entry =             $HTTP_POST_VARS['entry'];
$selectedEntries =   $HTTP_POST_VARS['selectedEntries'];
$newNames =          $HTTP_POST_VARS['newNames'];
$dirorfile =         $HTTP_POST_VARS['dirorfile'];
$formresult =        $HTTP_POST_VARS['formresult'];
$chmodStrings =      $HTTP_POST_VARS['chmodStrings'];
$targetDirectories = $HTTP_POST_VARS['targetDirectories'];
$copymovedelete =    $HTTP_POST_VARS['copymovedelete'];
$url =               $HTTP_POST_VARS['url'];
$text =              $HTTP_POST_VARS['text'];
$wysiwyg =           $HTTP_POST_VARS['wysiwyg'];
$FormAndFieldName =  $HTTP_POST_VARS['FormAndFieldName'];
$use_folder_names =  $HTTP_POST_VARS['use_folder_names'];
$command =           $HTTP_POST_VARS['command'];


// Used in function printFeedbackForm(), file authorizations.inc.php
$name =              $HTTP_POST_VARS['name'];
$subject =           $HTTP_POST_VARS['subject'];
$email =             $HTTP_POST_VARS['email'];
$messagebody =       $HTTP_POST_VARS['messagebody'];


// -------------------------------------------------------------------------
// Move content of HTTP_POST_FILES to uploadedFilesArray and uploadedArchivesArray
// -------------------------------------------------------------------------
$file_counter = 0;
$archive_counter = 0;

for ($i=1; $i<=$nr_upload_files; $i=$i+1) {
	if ($HTTP_POST_FILES["file$i"]["size"] > 0) {
		$file_counter = $file_counter + 1;
		$uploadedFilesArray["$file_counter"]["tmp_name"]       = $HTTP_POST_FILES["file$i"]["tmp_name"];
		$uploadedFilesArray["$file_counter"]["name"]           = $HTTP_POST_FILES["file$i"]["name"];
		$uploadedFilesArray["$file_counter"]["size"]           = $HTTP_POST_FILES["file$i"]["size"];
	} // end if
} // end for

for ($i=1; $i<=$nr_upload_archives; $i=$i+1) {
	if ($HTTP_POST_FILES["archive$i"]["size"] > 0) {
		$archive_counter = $archive_counter + 1;
		$uploadedArchivesArray["$archive_counter"]["tmp_name"] = $HTTP_POST_FILES["archive$i"]["tmp_name"];
		$uploadedArchivesArray["$archive_counter"]["name"]     = $HTTP_POST_FILES["archive$i"]["name"];
		$uploadedArchivesArray["$archive_counter"]["size"]     = $HTTP_POST_FILES["archive$i"]["size"];
	} // end if
} // end for

// -------------------------------------------------------------------------
// GET variables (from URL)
// -------------------------------------------------------------------------

$get_ftpserver = $HTTP_GET_VARS['ftpserver'];
$get_ftpserverport = $HTTP_GET_VARS['ftpserverport'];
$get_username = $HTTP_GET_VARS['username'];
$get_language = $HTTP_GET_VARS['language'];
$get_skin = $HTTP_GET_VARS['skin'];
$get_directory = $HTTP_GET_VARS['directory'];
$get_state = $HTTP_GET_VARS['state'];
$get_state2 = $HTTP_GET_VARS['state2'];

// -------------------------------------------------------------------------
// SERVER variabes
// -------------------------------------------------------------------------

$PHP_SELF =        $HTTP_SERVER_VARS['PHP_SELF'];
$HTTP_REFERER =    $HTTP_SERVER_VARS['HTTP_REFERER'];
$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
$REMOTE_ADDR =     $HTTP_SERVER_VARS['REMOTE_ADDR'];
$REMOTE_PORT =     $HTTP_SERVER_VARS['REMOTE_PORT'];

$PHP_AUTH_USER = $HTTP_SERVER_VARS['PHP_AUTH_USER'];
$PHP_AUTH_PW = $HTTP_SERVER_VARS['PHP_AUTH_PW'];



// -------------------------------------------------------------------------
// User logs in using the form: 
//   1. clean the input
//   2. set the directory to the one from the cookie
//   3. log the login (Note: The logging can be activated or not activated, 
//      depending on a setting in the settings.inc.php file)
// -------------------------------------------------------------------------	
if (strlen($input_ftpserver) > 1 && strlen($input_username) > 1) {
	$net2ftp_ftpserver     = cleanFtpserver($input_ftpserver);
	$net2ftp_ftpserverport = trim($input_ftpserverport);
	$net2ftp_username      = trim($input_username);
	$net2ftp_password_encrypted = encryptPassword(trim($input_password));
	$net2ftp_language      = trim($input_language);
	$net2ftp_skin          = trim($input_skin);

	if ($net2ftp_ftpserver == $net2ftpcookie_ftpserver) {	$directory = $net2ftpcookie_directory; }

	$resultArray = logLogin($input_ftpserver, $input_username);
	$result = getResult($resultArray);
	if ($result == false) { printErrorMessage($resultArray, "exit"); }
}


// -------------------------------------------------------------------------
// User logs in using a bookmark 
// -------------------------------------------------------------------------	
if (strlen($get_ftpserver) > 1 && strlen($get_username) > 1 && strlen($net2ftp_ftpserver) < 1) {

	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		header("WWW-Authenticate: Basic realm=\"Please enter your username and password for FTP server " . $get_ftpserver . "\"");
		header("HTTP/1.0 401 Unauthorized");
		$resultArray['message'] = "You did not fill in your login information in the popup window.<br />Follow the link below to the login page to log in.\n";
		printErrorMessage($resultArray, "exit");
		exit;
	}

	$net2ftp_ftpserver = cleanFtpserver($get_ftpserver);
	$net2ftp_ftpserverport = trim($get_ftpserverport);
	$net2ftp_username = trim($PHP_AUTH_USER);
	$net2ftp_password_encrypted = encryptPassword(trim($PHP_AUTH_PW));
	$net2ftp_language = trim($get_language);
	$net2ftp_skin = trim($get_skin);
	$directory = trim($get_directory);
	$state = trim($get_state);
	$state2 = trim($get_state2);

	$resultArray = logLogin($get_ftpserver, $get_username);
	$result = getResult($resultArray);
	if ($result == false) { printErrorMessage($resultArray, "exit"); }
}


// -------------------------------------------------------------------------
// Clean and verify the input
// -------------------------------------------------------------------------

if (strlen($directory) < 1) { $directory = ""; $printdirectory = "/"; }
else { $directory = cleanDirectory($directory); }



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function remove_magic_quotes(&$x, $keyname="") {

	// http://www.php.net/manual/en/configuration.php#ini.magic-quotes-gpc (by the way: gpc = get post cookie)
	// if (magic_quotes_gpc == 1), then PHP converts automatically " --> \", ' --> \'
	// Has only to be done when getting info from get post cookie
	if (get_magic_quotes_gpc() == 1) {

		if (is_array($x)) {
			while (list($key,$value) = each($x)) {
				if ($value) { remove_magic_quotes(&$x[$key],$key); }
			}
		}
		else { 
			$quote = "'";
			$doublequote = "\"";
			$backslash = "\\";

			$x = str_replace("$backslash$quote", $quote, $x);
			$x = str_replace("$backslash$doublequote", $doublequote, $x);
			$x = str_replace("$backslash$backslash", $backslash, $x);
		}

	} // end if get_magic_quotes_gpc

} // end function remove_magic_quotes

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************


?>