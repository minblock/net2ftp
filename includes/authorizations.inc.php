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
function printLoginForm() {

// --------------
// This function prints the login forms
// It is shown when the user arrives for the first time, and when he logs out (and maybe wants to log in again)
// --------------

global $net2ftpcookie_ftpserver, $net2ftpcookie_username;
global $my_net2ftp_url;
global $net2ftp_allowed_ftpservers, $net2ftp_allowed_ftpserverport;

// ---------------------------------------
// Form used to send the users to the forum, download page, ...
// ---------------------------------------

	if ($my_net2ftp_url == "http://www.net2ftp.com") {
		echo "<script type=\"text/javascript\">\n";
		echo "function submitForm(state_link) {\n";
		echo "document.RedirectForm.state.value = state_link;\n";
		echo "document.RedirectForm.submit();\n";
		echo "}\n";
		echo "</script>\n";

		echo "<form name=\"RedirectForm\" id=\"RedirectForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"state\" value=\"printdetails\" />\n";
		echo "</form>\n";

	} // end if net2ftp

// ---------------------------------------
// Table
// ---------------------------------------
	echo "<table border=\"0\" cellspacing=\"2\" style=\"margin-top: 50px; margin-left: auto; margin-right: auto; padding: 0px;\">\n";

// ---------------------------------------
// Row 1: Login form
// ---------------------------------------
	echo "<tr>\n";

// ----------------
// Row 1, left: login form
// ----------------
	echo "<td valign=\"top\" style=\"width: 400px;\">\n";
	echo "<form action=\"" . printPHP_SELF("no") . "\" method=\"post\" onSubmit=\"return CheckInput(this);\">\n";
	echo "<table style=\"margin-left: auto; margin-right: auto;\">\n";

	echo "<tr>\n";
	echo "<td valign=\"top\">FTP server:</td>\n";
	echo "<td>\n";

// ftpserver
	if ($net2ftp_allowed_ftpservers[1] == "ALL") { echo "<input type=\"text\" class=\"input\" name=\"input_ftpserver\" value=\"$net2ftpcookie_ftpserver\" />\n"; }
	elseif ($net2ftp_allowed_ftpservers[1] != "ALL" && sizeof($net2ftp_allowed_ftpservers) == 1) { echo "<input type=\"hidden\" name=\"input_ftpserver\" value=\"$net2ftp_allowed_ftpservers[1]\" />\n"; echo "<b>$net2ftp_allowed_ftpservers[1]</b>\n"; }
	else {
		echo "<select name=\"input_ftpserver\">\n";
		for ($i=1; $i<=sizeof($net2ftp_allowed_ftpservers); $i=$i+1) {
			// Select the first entry by default
			if ($i == 1) { $selected = "selected"; }
			else { $selected = ""; }

			echo "<option value=\"$net2ftp_allowed_ftpservers[$i]\" $selected>$net2ftp_allowed_ftpservers[$i]</option>\n";
		} // end for
		echo "</select>\n";
	} // end if elseif else (ftpserver)

// ftpserverport
	if ($net2ftp_allowed_ftpserverport == "ALL") {
		if ($net2ftpcookie_ftpserverport != "") {
			echo " port <input type=\"text\" class=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport\" value=\"$net2ftpcookie_ftpserverport\" />\n";
		}
		else {
			echo " port <input type=\"text\" class=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport\" value=\"21\" />\n";
		}
	}
	else {
		echo "<input type=\"hidden\" name=\"input_ftpserverport\" value=\"$net2ftp_allowed_ftpserverport\" />\n";
	}

// Explanation
	if ($net2ftp_allowed_ftpservers[1] == "ALL") { 
		echo "<div style=\"font-size: 65%;\">\n";
		echo "Example: ftp.server.com or 192.123.45.67\n";
		echo "</div>\n";
	}

	echo "</td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

// username
	echo "<tr>\n";
	echo "<td>Username:</td>\n";
	echo "<td><input type=\"text\" class=\"input\" name=\"input_username\" value=\"$net2ftpcookie_username\" /></td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

// password
	echo "<tr>\n";
	echo "<td>Password:</td>\n";
	echo "<td><input type=\"password\" class=\"input\" name=\"input_password\" /></td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

// language
//	echo "<tr>\n";
//	echo "<td>Language:</td>\n";
//	echo "<td>\n";
//	printLanguageSelect();
//	echo "</td>\n";
//	echo "<td></td>\n";
//	echo "</tr>\n";

// skin
// Print the select input field only if there is more than 1 skin
	$skinArray = getSkinArray();

	if (sizeof($skinArray) > 1) {
		echo "<tr>\n";
		echo "<td>Skin:</td>\n";
		echo "<td>\n";
		printSkinSelect();
		echo "</td>\n";
		echo "<td></td>\n";
		echo "</tr>\n";
	}
	else {
		printSkinSelect();
	}

// login button
	echo "<tr><td colspan=\"3\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"main\" />\n";
	echo "<input type=\"hidden\" name=\"cookiesetonlogin\" value=\"yes\" />\n";
	echo "<div style=\"text-align: center; margin-top: 10px;\">\n";
	echo "<input type=\"submit\" class=\"button\" value=\"Login\" /><br /><br />\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";


	echo "</table>\n";
	echo "</form>\n";
	echo "</td>\n\n";


// ----------------
// Row 1, right: description
// ----------------
	echo "<td valign=\"top\" style=\"width: 400px;\">\n";
	printDescription();
	echo "</td>\n\n";
	echo "</tr>\n\n";

// ---------------------------------------
// Row 2: spacing
// ---------------------------------------
	if ($my_net2ftp_url == "http://www.net2ftp.com") {
		echo "<tr style=\"height: 40px;\">\n\n";
		echo "<td colspan=\"2\">\n\n";
		echo "</td>\n\n";
		echo "</tr>\n\n";

// ---------------------------------------
// Row 3: Details and Download
// ---------------------------------------
		echo "<tr>\n\n";

		echo "<td valign=\"top\">\n\n";
		echo "<a href=\"javascript: submitForm('printdetails');\"     style=\"font-size: 120%;\">Details</a><br />\n";
		echo "<div style=\"font-size: 80%; margin-left: 20px;\">Read about the technical details</div><br />\n";
		echo "</td>\n\n";

		echo "<td valign=\"top\">\n\n";
		echo "<a href=\"javascript: submitForm('printdownload');\"    style=\"font-size: 120%;\">Download</a><br />\n";
//		echo "<div                                                       style=\"font-size: 120%;\">Download</div>\n";
		echo "<div style=\"font-size: 80%; margin-left: 20px;\">Install net2ftp on your own web server<br>Requirements: PHP 4. MySQL is optional</div>\n";
		echo "<div style=\"font-size: 80%; margin-left: 20px;\">Latest version: 0.61 released on June 7 <a href=\"download/net2ftp_v0.61.zip\">Download</a></div><br />\n";
		echo "</td>\n\n";

		echo "</tr>\n\n";

// ---------------------------------------
// Row 4: Screenshots and Forum
// ---------------------------------------
		echo "<tr>\n\n";

		echo "<td valign=\"top\">\n\n";
		echo "<a href=\"javascript: submitForm('printscreenshots');\" style=\"font-size: 120%;\">Screenshots</a><br />\n";
		echo "<div style=\"font-size: 80%; margin-left: 20px;\">View some screenshots of the application</div><br />\n";
		echo "</td>\n\n";

		echo "<td valign=\"top\">\n\n";
		echo "<a href=\"forum\"                                         style=\"font-size: 120%;\">User forum</a><br />\n";
		echo "<div style=\"font-size: 80%; margin-left: 20px;\">You have a question? Contact other users and the developers</div><br />\n";
		echo "</td>\n";

		echo "</tr>\n";



// ---------------------------------------
// Row 5: Voting buttons
// ---------------------------------------
		echo "<tr>\n\n";

		echo "<td valign=\"top\">\n\n";
		echo "<!-- ----- Start Hotscripts ----- -->\n";
		echo "<form action=\"http://www.hotscripts.com/cgi-bin/rate.cgi\" method=\"POST\" target=\"_blank\">\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"20386\">\n";
		echo "<table BORDER=\"0\" CELLSPACING=\"0\" bgcolor=\"#000000\">\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "			<table border=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#EFEFEF\" cellpadding=\"3\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\">\n";
		echo "						<font face=\"arial, verdana\" size=\"2\">\n";
		echo "						<b>Rate <b>net2ftp</b> @ <a href=\"http://www.hotscripts.com/Detailed/20386.html\">HotScripts.com</a></b>\n";
		echo "						</font>\n";
		echo "					</td>\n";
		echo "					<td align=\"center\">\n";
		echo "						<select name=\"ex_rate\" size=\"1\">\n";
		echo "						<option>Select</option>\n";
		echo "						<option value=\"5\" selected>Excellent!</option>\n";
		echo "						<option value=\"4\">Very Good</option>\n";
		echo "						<option value=\"3\">Good</option>\n";
		echo "						<option value=\"2\">Fair</option>\n";
		echo "						<option value=\"1\">Poor</option>\n";
		echo "						</select>\n";
		echo "					</td>\n";
		echo "					<td align=\"center\">\n";
		echo "						<input type=\"submit\" value=\"Go!\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "<!-- ----- End Hotscripts ----- -->\n";
		echo "</td>\n\n";


		echo "<td valign=\"top\">\n\n";
		echo "<!-- ----- Start Scriptsearch ----- -->\n";
		echo "<form action=\"http://www.scriptsearch.com/cgi-bin/rateit.cgi\" method=\"POST\" target=\"_blank\">\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"7563\">\n";
		echo "<table BORDER=\"0\" CELLSPACING=\"0\" bgcolor=\"#000000\">\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "			<table border=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#EFEFEF\" cellpadding=\"3\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\">\n";
		echo "						<font face=\"arial, verdana\" size=\"2\">\n";
		echo "						<b>Rate <b>net2ftp</b> @ <a href=\"http://www.scriptsearch.com/details/7563.html\">ScriptSearch.com</a></b>\n";
		echo "						</font>\n";
		echo "					</td>\n";
		echo "					<td align=\"center\">\n";
		echo "						<select name=\"rate\" size=\"1\">\n";
		echo "						<option>Select</option>\n";
		echo "						<option value=\"5\" selected>Excellent!</option>\n";
		echo "						<option value=\"4\">Very Good</option>\n";
		echo "						<option value=\"3\">Good</option>\n";
		echo "						<option value=\"2\">Fair</option>\n";
		echo "						<option value=\"1\">Poor</option>\n";
		echo "						</select>\n";
		echo "					</td>\n";
		echo "					<td align=\"center\">\n";
		echo "						<input type=\"submit\" value=\"Go!\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "<!-- ----- End Scriptsearch ----- -->\n";
		echo "</td>\n\n";

		echo "</tr>\n\n";

// ---------------------------------------
// Row 6: Spacing
// ---------------------------------------
		echo "<tr style=\"height: 40px;\">\n\n";
		echo "<td colspan=\"2\">\n\n";
		echo "</td>\n\n";
		echo "</tr>\n\n";


// ---------------------------------------
// Row 7: Terms of Use
// ---------------------------------------
		echo "<tr><td colspan=\"2\">\n";
		echo "<div style=\"text-align: center; font-size: 80%\">\n";
		echo "By using this website, you agree to these Terms of Use:<br /><br />\n";
		echo "<textarea rows=\"5\" cols=\"50\" onfocus=\"this.blur()\" readonly=\"readonly\">\n";
		printTermsOfUse();
		echo "</textarea>\n";
		echo "</div>\n";
		echo "</td></tr>\n";

	} // end if net2ftp

	echo "</table>\n\n\n";
	echo "<br /><br /><br />\n";


} // End function printLoginForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 
function printDescription() {

// -------------- 
// This function prints the description of the service offered
// -------------- 


// ------------------------------------------------------------------------- 
// Globals
// ------------------------------------------------------------------------- 
global $my_net2ftp_url;

	if ($my_net2ftp_url == "http://www.net2ftp.com") {
		echo "<div style=\"font-size: 120%; font-weight: bold;\">net2ftp - a web based FTP client</div>\n";
		echo "<ul>\n";
		echo "<li> Navigate the FTP server</li>\n";
		echo "<li> Upload - download</li>\n";
		echo "<li> Copy - move - delete</li>\n";
		echo "<li> Copy - move to a 2nd FTP server</li>\n";
		echo "<li> Rename - chmod</li>\n";
		echo "<li> View code with syntax highlighting</li>\n";
		echo "<li> Edit text files</li>\n";
		echo "<li> Edit text in a WYSIWYG form (only Internet Explorer) <span style=\"font-size: 80%; color: red;\">new</span></li>\n";
		echo "<li> Download and upload Zip files <span style=\"font-size: 80%; color: red;\">new</span></li>\n";
		echo "</ul>\n";
	} // end if net2ftp

	else {
		echo "<div style=\"font-size: 110%; font-weight: bold;\">Please enter your login information !</div><br />\n";
		echo "Once you are logged in, you will be able to: \n";
		echo "<ul>\n";
		echo "<li> Navigate the FTP server</li>\n";
		echo "<li> Upload and download files</li>\n";
		echo "<li> Copy, move and delete files and directories</li>\n";
		echo "<li> Copy and move files and directories to a 2nd FTP server</li>\n";
		echo "<li> Rename and chmod files and directories</li>\n";
		echo "<li> View code with syntax highlighting</li>\n";
		echo "<li> Edit text files</li>\n";
		echo "<li> Edit text files in a WYSIWYG form (only Internet Explorer) <span style=\"font-size: 80%; color: red;\">new</span></li>\n";
		echo "<li> Download and upload Zip files <span style=\"font-size: 80%; color: red;\">new</span></li>\n";
		echo "</ul>\n";
	} // end if net2ftp

} // End function printDescription
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printLogoutForm() {

// --------------
// This function prints the login forms
// It is shown when the user arrives for the first time, and when he logs out (and maybe wants to log in again)
// --------------

//global $net2ftp_ftpserver, $net2ftp_username;

//	echo "<form action=\"" . printPHP_SELF("no") . "\" method=\"post\" style=\"margin-top: 30px; margin-bottom: 15px; text-align: center;\">\n";
//	echo "<input type=\"hidden\" name=\"state\" value=\"logout\" />\n";
//	echo "Logged in as <b>$net2ftp_username</b> on <b>$net2ftp_ftpserver</b> <input type=\"submit\" class=\"button\" value=\"Logout\">\n";
//	echo "</form>\n";

//	echo "<div style=\"margin-top: 30px; margin-bottom: 15px; text-align: center;\">\n";
//	echo "Logged in as <b>$net2ftp_username</b> on <b>$net2ftp_ftpserver</b>\n";
//	echo "</div>\n";

} // End function printLogoutForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printFeedbackForm($formresult) {

// --------------
// This function prints the feedback form
// --------------

global $email_feedback;                               // See settings.inc.php
global $name, $subject, $email, $messagebody, $REMOTE_ADDR;


if ($formresult== "") { $formresult = "form"; }

switch ($formresult) {

// -------------------------------------------------------------------------
// formormail: form
// -------------------------------------------------------------------------
	case "form":
		echo "<form action=\"" . printPHP_SELF("no") . "\" method=post>\n";
		echo "<center>\n";
		echo "If you want to send us some feedback, please use the form below.<br />Do not forget to mention your email address if you want us to reply to you.<br /><br />\n";
		echo "<table>\n";
		echo "<tr><td>Name:</td><td><input type=\"text\" name=\"name\" /></td></tr>\n";
		echo "<tr><td>Subject:</td><td><input type=\"text\" name=\"subject\" /></td></tr>\n";
		echo "<tr><td>Email address:</td><td><input type=\"text\" name=\"email\" /></td></tr>\n";
		echo "</table>\n";
		echo "<textarea rows=\"10\" cols=\"45\" name=\"messagebody\"></textarea><br /><br />\n";
		echo "<input type=\"hidden\" name=\"state\" value=\"feedback\" />\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\" />\n";
		echo "<input type=\"button\" class=\"button\" value=\"Back\" onClick=\"top.history.back();\" />\n";
		echo "<input type=\"button\" class=\"button\" value=\"Send\" onClick=\"this.form.submit();\" />\n";
		echo "</center>\n";
		echo "</form>\n";
	break;
// -------------------------------------------------------------------------
// formormail: result
// -------------------------------------------------------------------------
	case "result":

// To
	   	$to = $email_feedback;

// Message
	   	$message = "";
	   	$message = $message . "Name: $name\n";
		$message = $message . "Subject: $subject\n";
		$message = $message . "Email: $email\n";
		$message = $message . "IP address: $REMOTE_ADDR\n";
		$message = $message . "Time: $currenttime\n";
		$message = $message . "\nMessagebody:\n$messagebody\n";

// From
		// Verify the email address supplied by the user
		// If it appears valid, use it
		// If it does not appear valid, set it to the email_feedback value

		if (!eregi( "^" .
	           "[a-z0-9]+([_\\.-][a-z0-9]+)*" .    //user
	           "@" .
	           "([a-z0-9]+([\.-][a-z0-9]+)*)+" .   //domain
	           "\\.[a-z]{2,}" .                    //sld, tld 
	           "$", $email, $regs)) {

			$email = $email_feedback;
		}

// Headers
		$headers = "From: $email\nReply-To: $email\nX-Mailer: PHP/" . phpversion();

// SEND EMAIL 
// bool mail(string to, string subject, string message, string [additional_headers]);

		$mybool = mail($to, $subject, $message, $headers);

		if ($mybool == 1) {
			echo "<div style=\"text-align: center;\">\n";

			echo "<p>\n";
			echo "<b>Your message has been sent.</b>\n";
			echo "</p>\n";

			echo "<p>\n";
			echo "Name: " . $name . "<br />\n";
			echo "Subject: " . $subject . "<br />\n";
			echo "Email address: " . $email . "<br /><br />\n";
			echo "<u>Message:</u> <br />" . $messagebody . "<br />\n";
			echo "</p>\n";

			echo "<a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a>\n";

			echo "</div>\n";
		}
		else {
			$resultArray['message'] = "Due to a technical problem, your message could not be sent. You may send the message via email to <a href=\"mailto:$email_feedback\">$email_feedback</a>."; 
			printErrorMessage($resultArray, "exit");
			echo "<u>Message:</u> <br />" . $messagebody . "<br />\n";
		}

	break;

	default:
		$resultArray['message'] = "Unexpected formresult string."; 
		printErrorMessage($resultArray, "");
	break;
} // End switch



} // End function printFeedbackForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function encryptPassword($password) {

// --------------
// This function takes a clear-text password and returns an encrypted password
// --------------

// 1 - Convert pw to nr

	for ($i=0; $i<strlen($password); $i=$i+1) {
		$ascii_number = ord($password[$i]);
		if ($ascii_number < 10) { $ascii_number = "00" . $ascii_number; }
		elseif ($ascii_number < 100) { $ascii_number = "0" . $ascii_number; }
		$password_ascii_number = $password_ascii_number . $ascii_number;
	}


// 2 - Do stuff with nr

// Method 1: pwe = a.pw + b
//	$password_encrypted = 2*($password_ascii_number) + 891;

// Method 2: number per number
	for ($i=0; $i<strlen($password_ascii_number); $i=$i+3) { 
		$number_unencrypted = substr($password_ascii_number, $i, 3);
		$number_encrypted = 999 - $number_unencrypted;
		$password_encrypted = $password_encrypted . "$number_encrypted";
	}

// No "encryption"
//	$password_encrypted = $password_ascii_number; // to comment later on!

	return $password_encrypted;

} // End function encryptPassword
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function decryptPassword($password_encrypted) {

// --------------
// This function takes an encrypted password and returns the clear-text password
// --------------

// 1 - Undo stuff to nr

// Method 1: pwe = a.pw + b
//	$password_ascii_number = round(1/2*($password_encrypted - 891)); // 097098 is converted to 97098 ==> add 0 or 00 in front
//	$pwn_length = strlen($password_ascii_number);
//	if     ($pwn_length - 3*floor($pwn_length/3) == 1) { $password_ascii_number = "00" . $password_ascii_number; }
//	elseif ($pwn_length - 3*floor($pwn_length/3) == 2) { $password_ascii_number = "0" . $password_ascii_number; }

// Method 2: number per number
	for ($i=0; $i<strlen($password_encrypted); $i=$i+3) { 
		$number_encrypted = substr($password_encrypted, $i, 3);
		$number_unencrypted = 999 - $number_encrypted;
		if ($number_unencrypted < 10) { $number_unencrypted = "00" . $number_unencrypted; }
		elseif ($number_unencrypted < 100) { $number_unencrypted = "0" . $number_unencrypted; }
		$password_ascii_number = $password_ascii_number . "$number_unencrypted";
	}

// No "encryption"
//	$password_ascii_number = $password_encrypted; // to comment later on!

// 2 - Convert nr to pw

	for ($j=0; $j<strlen($password_ascii_number); $j=$j+3) {
		$ascii_letter = chr(substr($password_ascii_number, $j, 3));
		$password = $password . $ascii_letter;
	}

	return $password;
//	return $password_ascii_number;

} // End function decryptPassword
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printLoginInfo() {

// --------------
// This function prints the ftpserver, username and login information
// --------------

global $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $net2ftp_password_encrypted, $net2ftp_language, $net2ftp_skin;

	echo "<input type=\"hidden\" name=\"net2ftp_ftpserver\" value=\"$net2ftp_ftpserver\" />\n";
	echo "<input type=\"hidden\" name=\"net2ftp_ftpserverport\" value=\"$net2ftp_ftpserverport\" />\n";
	echo "<input type=\"hidden\" name=\"net2ftp_username\" value=\"$net2ftp_username\" />\n";
	echo "<input type=\"hidden\" name=\"net2ftp_password_encrypted\" value=\"$net2ftp_password_encrypted\" />\n";
	echo "<input type=\"hidden\" name=\"net2ftp_language\" value=\"$net2ftp_language\" />\n";
	echo "<input type=\"hidden\" name=\"net2ftp_skin\" value=\"$net2ftp_skin\" />\n";

} // End function printLoginInfo
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printLoginInfo_javascript() {

// --------------
// This function prints the ftpserver, username and login information -- for javascript input
// --------------

global $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $net2ftp_password_encrypted, $net2ftp_language, $net2ftp_skin;

	echo "	d.writeln('<input type=\"hidden\" name=\"net2ftp_ftpserver\" value=\"$net2ftp_ftpserver\" />');\n";
	echo "	d.writeln('<input type=\"hidden\" name=\"net2ftp_ftpserverport\" value=\"$net2ftp_ftpserverport\" />');\n";
	echo "	d.writeln('<input type=\"hidden\" name=\"net2ftp_username\" value=\"$net2ftp_username\" />');\n";
	echo "	d.writeln('<input type=\"hidden\" name=\"net2ftp_language\" value=\"$net2ftp_language\" />');\n";
	echo "	d.writeln('<input type=\"hidden\" name=\"net2ftp_skin\" value=\"$net2ftp_skin\" />');\n";
	echo "	d.writeln('<input type=\"hidden\" name=\"net2ftp_password_encrypted\" value=\"$net2ftp_password_encrypted\" />');\n";

} // End function printLoginInfo
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printBack($directory) {

// --------------
// This function prints a Back button which has its own form
// --------------

//	echo "<div style=\"text-align: center;\">\n";
	echo "<form name=\"stateForm\" id=\"stateForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\" />\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"main\" />\n";
	echo "<input type=\"submit\" class=\"button\" value=\"Back\" />\n";
	echo "</form>\n";
//	echo "</div>\n";

} // End function printBack
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printBackInForm($directory) {

// --------------
// This function prints a Back button which can be put into another form
// --------------

	echo "<input type=\"button\" class=\"button\" value=\"Back\" onClick=\"state.value='browse'; state2.value='main'; this.form.submit();\"/>\n";

} // End function printBackInForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printPHP_SELF($printstateinfo) {

// --------------
// This function prints $PHP_SELF, the name of the script itself
// --------------

// -------------------------------------------------------------------------
// Global variables (declared as global in functions)
// -------------------------------------------------------------------------
	global $PHP_SELF, $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $directory, $state, $state2;

	if ($printstateinfo == "yes") {
		return "$PHP_SELF?ftpserver=$net2ftp_ftpserver&ftpserverport=$net2ftp_ftpserverport&username=$net2ftp_username&directory=$directory&state=$state&state2=$state2";
	}
	else { return $PHP_SELF; }

} // End function printPHP_SELF

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************













// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 
function printTermsOfUse() {

// -------------- 
// This function prints the terms of use
// -------------- 



// ------------------------------------------------------------------------- 
// Globals
// ------------------------------------------------------------------------- 
global $email_feedback;

$myname = "This website's owner";

// ------------------------------------------------------------------------- 
// Print Terms Of Use
// ------------------------------------------------------------------------- 
echo "Disclaimer For Interactive Services\n\n";

echo "$net2ftp maintains the interactive portion(s) of their Web site as a service free of charge. By using any interactive services provided herein, you are agreeing to comply with and be bound by the terms, conditions and notices relating to its use.\n\n";

echo "1.  As a condition of your use of this Web site and the interactive services contained therein, you represent and warrant to $myname that you will not use this Web site for any purpose that is unlawful or prohibited by these terms, conditions, and notices.\n\n";

echo "2.  This Web site contains one or more of the following interactive services: bulletin boards, chat areas, news groups, forums, communities and/or other message or communication facilities.  You agree to use such services only to send and receive messages and material that are proper and related to the particular service, area, group, forum, community or other message or communication facility. In addition to any other terms or conditions of use of any bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities, you agree that when using one, you will not:\n";
echo "Publish, post, upload, distribute or disseminate any inappropriate, profane, derogatory, defamatory, infringing, improper, obscene, indecent or unlawful topic, name, material or information.\n";
echo "Upload files that contain software or other material protected by intellectual property laws or by rights of privacy of publicity unless you own or control such rights or have received all necessary consents.\n"; 
echo "Upload files that contain viruses, corrupted files, or any other similar software or programs that may damage the operation of another's computer.\n";
echo "Advertise any goods or services for any commercial purpose.\n";
echo "Offer to sell any goods or services for any commercial purpose.\n";
echo "Conduct or forward chain letters or pyramid schemes.\n";
echo "Download for distribution in any manner any file posted by another user of a forum that you know, or reasonably should know, cannot be legally distributed in such manner.\n"; 
echo "Defame, abuse, harass, stalk, threaten or otherwise violate the legal rights (such as rights of privacy and publicity) of others.\n";
echo "Falsify or delete any author attributions, legal or other proper notices, proprietary designations, labels of the origin, source of software or other material contained in a file that is uploaded.\n"; 
echo "Restrict or inhibit any other user from using and enjoying any of the bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities.\n\n";

echo "3. $myname has no obligation to monitor the bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities. However, $myname reserves the right at all times to disclose any information deemed by $myname necessary to satisfy any applicable law, regulation, legal process or governmental request, or to edit, refuse to post or to remove any information or materials, in whole or in part.\n\n";

echo "4. You acknowledge that communications to or with bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities are not private communications, therefore others may read your communications without your knowledge. You should always use caution when providing any personal information about yourself or your children. $myname does not control or endorse the content, messages or information found in any bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities and, specifically disclaims any liability with regard to same and any actions resulting from your participation. To the extent that there are moderators, forum managers or hosts, none are authorized $myname spokespersons, and their views do not necessarily reflect those of $myname.\n\n";

echo "5. The information, products, and services included on this Web site may include inaccuracies or typographical errors. Changes are periodically added to the information herein. $myname may make improvements and/or changes in this Web site at any time. Advice received via this Web site should not be relied upon for personal, legal or financial decisions and you should consult an appropriate professional for specific advice tailored to your situation.\n\n";

echo "6. $myname MAKES NO REPRESENTATIONS ABOUT THE SUITABILITY, RELIABILITY, TIMELINESS, AND ACCURACY OF THE INFORMATION, PRODUCTS, AND SERVICES CONTAINED ON THIS WEB SITE FOR ANY PURPOSE. ALL SUCH INFORMATION, PRODUCTS, AND SERVICES ARE PROVIDED \"AS IS\" WITHOUT WARRANTY OF ANY KIND.\n\n";

echo "7. $myname HEREBY DISCLAIMS ALL WARRANTIES AND CONDITIONS WITH REGARD TO THE INFORMATION, PRODUCTS, AND SERVICES CONTAINED ON THIS WEB SITE, INCLUDING ALL IMPLIED WARRANTIES AND CONDITIONS OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT.\n\n";

echo "8. IN NO EVENT SHALL $myname BE LIABLE FOR ANY DIRECT, INDIRECT, PUNITIVE, INCIDENTAL, SPECIAL, CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER INCLUDING, WITHOUT LIMITATION, DAMAGES FOR LOSS OF USE, DATA OR PROFITS, ARISING OUT OF OR IN ANY WAY CONNECTED\n";
echo "WITH THE USE OR PERFORMANCE OF THIS WEB SITE,\n";
echo "WITH THE DELAY OR INABILITY TO USE THIS WEB SITE,\n";  
echo "WITH THE PROVISION OF OR FAILURE TO PROVIDE SERVICES, OR\n";  
echo "FOR ANY INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED GRAPHICS OBTAINED THROUGH THIS WEB SITE, OR OTHERWISE ARISING OUT OF THE USE OF THIS WEB SITE, WHETHER BASED ON CONTRACT, TORT, STRICT LIABILITY OR OTHERWISE, EVEN IF $myname HAS BEEN ADVISED OF THE POSSIBILITY OF DAMAGES.\n\n"; 

echo "9. DUE TO THE FACT THAT CERTAIN JURISDICTIONS DO NOT PERMIT OR RECOGNIZE AN EXCLUSION OR LIMITATION OF LIABILITY FOR CONSEQUENTIAL OR INCIDENTAL DAMAGES, THE ABOVE LIMITATION MAY NOT APPLY TO YOU. IF YOU ARE DISSATISFIED WITH ANY PORTION OF THIS WEB SITE, OR WITH ANY OF THESE TERMS OF USE, YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE USING THIS WEB SITE.\n\n";

echo "10. $myname reserves the right in its sole discretion to deny any user access to this Web site, any interactive service herein, or any portion of this Web site without notice, and the right to change the terms, conditions, and notices under which this Web site is offered.\n\n";

echo "11. This agreement is governed by the laws of the Kingdom of Belgium. You hereby consent to the exclusive jurisdiction and venue of courts of Brussels, Belgium. in all disputes arising out of or relating to the use of this Web site. Use of this Web site is unauthorized in any jurisdiction that does not give effect to all provisions of these terms and conditions, including without limitation this paragraph. You agree that no joint venture, partnership, employment, or agency relationship exists between you and $myname as a result of this agreement or use of this Web site. The performance of this agreement by $myname is subject to existing laws and legal process, and nothing contained in this agreement is in derogation of its right to comply with governmental, court and law enforcement requests or requirements relating to your use of this Web site or information provided to or gathered with respect to such use. If any part of this agreement is determined to be invalid or unenforceable pursuant to applicable law including, but not limited to, the warranty disclaimers and liability limitations set forth above, then the invalid or unenforceable provision will be deemed superseded by a valid, enforceable provision that most closely matches the intent of the original provision and the remainder of the agreement shall continue in effect.\n\n";

echo "12. This agreement constitutes the entire agreement between the user and $myname with respect to this Web site and it supersedes all prior or contemporaneous communications and proposals, whether electronic, oral or written with respect to this Web site. A printed version of this agreement and of any notice given in electronic form shall be admissible in judicial or administrative proceedings based upon or relating to this agreement to the same extent and subject to the same conditions as other business documents and records originally generated and maintained in printed form. Fictitious names of companies, products, people, characters and/or data mentioned herein are not intended to represent any real individual, company, product or event. Any rights not expressly granted herein are reserved.\n\n";

echo "13. $myname can be reached by email: $email_feedback.\n\n";

echo "14. All contents of this Web site are: Copyright © $myname.\n\n";

} // End printTermsOfUse

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printDetails() {

// ------------------------------------------------------------------------- 
// How it works
// ------------------------------------------------------------------------- 

	echo "<div class=\"header21\">How it works</div>\n";

	echo "The normal way to connect to your FTP server is to use an FTP client and to communicate via the FTP protocol. This is however not always possible:\n";
	echo "<ul>\n";
	echo "<li>you may be behind a corporate firewall at work, which may block the FTP communications;</li>\n";
	echo "<li>you may be on holiday and connecting to the internet via a CyberCafe, where you may not install an FTP client.</li>\n";
	echo "</ul><br />\n";
	echo "With net2ftp, you connect to net2ftp using the HTTP protocol and a web browser, and net2ftp establishes a FTP connection with your FTP server.<br /><br />\n";
	echo "You don't have to worry about keeping the connection alive, for example when you are coding a script for a long period of time, because no session information is kept on the net2ftp servers, and each time you connect to net2ftp, a new connection is made to your FTP server.<br /><br />\n";
	echo "net2ftp also provides additional features, on top of the regular FTP features: the possibility to <b>edit code using your web browser</b>, and to view the code with <b>syntax highlighting</b>.<br /><br />\n";

	echo "<a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br /><br /><br /><br />\n";

// ------------------------------------------------------------------------- 
// Data security
// ------------------------------------------------------------------------- 

	echo "<div class=\"header21\">Data security</div>\n";

	echo "<div class=\"header31\">On the internet</div>\n";
	echo "Password - As with regular FTP, your password is sent in clear text over the network.<br /><br />\n";
	echo "Program Code - Idem. As with regular FTP, data is sent in clear text.<br /><br />\n";
	echo "In the future, encrypted HTTPS connections might be offered on net2ftp. The password and code will be protected up to the net2ftp servers, but they will still be unencrypted from the net2ftp servers to your FTP server -- as is the case with regular FTP.<br /><br /><br />\n";

	echo "<div class=\"header31\">At net2ftp</div>\n";
	echo "Password - net2ftp does not log passwords.<br /><br />\n";
	echo "Program Code - Once the data files are uploaded to the FTP server, they are erased from the net2ftp servers. While in transit, those files are inaccessible from the internet.<br /><br />\n";

	echo "<a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br /><br /><br /><br />\n";

// ------------------------------------------------------------------------- 
// Data integrity
// ------------------------------------------------------------------------- 

	echo "<div class=\"header21\">Data integrity</div>\n";

	echo "<div class=\"header31\">Bugs</div>\n";
	echo "PLEASE MAKE A BACKUP OF YOUR DATA.<br />\n";
	echo "Although net2ftp has been tested intensively, there might still be some undiscovered bugs. If you think you found one, please <a href=\"" . printPHP_SELF("no") . "?state=feedback\" target=\"_blank\">contact us</a>.<br /><br />\n";

	echo "<div class=\"header31\">FTP tranmission mode</div>\n";
	echo "When data is transferred using FTP, this can be done using the ASCII mode or the BINARY mode.<br />\n";
	echo "net2ftp makes this decision automatically based on the filename extension:\n";
	echo "<ul>\n";
	echo "	<li> The default mode is ASCII.</li>\n";
	echo "	<li> The BINARY mode is used for:</li>\n";
	echo "	<ul>\n";
	echo "		<li> images: png, jpg, jpeg, gif, bmp, tif, tiff;</li>\n";
	echo "		<li> executables: exe, com, bin;</li>\n";
	echo "		<li> MS Office documents: doc, xls, ppt, mdb, vsd, mpp;</li>\n";
	echo "		<li> archives: zip, tar, gz, arj, arc;</li>\n";
	echo "		<li> and others: mov, mpg, mpeg, ram, rm, qt, swf, fla, pdf, ps, wav.</li>\n";
	echo "	</ul>\n";
	echo "</ul>\n";
	echo "If you would like other extensions to be transmitted using the BINARY mode, please <a href=\"" . printPHP_SELF("no") . "?state=feedback\" target=\"_blank\">contact us</a>.<br /><br />\n";

	echo "<a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br /><br /><br /><br />\n";

// ------------------------------------------------------------------------- 
// Abuse
// ------------------------------------------------------------------------- 

	echo "<div class=\"header21\">Abuse</div>\n";

	echo "For every connection to net2ftp, these data are logged: time, browser IP address, target FTP server and FTP username. This is to prevent the abuse of net2ftp or FTP servers from net2ftp. The use of net2ftp is a priviledge, not a right, and users may be banned at the sole discretion of the net2ftp webmasters.<br /><br />\n";
	echo "If you want your FTP server not to be accessible via net2ftp, please <a href=\"" . printPHP_SELF("no") . "?state=feedback\" target=\"_blank\">contact us</a>.<br /><br />\n";

	echo "<a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br /><br /><br /><br />\n";

} // End printDetails

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printScreenshots() {

$client_imagesdir = "/images";

	echo "<table cellspacing=\"10\" style=\"padding: 10px;\">\n";

	echo "<tr><td colspan=\"2\"><a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a></td></tr>\n";

// Browse IE and Konqueror
	echo "<tr>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/browse-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/browse-35.jpg\" border=\"2\"></a><br />Browse the FTP server (Phoenix under Windows)</td>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/browse-konqueror-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/browse-konqueror-35.jpg\" border=\"2\"></a><br />Browse the FTP server (Konqueror under Linux)</td>\n";
	echo "</tr>\n";

// Download and upload
	echo "<tr>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/download-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/download-35.jpg\" border=\"2\"></a><br />Download a file</td>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/upload-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/upload-35.jpg\" border=\"2\"></a><br />Upload files</td>\n";
	echo "</tr>\n";

// Edit and view
	echo "<tr>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/edit-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/edit-35.jpg\" border=\"2\"></a><br />Edit a text file</td>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/view-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/view-35.jpg\" border=\"2\"></a><br />View code with syntax highlighting</td>\n";
	echo "</tr>\n";

// Copy and rename
	echo "<tr>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/copy-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/copy-35.jpg\" border=\"2\"></a><br />Copy directories and files to another directory (same or different FTP server)</td>\n";
	echo "<td valign=\"top\"><a href=\"$client_imagesdir/rename-70.jpg\" target=\"_blank\" alt=\"Screenshot of net2ftp\" title=\"Click to view a larger picture in a new window\"><img src=\"$client_imagesdir/rename-35.jpg\" border=\"2\"></a><br />Rename directories and files</td>\n";
	echo "</tr>\n";

	echo "<tr><td colspan=\"2\"><a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a></td></tr>\n";

	echo "</table>\n";

} // End printScreenshots

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printDownload() {

// ------------------------------------------------------------------------- 
// Top: introduction
// ------------------------------------------------------------------------- 
	echo "<div class=\"header21\">Installation instructions & support</div>\n";
	echo "In order to <b>install</b> net2ftp on your own web server, download the most recent zip file below, and unzip it on your computer.\n";
	echo "Edit the settings.inc.php file to set your preferences, and then upload the files to your web server -- done!<br /><br />\n";
	echo "If you need <b>help</b>, if you think you've found a bug, or just want to make a remark, post a message on the <a href=\"/forum\">User Forum</a>.<br /><br />\n";

// ------------------------------------------------------------------------- 
// Bottom: download
// ------------------------------------------------------------------------- 
	echo "<table border=\"0\" cellspacing=\"2\" style=\"margin-left: 50px; padding: 2px;\">\n";
	echo "<tr><td><b>Version 0.61</b></td>  <td><a href=\"download/net2ftp_v0.61.zip\">Download</a> 135 kB</td> <td><a href=\"download/_CHANGES_v0.61\" target=\"_blank\">Changelog</a></td>  <td><a href=\"download/_TODO_v0.61\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.6</td>          <td><a href=\"download/net2ftp_v0.6.zip\">Download</a> 135 kB</td>  <td><a href=\"download/_CHANGES_v0.6\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.6\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.5</td>          <td><a href=\"download/net2ftp_v0.5.zip\">Download</a> 66 kB</td>   <td><a href=\"download/_CHANGES_v0.5\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.5\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.4</td>          <td><a href=\"download/net2ftp_v0.4.zip\">Download</a> 66 kB</td>   <td><a href=\"download/_CHANGES_v0.4\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.4\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.3</td>          <td><a href=\"download/net2ftp_v0.3.zip\">Download</a> 65 kB</td>   <td><a href=\"download/_CHANGES_v0.3\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.3\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.2</td>          <td><a href=\"download/net2ftp_v0.2.zip\">Download</a> 62 kB</td>   <td><a href=\"download/_CHANGES_v0.2\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.2\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.1</td>          <td><a href=\"download/net2ftp_v0.1.zip\">Download</a> 60 kB</td>   <td><a href=\"download/_CHANGES_v0.1\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.1\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "</tr></table>\n";


	echo "<br /><br /><br />\n";
	echo "<a href=\"" . printPHP_SELF("no") . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br /><br /><br /><br />\n";

} // End printDownload

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function checkAuthorization($ftpserver, $ftpserverport, $username) {

// --------------
// This function 
//    checks if the FTP server is in the list of those that may be accessed
//    checks if the FTP server is in the list of those that may NOT be accessed
//    checks if the IP address is in the list of banned IP addresses
//    checks if the FTP server port is in the allowed range
// If all is OK, then the user may continue...
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $check_authorization, $net2ftp_allowed_ftpservers, $net2ftp_banned_ftpservers, $net2ftp_banned_addresses, $net2ftp_allowed_ftpserverport;
	global $REMOTE_ADDR;

	if ($check_authorization == "yes") {

// -------------------------------------------------------------------------
// Check if the FTP server is in the list of those that may be accessed
// -------------------------------------------------------------------------
		if ($net2ftp_allowed_ftpservers[1] != "ALL") {       // net2ftp_allowed_servers contains either "ALL", either a list of allowed servers
			$result1 = array_search($ftpserver, $net2ftp_allowed_ftpservers);
			if ($result1 == false) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 1, allowed FTP servers", "The FTP server <b>$ftpserver</b> is not in the list of allowed FTP servers.<br />"); }
		}

// -------------------------------------------------------------------------
// Check if the FTP server is in the list of those that may NOT be accessed
// -------------------------------------------------------------------------
		$result2 = array_search($ftpserver, $net2ftp_banned_ftpservers);
		if ($result2 != false) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 2, banned FTP servers", "The FTP server <b>$ftpserver</b> is in the list of banned FTP servers.<br />"); }

// -------------------------------------------------------------------------
// Check if the IP address is in the list of banned IP addresses
// -------------------------------------------------------------------------
		$result3 = array_search($REMOTE_ADDR, $net2ftp_banned_addresses);
		if ($result3 != false) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 3, banned IP addresses", "Your IP address ($REMOTE_ADDR) is in the list of banned IP addresses.<br />"); }

// -------------------------------------------------------------------------
// Check if the FTP server port is OK
// -------------------------------------------------------------------------
// Do not perform this check if ALL ports are allowed
		if ($net2ftp_allowed_ftpserverport != "ALL" ) { 
// Report the error if another port nr has been entered than the one which is allowed
			if ($ftpserverport != $net2ftp_allowed_ftpserverport) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 4, allowed FTP server port", "The FTP server port $ftpserverport may not be used.<br />"); }
		}


	} // end if check_authorization

// -------------------------------------------------------------------------
// If everything is OK, return true
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end checkAuthorization

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function logAccess($page) {

// --------------
// This function logs user accesses to the site
// Used in the function HtmlBegin(), see file html.inc.php
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $log_access, $use_database;
	global $net2ftp_ftpserver, $net2ftp_username;
	global $REMOTE_ADDR, $REMOTE_PORT, $HTTP_USER_AGENT, $HTTP_REFERER;
	global $state, $state2, $directory, $file;

// -------------------------------------------------------------------------
// Check if the logging of Errors is ON or OFF
// -------------------------------------------------------------------------

	if ($log_access == "yes" && $use_database == "yes") {

// -------------------------------------------------------------------------
// Connect to the DB
// -------------------------------------------------------------------------
		$resultArray = connect2db();
		$mydb = getResult($resultArray);
		if ($mydb == false) { return putResult(false, "", "logAccess", "logAccess > " . $resultArray['drilldown'], $resultArray['message']); }

// -------------------------------------------------------------------------
// Log the accesses
// -------------------------------------------------------------------------
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$sqlquerystring = "INSERT INTO net2ftp_logAccess VALUES('$date', '$time', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT', '$page', '$net2ftp_ftpserver', '$net2ftp_username', '$state', '$state2', '$directory', '$file', '$HTTP_REFERER')";
		$result1 = @mysql_query($sqlquerystring);
		if ($result1 == false) { return putResult(false, "", "logAccess", "logAccess > sqlquery 1", "Unable to execute the SQL query 1<br />"); }
//		$affectedofrows = @mysql_affected_rows($mydb);

	} // end if logAccesses

// -------------------------------------------------------------------------
// If everything is OK, return true, let the user in
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end logAccess()
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function logLogin($input_ftpserver, $input_username) {

// --------------
// This function logs user logins to the site
// Used in the index.php page
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $log_login, $use_database;
	global $REMOTE_ADDR, $REMOTE_PORT, $HTTP_USER_AGENT;

// -------------------------------------------------------------------------
// Check if the logging of Logins is ON or OFF
// -------------------------------------------------------------------------
	if ($log_login == "yes" && $use_database == "yes") {

// -------------------------------------------------------------------------
// Connect to the DB
// -------------------------------------------------------------------------
	$resultArray = connect2db();
	$mydb = getResult($resultArray);
	if ($mydb == false) { return putResult(false, "", "logLogin", "logLogin > " . $resultArray['drilldown'], $resultArray['message']); }

// -------------------------------------------------------------------------
// Log the Logins
// -------------------------------------------------------------------------
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$sqlquerystring = "INSERT INTO net2ftp_logLogin VALUES('$date', '$time', '$input_ftpserver', '$input_username', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT')";
		$result1 = @mysql_query($sqlquerystring);
		if ($result1 == false) { return putResult(false, "", "logLogin", "logLogin > sqlquery 1", "Unable to execute the SQL query 1<br />"); }
//		$affectedofrows = @mysql_affected_rows($mydb);

	} // end if logLogins 

// -------------------------------------------------------------------------
// If everything is OK, return true, let the user in
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end logLogin()
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function logError($message, $cause, $drilldown, $debug1, $debug2, $debug3, $debug4, $debug5) {

// --------------
// This function logs user accesses to the site
// Used in the function printErrorMessage(), see file errorhandling.inc.php
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $log_error, $use_database;
	global $net2ftp_ftpserver, $net2ftp_username;
	global $state, $state2, $directory;
	global $REMOTE_ADDR, $REMOTE_PORT, $HTTP_USER_AGENT;

// -------------------------------------------------------------------------
// Check if the logging of Errors is ON or OFF
// -------------------------------------------------------------------------
	if ($log_error == "yes" && $use_database == "yes") {

// -------------------------------------------------------------------------
// Connect to the DB
// -------------------------------------------------------------------------
		$resultArray = connect2db();
		$mydb = getResult($resultArray);
		if ($mydb == false) { return putResult(false, "", "logError", "logError > " . $resultArray['drilldown'], $resultArray['message']); }

// -------------------------------------------------------------------------
// Log the Errors
// -------------------------------------------------------------------------
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$sqlquerystring = "INSERT INTO net2ftp_logError VALUES('$date', '$time', '$net2ftp_ftpserver', '$net2ftp_username', '$message', '$cause', '$drilldown', '$state', '$state2', '$directory', '$debug1', '$debug2', '$debug3', '$debug4', '$debug5', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT')";
		$result1 = @mysql_query($sqlquerystring);
		if ($result1 == false) { return putResult(false, "", "loguser", "loguser > sqlquery 1", "Unable to execute the SQL query 1<br />"); }
//		$affectedofrows = @mysql_affected_rows($mydb);

	} // end if logErrors

// -------------------------------------------------------------------------
// If everything is OK, return true, let the user in
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end logError()
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************

?>