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
function manage($state2, $directory, $entry, $selectedEntries, $newNames, $dirorfile, $formresult, $chmodStrings, $targetDirectories, $copymovedelete, $text, $wysiwyg, $uploadedFilesArray, $uploadedArchivesArray, $use_folder_names, $command) {

// --------------
// This function allows to manage a file: view/edit/rename/delete
// The real action is done in subfunctions
// --------------

// Check that at least one entry was chosen
	if (is_array($selectedEntries) == false && ($state2 == "renamedirectory" || $state2 == "chmoddirectory" || $state2 == "copydirectory" || $state2 == "movedirectory" || $state2 == "deletedirectory" || $state2 == "renamefile" || $state2 == "chmodfile" || $state2 == "copyfile" || $state2 == "movefile" || $state2 == "deletefile" || $state2 == "downloadzip")) {
		$resultArray['message'] = "Please select at least one directory or file !";
  		printErrorMessage($resultArray, "exit");
	}

	switch ($state2) {
// Directories
		case "renamedirectory":
			renameentry($directory, $selectedEntries, $newNames, "directory", $formresult);	
		break;
		case "chmoddirectory":
			chmodentry($directory, $selectedEntries, $chmodStrings, "directory", $formresult);
		break;
		case "copydirectory":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "copy", "directory", $formresult);
		break;
		case "movedirectory":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "move", "directory", $formresult);
		break;
		case "deletedirectory":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "delete", "directory", $formresult);
		break;
		case "newdirectory":
			newdirectory($directory, $newNames, $formresult);
		break;
		case "downloadzipdirectory":
			// Things are done in httpheaders.inc.php
		break;
// Files
		case "view":
			view($directory, $entry);
		break;
		case "edit":
			edit($directory, $entry, $text, $wysiwyg, $formresult);
		break;
		case "renamefile":
			renameentry($directory, $selectedEntries, $newNames, "file", $formresult);
		break;
		case "chmodfile":
			chmodentry($directory, $selectedEntries, $chmodStrings, "file", $formresult);
		break;
		case "copyfile":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "copy", "file", $formresult);
		break;
		case "movefile":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "move", "file", $formresult);
		break;
		case "deletefile":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "delete", "file", $formresult);
		break;
		case "downloadfile":
			// Things are done in httpheaders.inc.php
		break;
		case "downloadzipfile":
			// Things are done in httpheaders.inc.php
		break;
		case "newfile":
			edit($directory, $newNames[0], $text, $wysiwyg, $formresult);
		break;
		case "uploadfile":
			uploadfile($directory, $uploadedFilesArray, $uploadedArchivesArray, $use_folder_names, $formresult);
		break;
// Advanced options
//		case "advanced":
//			printAdvancedFunctions($directory);	
//		break;
//		case "site":
//			sendsitecommand($directory, $command, $formresult);
//		break;
//		case "apache":
//			apache($directory, $command, $formresult);
//		break;
//		case "mysql":
//			mysql($directory, $command, $formresult);
//		break;
// Default
		default:
			$resultArray['message'] = "Unexpected state2 string. Exiting."; 
  			printErrorMessage($resultArray, "exit");
  		break;

		} // End switch

} // End function manage
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function renameentry($directory, $selectedEntries, $newNames, $dirorfile, $formresult) {

// --------------
// This function allows to rename a directory or file $entry to $newentry
// --------------

// -------------------------------------------------------------------------
// Initial checks
// -------------------------------------------------------------------------

	if ($dirorfile != "directory") { $dirorfile = "file"; }

	if ($dirorfile == "directory") { printTitle("Rename directory"); }
	elseif ($dirorfile == "file")  { printTitle("Rename file"); }

	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";


// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {
		echo "<form name=\"RenameForm\" id=\"RenameForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
		if ($dirorfile == "directory") { echo "<input type=\"hidden\" name=\"state2\" value=\"renamedirectory\" />\n"; }
		elseif ($dirorfile == "file")  { echo "<input type=\"hidden\" name=\"state2\" value=\"renamefile\" />\n"; }
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
		printBackInForm($directory);
		echo "<input type=\"submit\" class=\"button\" value=\"Rename\" /><br /><br />\n";

		for ($k=0; $k<count($selectedEntries); $k++) {
			echo "<input type=\"hidden\" name=\"selectedEntries[]\" value=\"$selectedEntries[$k]\" />\n";
			echo "Old name: <b>$selectedEntries[$k]</b><br />\n";
			echo "New name: <input type=\"text\" name=\"newNames[]\" value=\"$selectedEntries[$k]\" /><br /><br />\n";
		} // End for

		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	elseif ($formresult == "result") {

		printBack($directory);

// Open connection
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { printErrorMessage($resultArray, "exit"); }

// Rename files
		for ($k=0; $k<count($selectedEntries); $k++) {
			if (strstr($selectedEntries[$k], "..") != false) {
				echo "The new filename may not contain any dots. The file was not renamed to <b>$selectedEntries[$k]</b>.<br />";
				break;
			}
			$resultArray = ftp_myrename($conn_id, $directory, $selectedEntries[$k], $newNames[$k]);	// filesystem.inc.php
			$success = getResult($resultArray);
			if ($success ==	false) { printErrorMessage($resultArray, ""); break; }
			else { echo "<b>$selectedEntries[$k]</b> was successfully renamed to <b>$newNames[$k]</b><br />"; }
		} // End for

// Close connection
	ftp_closeconnection($conn_id);

	} // End if elseif (form or result)

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";

} // End function renameentry
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function chmodentry($directory, $selectedEntries, $chmodStrings, $dirorfile, $formresult) {

// --------------
// This function allows to chmod a directory or file
// The initial permissions are contained in chmodstring, and are coming from the browse view
// The permissions to be set are contained in chmodoctal
// --------------

// -------------------------------------------------------------------------
// Initial checks
// -------------------------------------------------------------------------

	if ($dirorfile != "directory") { $dirorfile = "file"; }

	if ($dirorfile == "directory") { printTitle("Chmod directory"); }
	elseif ($dirorfile == "file")  { printTitle("Chmod file"); }

	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {

		echo "<form name=\"ChmodForm\" id=\"ChmodForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
		if ($dirorfile == "directory") { echo "<input type=\"hidden\" name=\"state2\" value=\"chmoddirectory\" />\n"; }
		elseif ($dirorfile == "file")  { echo "<input type=\"hidden\" name=\"state2\" value=\"chmodfile\" />\n"; }
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
		printBackInForm($directory);
		echo "<input type=\"submit\" class=\"button\" value=\"Chmod\" /><br /><br />\n";

		for ($k=0; $k<count($selectedEntries); $k++) {
			echo "<input type=\"hidden\" name=\"selectedEntries[]\" value=\"$selectedEntries[$k]\" />\n";

			if ($dirorfile == "directory") { echo "Set the permissions of directory <b>$selectedEntries[$k]</b> to: <br />\n"; }
			elseif ($dirorfile == "file")  { echo "Set the permissions of file <b>$selectedEntries[$k]</b> to: <br />\n"; }

			$owner_chmod = 0;
			if (substr($chmodStrings[$k], 0, 1) == "r") { $owner_chmod+=4; $owner_read = "checked=\"checked\""; }
			else $owner_read = "";
			if (substr($chmodStrings[$k], 1, 1) == "w") { $owner_chmod+=2; $owner_write = "checked=\"checked\"";  }
			else $owner_write = "";
			if (substr($chmodStrings[$k], 2, 1) == "x") { $owner_chmod+=1; $owner_execute = "checked=\"checked\"";  }
			else $owner_execute = "";

			$group_chmod = 0;
			if (substr($chmodStrings[$k], 3, 1) == "r") { $group_chmod+=4; $group_read = "checked=\"checked\"";  }
			else $group_read = "";
			if (substr($chmodStrings[$k], 4, 1) == "w") { $group_chmod+=2; $group_write = "checked=\"checked\"";  }
			else $group_write = "";
			if (substr($chmodStrings[$k], 5, 1) == "x") { $group_chmod+=1; $group_execute = "checked=\"checked\"";  }
			else $group_execute = "";

			$other_chmod = 0;
			if (substr($chmodStrings[$k], 6, 1) == "r") { $other_chmod+=4; $other_read = "checked=\"checked\"";  }
			else $other_read = "";
			if (substr($chmodStrings[$k], 7, 1) == "w") { $other_chmod+=2; $other_write = "checked=\"checked\"";  }
			else $other_write = "";
			if (substr($chmodStrings[$k], 8, 1) == "x") { $other_chmod+=1; $other_execute = "checked=\"checked\"";  }
			else $other_execute = "";

			echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
			echo "	<tr> \n";
			echo "		<td>Owner:</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][owner_read]\" value=\"4\" $owner_read>Read</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][owner_write]\" value=\"2\" $owner_write>Write</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][owner_execute]\" value=\"1\" $owner_execute>Execute</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>Group:</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][group_read]\" value=\"4\" $group_read>Read</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][group_write]\" value=\"2\" $group_write>Write</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][group_execute]\" value=\"1\" $group_execute>Execute</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>Everyone:</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][other_read]\" value=\"4\" $other_read>Read</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][other_write]\" value=\"2\" $other_write>Write</td>\n";
			echo "		<td><input type=\"checkbox\" name=\"chmodStrings[$k][other_execute]\" value=\"1\" $other_execute>Execute</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";

			echo "<br /><br />\n";

		} // End for

		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	elseif ($formresult == "result") {

		printBack($directory);

// Open connection
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { printErrorMessage($resultArray, "exit"); }

// Chmod entries
		for ($k=0; $k<count($selectedEntries); $k++) {

			$ownerOctal = $chmodStrings[$k]['owner_read'] + $chmodStrings[$k]['owner_write'] + $chmodStrings[$k]['owner_execute'];
			$groupOctal = $chmodStrings[$k]['group_read'] + $chmodStrings[$k]['group_write'] + $chmodStrings[$k]['group_execute'];
			$otherOctal = $chmodStrings[$k]['other_read'] + $chmodStrings[$k]['other_write'] + $chmodStrings[$k]['other_execute'];

			$chmodOctal = $ownerOctal . $groupOctal . $otherOctal;

			if ($chmodOctal > 777 || $chmodOctal < 0) {
				$resultArray['message'] = "The chmod nr <b>$chmodOctal</b> is out of the range 000-777. Please try again.\n";
				printErrorMessage($resultArray, "exit");
			}

			$resultArray = ftp_mychmod($conn_id, $directory, $selectedEntries[$k], $chmodOctal);	// filesystem.inc.php
			$success = getResult($resultArray);
			if ($success ==	false) { printErrorMessage($resultArray, ""); }
			else { echo "The permissions on <b>$directory/$selectedEntries[$k]</b> were successfully changed to <b>$chmodOctal</b>.<br />"; }

		} // End for

// Close connection
	ftp_closeconnection($conn_id);

	} // End if elseif (form or result)

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";


} // End function chmodentry
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, $copymovedelete, $dirorfile, $formresult) {

// --------------
// This function allows to copy or move a directory or file
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------

global $input_ftpserver2, $input_username2;

// -------------------------------------------------------------------------
// Initial checks
// -------------------------------------------------------------------------
	if (($copymovedelete != "move" && $copymovedelete != "delete")) { $copymovedelete = "copy"; }
	if ($dirorfile != "directory") { $dirorfile = "file"; }

	if ($copymovedelete == "copy" && $dirorfile == "directory") { printTitle("Copy directories"); }
	elseif ($copymovedelete == "copy" && $dirorfile == "file") { printTitle("Copy files"); }
	elseif ($copymovedelete == "move" && $dirorfile == "directory") { printTitle("Move directories"); }
	elseif ($copymovedelete == "move" && $dirorfile == "file") { printTitle("Move files"); }
	elseif ($copymovedelete == "delete" && $dirorfile == "directory") { printTitle("Delete directories"); }
	elseif ($copymovedelete == "delete" && $dirorfile == "file") { printTitle("Delete files"); }

	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -------------------------------------------------------------------------
// Show form
// -------------------------------------------------------------------------
	if ($formresult != "result") {

// Hidden stuff
		echo "<form name=\"CopyMoveDeleteForm\" id=\"CopyMoveDeleteForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
		echo "<input type=\"hidden\" name=\"state2\" value=\"$copymovedelete$dirorfile\" />\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
		echo "<input type=\"hidden\" name=\"copymovedelete\" value=\"$copymovedelete\" />\n";
		echo "<input type=\"hidden\" name=\"dirorfile\" value=\"$dirorfile\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
		printBackInForm($directory);
// Submit buttons
		if ($copymovedelete == "copy")       { echo "<input type=\"submit\" class=\"button\" value=\"Copy\" /><br /><br />\n"; }
		elseif ($copymovedelete == "move")   { echo "<input type=\"submit\" class=\"button\" value=\"Move\" /><br /><br />\n"; }
		elseif ($copymovedelete == "delete") { echo "<input type=\"submit\" class=\"button\" value=\"Delete\" /><br /><br />\n"; }

// Title and text
//		if ($copymovedelete == "copy") { echo "Copy $dirorfile<br /><br />\n"; }
//		elseif ($copymovedelete == "move")   { echo "Move $dirorfile<br /><br /><br />\n"; }
		if ($copymovedelete == "delete" && $dirorfile == "directory") { echo "Are you sure you want to delete these directories</b>?<br />Note that all its subdirectories and files will also be deleted!<br /><br />\n"; }
		elseif ($copymovedelete == "delete" && $dirorfile == "file") { echo "Are you sure you want to delete these files?<br /><br />\n"; }

// Header: directory and button to copy text to all target directory textboxes -- only for copy/move
		if ($copymovedelete != "delete") {
			echo "<table style=\"border-color: #000066; border-style: solid; border-width: 1px; padding: 10px;\">\n";
			echo "<tr><td>\n";
			echo "<input type=\"button\" class=\"extralongbutton\" value=\"Set all targetdirectories\" onClick=\"CopyToAll(document.CopyMoveDeleteForm)\" /> &nbsp; \n";
			echo "<input type=\"text\" name=\"headerDirectory\" value=\"$directory\" />\n";
			printDirectoryTreeLink($directory, "CopyMoveDeleteForm.headerDirectory");
			echo "<div style=\"font-size: 65%\">To set a common target directory, enter that target directory in the textbox above and click on the button \"Set all targetdirectories\". Note: the target directory must already exist before anything can be copied into it.</div>\n";
			echo "</td></tr>\n";
			echo "</table>\n";
		} // End if

		echo "<br />";

// Header: option to copy/move to a different FTP server -- only for copy/move
		if ($copymovedelete != "delete") {

			echo "<table style=\"border-color: #000066; border-style: solid; border-width: 1px; padding: 10px; margin-right: 100px; margin-bottom: 30px;\">\n";
			echo "<tr>\n";
			echo "<td valign=\"top\" width=\"40%\">Different target FTP server:</td>\n";
			echo "<td>\n";
			echo "<input type=\"text\" class=\"input\" name=\"input_ftpserver2\" value=\"$net2ftpcookie_ftpserver\" /> port \n";
			if ($net2ftpcookie_ftpserverport != "") {
				echo "<input type=\"text\" class=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport2\" value=\"$net2ftpcookie_ftpserverport\" />\n";
			}
			else {
				echo "<input type=\"text\" class=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport2\" value=\"21\" />\n";
			}

			echo "</td>\n";
			echo "</tr>\n";

			echo "<tr>\n";
			echo "<td>Username:</td>\n";
			echo "<td><input type=\"text\" class=\"input\" name=\"input_username2\" value=\"$net2ftpcookie_username\" /></td>\n";
			echo "</tr>\n";

			echo "<tr>\n";
			echo "<td>Password:</td>\n";
			echo "<td><input type=\"password\" class=\"input\" name=\"input_password2\" /></td>\n";
			echo "</tr>\n";

			echo "<tr>\n";
			echo "<td colspan=\"2\">\n";
			echo "<div style=\"font-size: 65%;\">\n";
			echo "Leave empty if you want to $copymovedelete the files to the same FTP server.<br />\n";
			echo "If you want to $copymovedelete the files to another FTP server, enter your login data.\n";
			echo "</div>\n";
			echo "</td>\n";
			echo "</tr>\n";

			echo "</table>\n";
		} // End if

// Items
		for ($k=0; $k<count($selectedEntries); $k++) {
// Basic, for both copy/move as for delete
			echo "<input type=\"hidden\" name=\"selectedEntries[]\" value=\"$selectedEntries[$k]\" />\n";
			if ($copymovedelete == "copy") { echo "Copy $dirorfile <b>$selectedEntries[$k]</b> to:<br />\n"; }
			elseif ($copymovedelete == "move") { echo "Move $dirorfile <b>$selectedEntries[$k]</b> to:<br />\n"; }
			elseif ($copymovedelete == "delete") { echo "Delete $dirorfile <b>$selectedEntries[$k]</b><br />\n"; }
// Options
//    Copy or move: ask for options
			if ($copymovedelete != "delete") {
				echo "<table>\n";
				echo "<tr><td>\n";
				echo "Target directory: </td><td><input type=\"text\" name=\"targetDirectories[$k]\" value=\"$directory\" />\n";

				printDirectoryTreeLink($directory, "CopyMoveDeleteForm.elements[\'targetDirectories[$k]\']");

				echo "</td></tr>\n";
				echo "<tr><td>Target name:      </td><td><input type=\"text\" name=\"newNames[$k]\" value=\"$selectedEntries[$k]\" /></td></tr>\n";
				echo "</table>\n";
			}
//    Delete: no targetdirectory and ftpmode are not applicable
			else {
				echo "<input type=\"hidden\" name=\"targetDirectories[]\" value=\"\" />\n";
				echo "<input type=\"hidden\" name=\"newNames[]\" value=\"\" />\n";
			}
		} // End for

		echo "</form>\n";

	}


// -------------------------------------------------------------------------
// Show result
// -------------------------------------------------------------------------
	elseif ($formresult == "result") {

		printBack($directory);

		if ($dirorfile == "file") {
			$ftpModes = ftpAsciiBinary($selectedEntries);
		}

// Open connection to the source server
		$resultArray = ftp_openconnection();
		$conn_id_source = getResult($resultArray);
		if ($conn_id_source == false)  { printErrorMessage($resultArray, "exit"); }

// Open connection to the target server, if it is different from the source server, or if the username
// is different (different users may have different authorizations on the same FTP server)
		if (($input_ftpserver2 != $net2ftp_ftpserver) || ($input_username2 != $net2ftp_username)) {
			$resultArray = ftp_openconnection2();       // Note: ftp_openconnection2 cleans the input values
			$conn_id_target = getResult($resultArray);
			if ($conn_id_target == false)  { printErrorMessage($resultArray, "exit"); }
		}
		else { $conn_id_target = $conn_id_source; }

// ------------------------------
		for ($k=0; $k<count($selectedEntries); $k++) {

// Check entries
			if (("$directory/$selectedEntries[$k]" == "$targetDirectories[$k]") && ($conn_id_source == $conn_id_target)) { 
				$resultArray['message'] = "Directory <b>$directory/$selectedEntries[$k]</b> may not be copied or moved into itself -- this would create an infinite loop!<br />"; 
	  			printErrorMessage($resultArray, "exit");
			}

// Copy/Move/Delete
			if ($dirorfile == "directory") { $resultArray = ftp_copymovedeletedirectory($conn_id_source, $conn_id_target, $directory, $selectedEntries[$k], $targetDirectories[$k], $newNames[$k], $copymovedelete, "0"); }
			elseif ($dirorfile == "file")  { $resultArray = ftp_copymovedeletefile($conn_id_source, $conn_id_target, $directory, $selectedEntries[$k], $targetDirectories[$k], $newNames[$k], $ftpModes[$k], $copymovedelete); }
			$success1 = getResult($resultArray);

// Do not print message below, function always returns true; read messages from function...
//			if ($success1 == true && $copymovedelete == "copy")   { echo "<br />The $dirorfile <b>$directory/$selectedEntries[$k]</b> was successfully copied to <b>$targetDirectories[$k]/$newNames[$k]</b>.<br />\n"; }
//			elseif ($success1 == true && $copymovedelete == "move")   { echo "<br />The $dirorfile <b>$directory/$selectedEntries[$k]</b> was successfully moved to <b>$targetDirectories[$k]/$newNames[$k]</b>.<br />\n"; }
//			elseif ($success1 == true && $copymovedelete == "delete") { echo "<br />The $dirorfile <b>$directory/$selectedEntries[$k]</b> was successfully deleted.<br />\n"; }

		} // End for
// ------------------------------

// Close the connection to the source server
		ftp_closeconnection($conn_id_source);

// Close the connection to the target server, if it is different from the source server
		if ($conn_id_source != $conn_id_target) { $resultArray = ftp_closeconnection($conn_id_target); }

	} // End if elseif (form or result)

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

} // End function copymovedeleteentry 
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
 function newdirectory($directory, $newNames, $formresult) {

// --------------
// This function allows to make a new directory
// --------------

	printTitle("Create new directories");
	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";

	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

// -------------------------------------------------------------------------
// Show form
// -------------------------------------------------------------------------

	if ($formresult != "result") {
		echo "<form name=\"NewForm\" id=\"NewForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
		echo "<input type=\"hidden\" name=\"state2\" value=\"newdirectory\" />\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
		printBackInForm($directory);
		echo "<input type=\"submit\" class=\"button\" value=\"Create\" /><br /><br />\n";

		echo "The new directories will be created in <b>$printdirectory</b>.<br /><br />\n";

		for ($k=0; $k<5; $k++) {
			echo "New directory name: <input type=\"text\" name=\"newNames[]\" /><br /><br />\n";
		} // End for

		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Show result
// -------------------------------------------------------------------------

	elseif ($formresult == "result") {

		printBack($directory);

// Open connection
		$resultArray = ftp_openconnection();
		$conn_id = getResult($resultArray);
		if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }

		for ($k=0; $k<count($newNames); $k++) {
			if (strlen($newNames[$k]) > 0) {
// Create new directories
				$newsubdir = glueDirectories($directory, $newNames[$k]);		// filesystem.inc.php
				$resultArray = ftp_newdirectory($conn_id, $newsubdir);
				$success = getResult($resultArray);
				if ($success == false)  { printErrorMessage($resultArray, ""); }
				else { echo "Directory <b>$newNames[$k]</b> was successfully created.<br />"; }
			} // End if
		} // End for

// Close connection
		ftp_closeconnection($conn_id);

	} // End if elseif (form or result)

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";

} // End function newdirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function view($directory, $entry) {

// --------------
// This function allows to view a file
// --------------

	printTitle("View file $entry");
	echo "<div style=\"margin-left: 30px;\">\n";
	printBack($directory);
	echo "</div>\n";

	$resultArray = ftp_readfile("", $directory, $entry); // see filesystem.inc.php
	$text = getResult($resultArray); // see filesystem.inc.php
	if ($text == false)  { printErrorMessage($resultArray, "exit"); }

	printCode($directory, $entry, $text);

} // End function view
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function edit($directory, $entry, $text, $wysiwyg, $formresult) {

// --------------
// This function allows to edit a file in a regular textarea
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $state2;
global $application_rootdir;

// -------------------------------------------------------------------------
// First step: show edit form
// -------------------------------------------------------------------------
	if ($formresult != "result") {
		if ($state2 == "edit") { 
			$resultArray = ftp_readfile("", $directory, $entry); // see filesystem.inc.php
			$text_fromfile = getResult($resultArray); // see filesystem.inc.php
			if ($text_fromfile == false)  { echo printErrorMessage($resultArray, "exit"); }
		}
		elseif ($state2 == "newfile") { 
			$handle = fopen("$application_rootdir/template.txt", "r"); // Open the local template file for reading only
			if ($handle == false) { echo "Unable to open the temporary file"; exit(); }

			clearstatcache(); // for filesize

			$text_fromfile = fread($handle, filesize("$application_rootdir/template.txt"));
			if ($text_fromfile == false) { echo "Unable to read the temporary file"; exit(); }


			$success1 = fclose($handle);
//			if ($success1 == false) { echo "Unable to close the temporary file"; }

		}
		printEditForm($directory, $entry, $text_fromfile, $wysiwyg, "notsavedyet");
	} 
// -------------------------------------------------------------------------
// Second step: save to remote file, and show View/Edit screen
// -------------------------------------------------------------------------
	elseif ($formresult == "result") {
		if (strlen($entry)<1) { $resultArray['message'] = "Please specify a filename.\n"; printErrorMessage($resultArray, "exit"); }

		$resultArray = ftp_writefile("", $directory, $entry, $text); // see filesystem.inc.php
		$success_save = getResult($resultArray);
		if ($success_save == false)  { printErrorMessage($resultArray, "exit"); }

		printEditForm($directory, $entry, $text, $wysiwyg, $success_save);
	}

} // End function edit
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printCode($directory, $entry, $text) {

// --------------
// This function prints the code
// --------------

	echo "<div class=\"view\">\n";
	echo "<!-- -------------------- Start of code -------------------- -->\n";
	highlight_string($text);
	echo "<!-- -------------------- End  of code  -------------------- -->\n";
	echo "</div>\n";

} // End function printCode
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printEditForm($directory, $entry, $text, $wysiwyg, $success_save) {

// --------------
// This function prints the form containing the textarea in which text is edited
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $state, $state2;
global $edit_nrofcolumns, $edit_nrofrows, $edit_fontsize, $edit_fontfamily;

	$text = htmlspecialchars($text, ENT_QUOTES);

	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

	echo "<form name=\"EditForm\" id=\"EditForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"edit\" />\n";
	echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
	echo "<input type=\"hidden\" name=\"entry\" value=\"$entry\" />\n";
	echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";

	if ($wysiwyg != "wysiwyg") {     echo "<input type=\"hidden\" name=\"wysiwyg\" value=\"plain\" />\n"; }
	elseif ($wysiwyg == "wysiwyg") { echo "<input type=\"hidden\" name=\"wysiwyg\" value=\"wysiwyg\" />\n"; }

	echo "<table style=\"padding: 2px; width: 100%; height: 100%; border: 0px;\">\n";

// Row 1, Col1: Directory and Filename
//------------------------------------
	echo "<tr>\n";
	echo "<td valign=\"top\" style=\"text-align: left;\">\n";
  // Edit ==> print filename
	if ($state2 == "edit") {
		echo "<table>\n";
		echo "<tr><td valign=\"top\">Directory:</td><td><b>$printdirectory</b></td></tr>\n";
		echo "<tr><td valign=\"top\">File:</td><td><b>$entry</b></td></tr>\n";
		echo "</table>\n";
	}
  // Newfile ==> print new filename textbox
	elseif ($state2 == "newfile") { 
		echo "<table>\n";
		echo "<tr><td valign=\"top\">Directory:</td><td><b>$printdirectory</b></td></tr>\n";
		echo "<tr><td valign=\"top\">New file name:</td><td> <input class=\"input\" type=\"text\" name=\"entry\" /></td></tr>\n"; 
		echo "</table>\n";
	}
	echo "</td>\n";

// Row 1, Col2
//---------------------------------------
	echo "<td valign=\"top\" style=\"text-align: center;\">\n";

// Plain or WYSIWYG, only for IE 5.5 and 6.0
	$browser_agent   = getBrowser("agent");
	$browser_version = getBrowser("version");
	if ($browser_agent == "IE" && ($browser_version == "5.5" || $browser_version == "6.0")) {
		echo "Textarea type: &nbsp; ";
		if ($wysiwyg != "wysiwyg") {
			echo "<b>Plain</b> | <a href=\"javascript: document.EditForm.wysiwyg.value='wysiwyg'; document.EditForm.submit();\">WYSIWYG</a>\n";
		}
		elseif ($wysiwyg == "wysiwyg") {
			echo "<a href=\"javascript: document.EditForm.wysiwyg.value='plain'; document.EditForm.submit();\">Plain</a> | <b>WYSIWYG</b>\n";
		}
		echo "<div style=\"font-size: 60%;\">Switching the textarea type will save the changes</div>";
	}

	echo "</td>\n";

// Row 1, Col3: Buttons and saving-status
//---------------------------------------
	echo "<td valign=\"top\" style=\"text-align: right;\">\n";

	echo "<input type=\"button\" class=\"button\" value=\"Save\" onClick=\"this.form.submit();\"                                         title=\"Save this file\" /> &nbsp;\n";
	if ($state2 == "edit") { echo "<input type=\"button\" class=\"button\" value=\"Open\" onClick='window.open(\"" . printURL($directory, $entry, no) . "\");'    title=\"Open this file in a new window\" /> &nbsp;\n"; }
	echo "<input type=\"button\" class=\"button\" value=\"Back\" onClick=\"document.EditForm.state.value='browse'; document.EditForm.state2.value='main'; this.form.submit();\" title=\"Cancel and go back to the browse view\" />\n";
	echo "<br />\n";
	if ($success_save === "notsavedyet") { echo "<div style=\"font-size: 70%;\">This file has not yet been saved</div>\n"; }
	elseif ($success_save === true)      { echo "<div style=\"font-size: 70%;\">This file was saved on <b>" . mytime() . "</b></div>\n"; }
	elseif ($success_save === false)     { echo "<div style=\"font-size: 70%;\"><b>This file could not be saved</b></div>\n"; }
	echo "</td>\n";

	echo "</tr>\n";

// Row 2:       Textarea
//----------------------
	echo "<tr>\n";
	echo "<td colspan=\"3\" valign=\"top\" style=\"text-align: left;\">\n";
	echo "<div style=\"margin-left: 0px; text-align: left;\">\n";
	echo "\n\n<!-- -------------------- Start of code -------------------- -->\n";
	echo "<textarea name=\"text\" class=\"edit\" rows=\"$edit_nrofrows\" cols=\"$edit_nrofcolumns\" wrap=\"off\">\n";
	echo "$text\n";
	echo "</textarea>\n";
	echo "<!-- -------------------- End  of code  -------------------- -->\n\n\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";


	echo "</table>\n";
	echo "</form>\n";

} // End function printEditForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
// function newfile()
//
//    is now implemented using the edit() function
//
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************











// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function uploadfile($directory, $uploadedFilesArray, $uploadedArchivesArray, $use_folder_names, $formresult) {

// --------------
// This function allows to upload a file to a directory
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;


// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {

		printTitle("Upload files and archives");

		printUploadForm($directory);

	} // end if (show form, show result)


// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	else {

// -------------------------------------------------------------------------
// Results
// -------------------------------------------------------------------------

		printTitle("Upload results");

		printBack($directory);

		echo "<table style=\"border-color: #000066; border-style: solid; border-width: 1px; padding: 10px; margin-right: 100px; margin-bottom: 30px;\">\n";
		echo "<tr>\n";
		echo "<td>\n";

// ---------------------------------------
// Check the files and move them to the net2ftp temp directory
// A .txt extension is added
// ---------------------------------------
		if (sizeof($uploadedFilesArray) > 0 || sizeof($uploadedArchivesArray) > 0) {
			echo "<b><u>Checking files:</u></b> <br />\n";
			echo "<ul>\n";
		}
		else { 
			$resultArray['message'] = "The array of uploaded files and archives is empty.";
			printErrorMessage($resultArray, "exit");
		}

		if (sizeof($uploadedFilesArray) > 0) {
			$resultArray = acceptFiles($uploadedFilesArray, $application_tempdir);
			$acceptedFilesArray = getResult($resultArray);
			if ($acceptedFilesArray == false)  { printErrorMessage($resultArray, "exit"); }
		}
		if (sizeof($uploadedArchivesArray) > 0) {
			$resultArray = acceptFiles($uploadedArchivesArray, $application_tempdir);
			$acceptedArchivesArray = getResult($resultArray);
			if ($acceptedArchivesArray == false)  { printErrorMessage($resultArray, "exit"); }
		}
		echo "</ul>\n";

// ---------------------------------------
// Transfer files
// ---------------------------------------
		if (sizeof($acceptedFilesArray) > 0) {
			echo "<b><u>Transferring files to the FTP server:</u></b> <br />\n";
			echo "<ul>\n";

			$resultArray = ftp_transferfiles($acceptedFilesArray, $application_tempdir, $directory);
			$result3 = getResult($resultArray);
			if ($result3 == false)  { printErrorMessage($resultArray, ""); }

			echo "</ul>\n";
		}

// ---------------------------------------
// Unzip archives and transfer the files (create subdirectories if needed)
// ---------------------------------------
		if (sizeof($acceptedArchivesArray) > 0) {
			echo "<b><u>Unzipping and transferring files to the FTP server:</u></b> <br />\n";
			echo "<ul>\n";

			$resultArray = ftp_unziptransferfiles($acceptedArchivesArray, $use_folder_names, $application_tempdir, $directory);
			$result4 = getResult($resultArray);
			if ($result4 == false)  { printErrorMessage($resultArray, ""); }

			echo "</ul>\n";
		}

		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";


// -------------------------------------------------------------------------
// Upload more files
// -------------------------------------------------------------------------
		printTitle("Upload more files and archives");
		printUploadForm($directory);

	} // end else

	echo "</div>\n";

} // End function uploadfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printUploadForm($directory) {

// --------------
// This function prints the upload form
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $my_net2ftp_url;
	global $max_upload_size;
	global $nr_upload_files, $nr_upload_archives;

	$max_upload_size_MB = $max_upload_size / 1000;
	$max_execution_time = ini_get("max_execution_time");
	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

	echo "<form name=\"UploadForm\" id=\"UploadForm\" method=\"post\" enctype=\"multipart/form-data\" action=\"" . printPHP_SELF("no") . "\">\n";

	printBackInForm($directory);
	echo "<input type=\"submit\" class=\"button\" value=\"Upload\" /><br /><br />\n";

	echo "Upload to directory: <input type=\"text\" name=\"directory\" value=\"$printdirectory\" />\n";
	printDirectoryTreeLink($directory, "UploadForm.directory");
	echo "<br /><br />\n";

	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"uploadfile\" />\n";
	echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
	echo "<input type=\"hidden\" name=\"max_file_size\" value=\"$max_upload_size\" />\n"; // in bytes, advisory to browser, easy to circumvent; see also below, in PHP code!

	echo "<table>\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" width=\"50%\">\n";

// Files
	for ($i=1; $i<=$nr_upload_files; $i=$i+1) {
		echo "File $i: <input type=\"file\" class=\"uploadinputbutton\" name=\"file" . $i . "\" /><br />\n";
	}
	echo "<br />\n";

	echo "</td>\n";


// Archives
	echo "<td valign=\"top\" width=\"50%\">\n";

	if (function_exists("zip_open") == true) {
		for ($i=1; $i<=$nr_upload_archives; $i=$i+1) {
			echo "Archive $i: <input type=\"file\" class=\"uploadinputbutton\" name=\"archive" . $i . "\" /><br />\n";
		}
		echo "<br /><div style=\"font-size: 80%;\"><input type=\"checkbox\" name=\"use_folder_names\" value=\"yes\" checked/> Use folder names (create subdirectories automatically)</div><br />\n";
		echo "<div style=\"font-size: 80%;\">Archives entered here will be decompressed on the web server, and the files they contain will be transferred to the FTP server.</div><br />\n";
	}

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "</form>\n";


	echo "<u>Restrictions:</u>\n";
	echo "<div style=\"font-size: 80%\">\n";
	echo "<ul>\n";
	echo "	<li> The maximum size of one file is <b>$max_upload_size_MB kB</b></li>\n";
	echo "	<li> The maximum execution time is <b>$max_execution_time seconds</b></li>\n";
	echo "	<li> The FTP transfer mode (ASCII or BINARY) will be automatically determined, based on the filename extension\n";
	echo "	<li> If the destination file already exists, it will be overwritten</li>\n";
	echo "</ul>\n";
	echo "</div><br />\n";

} // End function printUploadForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printAdvancedFunctions($directory) {

// --------------
// This function prints the advanced options screen
// --------------

	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";

	printTitle("Advanced functions");

	echo "<form name=\"AdvancedOptionsForm\" id=\"AdvancedOptionsForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"advanced\" />\n";
	echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
	printBackInForm($directory);
	echo "<br /><br />\n";

	echo "<input type=\"button\" class=\"smallbutton\" value=\"Go\" onClick=\"document.AdvancedOptionsForm.state2.value='site'; document.AdvancedOptionsForm.submit();\" /> Send a site command to the FTP server<br /><br />\n";
	echo "<input type=\"button\" class=\"smallbutton\" value=\"Go\" onClick=\"document.AdvancedOptionsForm.state2.value='apache';  document.AdvancedOptionsForm.submit();\" /> Apache: password-protect a directory, create custom error pages<br /><br />\n";
	echo "<input type=\"button\" class=\"smallbutton\" value=\"Go\" onClick=\"document.AdvancedOptionsForm.state2.value='mysql';  document.AdvancedOptionsForm.submit();\" /> MySQL: execute an SQL query<br /><br />\n";
	echo "</form>\n";

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";


} // End function printAdvancedFunctions

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function sendsitecommand($directory, $command, $formresult) {

// --------------
// This function allows to send a site command to the FTP server
// --------------


	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";

	printTitle("Send site command");

// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {

		echo "<form name=\"SiteCommandForm\" id=\"SiteCommandForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
		echo "<input type=\"hidden\" name=\"state2\" value=\"site\" />\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
		printBackInForm($directory);
		echo "<input type=\"submit\" class=\"button\" value=\"Send\" /><br /><br />\n";

		echo "Enter the site command you want to send to the FTP server: <input type=\"text\" name=\"command\" value=\"\" /><br /><br />\n";

		echo "<div style=\"font-size: 80%;\">Which commands you can use depends on your FTP server. These commands are not standard and vary a lot from one server to the other.</div><br />\n";
		echo "<div style=\"font-size: 80%;\">Note also that net2ftp cannot display the output of the FTP server, it can only tell if the command returned TRUE or FALSE. This is not a limitation of net2ftp but of PHP, the language in which net2ftp is written.</div><br />\n";

		echo "</form>\n";

	} // end if


// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	else {

		printBack($directory);

// Open connection
		$resultArray = ftp_openconnection();
		$conn_id = getResult($resultArray);
		if ($conn_id == false) { printErrorMessage($resultArray, "exit"); }

// Send site command
		$resultArray = ftp_mysite($conn_id, $command);
		$success = getResult($resultArray);
		if ($success == false) { printErrorMessage($resultArray, ""); }
		else { echo "The command <b>$command</b> was executed successfully.<br />"; }

// Close connection
		ftp_closeconnection($conn_id);

	} // end else

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";

} // End function sendsitecommand

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function apache($directory) {

// --------------
// This function allows to perform Apache specific actions
// --------------

	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

	echo "<table style=\"margin-left: 30px; margin-right: auto;\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {

		echo "<form name=\"ApacheForm\" id=\"ApacheForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\" />\n";
		echo "<input type=\"hidden\" name=\"state2\" value=\"apache\" />\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";

// Password protection
		printTitle("Password protect a directory");
		printBackInForm($directory);
		echo "<input type=\"button\" class=\"button\" value=\"Submit\" onClick=\"document.ApacheForm.state2.value='apache'; document.ApacheForm.directory.value=document.ApacheForm.directory1.value; document.ApacheForm.submit();\" /><br /><br />\n";

		echo "Protect directory: <input type=\"text\" name=\"directory1\" value=\"$printdirectory\" />\n";
		printDirectoryTreeLink($directory, "ApacheForm.directory1");
		echo "<br /><br />\n";

		for ($i=1; $i<=5; $i=$i+1) {
			echo "Username $i: <input type=\"text\" name=\"apache_username$i\" value=\"\" />  Password $i: <input type=\"password\" name=\"apache_password$i\" value=\"\" /><br />\n";
		} // end for

// Custom error page
		printTitle("Custom error page");
		printBackInForm($directory);
		echo "<input type=\"button\" class=\"button\" value=\"Submit\" onClick=\"document.ApacheForm.state2.value='apache'; document.ApacheForm.directory.value=document.ApacheForm.directory2.value; document.ApacheForm.submit();\" /><br /><br />\n";

		echo "For directory: <input type=\"text\" name=\"directory2\" value=\"$printdirectory\" />\n";
		printDirectoryTreeLink($directory, "ApacheForm.directory2");
		echo "<br /><br />\n";

		echo "Error <input type=\"text\" name=\"apache_error_1\" value=\"400\" /> is redirected to page <input type=\"text\" name=\"apache_page_1\" value=\"error.php?code=400\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_2\" value=\"401\" /> is redirected to page <input type=\"text\" name=\"apache_page_2\" value=\"error.php?code=401\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_3\" value=\"404\" /> is redirected to page <input type=\"text\" name=\"apache_page_3\" value=\"error.php?code=404\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_4\" value=\"500\" /> is redirected to page <input type=\"text\" name=\"apache_page_4\" value=\"error.php?code=500\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_5\" value=\"501\" /> is redirected to page <input type=\"text\" name=\"apache_page_5\" value=\"error.php?code=501\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_6\" value=\"502\" /> is redirected to page <input type=\"text\" name=\"apache_page_6\" value=\"error.php?code=502\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_7\" value=\"503\" /> is redirected to page <input type=\"text\" name=\"apache_page_7\" value=\"error.php?code=503\" /><br />\n";
		echo "Error <input type=\"text\" name=\"apache_error_8\" value=\"505\" /> is redirected to page <input type=\"text\" name=\"apache_page_8\" value=\"error.php?code=505\" /><br />\n";

		echo "<br /><input type=\"checkbox\" name=\"put_error_page\" value=\"yes\" checked/> Put an example error.php page on the server<br />\n";

		echo "</form>\n";

	} // end if


// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	else {

		printBack($directory);

// Open connection
		$resultArray = ftp_openconnection();
		$conn_id = getResult($resultArray);
		if ($conn_id == false) { printErrorMessage($resultArray, "exit"); }

// Send site command
		$resultArray = ftp_mysite($conn_id, $command);
		$success = getResult($resultArray);
		if ($success == false) { printErrorMessage($resultArray, ""); }
		else { echo "The command <b>$command</b> was executed successfully.<br />"; }

// Close connection
		ftp_closeconnection($conn_id);

	} // end else

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";

} // End function apache

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




?>