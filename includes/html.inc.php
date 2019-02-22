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
function HtmlBegin($pagetitle, $state, $state2, $directory, $entry) {

// -------------------------------------------------------------------------
// Global variables (declared as global in functions)
// -------------------------------------------------------------------------
global $starttime;
global $client_css;
global $wysiwyg;
global $net2ftp_ftpserver, $net2ftp_skin;

// -------------------------------------------------------------------------
// Timer: start
// -------------------------------------------------------------------------
	$starttime = microtime();

// -------------------------------------------------------------------------
// Log access
// -------------------------------------------------------------------------
	$page = printPHP_SELF("no");
	logAccess($page);

// -------------------------------------------------------------------------
// HTML begin
// -------------------------------------------------------------------------

// Strict XHTML 1.0
//	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n";

// Transitional HTML 4.01
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";


	echo "<html>\n\n\n";
// -------------------------------------------------------------------------
// Head
// -------------------------------------------------------------------------
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<meta name=\"keywords\" content=\"net2ftp, web based ftp client, ftp, client, web, based, net, connect, user, gui, interface, web2ftp, net2ftp, edit, editor, online, code, php, upload, download, copy, move, delete, recursive, rename, chmod, syntax, highlighting\">\n";
	echo "<meta name=\"description\" content=\"net2ftp is a web based FTP client. It is mainly aimed at managing websites using a browser. Edit code, upload/download files, copy/move/delete directories recursively, rename files and directories -- without installing any software.\">\n";
	if ($state2 == "view" || $state2 == "edit") { echo "<title>--> $pagetitle --> $net2ftp_ftpserver$directory/$entry</title>\n"; }
	else                                        { echo "<title>--> $pagetitle --> $net2ftp_ftpserver$directory</title>\n"; }	

// Include stylesheet
	$skinArray = getSkinArray();
	if ($net2ftp_skin == "") { $css = $skinArray[1]['css']; }
	else { $css = $skinArray[$net2ftp_skin]['css'];	}
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$css\">\n";

// Include javascript data and code
//	echo "<script type=\"text/javascript\" src=\"javascript/file.js.php\"></script>\n";
	printJavascriptFunctions($state, $state2);

// Link to favicon
//	echo "<link rel=\"shortcut icon\" href=\"favicon.ico\">\n";

// WYSIWYG textarea
	$browser_agent   = getBrowser("agent");
	$browser_version = getBrowser("version");

	if (($state2 == "edit" || $state2 == "newfile") && $wysiwyg == "wysiwyg" && ($browser_agent == "IE" && ($browser_version == "5.5" || $browser_version == "6.0"))) {
		echo "<script type=\"text/javascript\"><!--\n";
		echo "_editor_url = \"./htmlarea/\"\n";
		echo "//--></script>\n";
		echo "<script type=\"text/javascript\" src=\"htmlarea/editor.js\"></script>\n";
		echo "<script type=\"text/javascript\" defer> editor_generate('text'); </script>\n";
	} // end if

	echo "</head>\n\n\n";

// -------------------------------------------------------------------------
// Body
// -------------------------------------------------------------------------
	echo "<body>\n";
	if (($state=="manage" && $state2=="edit") || ($state=="manage" && $state2=="newfile") || ($state=="browse" && $state2=="popup")) {
		// Do not print anything
	}
	else {
		echo "<table border=\"0\" style=\"width: 90%;  padding: 0px; margin-left: auto; margin-right: auto;\" cellspacing=\"0\"><tr><td class=\"tdbackground\"> <!-- Table for background, begin -->\n";
		echo "<table border=\"0\" style=\"width: 100%; padding: 0px;\" cellspacing=\"0\"> <!-- Table for content, begin -->\n";
		echo "<tr> <!-- Table for content, row1 -->\n";

// tdleft1
		echo "<td valign=\"top\" class=\"tdleft1\"></td>\n";
// tdleft2
		echo "<td valign=\"top\" class=\"tdleft2\"></td>\n";
// tdmiddle
		echo "<td valign=\"top\" class=\"tdmiddle\">\n";
		echo "<table border=\"0\" style=\"width: 100%; padding: 0px;\" cellspacing=\"0\"> <!-- Table for title and bookmark link, begin -->\n";
		echo "<tr>\n";
		echo "<td width=\"50px;\"></td>\n";
		echo "<td><div class=\"header11\">" . $pagetitle . "</div></td>\n";
		echo "<td width=\"50px;\">\n";
		if ($state == "browse" || $state == "manage") { printBookmarkLink(); }
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table> <!-- Table for title and bookmark link, end -->\n";
	}
}
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function HtmlEnd() {

global $starttime;
global $state, $state2;
global $application_version;

// -------------------------------------------------------------------------
// Edit: do not print anything
// -------------------------------------------------------------------------
	if (($state=="manage" && $state2=="edit") || ($state=="manage" && $state2=="newfile") || ($state=="browse" && $state2=="popup")) {
		// Do not print anything
	}

// -------------------------------------------------------------------------
// printloginform and feedback: print only copyright notice
// -------------------------------------------------------------------------
	elseif ($state=="printloginform" || $state=="feedback")  {

	// Timer that shows the execution time of the page
		//timer($starttime, $endtime);

	// Advertisement
	// You are entitled to remove the "advertisement" below, because this 
	// software is released under the GPL license. However, the copyright 
	// notice at the beginning of this file may not be removed; note though
	// that this is not printed.
		echo "<div style=\"text-align: center; margin-top: 20px; margin-bottom: 10px; font-size: 70%;\">\n";
		echo "Powered by net2ftp &copy; <a href=\"http://www.net2ftp.com\">net2ftp.com</a><br />net2ftp is free software, released under the <a href=\"http://www.gnu.org\">GNU/GPL license</a>\n";
		echo "</div>\n";

	// End tables
		echo "\n\n\n</td>\n";
		echo "<td class=\"tdright2\"></td>\n";
		echo "<td class=\"tdright1\"></td>\n";
		echo "</tr>\n";
		echo "</table> <!-- Table for content, end -->\n";
		echo "</td></tr></table> <!-- Table for background, end -->\n";

		echo "<p></p><p></p>\n";
	}

// -------------------------------------------------------------------------
// All other cases: print link to feedback form, and the copyright notice
// -------------------------------------------------------------------------
	else {

	// Timer that shows the execution time of the page
		//timer($starttime, $endtime);

	// Link to the feedback form
		echo "<form name=\"FeedbackForm\" id=\"FeedbackForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"state\" value=\"feedback\">\n";
		echo "</form>\n";

		echo "<div style=\"text-align: center; margin-top: 30px; margin-bottom: 10px; font-size: 70%;\">\n";
		echo "<a href=\"javascript: document.FeedbackForm.submit();\">Comments? Questions? Send feedback!</a>\n";
		echo "</div>\n";

	// Advertisement
	// You are entitled to remove the "advertisement" below, because this 
	// software is released under the GPL license. However, the copyright 
	// notice at the beginning of this file may not be removed; note though
	// that this is not printed.
		echo "<div style=\"text-align: center; margin-top: 10px; margin-bottom: 10px; font-size: 70%;\">\n";
		echo "Powered by net2ftp &copy; <a href=\"http://www.net2ftp.com\">net2ftp.com</a><br />net2ftp is free software, released under the <a href=\"http://www.gnu.org\">GNU/GPL license</a>\n";
		echo "</div>\n";

	// End tables
		echo "\n\n\n</td>\n";
		echo "<td valign=\"top\" class=\"tdright2\"></td>\n";
		echo "<td valign=\"top\" class=\"tdright1\"></td>\n";
		echo "</tr>\n";
		echo "</table> <!-- Table for content, end -->\n";
		echo "</td></tr></table> <!-- Table for background, end -->\n";		

		echo "<p></p><p></p>\n";
	}


	echo "<!-- net2ftp version $application_version -->\n";
	echo "</body>\n\n\n";
	echo "</html>\n";
}

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printJavascriptFunctions($state, $state2) {

// --------------
// This functions prints the Javascript functions in the header of each HTML page
// --------------

	global $popup_height, $popup_width;

	echo "\n\n\n<script type=\"text/javascript\"><!--\n";

	if ($state == "browse" && $state2 == "main") {
		echo "\nfunction CheckAll(myform) {\n";
		echo "   for (var i = 0; i < myform.elements.length; i++) {\n";
		echo "       if (myform.elements[i].type == 'checkbox') {\n";
		echo "           myform.elements[i].checked = !(myform.elements[i].checked);\n";
		echo "       }\n";
		echo "   }\n";
		echo "}\n\n";
	}

	if (($state == "browse" && $state2 == "main") || ($state == "manage" && ($state2 == "copydirectory" || $state2 == "movedirectory" || $state2 == "copyfile" || $state2 == "movefile" || $state2 == "uploadfile" || $state2 == "apache"))) {
		echo "\nfunction createDirectoryTreeWindow(directory, FormAndFieldName) {\n\n";
		echo "	directoryTreeWindow = window.open(\"\",\"directoryTreeWindow\",\"height=$popup_height,width=$popup_width,resizable=yes,scrollbars=yes\");\n";
		echo "	var d = directoryTreeWindow.document;\n";
		echo "	d.writeln('<html>');\n";
		echo "	d.writeln('<head>');\n";
		echo "	d.writeln('<title>Choose a directory</title>');\n";
		echo "	d.writeln('</head>');\n\n";
		echo "	d.writeln('<bo' + 'dy on' + 'load=\"document.DirectoryTreeForm.submit();\">');\n";
//		echo "	d.writeln('<body>');\n";
		echo "	d.writeln('Please wait...<br /><br />');\n";
		echo "	d.writeln('<form name=\"DirectoryTreeForm\" id=\"DirectoryTreeForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\"/>');\n";
		printLoginInfo_javascript();
		echo "	d.writeln('<input type=\"hidden\" name=\"state\" value=\"browse\" />');\n";
		echo "	d.writeln('<input type=\"hidden\" name=\"state2\" value=\"popup\" />');\n";
		echo "	d.writeln('<input type=\"hidden\" name=\"directory\" value=\"' + directory + '\"  />');\n";
		echo "	d.writeln('<input type=\"hidden\" name=\"FormAndFieldName\" value=\"' + FormAndFieldName + '\"  />');\n";
//		echo "	d.writeln('<input type=\"submit\" class=\"smallbutton\" value=\"Submit\"/>');\n";
//		echo "	d.writeln('<input type=\"button\" class=\"smallbutton\" value=\"Close\" onClick=\"self.close()\" />');\n";
		echo "	d.writeln('</form>');\n";
		echo "	d.writeln('</div>');\n";
		echo "	d.writeln('</body>');\n";
		echo "	d.writeln('</html>');\n";
		echo "	d.close();\n";
		echo "} // end function createDirectoryTreeWindow\n\n";
	}


	if ($state == "browse" && $state2 == "popup") {
		echo "\nfunction submitDirectoryTreeForm() {\n";
		echo "	if (document.DirectoryTreeForm.DirectoryTreeSelect.options[document.DirectoryTreeForm.DirectoryTreeSelect.selectedIndex].value != 'up') { document.DirectoryTreeForm.directory.value=document.DirectoryTreeForm.directory.value + '/' + document.DirectoryTreeForm.DirectoryTreeSelect.options[document.DirectoryTreeForm.DirectoryTreeSelect.selectedIndex].value; }\n";
		echo "	else { document.DirectoryTreeForm.directory.value = document.DirectoryTreeForm.updirectory.value; }\n";
		echo "document.DirectoryTreeForm.submit();\n";
		echo "} // end function submitDirectoryTreeForm\n\n";

	}

	if (($state == "manage" && ($state2 == "copydirectory" || $state2 == "movedirectory" || $state2 == "copyfile" || $state2 == "movefile"))) {
		echo "\nfunction CopyToAll(myform) {\n";
		echo "   for (var i = 0; i < myform.elements.length; i++) {\n";
		echo "       if (myform.elements[i].name.indexOf('targetDirectories') >= 0) {\n";
		echo "           myform.elements[i].value = myform.headerDirectory.value;\n";
		echo "       }\n";
		echo "   }\n";
		echo "}\n\n";
	}


	if ($state == "" || $state == "printloginform") {

		echo "\nfunction CheckInput(form) {\n";
		echo "	var u,p1,p2,e;\n";
		echo "	s=form.input_ftpserver.value;\n";
		echo "	u=form.input_username.value;\n";
		echo "	p=form.input_password.value;\n";

		echo "	if (s.length==0) {\n";
		echo "		form.input_ftpserver.focus();\n";
		echo "		alert(\"Please enter an FTP server.\");\n";
		echo "		return false;\n";
		echo "	}\n";

		echo "	if (u.length==0) {\n";
		echo "		form.input_username.focus();\n";
		echo "		alert(\"Please enter a username.\");\n";
		echo "		return false;\n";
		echo "	}\n";

//		echo "	if (p.length==0) {\n";
//		echo "		form.input_password.focus();\n";
//		echo "		alert(\"Please enter a password.\");\n";
//		echo "		return false;\n";
//		echo "	}\n";

		echo "}\n\n";
	}

	echo "//--></script>\n\n\n";

} // end printJavascriptFunctions

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function timer($starttime, $endtime) {

// --------------
// This function calculates the time between starttime and endtime, and prints it
// It is used in to print the execution time on each page
// --------------
	list($start_usec, $start_sec) = explode(' ', $starttime);
	$starttime = ((float)$start_usec + (float)$start_sec); 
	list($end_usec, $end_sec) = explode(' ', $endtime);
	$endtime   = ((float)$end_usec + (float)$end_sec); 
	$time_taken         = ($endtime - $starttime)*1000; // to convert from sec to millisec
	$time_taken         = number_format($time_taken, 2);  // optional
	echo "<div style=\"text-align: center; margin-top: 30px; font-size: 80%;\">\n";
	echo "Page created in <b>" . $time_taken . "</b> milliseconds on <b>" . mytime() . "</b>\n";
	echo "</div>\n";
} // End function timer
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function mytime() {
	$datetime = date("Y-m-d H:i:s");                          
	return $datetime;
}
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printTitle($title) {

// --------------
// This function prints the a title
// --------------

	echo "<div class=\"header21\">\n";
	echo "$title\n";
	echo "</div>\n";

} // End function printTitle
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function getBrowser($what) {

// --------------
// This function returns the browser name, version and platform using the http_user_agent string
// --------------

// Original code comes from http://www.phpbuilder.com/columns/tim20000821.php3?print_mode=1
// Written by Tim Perdue, and released under the GPL license
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: tim20000821.php3,v 1.2 2001/05/22 19:22:47 tim Exp $


	global $HTTP_USER_AGENT;

	if ($what == "version" || $what == "agent") {

// -------------------------------------------------------------------------
// Determine browser and version
// -------------------------------------------------------------------------

		if (ereg('MSIE ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$regs)) {
			$BROWSER_VERSION = $regs[1];
			$BROWSER_AGENT = 'IE';
		}
		elseif (ereg('Opera ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$regs)) {
			$BROWSER_VERSION = $regs[1];
			$BROWSER_AGENT = 'Opera';
		}
		elseif (ereg('Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$regs)) {
			$BROWSER_VERSION = $regs[1];
			$BROWSER_AGENT = 'Mozilla';
		}
		else {
			$BROWSER_VERSION = 0;
			$BROWSER_AGENT = 'Other';
		}

		if ($what == "version") { return $BROWSER_VERSION; }
		elseif ($what == "agent")   { return $BROWSER_AGENT; }

	} // end if	

// -------------------------------------------------------------------------
// Determine platform
// -------------------------------------------------------------------------

	elseif ($what == "platform") {

		if (strstr($HTTP_USER_AGENT,'Win')) {
			$BROWSER_PLATFORM = 'Win';
		}
		else if (strstr($HTTP_USER_AGENT,'Mac')) {
			$BROWSER_PLATFORM = 'Mac';
		}
		else if (strstr($HTTP_USER_AGENT,'Linux')) {
			$BROWSER_PLATFORM = 'Linux';
		}
		else if (strstr($HTTP_USER_AGENT,'Unix')) {
			$BROWSER_PLATFORM = 'Unix';
		}
		else {
			$BROWSER_PLATFORM = 'Other';
		}

		return $BROWSER_PLATFORM;
		
	} // end if elseif

} // End function getBrowser
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************

?>