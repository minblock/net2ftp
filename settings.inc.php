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


//  ------------------------
// | Table of contents:     |
// |    General settings    |
// |    Technical settings  |
// |    Logging             |
// |    Authorizations      |
// |    Layout settings     |
//  ------------------------

// WHEN YOU EDIT THIS FILE, DO NOT ERASE THE SPECIAL CHARACTERS: the dollar 
// sign, double quotes and semi-colon.
//
// Variables are assigned values like this:  $variable_name = "variable_value";
//
// 1 - There should be a leading dollar sign $
// 2 - The variable value should be within double quotes "
// 3 - The line should end with a semi-colon ;

// ----------------------------------------------------------------------------------
// ------------------------------- General settings ---------------------------------
// ----------------------------------------------------------------------------------

// Enter your website address and your email
$my_net2ftp_url = "http://www.my-website.com";
$email_feedback = "net2ftp-feedback@my-website.com";


// ----------------------------------------------------------------------------------
// ------------------------------- Technical settings -------------------------------
// ----------------------------------------------------------------------------------

// ---------------------------------
// Location of net2ftp on the server
// Do not change anything here!
// ---------------------------------
$application_rootdir = dirname(__FILE__);
$application_tempdir = $application_rootdir. "/temp";
$application_tempzipdir = $application_rootdir. "/temp";
$application_version = "0.61";

// ---------------------------------
// Database settings, used to log the actions of the users (no password logging)
// ---------------------------------

// If you want to enable logging (see below), set this to "yes"
$use_database = "no"; 

// Enter your MySQL settings
$dbusername = "";
$dbpassword = "";
$dbname = "";
$dbserver = "localhost";   // on many configurations, this is "localhost"

// ---------------------------------
// Browse settings 
// ---------------------------------

// Show hidden files on the FTP server
// Set to "yes" if you want net2ftp to show hidden directories and files.
// Set it to "no" if you don't care about hidden directories and files. This 
// will make net2ftp a little faster.
$show_hidden_files = "no";

// ---------------------------------
// Upload settings
// ---------------------------------

// Maximum upload filesize allowed **by net2ftp**
// Default: 500 kB, but users have reported it to work up to 15 MB
$max_upload_size = "500000"; // in Bytes

// IF YOU WANT TO ALLOW LARGE FILE UPLOADS, YOU MAY HAVE TO ADJUST
// THE FOLLOWING PARAMETERS:
// 1 - in the file php.ini: upload_max_filesize, post_max_size, 
//     max_execution_time, memory_limit
// 2 - in the file php.conf: LimitRequestBody

// Nr of files and archives that can be uploaded per page
$nr_upload_files = "5";
$nr_upload_archives = "5";


// ---------------------------------
// PHP error reporting. 
// ---------------------------------

// Set to "ALL" or "standard" while you are testing net2ftp
// Set to "NONE" once the testing is done

//$error_reporting = "ALL";
//$error_reporting = "NONE";
$error_reporting = "standard";


// ---------------------------------
// Compress web pages (transmission between web server and browser)
// ---------------------------------
// Benefit: speeds up the transmission
// Drawback: puts more load on the web server's processor

// Set output compression to yes or no
$compress_output = "no";


// ----------------------------------------------------------------------------------
// ------------------------------- Logging ------------------------------------------
// ----------------------------------------------------------------------------------

// Note: if you want to enable the logging, the $use_database setting above
// must also be set to "yes"
$log_access = "yes";
$log_login = "yes";
$log_error = "yes";


// ----------------------------------------------------------------------------------
// ------------------------------- Authorizations -----------------------------------
// ----------------------------------------------------------------------------------

// Choose if you want to perform these checks, each time a user requests a page:
// - If the FTP server is in the list of allowed FTP servers (you can set this to ALL)
// - If the FTP server is in the list of banned FTP servers
// - If the IP address of the user is in the list of banned IP addresses
// - If the FTP server port is in the range allowed

// Note: the checks on the FTP server are only performed on the primary FTP server. 
// It is possible to copy/move files from the primary FTP server to a second FTP server; this one is not checked.

$check_authorization = "yes";


// ---------------------------------
// Allowed FTP servers: either set it to ALL, or else provide a list of allowed servers
// This will automatically change the layout of the login page:
//    - if ALL is entered, then the FTP server input field will be free text
//    - if only 1 entry is entered, then the FTP server input field will not be shown
//    - if more than 1 entry is entered, then the FTP server will have to be chosen from a drop-down list
// ---------------------------------

$net2ftp_allowed_ftpservers[1] = "ALL";
//$net2ftp_allowed_ftpservers[1] = "ftp.your-server.com";
//$net2ftp_allowed_ftpservers[2] = "192.168.1.1";
//$net2ftp_allowed_ftpservers[3] = "ftp.mydomain2.org";



// ---------------------------------
// Banned FTP servers
// Modify this entry, and add other entries if needed, but there should be at least one!
// ---------------------------------

$net2ftp_banned_ftpservers[1] = "ftp.download-music-for-free.com";


// ---------------------------------
// Banned IP addresses
// Modify this entry, and add other entries if needed, but there should be at least one!
// ---------------------------------

$net2ftp_banned_addresses[1] = "10.0.0.1";


// ---------------------------------
// Allowed FTP server port. 
// Set it either to ALL, or to a fixed number
// ---------------------------------

$net2ftp_allowed_ftpserverport = "ALL";
//$net2ftp_allowed_ftpserverport = "21";


// ----------------------------------------------------------------------------------
// ------------------------------- Layout settings ----------------------------------
// ----------------------------------------------------------------------------------

// ---------------------------------
// Skins
// ---------------------------------
// See the file /includes/skins.inc.php


// ---------------------------------
// Settings for the Browse screen
// Choose which column you want to see or hide (set to 0 or 1)
// ---------------------------------
$browse_dir_size = 1; 
$browse_dir_owner = 1; 
$browse_dir_group = 1;
$browse_dir_permissions = 1;
$browse_dir_mtime = 1;

$browse_file_size = 1;
$browse_file_owner = 1;
$browse_file_group = 1;
$browse_file_permissions = 1;
$browse_file_mtime = 1;


// ---------------------------------
// Settings for the Edit screen
// ---------------------------------
// Size of the textarea in which the files are edited
$edit_nrofcolumns = "118";
$edit_nrofrows = "35";


// ---------------------------------
// Settings for the Popup window of the list of directories
// ---------------------------------
$popup_height = "450";
$popup_width = "300";

?>