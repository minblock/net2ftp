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



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_openconnection() {

// --------------
// This function opens an ftp connection
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $net2ftp_password_encrypted;


// Check if the FTP module of PHP is installed
	if (function_exists("ftp_connect") == false) { return putResult(false, "", "function_exists", "ftp_openconnection > function_exists > ftp_connect: FTP module of PHP", "The FTP module of PHP is not installed. Please install this FTP module.<br />"); }

// Decrypt password
	$net2ftp_password = decryptPassword($net2ftp_password_encrypted);

// Check if port nr is filled in
	if ($net2ftp_ftpserverport < 1 || $net2ftp_ftpserverport > 65535 || $net2ftp_ftpserverport == "") { $net2ftp_ftpserverport = 21; }

// Set up basic connection
	$conn_id = ftp_connect("$net2ftp_ftpserver", $net2ftp_ftpserverport);
	if ($conn_id == false) { return putResult(false, "", "ftp_connect", "ftp_openconnection > ftp_connect: net2ftp_ftpserver=$net2ftp_ftpserver.", "Unable to connect to FTP server <b>$net2ftp_ftpserver</b> on port <b>$net2ftp_ftpserverport</b>.<br /><br />Are you sure this is the address of the FTP server? This is often different from that of the HTTP (web) server. Please contact your ISP helpdesk or system administrator for help.<br /><br />"); }

// Login with username and password
	$login_result = ftp_login($conn_id, $net2ftp_username, $net2ftp_password);
	if ($login_result == false) { return putResult(false, "", "ftp_login", "ftp_openconnection > ftp_login: conn_id=$conn_id; net2ftp_username=$net2ftp_username.", "Unable to login to FTP server <b>$net2ftp_ftpserver</b> with username <b>$net2ftp_username</b>.<br /><br />Are you sure your username and password are correct? Please contact your ISP helpdesk or system administrator for help.<br /><br />"); }

// Return true if everything went fine
	return putResult(true, $conn_id, "", "", "");


} // End function ftp_openconnection

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_openconnection2() {

// --------------
// This function opens an ftp connection to the secondary FTP server, to which
// files can be copied or moved.
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $input_ftpserver2, $input_ftpserverport2, $input_username2, $input_password2;


// Check if the FTP module of PHP is installed
	if (function_exists("ftp_connect") == false) { return putResult(false, "", "function_exists", "ftp_openconnection2 > function_exists > ftp_connect: FTP module of PHP", "The FTP module of PHP is not installed. Please install this FTP module.<br />"); }

// Clean the values that have been filled in 
	$input_ftpserver2 = cleanFtpserver($input_ftpserver2);
	$input_ftpserverport2 = trim($input_ftpserverport2);
	$input_username2 = trim($input_username2);
	$input_password2 = trim($input_password2);

// Check if port nr is correct
	if ($input_ftpserverport2 < 1 || $input_ftpserverport2 > 65535 || $input_ftpserverport2 == "") { $input_ftpserverport2 = 21; }

// Set up basic connection
	$conn_id = ftp_connect("$input_ftpserver2", $input_ftpserverport2);
	if ($conn_id == false) { return putResult(false, "", "ftp_connect", "ftp_openconnection2 > ftp_connect: input_ftpserver2=$input_ftpserver2.", "Unable to connect to the second (target) FTP server <b>$input_ftpserver2</b> on port <b>$input_ftpserverport2</b>.<br /><br />Are you sure this is the address of the second (target) FTP server? This is often different from that of the HTTP (web) server. Please contact your ISP helpdesk or system administrator for help.<br /><br />"); }

// Login with username and password
	$login_result = ftp_login($conn_id, $input_username2, $input_password2);
	if ($login_result == false) { return putResult(false, "", "ftp_login", "ftp_openconnection2 > ftp_login: conn_id=$conn_id; input_username2=$input_username2.", "Unable to login to the second (target) FTP server <b>$input_ftpserver2</b> with username <b>$input_username2</b>.<br /><br />Are you sure your username and password are correct? Please contact your ISP helpdesk or system administrator for help.<br /><br />"); }

	return putResult(true, $conn_id, "", "", "");

} // End function ftp_openconnection2
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_openconnection_ssl() {

// --------------
// This function opens an SSL FTP connection
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $net2ftp_password_encrypted;


// Check if the FTP module of PHP is installed
	if (function_exists("ftp_login") == false) { return putResult(false, "", "function_exists", "ftp_openconnection > function_exists > ftp_login: FTP module of PHP", "The FTP module of PHP is not installed. Please install this FTP module.<br />"); }
	if (function_exists("ftp_ssl_connect") == false) { return putResult(false, "", "function_exists", "ftp_openconnection_ssl > function_exists > ftp_ssl_connect", "The OpenSSL module of PHP is not installed. If you want to use SSL connections, this module is needed.<br />"); }

// Decrypt password
	$net2ftp_password = decryptPassword($net2ftp_password_encrypted);

// Check if port nr is filled in
	if ($net2ftp_ftpserverport < 1 || $net2ftp_ftpserverport > 65535 || $net2ftp_ftpserverport == "") { $net2ftp_ftpserverport = 21; }

// Set up basic connection WITH SSL
	$conn_id = ftp_ssl_connect("$net2ftp_ftpserver", $net2ftp_ftpserverport);
	if ($conn_id == false) { return putResult(false, "", "ftp_connect", "ftp_openconnection > ftp_connect: net2ftp_ftpserver=$net2ftp_ftpserver.", "Unable to connect to FTP server <b>$net2ftp_ftpserver</b> on port <b>$net2ftp_ftpserverport</b>.<br /><br />Are you sure this is the address of the FTP server? This is often different from that of the HTTP (web) server. Please contact your ISP helpdesk or system administrator for help.<br /><br />"); }

// Login with username and password
	$login_result = ftp_login($conn_id, $net2ftp_username, $net2ftp_password);
	if ($login_result == false) { return putResult(false, "", "ftp_login", "ftp_openconnection > ftp_login: conn_id=$conn_id; net2ftp_username=$net2ftp_username.", "Unable to login to FTP server <b>$net2ftp_ftpserver</b> with username <b>$net2ftp_username</b>.<br /><br />Are you sure your username and password are correct? Please contact your ISP helpdesk or system administrator for help.<br /><br />"); }

// Return true if everything went fine
	return putResult(true, $conn_id, "", "", "");


} // End function ftp_openconnection_ssl

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_closeconnection($conn_id) {

// --------------
// This function closes an ftp connection
// --------------

	ftp_quit($conn_id);

// In PHP 4.2.3, ftp_quit does not return anything any more

	return putResult(true, true, "", "", "");

} // End function ftp_closeconnection
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_myrename($conn_id, $directory, $selectedEntry, $newName) {

// --------------
// This function renames a directory
// --------------

	$success1 = ftp_rename($conn_id, "$directory/$selectedEntry", "$directory/$newName");
	if ($success1 == false) { return putResult(false, "", "ftp_rename", "ftp_myrename > ftp_rename: conn_id=$conn_id; old=$directory/$selectedEntry; new=$directory/$newName.", "Unable to rename directory or file <b>$ftp_old</b> into <b>$ftp_new</b><br />"); }

	return putResult(true, true, "", "", "");

} // End function ftp_myrename
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_mychmod($conn_id, $directory, $selectedEntry, $chmodOctal) {

// --------------
// This function chmods a directory or file
// --------------


	$success1 = ftp_site($conn_id, "chmod 0$chmodOctal $directory/$selectedEntry");
	if ($success1 == false) { return putResult(false, "", "ftp_site", "ftp_mychmod > ftp_site: conn_id=$conn_id; directory=$directory; selectedEntry=$selectedEntry; chmodOctal=$chmodOctal.", "Unable to execute site command <b>chmod 0$chmodOctal $selectedEntry</b>"); }

	return putResult(true, true, "", "", "");

} // End function ftp_mychmod
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_mydelete($directoryorfile) {

// --------------
// This function deletes a remote directory or a file
// NOT IN USE ANY MORE
// --------------

// Open connection
//	$resultArray = ftp_openconnection();
//	$conn_id = getResult($resultArray);
//	if ($conn_id == false)  { return putResult(false, "", "ftp_openconnection", "ftp_mydelete > " . $resultArray['drilldown'], $resultArray['message']); }

// Delete directory or file
//	$success1 = ftp_delete($conn_id, $directoryorfile);
//	if ($success1 == false) { return putResult(false, "", "ftp_delete", "ftp_mydelete > ftp_delete: conn_id=$conn_id; directoryorfile=$directoryorfile.", "Unable to delete the directory <b>$directoryorfile</b><br />"); }

// Close connection
//	$resultArray = ftp_closeconnection($conn_id);
//	$success2 = getResult($resultArray);
//	if ($success2 == false) { return putResult(false, "", "ftp_closeconnection", "ftp_mydelete > " . $resultArray['drilldown'], $resultArray['message']); }

//	return putResult(true, true, "", "", "");

} // End function ftp_mydelete
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_newdirectory($conn_id, $directory) {

// --------------
// This function creates a new remote directory
// --------------

	$success1 = ftp_mkdir($conn_id, $directory);
	if ($success1 == false) { return putResult(false, "", "ftp_newdirectory", "ftp_newdirectory > ftp_mkdir: conn_id=$conn_id; directory=$directory.", "Unable to create the directory <b>$directory</b><br />"); }

	return putResult(true, true, "", "", "");

} // End function ftp_newdirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_readfile($conn_id, $directory, $file) {

// --------------
// This function opens a remote text file and it returns a string
// It can be used stand-alone (with conn_id = "") and then a new connection is opened
// Else it can also be used in a loop (with conn_id != false) and then the existing connection is opened
// --------------


// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

	$source = $directory . "/" . $file;


// --------------------
// Step 1/4: Create a temporary filename
	$tempfilename = tempnam($application_tempdir, "ftpread");
	if ($tempfilename == false)  { return putResult(false, "", "tempnam", "ftp_readfile > tempnam: application_tempdir=$application_tempdir.", "Unable to create the temporary file<br />"); }

// --------------------
// Step 2/4: Copy remote file to the temporary file
// Open connection if needed
	if ($conn_id == "") {
		$resultArray = ftp_openconnection();
		$conn_id = getResult($resultArray);
		if ($conn_id == false)  { return putResult(false, "", "ftp_openconnection", "ftp_readfile > " . $resultArray['drilldown'], $resultArray['message']); }
		$leave_conn_open = "no";
	}

// Get file
	$selectedEntries[0] = $source;
	$ftpModes = ftpAsciiBinary($selectedEntries);
	$ftpMode = $ftpModes[0]; // FTP_ASCII or FTP_BINARY

	$success1 = ftp_get($conn_id, "$tempfilename", "$source", $ftpMode);
	if ($success1 == false) { return putResult(false, "", "ftp_get", "ftp_readfile > ftp_get: conn_id=$conn_id; tempfilename=$tempfilename, source=$source, ftpMode=$ftpMode.", "Unable to get the file <b>$source</b> from the FTP server and to save it as temporary file <b>$tempfilename</b>.<br />Check the permissions of the $application_tempdir directory.<br />"); }

// Close connection
	if ($leave_conn_open == "no") {
		$resultArray = ftp_closeconnection($conn_id);
		$success2 = getResult($resultArray);
	}

// --------------------
// Step 3/4: Read temporary file

// From the PHP manual:
// Note:  The mode may contain the letter 'b'. 
// This is useful only on systems which differentiate between binary and text 
// files (i.e. Windows. It's useless on Unix). If not needed, this will be 
// ignored. You are encouraged to include the 'b' flag in order to make your scripts 
// more portable.
// Thanks to Malte for bringing this to my attention !

	$handle = fopen($tempfilename, "rb"); // Open the file for reading only
	if ($handle == false) { return putResult(false, "", "fopen", "ftp_readfile > fopen: tempfilename=$tempfilename.", "Unable to open the temporary file<br />"); }

	clearstatcache(); // for filesize

	$string = fread($handle, filesize($tempfilename));
	if ($string == false) { return putResult(false, "", "fread", "ftp_readfile > fread: handle=$handle; tempfilename=$tempfilename.", "Unable to read the temporary file<br />"); }

	$success3 = fclose($handle);
	if ($success3 == false) { return putResult(false, "", "fclose", "ftp_readfile > fclose: handle=$handle", "Unable to close the temporary file<br />"); }

// --------------------
// Step 4/4: Delete temporary file
	$success4 = unlink($tempfilename);
	if ($success4 == false) { return putResult(false, "", "unlink", "ftp_readfile > unlink: tempfilename=$tempfilename.", "Unable to delete the temporary file<br />"); } 

	return putResult(true, $string, "", "", "");

} // End function ftp_readfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_writefile($conn_id, $directory, $file, $string) {

// --------------
// This function writes a string to a remote text file.
// If it already existed, it will be overwritten without asking for a confirmation.
// It can be used stand-alone (with conn_id = "") and then a new connection is opened
// Else it can also be used in a loop (with conn_id != false) and then the existing connection is opened
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

	$target= $directory . "/" . $file;


// Step 1/4: Create a temporary filename
	$tempfilename = tempnam($application_tempdir, "ftpwrite");
	if ($tempfilename == false)  { return putResult(false, "", "tempnam", "ftp_writefile > tempnam: application_tempdir=$application_tempdir.", "Unable to create the temporary file<br />"); }

// Step 2/4: Write the string to the temporary file
	$handle = fopen($tempfilename, "wb");
	if ($handle == false) { return putResult(false, "", "fopen", "ftp_writefile > fopen: tempfilename=$tempfilename.", "Unable to open the temporary file<br />"); }

	$success1 = fwrite($handle, $string);
	if ($success1 == false) { return putResult(false, "", "fwrite", "ftp_writefile > fwrite: handle=$handle; string=$string.", "Unable to write the string to the temporary file <b>$tempfilename</b>.<br />Check the permissions of the $application_tempdir directory.<br />"); }

	$success2 = fclose($handle);
	if ($success2 == false) { return putResult(false, "", "fclose", "ftp_writefile > fclose: handle=$handle.", "Unable to write to the temporary file<br />"); }

// Step 3/4: Copy temporary file to remote file
// Open connection if needed
	if ($conn_id == "") {
		$resultArray = ftp_openconnection();
		$conn_id = getResult($resultArray);
		if ($conn_id == false)  { return putResult(false, "", "ftp_openconnection", "ftp_writefile > " . $resultArray['drilldown'], $resultArray['message']); }
		$leave_conn_open = "no";
	}

// Put file
	$selectedEntries[0] = $target;
	$ftpModes = ftpAsciiBinary($selectedEntries);
	$ftpMode = $ftpModes[0]; // FTP_ASCII or FTP_BINARY

	$success3 = ftp_put($conn_id, $target, $tempfilename, $ftpMode);
	if ($success3 == false) { return putResult(false, "", "ftp_get", "ftp_writefile > ftp_put: conn_id=$conn_id; target=$target; tempfilename=$tempfilename, ftpMode=$ftpMode.", "Unable to put the file <b>$target</b> on the FTP server.<br />You may not have write permissions on the directory.<br />"); }

// Close connection
	if ($leave_conn_open == "no") {
		$resultArray = ftp_closeconnection($conn_id);
		$success2 = getResult($resultArray);
	}

// Step 4/4: Delete temporary file
	$success5 = unlink($tempfilename);
	if ($success5 == false) { return putResult(false, "", "unlink", "ftp_writefile > unlink: tempfilename=$tempfilename.", "Unable to delete the temporary file<br />"); } 

	return putResult(true, true, "", "", "");

} // End function ftp_writefile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_copymovedeletedirectory($conn_id_source, $conn_id_target, $directory, $entry, $targetdirectory, $targetentry, $copymovedelete, $divelevel) {

// --------------
// This function copies/moves/deletes a remote directory to a remote directory
// $ftpmode is used to specify whether ALL files are to be transferred in ASCII or BINARY mode
// $copymovedelete is used to specify whether to delete the source -- in case of move or delete, or not -- in case of copy
//
// sourcedirectory = /test
// subdirectorytomove = /d1
// targetdirectory = /test/target
// ==> /test/d1 will be copied/moved to /test/target/d1
//
// ---------
// | Steps |
// ---------
// 1 -- copy/move, divelevel 0    create targetdirectory/targetentry
//
// 2 -- all                       get a list of all subdirectories and files in /directory/entry
//
// 3 --                           for all the entries, do
//                                   directory
//                                      copy/move     create targetdirectory/targetentry/dirfilename
//                                      all           recursive algorithm: do the same with sourcedirectory="$directory/$entry", targetdirectory="$targetdirectory/$targetentry", entry="dirfilename"
//                                      move/delete   delete directory/entry/dirfilename
//                                   file
//                                      copy/move     copy or move directory/entry/dirfilename to local tempdir
//                                      copy/move     move from local tempdir to targetdirectory/targetentry/dirfilename 
//                                      delete        delete directory/entry/dirfilename 
//
// 4 -- move/delete, divelevel 0  delete directory/entry
// --------------


// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

// -------------------------------------------------------------------------
// Print text: begin
// -------------------------------------------------------------------------
	echo "<br /><ul>\n";
	echo "<li><u>Processing directory <b>$directory/$entry</b></u><br />\n";

// -------------------------------------------------------------------------
// Clean data
// -------------------------------------------------------------------------
	if ($targetdirectory == "/") { $targetdirectory = ""; }

// -------------------------------------------------------------------------
// Create new subdirectory $targetdirectory/$subdirectorytomove
// -------------------------------------------------------------------------
	if ($divelevel == 0 && $copymovedelete != "delete") {
		$success1 = ftp_mkdir($conn_id_target, "$targetdirectory/$targetentry");
//		if ($success1 == false) { return putResult(false, "", "ftp_mkdir1", "ftp_copymovedeletedirectory > ftp_mkdir: conn_id_target=$conn_id_target; targetdirectory/targetentry=$targetdirectory/$targetentry.", "Unable to create the subdirectory <b>$targetdirectory/$targetentry</b>. Either it already exists, either the parent directory does not exist. Continuing the copy/move process. Level=$divelevel.<br />"); }
		if ($success1 == false) { printWarningMessage("Unable to create the subdirectory <b>$targetdirectory/$targetentry</b>. Either it already exists, either the parent directory <b>$targetdirectory</b> does not exist. Continuing the copy/move process...<br />"); }
		if ($success1 == true)  { echo "Created target directory <b>$targetdirectory/$targetentry</b><br />"; }
	}

// -------------------------------------------------------------------------
// Get nice list of all subdirectories and files
// -------------------------------------------------------------------------
	$nicelist = ftp_getlist($conn_id_source, "$directory/$entry");

// -------------------------------------------------------------------------
// For all the subdirectories and files...
// -------------------------------------------------------------------------
	for ($i=1; $i<=count($nicelist); $i++) {
		$dirfileindicator = $nicelist[$i][0];
		$dirfilename = $nicelist[$i][1];
		$dirfilesize = $nicelist[$i][2];
		$dirfileowner = $nicelist[$i][3];
		$dirfilegroup = $nicelist[$i][4];
		$dirfilepermissions = $nicelist[$i][5];
		$dirfilemtime = $nicelist[$i][6];

// ------------------------------
// Subdirectory: create new remote subdirectory
// ------------------------------
		if ($dirfileindicator == "d") {
			if ($copymovedelete == "copy" || $copymovedelete == "move") { 
				$success2 = ftp_mkdir($conn_id_target, "$targetdirectory/$targetentry/$dirfilename");
//				if ($success2 == false) { return putResult(false, "", "ftp_mkdir2", "ftp_copymovedeletedirectory > ftp_mkdir: conn_id_target=$conn_id_target; targetdirectory/targetentry/dirfilename=$targetdirectory/$targetentry/$dirfilename. Level=$divelevel.", "Unable to create the subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b><br />"); }
				if ($success2 == false) { printWarningMessage("Unable to create the subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b>. It may already exist. Continuing the copy/move process...<br />"); }
				if ($success2 == true) { echo "<br />Created target subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b>.<br />"; }
			}

                        //--------------------------
			$divelevel = $divelevel +1;
			$resultArray = ftp_copymovedeletedirectory($conn_id_source, $conn_id_target, "$directory/$entry", $dirfilename, "$targetdirectory/$targetentry", $dirfilename, $copymovedelete, $divelevel);
			$success3 = getResult($resultArray);
//			if ($success3 == false) { return putResult(false, "", "ftp_copymovedeletedirectory", "ftp_copymovedeletedirectory > " . $resultArray['drilldown'] . " Level=$divelevel.", "Unable to $copymovedelete the directory <b>$directory/$entry/$dirfilename</b><br />"); }
			if ($success3 == false) { printWarningMessage($resultArray); }
			$divelevel = $divelevel -1;
                        //--------------------------

			if ($copymovedelete == "move" || $copymovedelete == "delete") { 
				$success4 = ftp_rmdir($conn_id_source, "$directory/$entry/$dirfilename");
//				if ($success4 == false) { return putResult(false, "", "ftp_rmdir", "ftp_copymovedeletedirectory > ftp_rmdir: conn_id_source=$conn_id_source; directory/entry/dirfilename=$directory/$entry/$dirfilename. Level=$divelevel.", "Unable to delete the directory <b>$targetdirectory/$targetentry/$dirfilename</b>. A possible reason is that it is not empty.<br />"); }
				if ($success4 == false) { printWarningMessage ("Unable to delete the subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b>. It may not be empty.<br />"); }
				if ($success4 == true) { echo "<br />Deleted subdirectory <b>$directory/$entry/$dirfilename</b>.<br />"; }
 			}
		}
// ------------------------------
// File:
// 1 - Get remote file to local temporary directory
// 2 - Put local file to remote target directory; choose move so the local file is deleted
// 3 - If move: delete remote file
// ------------------------------
		elseif ($dirfileindicator == "-") {
			$dirfilenameArray[0] = $dirfilename;
			$ftpmodeArray = ftpAsciiBinary($dirfilenameArray);
			$ftpmode = $ftpmodeArray[0];
			$resultArray = ftp_copymovedeletefile($conn_id_source, $conn_id_target, "$directory/$entry", $dirfilename, "$targetdirectory/$targetentry", $dirfilename, $ftpmode, $copymovedelete);
			$success5 = getResult($resultArray);
// Message is printed in function
//			if ($success5 == false) { printWarningMessage($resultArray); }

		}

	} // End for
// ------------------------------


// -------------------------------------------------------------------------
// Delete the directory source directory/subdirectorytomove
// -------------------------------------------------------------------------
	if ($divelevel == 0 && $copymovedelete != "copy") {
		$success8 = ftp_rmdir($conn_id_source, "$directory/$entry");
		if ($success8 == false) { printWarningMessage("Unable to delete the source directory <b>$directory/$entry</b> either because it does not exist, or because it is not empty."); }
	}

// -------------------------------------------------------------------------
// Print text: end
// -------------------------------------------------------------------------
	if ($divelevel == 0) {
		echo "<br /><div style=\"color: green; font-weight: bold;\">The directory <b>$directory/$entry</b> has been processed.<br /> Please read the messages above. The error and warning messages are in red.</div><br />\n";
	}
	echo "</ul>\n";

	return putResult(true, true, "", "", "");

} // End function ftp_copymovedeletedirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************










// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_copymovedeletefile($conn_id_source, $conn_id_target, $directory, $entry, $targetdirectory, $targetentry, $ftpmode, $copymovedelete) {

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;


// -------------------------------------------------------------------------
// Copy
// -------------------------------------------------------------------------

	if ($copymovedelete == "copy") {
//    Get file from remote sourcedirectory to local temp directory
		//             ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove);
		$resultArray = ftp_getfile($conn_id_source, $application_tempdir, "$entry.txt", $directory, $entry, $ftpmode, "copy");
		$success1 = getResult($resultArray);
		if ($success1 == false) { printWarningMessage($resultArray); }

//    Put file from local temp directory to remote targetdirectory; move instead of copy to delete the temporary file
//    Delete the temporary file
		//             ftp_putfile($conn_id, $localsourcedir, $localsourcefile, $remotetargetdir, $remotetargetfile, $ftpmode, $copymove);
		$resultArray = ftp_putfile($conn_id_target, $application_tempdir, "$entry.txt", $targetdirectory, $targetentry, $ftpmode, "move");
		$success2 = getResult($resultArray);
		if ($success2 == false) { printWarningMessage($resultArray); }

	} // End copy


// -------------------------------------------------------------------------
// Move
// -------------------------------------------------------------------------

	elseif ($copymovedelete == "move") {
//    Get file from remote sourcedirectory to local temp directory
//    !! Do not delete the source file yet, wait until the copy to the target is successful
		//             ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove);
		$resultArray = ftp_getfile($conn_id_source, $application_tempdir, "$entry.txt", $directory, $entry, $ftpmode, "copy");
		$success3 = getResult($resultArray);
		if ($success3 == false) { printWarningMessage($resultArray); }

//    Put file from local temp directory to remote targetdirectory; move instead of copy to delete the temporary file
//    Delete the temporary file
		//             ftp_putfile($conn_id, $localsourcedir, $localsourcefile, $remotetargetdir, $remotetargetfile, $ftpmode, $copymove);
		$resultArray = ftp_putfile($conn_id_target, $application_tempdir, "$entry.txt", $targetdirectory, $targetentry, $ftpmode, "move");
		$success4 = getResult($resultArray);
		if ($success4 == false) { printWarningMessage($resultArray); }

//    If ftp_putfile is successful, delete the source file
		if ($success4 == true) { 
			$success5 = ftp_delete($conn_id_source, "$directory/$entry");
			if ($success5 == false) { $resultArray['message'] = "Unable to delete the file <b>$directory/$entry</b>"; printWarningMessage($resultArray); }
		}

	} // End move


// -------------------------------------------------------------------------
// Delete
// -------------------------------------------------------------------------

	elseif ($copymovedelete == "delete") {
		$success6 = ftp_delete($conn_id_source, "$directory/$entry");
		if ($success6 == false) { $resultArray['message'] = "Unable to delete the file <b>$directory/$entry</b>"; printWarningMessage($resultArray); }
	} // End delete


// -------------------------------------------------------------------------
// Print message
// -------------------------------------------------------------------------
	if ($ftpmode == FTP_ASCII) { $printftpmode = "FTP_ASCII"; }
	elseif ($ftpmode == FTP_BINARY) { $printftpmode = "FTP_BINARY"; }

	if ($copymovedelete == "copy" && $success2 == true)       { echo "<br />The file <b>$directory/$entry</b> was successfully copied to <b>$targetdirectory/$targetentry</b> using FTP mode <b>$printftpmode</b>.<br />\n"; }
	elseif ($copymovedelete == "move" && $success4 == true)   { echo "<br />The file <b>$directory/$entry</b> was successfully moved to <b>$targetdirectory/$targetentry</b> using FTP mode <b>$printftpmode</b>.<br />\n"; }
	elseif ($copymovedelete == "delete" && $success6 == true) { echo "<br />The file <b>$directory/$entry</b> was successfully deleted.<br />\n"; }

	return putResult(true, true, "", "", "");

} // End function ftp_copymovedeletefile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************












// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove) {

// --------------
// This function copies or moves a remote file to a local file
// $ftpmode is used to specify whether the file is to be transferred in ASCII or BINARY mode
// $copymove is used to specify whether to delete (move) or not (copy) the local source
//
// True or false is returned
//
// The opposite function is ftp_putfile
// --------------

	$remotesource = $remotesourcedir . "/" . $remotesourcefile;
	$localtarget = $localtargetdir . "/" . $localtargetfile;

// Get file
	$success1 = ftp_get($conn_id, $localtarget, $remotesource, $ftpmode);
	if ($success1 == false) { return putResult(false, "", "ftp_get", "ftp_getfile > ftp_get: conn_id=$conn_id; localtarget=$localtarget; remotesource=$remotesource.", "Unable to copy remote file <b>$remotesource</b> to local file using FTP mode <b>$ftpmode</b><br />"); }

// Copy ==> do nothing
// Move ==> delete remote source file
	if ($copymove != "copy") {
		$success2 = ftp_delete($conn_id, $remotesource);
		if ($success2 == false) { return putResult(false, "", "ftp_delete", "ftp_getfile > ftp_delete: conn_id=$conn_id; remotesource=$remotesource.", "Unable to delete file <b>$remotesource</b><br />"); }
	}

	return putResult(true, true, "", "", "");

} // End function ftp_getfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_putfile($conn_id, $localsourcedir, $localsourcefile, $remotetargetdir, $remotetargetfile, $ftpmode, $copymove) {

// --------------
// This function copies or moves a local file to a remote file
// $ftpmode is used to specify whether the file is to be transferred in ASCII or BINARY mode
// $copymove is used to specify whether to delete (move) or not (copy) the local source
//
// True or false is returned
//
// The opposite function is ftp_getfile
// --------------

	$localsource = $localsourcedir . "/" . $localsourcefile;
	$remotetarget = $remotetargetdir . "/" . $remotetargetfile;

// In the function ftp_put, use FTP_BINARY without the double quotes, otherwhise ftp_put assumes FTP_ASCII
// DO NOT REMOVE THIS OR THE BINARY FILES WILL BE CORRUPTED (when copying, moving, uploading,...)
	if ($ftpmode == "FTP_BINARY") { $ftpmode = FTP_BINARY; } 

// Put local file to remote file
// int ftp_put (int ftp_stream, string remote_file, string local_file, int mode)
	$success1 = ftp_put($conn_id, $remotetarget, $localsource, $ftpmode);
	if ($success1 == false) { return putResult(false, "", "ftp_put", "ftp_putfile > ftp_put: conn_id=$conn_id; remotetarget=$remotetarget; localsource=$localsource.", "Unable to copy the local file to the remote file <b>$remotetarget</b> using FTP mode <b>$ftpmode</b><br />"); }
// If ftp_put fails, this function returns an error message and does not delete the temporary file.
// In case the file was copied, a copy exists in the source directory.
// In case the file was moved, the only copy is in the temporary directory, and so this has to be moved back to the source directory.

// Copy ==> do nothing
// Move ==> delete local source file
	if ($copymove != "copy") {
		$success2 = unlink($localsource);
		if ($success2 == false) { return putResult(false, "", "unlink", "ftp_putfile > unlink: localsource=$localsource.", "Unable to delete the local file<br />"); }
	}

	return putResult(true, true, "", "", "");

} // End function ftp_putfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_downloadfile($directory, $entry) {

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;


// -------------------------------------------------------------------------
// Note !!
// This function can handle multiple files (array selectedEntries), but it is always used to handle only one (entry)...
// -------------------------------------------------------------------------
$selectedEntries[0] = $entry;


// -------------------------------------------------------------------------
// Get remote file from FTP server to temp file
// -------------------------------------------------------------------------

// Parse the filenames to see in which FTP mode the files should be transferred
	$ftpModes = ftpAsciiBinary($selectedEntries);


// -------------------------------------------------------------------------
// Get files
// -------------------------------------------------------------------------

// Open connection
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }

	for ($k=0; $k<count($selectedEntries); $k++) {
//                     ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove)
		$resultArray = ftp_getfile($conn_id, $application_tempdir, "$selectedEntries[$k].txt", $directory, $selectedEntries[$k], $ftpModes[$k], "copy");
		$success1 = getResult($resultArray);

	} // end for

// Close connection
	$resultArray = ftp_closeconnection($conn_id);
	$success2 = getResult($resultArray);
// In PHP 4.2.3, ftp_quit does not return anything any more
//	if ($success2 == false) { printErrorMessage($resultArray, ""); }

// -------------------------------------------------------------------------
// Transfer temp file to browser
// -------------------------------------------------------------------------

	for ($k=0; $k<count($selectedEntries); $k++) {

		$fileType = getFileType($selectedEntries[$k]);

// --------------------
// Headers, see http://www.php.net/manual/en/function.header.php
// --------------------
// Content-type, for a complete list, see http://www.isi.edu/in-notes/iana/assignments/media-types/media-types
// Content-disposition: http://www.w3.org/Protocols/HTTP/Issues/content-disposition.txt

		if ($fileType == "TEXT") {
			header("Content-type: text/plain"); 
			header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); 
		}
		elseif ($fileType == "IMAGE") {
			if (ereg("(.*).jpg", $selectedEntries[$k], $regs) == true)     { header("Content-type: image/jpeg"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).png", $selectedEntries[$k], $regs) == true) { header("Content-type: image/png");  header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).gif", $selectedEntries[$k], $regs) == true) { header("Content-type: image/gif");  header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
		}
		elseif ($fileType == "ARCHIVE") {
			if (ereg("(.*).zip", $selectedEntries[$k], $regs) == true)     { header("Content-type: application/zip"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			else {
				header("Content-type: application/octet-stream");
				header("Content-Disposition: inline; filename=\"$selectedEntries[$k]\"");
			}
		}
		elseif ($fileType == "OFFICE") {
			if (ereg("(.*).doc", $selectedEntries[$k], $regs) == true)     { header("Content-type: application/msword"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).xls", $selectedEntries[$k], $regs) == true) { header("Content-type: application/vnd.ms-excel"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).ppt", $selectedEntries[$k], $regs) == true) { header("Content-type: application/vnd.ms-powerpoint"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).mpp", $selectedEntries[$k], $regs) == true) { header("Content-type: application/vnd.ms-project"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			else {
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\"");
			}
		}
		else { 
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); 
		}

// Size (allows the progress to be shown in the download popup of the browser)
		header("Content-Length: ". filesize("$application_tempdir/$selectedEntries[$k].txt")); 

// --------------------
// Send file
// --------------------

// From the PHP manual:
// Note:  The mode may contain the letter 'b'. 
// This is useful only on systems which differentiate between binary and text 
// files (i.e. Windows. It's useless on Unix). If not needed, this will be 
// ignored. You are encouraged to include the 'b' flag in order to make your scripts 
// more portable.
// Thanks to Malte for bringing this to my attention !

		$handle = fopen("$application_tempdir/$selectedEntries[$k].txt" , "rb"); 
		fpassthru($handle);

	} // End for

// -------------------------------------------------------------------------
// Delete temp files
// -------------------------------------------------------------------------
	for ($k=0; $k<count($selectedEntries); $k++) {
		$success2 = unlink("$application_tempdir/$selectedEntries[$k].txt");
	} // End for

} // End function ftp_downloadfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_downloadzipdirectory($conn_id, $directory, $selectedEntries, $zipdir, $divelevel) {

// --------------
// This function allows to download a zip of the selected files
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $zipfile;


// -------------------------------------------------------------------------
// Start: 
//    create zip file, 
//    open connection, 
//    for all selected subdirectories: recursive call
// -------------------------------------------------------------------------

	if ($divelevel == 0) {

// ------------------------------
// Create new zipfile
// ------------------------------
		$zipfile = new zipfile();


// ------------------------------
// Open connection
// ------------------------------
		$resultArray = ftp_openconnection();
		$conn_id = getResult($resultArray);
		if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }


// ------------------------------
// For all selected subdirectories...
// ------------------------------
		for ($k=0; $k<count($selectedEntries); $k++) {
			ftp_downloadzipdirectory($conn_id, "$directory/$selectedEntries[$k]", "", "$selectedEntries[$k]", 1);
		} // End for

	} // end if divelevel



// -------------------------------------------------------------------------
// Middle: 
//    get list of subdirectories and files
//       subdirectory --> recursive call
//       file --> add to archive
// -------------------------------------------------------------------------
	if ($divelevel > 0) {

// ------------------------------
// Get nice list of all subdirectories and files
// ------------------------------
		$nicelist = ftp_getlist($conn_id, $directory);


// ------------------------------
// For all the subdirectories and files...
// ------------------------------
		for ($i=1; $i<=count($nicelist); $i++) {
			$dirfileindicator = $nicelist[$i][0];
			$dirfilename = $nicelist[$i][1];
//			$dirfilesize = $nicelist[$i][2];
//			$dirfileowner = $nicelist[$i][3];
//			$dirfilegroup = $nicelist[$i][4];
//			$dirfilepermissions = $nicelist[$i][5];
//			$dirfilemtime = $nicelist[$i][6];

// ------------------------------
// Subdirectory --> recursive call
// File --> add to archive
// ------------------------------
			$divelevel = $divelevel + 1;

			if ($dirfileindicator == "d") { 
				$newzipdir = $zipdir . "/" . $dirfilename;
				ftp_downloadzipdirectory($conn_id, "$directory/$dirfilename", "", $newzipdir, $divelevel);
			}

			elseif ($dirfileindicator == "-") {
				$resultArray = ftp_readfile($conn_id, $directory, $dirfilename); 
				$text = getResult($resultArray); 
				if ($text != false)  { $zipfile->addFile($text, "$zipdir/$dirfilename"); }
			}

			$divelevel = $divelevel - 1;

		} // end for

	} // end if divelevel


// -------------------------------------------------------------------------
// End: 
//    close connection
//    send the archive to the browser
// -------------------------------------------------------------------------
	if ($divelevel == 0) {

// ------------------------------
// Close connection
// ------------------------------
		ftp_closeconnection($conn_id);


// ------------------------------
// Send the archive to the browser
// ------------------------------
		$timenow = time();
		$browser_agent = getBrowser("agent");

		header('Content-Type:  application/x-zip' );
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		if ($browser_agent == "IE") {
			header('Content-Disposition: inline; filename="net2ftp-'.$timenow.'.zip"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else {
			header('Content-Disposition: inline; filename="net2ftp-'.$timenow.'.zip"');
			header('Pragma: no-cache');
		}

		header("Content-Length: ". strlen($zipfile->file()));
		echo $zipfile->file();
		flush();

	} // end if divelevel

} // End function ftp_downloadzipdirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_downloadzipfile($directory, $selectedEntries) {

// --------------
// This function allows to download a zip of the selected files
// --------------

	$timenow = time();


// -------------------------------------------------------------------------
// Create new zipfile
// -------------------------------------------------------------------------
	$zipfile = new zipfile();

	$zipdir = $directory;
	if (substr($zipdir,0,1)!="/") $zipdir = "/".$zipdir;
	if (substr($zipdir,-1)!="/") $zipdir = $zipdir."/";

// -------------------------------------------------------------------------
// Open connection
// -------------------------------------------------------------------------
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }


// -------------------------------------------------------------------------
// Get files one by one and add them to the archive
// -------------------------------------------------------------------------
	for ($k=0; $k<sizeof($selectedEntries); $k++) {
		$resultArray = ftp_readfile($conn_id, $directory, $selectedEntries[$k]); // see filesystem.inc.php
		$text = getResult($resultArray); // see filesystem.inc.php
		if ($text != false)  { $zipfile->addFile($text,"net2ftp-$timenow$zipdir$selectedEntries[$k]"); }
	}


// -------------------------------------------------------------------------
// Close connection
// -------------------------------------------------------------------------
	ftp_closeconnection($conn_id);


// -------------------------------------------------------------------------
// Send the archive to the browser
// -------------------------------------------------------------------------
		$browser_agent = getBrowser("agent");

		header('Content-Type:  application/x-zip' );
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		if ($browser_agent == "IE") {
			header('Content-Disposition: inline; filename="net2ftp-'.$timenow.'.zip"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else {
			header('Content-Disposition: inline; filename="net2ftp-'.$timenow.'.zip"');
			header('Pragma: no-cache');
		}

		header("Content-Length: ". strlen($zipfile->file()));
		echo $zipfile->file();
		flush();

} // End function ftp_downloadzipfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function acceptFiles($uploadedFilesArray, $application_tempdir) {

// --------------
// This PHP function takes files that were just uploaded with HTTP POST, verifies if the size is smaller than
// a certain value, and moves them (move_uploaded_file) to a certain directory
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $max_upload_size;

	$moved_ok = 0;    // Index of the files that have been treated successfully
	$moved_notok = 0; // Index of the files that have been treated unsuccessfully
	for ($i=1; $i<=sizeof($uploadedFilesArray); $i++) {

// -------------------------------------------------------------------------
// 1 -- Get the data from the filesArray (for each file, its location, name, size, ftpmode
// -------------------------------------------------------------------------
		$file_temp = $uploadedFilesArray["$i"]["tmp_name"];
		$file_name = $uploadedFilesArray["$i"]["name"];
		$file_size = $uploadedFilesArray["$i"]["size"];

		if ($file_size > 0) {

// -------------------------------------------------------------------------
// 2 -- check size of the file
// -------------------------------------------------------------------------
			if ($file_size > $max_upload_size) { echo "<li> File nr $i $file_name ($file_size Bytes) is too big (>$max_upload_size). Breaking."; break; }

// -------------------------------------------------------------------------
// 3 -- upload and copy the file; if a file with the same name already exists, it is overwritten with the new file
// -------------------------------------------------------------------------
			$success2 = move_uploaded_file($file_temp, "$application_tempdir/$file_name.txt");
			if ($success2 == false) { echo "<li> File nr $i $file_name could not be moved\n"; }
			else { echo "<li> File $i <b>$file_name</b> is OK\n"; }

// -------------------------------------------------------------------------
// 4 -- if everything went fine, put file in acceptedFilesArray
// -------------------------------------------------------------------------
			if ($success2 == true) {
				$moved_ok = $moved_ok + 1;
				$acceptedFilesArray[$moved_ok] = $file_name;
			}
			else { $moved_notok = $moved_notok + 1; }

		} // End if file_size

	} // End for

	if     ($moved_ok == 0 && $moved_notok == 0) { return putResult(false, "", "acceptFiles", "acceptFiles", "You did not provide any file to upload."); }
	elseif ($moved_notok > 0)                    { return putResult(false, "", "acceptFiles", "acceptFiles > move_uploaded_file.", "Unable to move the uploaded file to the temp directory.<br /><br />The administrator has to <b>chmod 777</b> the /temp directory of net2ftp."); }
	else                                         { return putResult(true, $acceptedFilesArray, "", "", ""); }

} // End function acceptFiles

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftp_transferfiles($filesArray, $application_tempdir, $targetDir) {

// --------------
// This PHP function takes a file that was uploaded from a client computer via a browser to the web server, 
// and puts it on another FTP server
// --------------


// Determine which FTP mode should be used
	$ftpModes = ftpAsciiBinary($filesArray);

// Open connection
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { return putResult(false, "", "ftp_openconnection", "ftp_uploadfiles > " . $resultArray['drilldown'], $resultArray['message']); }

// Put files
	for ($i=1; $i<=sizeof($filesArray); $i++) {

		if ($ftpModes[$i] == FTP_ASCII) { $printftpmode = "FTP_ASCII"; }
		elseif ($ftpModes[$i] == FTP_BINARY) { $printftpmode = "FTP_BINARY"; }

		$resultArray = ftp_putfile($conn_id, "$application_tempdir", "$filesArray[$i].txt", $targetDir, $filesArray[$i], $ftpModes[$i], "move");
		$success2 = getResult($resultArray);
		if ($success2 == false) { echo "<li> File $i <b>$filesArray[$i]</b> could not be transferred to the FTP server\n";}
		if ($success2 == true)  { echo "<li> File $i <b>$filesArray[$i]</b> has been transferred to the FTP server using FTP mode <b>$printftpmode</b>\n"; }

	} // End for

// Close connection
	$resultArray = ftp_closeconnection($conn_id);
	$success2 = getResult($resultArray);

	return putResult(true, true, "", "", "");

} // End function ftp_transferfiles

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftp_unziptransferfiles($archivesArray, $use_folder_names, $application_tempdir, $directory) {

// --------------
// Decompress the archives and transfer the files to the FTP server
// If $use_folder_names == "yes" then create subdirectories
// If it is set to no, then transfer everything in the archive to the directory
// --------------


// -------------------------------------------------------------------------
// Open connection
// -------------------------------------------------------------------------
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { return putResult(false, "", "ftp_openconnection", "ftp_uploadfiles > " . $resultArray['drilldown'], $resultArray['message']); }


	for ($i=1; $i<=sizeof($archivesArray); $i=$i+1) {

// -------------------------------------------------------------------------
// Determine the type of archive depending on the filename extension
// -------------------------------------------------------------------------
		$archive = $application_tempdir . "/" . $archivesArray[$i] . ".txt";
		$archive_type = get_filename_extension($archivesArray[$i]);

// -------------------------------------------------------------------------
// ZIP archive
// -------------------------------------------------------------------------
		if ($archive_type == "zip") {

// ------------------------------
// Open the archive
// ------------------------------
			$zip = zip_open($archive);
			if ($zip == false) { return putResult(false, "", "zip_open", "ftp_unziptransferfiles > zip_open: filename=$archivesArray[$i].", "Unable to open the archive <b>$archivesArray[$i]</b><br />"); }

			while ($zip_entry = zip_read($zip)) { 

				$zip_entry_name = zip_entry_name($zip_entry); 
				$zip_entry_filesize = zip_entry_filesize($zip_entry); 
				$zip_entry_compressedsize = zip_entry_compressedsize($zip_entry); 
				$zip_entry_compressionmethod = zip_entry_compressionmethod($zip_entry); 

// ------------------------------
// Go to the next entry if the filesize is zero
// ------------------------------
				if ($zip_entry_filesize == 0) { continue; }

// ------------------------------
// From the zip_entry_name, determine the path and the real filename
// For example:
// 	zip_entry_name = subdir1/subdir2/file.txt
//	==> 	directory where the file has to be put = directory/subdir1/subdir2
//		filename = file.txt
// ------------------------------
// Remove leading and trailing "/"
				$zip_entry_name = stripDirectory($zip_entry_name);

// Break down into parts
// parts[0] contains the first part, parts[1] the second,...
				$zip_entry_name_subdirectories = explode("/", $zip_entry_name);
				$zip_entry_name_filename = array_pop($zip_entry_name_subdirectories);

// ------------------------------
// Create the subdirectory if needed
// ------------------------------
				if ($use_folder_names == "yes") {

					$targetdirectory = $directory;

					for ($j=0; $j<sizeof($zip_entry_name_subdirectories); $j=$j+1) {

// Create the targetdirectory string
						$targetdirectory = $targetdirectory . "/" . $zip_entry_name_subdirectories[$j];

// Check if the subdirectories exist
						$result = @ftp_chdir($conn_id, $targetdirectory);
						if ($result == false) {
							$resultArray = ftp_newdirectory($conn_id, $targetdirectory);
							$success = getResult($resultArray);
							if ($success == false)  { echo "<li> Could not create directory <b>$targetdirectory</b><br />\n"; }
							else { echo "<li> Created directory <b>$targetdirectory</b><br />\n"; }
						} // end if

					} // end for

				} // end if 

// ------------------------------
// Read the zip file entry content
// ------------------------------
				if (zip_entry_open($zip, $zip_entry, "r")) { 
//					echo "File Contents:<br /><br />\n"; 
					$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)); 
//					echo $buf;

// ------------------------------
// Write content to a file
// ------------------------------
					if ($use_folder_names == "yes") {
						$resultArray = ftp_writefile($conn_id, $targetdirectory, $zip_entry_name_filename, $buf); 
						$result = getResult($resultArray); 
						if ($result == false)  { echo "<li> Could not put file <b>$zip_entry_name_filename</b> to directory <b>$targetdirectory</b><br />"; }
						else { echo "<li> Transferred file <b>$zip_entry_name_filename</b> to directory <b>$targetdirectory</b><br />\n"; }
					}
					else {
						$resultArray = ftp_writefile($conn_id, $directory, $zip_entry_name_filename, $buf); 
						$result = getResult($resultArray); 
						if ($result == false)  { echo "<li> Could not write the file <b>$zip_entry_name_filename</b> to the directory <b>$directory</b><br />\n"; }
						else { echo "<li> Transferred file <b>$zip_entry_name_filename</b> to directory <b>$directory</b><br />\n"; }
					}

// ------------------------------
// Close zip file entry
// ------------------------------
					zip_entry_close($zip_entry); 
					echo "\n"; 

				}  // end if

			} // end while

// ------------------------------
// Close the archive
// ------------------------------
			zip_close($zip);

		} // end if

// -------------------------------------------------------------------------
// GZ archive
// -------------------------------------------------------------------------
		elseif ($archive_type == "gz") {
			echo "<li> Archive <b>$archivesArray[$i]</b> was not processed because net2ftp does not support gz archives yet.<br />\n";
		} // end elseif

// -------------------------------------------------------------------------
// Other filename extensions
// -------------------------------------------------------------------------
		else {
			echo "<li> Archive <b>$archivesArray[$i]</b> was not processed because its filename extension was not recognized. Only <b>zip</b> archives are supported at the moment; other archive types like <b>gz</b> will be added later.<br />\n";
		} // end else

// -------------------------------------------------------------------------
// Delete the uploaded archives
// -------------------------------------------------------------------------
		$success2 = unlink($archive);
		if ($success2 == false) { 
			$message = "Unable to delete the temporary file <b>$archive</b>.<br />";
			printWarningMessage($message);
		} // end if

	} // End for

// -------------------------------------------------------------------------
// Close connection
// -------------------------------------------------------------------------
	$resultArray = ftp_closeconnection($conn_id);
	$success3 = getResult($resultArray);

	return putResult(true, true, "", "", "");

} // End function ftp_unziptransferfiles

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_mysite($conn_id, $command) {

// --------------
// This function sends a site command to the FTP server
// Note:
//    - These commands vary a lot depending on the FTP server type
//    - PHP does not return any result other than TRUE or FALSE
// --------------

	$success1 = ftp_site($conn_id, $command);
	if ($success1 == false) { return putResult(false, "", "ftp_site", "ftp_mysite > ftp_site: conn_id=$conn_id; command=$command.", "Unable to execute site command <b>$command</b>.<br />"); }

	return putResult(true, true, "", "", "");

} // End function ftp_mysite
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function upDir($directory) {

// --------------
// This function takes a directory string and returns the parent directory string
// --------------
// directory = /david/cv
// parts = Array ( [0] => [1] => david [2] => cv ) 
// count($parts) = 3

	$parts = explode("/", $directory);

	$parentdirectory = "";
	for ($i=1; $i<count($parts)-1; $i++) {
		$parentdirectory = $parentdirectory . "/" . $parts[$i];
	}

	return $parentdirectory;

} // End function upDir
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function stripDirectory($directory) {

// --------------
// Returns the directory in the format home/dh1234/test (NO leading /, NO trailing /)
// --------------

	$directory = trim($directory);

	$firstchar = substr($directory, 0, 1);
	$lastchar  = substr($directory, strlen($directory)-1, 1);

// Remove a / in front if needed
	if ($firstchar == "/") { $directory= substr($directory, 1, strlen($directory)-1); }
// Remove a / at the end if needed
	if ($lastchar  == "/") { $directory= substr($directory, 0, strlen($directory)-1); }

	return $directory;

} // end stripDirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function glueDirectories($part1, $part2) {

// --------------
// Returns the 2 dirs glued together in the format /home/dh1234/test (leading /, NO trailing /)
// --------------

	$part1 = stripDirectory($part1);
	$part2 = stripDirectory($part2);

	if (strlen($part1)>0 && strlen($part2)>0) {
		return $part1 . "/" . $part2;
	}

	elseif ((strlen($part1)<1 || $part1 == "/")   &&   (strlen($part2)>0)) {
		return "/" . $part2;
	}
	elseif ((strlen($part2)<1 || $part2 == "/")   &&   (strlen($part1)>0)) {
		return "/" . $part1;
	}
	else {
		return "";
	}
} // end glueDirectories
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function get_filename_extension($filename) {

// --------------
// This function returns the extension of a filename:
// 	filename.ext1.ext2.ext3 --> ext3
// 	filename --> filename
// 	.filename --> filename
// It also converts the result to lower case:
// 	filename.ext1.EXT2 --> ext2
// --------------

	if (ereg("(.*)[\.]([^\.]*)", $filename, $regs) == true) {

		// Any character
		// Followed by a dot
		// Followed by any character except a dot

		$first = "$regs[1]";
		$last = "$regs[2]";
	}
	else {
		$last == $filename;
	}

	return strtolower($last);

} // End get_filename_extension

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftpAsciiBinary($filenameArray) {

// --------------
// Checks the extension of a file to see if it should be transferred in ASCII or Binary mode
//
//	Default: FTP_ASCII
//	Exceptions: FTP_BINARY (see list)
//	No extension: FTP_ASCII
//	A file with more than 1 dot: the last extension is taken into account
//
// --------------

	for ($k=0; $k<=count($filenameArray); $k++) {
// k=0 to k<=count so that this function would be able to handle arrays both from 0 to n-1 and from 1 to n.

		$last = get_filename_extension($filenameArray[$k]);

		if ($last == "png"  || 
		$last == "jpg"  || 
		$last == "jpeg" || 
		$last == "gif"  ||
		$last == "bmp"  ||
		$last == "tif"  ||
		$last == "tiff" ||

		$last == "exe"  || 
		$last == "com"  ||
		
		$last == "doc"  || 
		$last == "xls"  || 
		$last == "ppt"  || 
		$last == "mdb"  || 
		$last == "vsd"  || 
		$last == "mpp"  ||

		$last == "zip"  || 
		$last == "tar"  || 
		$last == "gz"   || 
		$last == "arj"  || 
		$last == "arc"  ||
		$last == "bin"  || 

		$last == "mov"  || 
		$last == "mpg"  || 
		$last == "mpeg" ||
		$last == "ram"  ||
		$last == "rm"   ||
		$last == "qt"   ||

		$last == "swf"  ||
		$last == "fla"  ||

		$last == "pdf"  ||
		$last == "ps"   ||

		$last == "wav" )	{ $ftpModes[$k] = FTP_BINARY; }
		else 			{ $ftpModes[$k] = FTP_ASCII; }

	} // End for

	return $ftpModes;

} // end ftpAsciiBinary

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function getFileType($filename) {

// --------------
// Checks the extension of a file to determine what should be done with it in the View and Edit functions
// Default: TEXT
// Exceptions (see list below): IMAGE, EXECUTABLE, OFFICE, ARCHIVE
// --------------

	$last = get_filename_extension($filename);

	if (	$last == "png"  || 
		$last == "jpg"  || 
		$last == "jpeg" || 
		$last == "gif"  ||
		$last == "bmp"  ||
		$last == "tif"  ||
		$last == "tiff"     ) { return "IMAGE"; }

	elseif ($last == "exe"  || 
		$last == "com"      ) { return "EXECUTABLE"; }

	elseif ($last == "doc"  || 
		$last == "xls"  || 
		$last == "ppt"  || 
		$last == "mdb"  || 
		$last == "vsd"  || 
		$last == "mpp"      ) { return "OFFICE"; }

	elseif ($last == "zip"  || 
		$last == "tar"  || 
		$last == "gz"   || 
		$last == "arj"  || 
		$last == "arc"      ) { return "ARCHIVE"; }

	elseif ($last == "bin"  || 

		$last == "mov"  || 
		$last == "mpg"  || 
		$last == "mpg"  ||
		$last == "ram"  ||
		$last == "rm"   ||
		$last == "qt"   ||

		$last == "swf"  ||
		$last == "fla"  ||

		$last == "pdf"  ||
		$last == "ps"   ||

		$last == "wav"       ){ return "OTHER"; }

	else 			     	    { return "TEXT"; }


} // end getFileType

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function cleanFtpserver($ftpserver) {

// --------------
// Input: " ftp://something.domainname.com:123/directory/file "
// Output: "something.domainname.com"
// --------------

// Remove unvisible characters in the beginning and at the end
	$cleaned = trim($ftpserver);

// Remove possible "ftp://"
	if (ereg("[ftpFTP]{2,4}[:]{1}[/\\]{1,2}(.*)", $cleaned, $regs) == true) {
		$cleaned = "$regs[1]";
	}

// Remove a possible port nr ":123"
	if (ereg("(.*)[:]{1}[0-9]+", $cleaned, $regs) == true) {
		$cleaned = "$regs[1]";
	}

// Remove a possible trailing / or \ 
// Remove a possible directory and file "/directory/file"
	if (ereg("([^/^\\]*)[/\\]{1,}.*", $cleaned, $regs) == true) {
		// Any characters except / and except \
		// Followed by at least one / or \
		// Followed by any characters
		$cleaned = "$regs[1]";
	}

	return $cleaned;

} // end cleanFTPserver

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function cleanDirectory($directory) {

// --------------
// Input: "/dir1/dir2/dir3/../../dir4/dir5"
// Output: "/dir1/dir4/dir5"
// --------------

// Nothing to do if the directory is the root directory
	if ($directory == "" || $directory == "/") { return $directory; }

// Remove leading and trailing "/"
	$directory = stripDirectory($directory);

// Break down into parts
// directoryparts[0] contains the first part, directoryparts[1] the second,...
	$directoryparts = explode("/", $directory);

// Start from the end
// If you encounter N times a "..", do not take into account the next N parts which are not ".."
// Example: "/dir1/dir2/dir3/../../dir4/dir5"  ---->  "/dir1/dir4/dir5"
	$dubbledotcounter = 0;
	$newdirectory = "";
	for ($i=sizeof($directoryparts)-1; $i>=0; $i = $i - 1) {
		if ($directoryparts[$i] == "..") { $doubledotcounter = $doubledotcounter + 1; }
		else {  
			if ($doubledotcounter == 0) { $newdirectory = $directoryparts[$i] . "/" . $newdirectory; }    // Add the new part in front
			elseif ($doubledotcounter > 0) { $doubledotcounter = $doubledotcounter - 1; }                 // Don't add the part, and reduce the counter by 1
		}
	}

	$newdirectory = "/" . stripDirectory($newdirectory);

	return $newdirectory;

} // end cleanDirectory

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



?>