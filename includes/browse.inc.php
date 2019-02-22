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
function browse($state2, $directory, $FormAndFieldName) {


// --------------
// This function shows the subdirectories and files in a particular directory
// From this page it is possible to go to subdirectories, or view/edit/rename/delete files
// --------------

// -------------------------------------------------------------------------
// Open connection
// -------------------------------------------------------------------------
	$resultArray = ftp_openconnection();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }


// -------------------------------------------------------------------------
// Get raw list of directories and files
// Parse the raw list and return a nice list
// -------------------------------------------------------------------------
	$nicelist= ftp_getlist($conn_id, $directory);


// -------------------------------------------------------------------------
// Close connection
// -------------------------------------------------------------------------
	ftp_closeconnection($conn_id);


// -------------------------------------------------------------------------
// Depending on the state2 variable...
// -------------------------------------------------------------------------
	if ($state2 == "main") {
		printLocationActions($directory);
		printdirfilelist($directory, $nicelist, "directories");
		printdirfilelist($directory, $nicelist, "files");
	}
	elseif ($state2 == "popup") {
		printDirectorySelect($directory, $nicelist, $FormAndFieldName);
	}

} // End function browse
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftp_getlist($conn_id, $directory) {

// --------------
// This function connects to the FTP server and returns an array with a list of directories and files.
// One row per directory or file, with rows from index 1 to n
// 
// Step 1: send ftp_rawlist request to the FTP server; this returns a string
// Step 2: parse that string and get a first array ($templist)
// Step 3: move the rows to another array, to index 1 to n ($nicelist)
// 
// !!!!!!!!!! Used in these functions: browse, ftp_copymovedeletedirectory !!!!!!!!!!
//
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $browse_dir_size;


// -------------------------------------------------------------------------
// If the $directory == "", set it to "/" to list the contents of the root directory
// If it would is "", some FTP servers list the contents of the home directory instead of the root directory
// -------------------------------------------------------------------------
	if (strlen($directory) < 1) { $directory = "/"; }


// -------------------------------------------------------------------------
// Chdir to the directory
// This is to check if the directory exists, but also because otherwise
// the ftp_rawlist does not work on some FTP servers.
// -------------------------------------------------------------------------
	$result1 = @ftp_chdir($conn_id, $directory);

// If the first ftp_chdir returns false, try a second time without the leading /
// Some Windows FTP servers do not work when you try with a leading /
	if ($result1 == false) { 
		if ($directory == "/") { $directory2 = ""; }
		else { $directory2 = stripDirectory($directory); }

		$result2 = @ftp_chdir($conn_id, $directory2);

		if ($result2 == false) { 
// Delete the directory in the cookie, to redirect the user to the / directory the next time he logs in
// Headers can still be sent at this point, because the output buffering is switched on in index.php
			setcookie ("net2ftpcookie_directory", "", time()+60*60*24*30);
// Print error message
			$resultArray['message'] = "The directory <b>$directory</b> does not exist.<br />"; 
			printErrorMessage($resultArray, "exit");

		} // end if result2

	} // end if result1

// -------------------------------------------------------------------------
// Step 1 - Get list of directories and files
// The -a option is used to show the hidden files as well on some FTP servers
// If the user does not have enough permissions, the -a option does not return anything
// $show_hidden_files can be set in the file settings.inc.php
// -------------------------------------------------------------------------
	if ($show_hidden_files == "yes") {
		$rawlist = ftp_rawlist($conn_id, "$directory -a");
		if (count($rawlist) == 0) { $rawlist = ftp_rawlist($conn_id, ""); }
	}
	else {
		$rawlist = ftp_rawlist($conn_id, "");
	}


// -------------------------------------------------------------------------
// Step 2 - Parse the raw list to get an array
// -------------------------------------------------------------------------
	for($i=0; $i<count($rawlist); $i++) {
		$templist[$i] = ftp_scanline($rawlist[$i]);
	} // End for

// -------------------------------------------------------------------------
// Step 3 - Move the rows so that the array would contain elements from 1 to n
// -------------------------------------------------------------------------
	$i = 0; // $i is the index of templist and could go from 0 to n+3
	$j = 1; // $j is the index of nicelist and should go from 1 to n  (n being the nr of valid rows)

	for ($i=0; $i<count($templist); $i=$i+1) { 
		if (is_array($templist[$i]) == true) { 
			$nicelist[$j] = $templist[$i]; 
			$j = $j + 1; 
		}
	}

	return $nicelist;

// -------------------------------------------------------------------------
// Some documentation:
// 1 - Some FTP servers return a total on the first line
// 2 - Some FTP servers return . and .. in their list of directories
// ftp_scanline does not return those entries.
// -------------------------------------------------------------------------


// 1 - After doing some tests on different public FTP servers, it appears that 
// they reply differently to the ftp_rawlist request:
//      - some FTP servers, like ftp.belnet.be, start with a line summarizing how 
//        many subdirectories and files there are in the current directory. The 
//        real list of subdirectories and files starts on the second line.
//               [0] => total 15
//               [1] => drwxr-xr-x 11 BELNET Archive 512 Feb 6 2000 BELNET
//               [2] => drwxr-xr-x 2 BELNET Archive 512 Oct 29 2001 FVD-SFI
//      - some other FTP servers, like ftp.redhat.com/pub, start directly with the 
//        list of subdirectories and files.
//               [0] => drwxr-xr-x 9 ftp ftp 4096 Jan 11 06:34 contrib
//               [1] => drwxr-xr-x 13 ftp ftp 4096 Jan 29 21:59 redhat
//               [2] => drwxrwsr-x 6 ftp ftp 4096 Jun 05 2002 up2date


// 2 - Some FTP servers return "." and ".." as directories. These fake entries 
// have to be eliminated! 
// They would cause infinite loops in the copy/move/delete functions.
//               [0] => drwxr-xr-x 5 80 www 512 Apr 10 09:39 . 
//               [1] => drwxr-xr-x 16 80 www 512 Apr 9 08:51 .. 
//               [2] => -rw-r--r-- 1 80 www 5647 Apr 9 08:12 _CHANGES_v0.5 
//               [3] => -rw-r--r-- 1 80 www 1239 Apr 9 08:12 _CREDITS_v0.5 


} // End function ftp_getlist
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_scanline($rawlistline) {

// --------------
// This function scans an ftp_rawlist line string and returns its parts (directory/file, name, size,...) using ereg()
// --------------

// ereg() doc comes from php.net
/*
mholdgate@wakefield.co.uk
11-Jan-2002 11:51 

^                Start of String
$                End of string

n*               Zero or more of 'n'
n+               One or more of 'n'
n?               A possible 'n'

n{2}             Exactly two of 'n'
n{2,}            At least 2 or more of 'n'
n{2,4}           From 2 to 4 of 'n'

()               Parenthesis to group expressions
(n|a)            Either 'n' or 'a'

.                Any single character

[1-6]            A number between 1 and 6
[c-h]            A lower case character between c and h
[D-M]            An upper case character between D and M
[^a-z]           Absence of lower case a to z
[_a-zA-Z]        An underscore or any letter of the alphabet

^.{2}[a-z]{1,2}_?[0-9]*([1-6]|[a-f])[^1-9]{2}a+$

A string beginning with any two characters
Followed by either 1 or 2 lower case alphabet letters
Followed by an optional underscore
Followed by zero or more digits
Followed by either a number between 1 and 6 or a character between a and f (Lowercase)
Followed by a two characters which are not digits between 1 and 9
Followed by one or more n characters at the end of a string
*/

// $regs can contain a maximum of 10 elements !! (regs[0] to regs[9])
// To specify what you really want back from ereg, use (). Only what is within () will be returned. See below.


// -----------------------------------------------------------------
//
// ftp.redhat.com:
//drwxr-xr-x    6 0        0            4096 Aug 21  2001 pub (one or more spaces between entries)
//
// ftp.suse.com:
//drwxr-xr-x   2 root     root         4096 Jan  9  2001 bin
//
//-rw-r--r--    1 suse     susewww       664 May 23 16:24 README.txt
//
// ftp.belnet.be:
//-rw-r--r--   1 BELNET   Mirror        162 Aug  6  2000 HEADER.html
//drwxr-xr-x  53 BELNET   Archive      2048 Nov 13 12:03 mirror
//
// ftp.microsoft.com:
//-r-xr-xr-x   1 owner    group               0 Nov 27  2000 dirmap.htm
//
// ftp.sourceforge.net:
//-rw-r--r--   1 root     staff    29136068 Apr 21 22:07 ls-lR.gz
//
// ftp.nec.com:
//dr-xr-xr-x  12 other        512 Apr  3  2002 pub
//
// ftp.intel.com
//drwxr-sr-x   11 root     ftp          4096 Sep 23 16:36 pub
//
//
	if (ereg("([-dl])([rwxst-]{9})[ ]+([0-9]+)[ ]+([a-zA-Z0-9_-]+)[ ]+([a-zA-Z0-9_ -]+)[ ]+([0-9]+)[ ]+([a-zA-Z]+[ ]+[0-9]+)[ ]+([0-9:]+)[ ]+(.*)", $rawlistline, $regs) == true) {
//              permissions             number      owner               group                size         month        day        year/hour              filename
		$nicelistline[0] = "$regs[1]";		// Directory ==> d, File ==> -
		$nicelistline[1] = "$regs[9]";		// Filename
		$nicelistline[2] = "$regs[6]";		// Size
		$nicelistline[3] = "$regs[4]";		// Owner
		$nicelistline[4] = "$regs[5]";		// Group
		$nicelistline[5] = "$regs[2]";		// Permissions
		$nicelistline[6] = "$regs[7] $regs[8]";	// Mtime -- format depends on what FTP server returns (year, month, day, hour, minutes... see above)
	}

// -----------------------------------------------------------------
// AS400 FTP servers return this:
//
// RGOVINDAN 932 03/29/01 14:59:53 *STMF /cert.txt
// QSYS 77824 12/17/01 15:33:14 *DIR /QOpenSys/
// QDOC 24576 12/31/69 20:00:00 *FLR /QDLS/
// QSYS 12832768 04/14/03 16:47:25 *LIB /QSYS.LIB/
// QDFTOWN 2147483647 12/31/69 20:00:00 *DDIR /QOPT/
// QSYS 2144 04/12/03 12:49:00 *DDIR /QFileSvr.400/
// QDFTOWN 1136 04/12/03 12:49:01 *DDIR /QNTC/ 

	elseif (ereg("([a-zA-Z0-9_-]+)[ ]+([0-9]+)[ ]+([0-9/-]+)[ ]+([0-9:]+)[ ]+([a-zA-Z0-9_ -\*]+)[ /]+([^/]+)", $rawlistline, $regs) == true) {
//                  owner               size        date          time          type                     filename

		if ($regs[5] != "*STMF") { $directory_or_file = "d"; }
		elseif ($regs[5] == "*STMF") { $directory_or_file = "-"; }

		$nicelistline[0] = "$directory_or_file";	// Directory ==> d, File ==> -
		$nicelistline[1] = "$regs[6]";		// Filename
		$nicelistline[2] = "$regs[2]";		// Size
		$nicelistline[3] = "$regs[1]";		// Owner
		$nicelistline[4] = "";				// Group
		$nicelistline[5] = "";				// Permissions
		$nicelistline[6] = "$regs[3] $regs[4]";	// Mtime -- format depends on what FTP server returns (year, month, day, hour, minutes... see above)
	}

// Some FTP servers return "." and ".." as directories. These fake entries 
// have to be eliminated! 
	if ($nicelistline[1] == "." || $nicelistline[1] == "..") { return ""; }

	return $nicelistline;

} // End function ftp_scanline

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printstateform($formName) {

// --------------
// This function prints *the beginning* of an HTML form and some javascript
// The form has to be closed afterwards with this tag: </form>
// The checkboxes contain the array of selected directories/files
// --------------

// -------- Form --------------------------------
// ----------------------------------------------
	echo "<form name=\"$formName\" id=\"$formName\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" />\n";
	echo "<input type=\"hidden\" name=\"directory\" />\n";
	echo "<input type=\"hidden\" name=\"entry\" />\n";

// -------- Javascript --------------------------
// ----------------------------------------------
	echo "<script type=\"text/javascript\"><!--\n";

//                   submitListOfDirectoriesForm and submitListOfFilesForm
	echo "function submit$formName(directory, entry, state, state2) {\n";

	echo "	document.$formName.state.value=state;\n";
	echo "	document.$formName.state2.value=state2;\n";
	echo "	document.$formName.directory.value=directory;\n";
	echo "	document.$formName.entry.value=entry;\n";

	echo "	document.$formName.submit(); \n";
	echo "}\n"; // End javascript function submit$formName

	echo "//--></script>\n";

} // End function printstateform
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printdirfilelist($directory, $nicelist, $directoriesorfiles) {

// --------------
// This function uses an array of directories or files to print a nice looking page ;-)
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $browse_dir_size,  $browse_dir_owner,  $browse_dir_group,  $browse_dir_permissions,  $browse_dir_mtime;
	global $browse_file_size, $browse_file_owner, $browse_file_group, $browse_file_permissions, $browse_file_mtime;
	global $net2ftp_skin;

	$browse_heading_fontcolor   = getBrowseColors($net2ftp_skin, "heading_fontcolor");
	$browse_heading_bgcolor     = getBrowseColors($net2ftp_skin, "heading_bgcolor");
	$browse_rows_fontcolor_odd  = getBrowseColors($net2ftp_skin, "rows_fontcolor_odd");
	$browse_rows_bgcolor_odd    = getBrowseColors($net2ftp_skin, "rows_bgcolor_odd");
	$browse_rows_fontcolor_even = getBrowseColors($net2ftp_skin, "rows_fontcolor_even");
	$browse_rows_bgcolor_even   = getBrowseColors($net2ftp_skin, "rows_bgcolor_even");
	$browse_cursor_fontcolor    = getBrowseColors($net2ftp_skin, "cursor_fontcolor");
	$browse_cursor_bgcolor      = getBrowseColors($net2ftp_skin, "cursor_bgcolor");
	$browse_border_color        = getBrowseColors($net2ftp_skin, "border_color");

	$dir_colspan =  1 + 1 + $browse_dir_size  + $browse_dir_owner  + $browse_dir_group  + $browse_dir_permissions  + $browse_dir_mtime; 		// name, ...
	$file_colspan = 1 + 1 + $browse_file_size + $browse_file_owner + $browse_file_group + $browse_file_permissions + $browse_file_mtime + 3;	// name, ..., 2 action column

// -------------------------------------------------------------------------
// Replace ' by \' in $directory and $dirfilename to avoid javascript errors if 
// these variables contain single quotes (they may not contain double quotes).
// -------------------------------------------------------------------------
		$directory_js = str_replace("'", "\'", $directory);
//		$dirfilename_js = str_replace("'", "\'", $dirfilename);  ==> see for loop, at this point, dirfilename does not contain any value yet

// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------
// ------------------------------- First rows --------------------------------------
// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------


// ------------------------------- Javascript functions and forms ------------------
// ---------------------------------------------------------------------------------

	if ($directoriesorfiles=="directories") { printstateform("ListOfDirectoriesForm"); }
	elseif ($directoriesorfiles=="files") { printstateform("ListOfFilesForm"); }






//	echo "\n\n<table align=\"center\" width=\"90%\" border=\"2\" frame=\"box\" rules=\"none\">\n";
	echo "\n\n<table align=\"center\" class=\"browse_table\">\n";

// ------------------------------- Subdirectories: first rows ----------------------
// ---------------------------------------------------------------------------------
	if ($directoriesorfiles=="directories") {

// First row
		echo "<tr class=\"browse_rows_heading\">\n";
		echo "<td colspan=\"";
		echo $dir_colspan; // Span all columns
		echo "\">\n";
		echo "<div style=\"font-size: 160%; text-align: center;\">Subdirectories</div>\n";
		echo "</td>\n";
		echo "</tr>\n";
// Second row: go up to higher directory
		echo "<tr ";
	// Style
		echo "class=\"browse_rows_heading\" style=\"font-size: 120%\" ";
	// onMouseOver / out
		echo "onMouseOver=\"this.style.background='" . $browse_cursor_bgcolor . "';\" ";
		echo "onMouseOut =\"this.style.background='" . $browse_heading_bgcolor . "';\" ";
	// onClick
		echo "onClick=\"submitListOfDirectoriesForm('" . upDir($directory_js) . "', '', 'browse', 'main');\" ";
	// Title
		echo "title=\"Go to the subdirectory ";
		if (upDir($directory) == "") { echo "/"; }
		elseif (upDir($directory) != "") { echo upDir($directory); }
		echo "\">\n";

		echo "<td colspan=\"" . $dir_colspan . "\" style=\"cursor: pointer; cursor: hand;\">Up</td>\n";
		echo "</tr>\n";

// Third row: actions
		echo "<tr class=\"browse_rows_odd\">\n";
		echo "<td colspan=\"" . $dir_colspan . "\" style=\"text-align: right;\">";
		echo "Transform selected subdirectories: ";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Copy\"   onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'copydirectory');\"   title=\"Copy the selected directories\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Move\"   onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'movedirectory');\"   title=\"Move the selected directories\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Delete\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'deletedirectory');\" title=\"Delete the selected directories\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Rename\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'renamedirectory');\" title=\"Rename the selected directories\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Chmod\"  onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'chmoddirectory');\"  title=\"Chmod the selected directories &#13; (Functionality only provided on Unix/Linux!/BSD servers)\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Zip\"    onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'downloadzipdirectory');\"     title=\"Download the selected directories in a zip archive\" />\n";
		echo "</td>\n";
		echo "</tr>\n";

// Fourth row: title
		echo "<tr class=\"browse_rows_heading\">\n";
                                              echo "<td style=\"cursor: pointer; cursor: hand;\"><a style=\"text-decoration: underline;\" onClick=\"CheckAll(document.ListOfDirectoriesForm);\"  title=\"Click to check or uncheck all rows\">All</a></td>\n";
                                              echo "<td>Name</td>\n";
        	if ($browse_dir_size==1)        { echo "<td>Size</td>\n"; }
        	if ($browse_dir_owner==1)       { echo "<td>Owner</td>\n"; }
		if ($browse_dir_group==1)       { echo "<td>Group</td>\n"; }
		if ($browse_dir_permissions==1) { echo "<td>Perms</td>\n"; }
		if ($browse_dir_mtime==1)       { echo "<td>Mod Time</td>\n"; }

//		echo "<td colspan=\"5\">\n";
//		echo "Action\n";
//		echo "</td>\n";
		echo "</tr>\n";
	}
// ------------------------------- Files: first rows -------------------------------
// ---------------------------------------------------------------------------------
	elseif ($directoriesorfiles=="files") {

// First row
		echo "<tr class=\"browse_rows_heading\">\n";
		echo "<td colspan=\"";
		echo $file_colspan; // Span all columns
		echo "\">\n";
		echo "<div style=\"font-size: 160%; text-align: center;\">Files</div>\n";
		echo "</td>\n";
		echo "</tr>\n";

// Second row: actions
		echo "<tr class=\"browse_rows_odd\">\n";
		echo "<td colspan=\"" . $file_colspan . "\" style=\"text-align: right;\">";
		echo "Transform selected files: ";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Copy\"   onClick=\"submitListOfFilesForm('$directory_js', '',   'manage', 'copyfile');\"        title=\"Copy the selected files\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Move\"   onClick=\"submitListOfFilesForm('$directory_js', '',   'manage', 'movefile');\"        title=\"Move the selected files\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Delete\" onClick=\"submitListOfFilesForm('$directory_js', '',   'manage', 'deletefile');\"      title=\"Delete the selected files\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Rename\" onClick=\"submitListOfFilesForm('$directory_js', '',   'manage', 'renamefile');\"      title=\"Rename the selected files\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Chmod\"  onClick=\"submitListOfFilesForm('$directory_js', '',   'manage', 'chmodfile');\"       title=\"Chmod the selected files &#13; (Functionality only provided on Unix/Linux!/BSD servers)\" />\n";
		echo "<input type=\"button\" class=\"smallbutton\" value=\"Zip\"    onClick=\"submitListOfFilesForm('$directory_js', '',   'manage', 'downloadzipfile');\" title=\"Download the selected files in a zip archive\" />\n";
		echo "</td>\n";
		echo "</tr>\n";

// Third row
		echo "<tr class=\"browse_rows_heading\">\n";
                                               echo "<td style=\"cursor: pointer; cursor: hand;\"><a style=\"text-decoration: underline;\" onClick=\"CheckAll(document.ListOfFilesForm);\" title=\"Click to check or uncheck all rows\">All</a></td>\n";
		                                   echo "<td>Name</td>\n";
		if ($browse_file_size==1)        { echo "<td>Size</td>\n"; }
		if ($browse_file_owner==1)       { echo "<td>Owner</td>\n"; }	
		if ($browse_file_group==1)       { echo "<td>Group</td>\n"; }
		if ($browse_file_permissions==1) { echo "<td>Perms</td>\n"; }
		if ($browse_file_mtime==1)       { echo "<td>Mod Time</td>\n"; }
		echo "<td colspan=\"3\">\n";
		echo "Actions\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------
// ------------------------------- Other rows --------------------------------------
// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------
	$rowcolor = 0; // To create alternating colors for different rows
	for ($i=1; $i<=count($nicelist); $i++) {
		$dirfilename = $nicelist[$i][1];
		$dirfilesize = $nicelist[$i][2];
		$dirfileowner = $nicelist[$i][3];
		$dirfilegroup = $nicelist[$i][4];
		$dirfilepermissions = $nicelist[$i][5];
		$dirfilemtime = $nicelist[$i][6];


// Replace ' by \' in $directory and $dirfilename to avoid javascript errors if 
// these variables contain single quotes (they may not contain double quotes).
//		$directory_js = str_replace("'", "\'", $dirfilename);  ==> see begin of this function, before the for loop
		$dirfilename_js = str_replace("'", "\'", $dirfilename);  

// Replace ' by &#039; to avoid errors when using this variable in an HTML 
// value tag
		$directory_html = htmlspecialchars($directory, ENT_QUOTES);
		$dirfilename_html = htmlspecialchars($dirfilename, ENT_QUOTES);

// ------------------------------- Subdirectories: other rows ----------------------
// ---------------------------------------------------------------------------------
		if ($directoriesorfiles=="directories" && ($nicelist[$i][0]=="d" || $nicelist[$i][0]=="l") && ($dirfilename != "." && $dirfilename != "..")) {
			$rowcolor = $rowcolor + 1;
			if ($rowcolor % 2 == 1) { echo "<tr class=\"browse_rows_odd\"  onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor_odd'; this.style.backgroundColor='$browse_rows_bgcolor_odd';\">\n"; }
			if ($rowcolor % 2 == 0) { echo "<tr class=\"browse_rows_even\" onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor_even'; this.style.backgroundColor='$browse_rows_bgcolor_even';\">\n"; }

// Checkbox
			if ($rowcolor % 2 == 1) { echo "<td title=\"Select the subdirectory $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename . "\" /></td>\n"; }
			if ($rowcolor % 2 == 0) { echo "<td title=\"Select the subdirectory $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename . "\" /></td>\n"; }

// Link: subdirectory
			if ($nicelist[$i][0]=="d") {
				echo "<td onClick=\"submitListOfDirectoriesForm('$directory_js/$dirfilename_js', '', 'browse', 'main');\" title=\"Go to the subdirectory $directory/$dirfilename\" style=\"cursor: pointer; cursor: hand;\">" . $dirfilename . "</td>\n";
			}
// Link: symlink
			elseif ($nicelist[$i][0]=="l") {
				// $dirfilename of symlinks is like this: "subdir1 -> anotherdir"
				// Split the string in 2 parts: "subdir1" and "anotherdir"
				if (ereg("(.*)[ ]*->[ ]*(.*)", $dirfilename_js, $regs) == true) {
					$symlinkname = "$regs[1]";
					$symlinkdir = "$regs[2]";
				}

				if ($directory_js != "") { $realpath = "$directory_js/$symlinkdir"; }
				else { $realpath = $symlinkdir; }
				echo "<td onClick=\"submitListOfDirectoriesForm('$realpath', '', 'browse', 'main');\" title=\"Symlink $dirfilename\" style=\"cursor: pointer; cursor: hand;\">" . $dirfilename . "</td>\n";
			}

// Properties: subdirectory and symlink are the same
			if ($browse_dir_size==1)        { echo "<td>$dirfilesize</td>\n"; }
			if ($browse_dir_owner==1)       { echo "<td>$dirfileowner</td>\n"; }
			if ($browse_dir_group==1)       { echo "<td>$dirfilegroup</td>\n"; }
			if ($browse_dir_permissions==1) { echo "<td>$dirfilepermissions  <input type=\"hidden\" name=\"chmodStrings[]\" value=\"$dirfilepermissions\" /></td>\n"; }
			if ($browse_dir_mtime==1)       { echo "<td>$dirfilemtime</td>\n"; }      
// Actions: directories
//			if ($nicelist[$i][0]=="d") {
//				echo "<td onClick=\"submitListOfDirectoriesForm('$directory_js', '$dirfilename_js', 'manage', 'copydirectory');\"   title=\"Copy the directory $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Copy</td>\n";
//			}
// Actions: symlink
//			elseif ($nicelist[$i][0]=="l") {
//			}

			echo "</tr>\n\n";
		}

// ------------------------------- Files: other rows -------------------------------
// ---------------------------------------------------------------------------------
		elseif ($directoriesorfiles=="files" && $nicelist[$i][0]=="-") {
			$rowcolor = $rowcolor + 1;
			if ($rowcolor % 2 == 1) { echo "<tr class=\"browse_rows_odd\"  onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor_odd'; this.style.backgroundColor='$browse_rows_bgcolor_odd';\">\n"; }
			if ($rowcolor % 2 == 0) { echo "<tr class=\"browse_rows_even\" onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor_even'; this.style.backgroundColor='$browse_rows_bgcolor_even';\">\n"; }

// Checkbox
			if ($rowcolor % 2 == 1) { echo "<td title=\"Select the file $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename_html . "\" /></td>\n"; }
			if ($rowcolor % 2 == 0) { echo "<td title=\"Select the file $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename_html . "\" /></td>\n"; }

// Link
			echo "<td title=\"View the file $dirfilename from your HTTP web server &#13; (Note: This link may not work if you don't have your own domain name.)\" style=\"cursor: pointer; cursor: hand;\" onClick='window.open(\"" . printURL($directory, $dirfilename, no) . "\");'>" . $dirfilename . "</td>\n";

// Properties
			if ($browse_file_size==1)        { echo "<td>$dirfilesize</td>\n"; }
			if ($browse_file_owner==1)       { echo "<td>$dirfileowner</td>\n"; }
			if ($browse_file_group==1)       { echo "<td>$dirfilegroup</td>\n"; }
			if ($browse_file_permissions==1) { echo "<td>$dirfilepermissions  <input type=\"hidden\" name=\"chmodStrings[]\" value=\"$dirfilepermissions\" /></td>\n"; }
			if ($browse_file_mtime==1)       { echo "<td>$dirfilemtime</td>\n"; }

// Actions
			$fileType = getFileType($dirfilename);
// TEXT
			if ($fileType == "TEXT") {
				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'view');\"           title=\"View the highlighted source code of file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">View</td>\n";
				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'edit');\"           title=\"Edit the source code of file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Edit</td>\n";
//				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'copyfile');\"       title=\"Copy the file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Copy</td>\n";
//				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'movefile');\"       title=\"Move the file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Move</td>\n";
//				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'deletefile');\"     title=\"Delete the file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Delete</td>\n";
//				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'renamefile');\"     title=\"Rename the file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Rename</td>\n";
//				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'chmodfile');\"      title=\"Chmod the file $dirfilename &#13; (Only on Unix/Linux/BSD servers)\" style=\"cursor: pointer; cursor: hand;\">Chmod</td>\n";
				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'downloadfile');\"   title=\"Download the file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Download</td>\n";
			} // end if TEXT
// IMAGE, EXECUTABLE, OFFICE, ARCHIVE, OTHER
			else {
				echo "<td></td>\n";
				echo "<td></td>\n";
				echo "<td onClick=\"submitListOfFilesForm('$directory_js', '$dirfilename_js', 'manage', 'downloadfile');\"    title=\"Download the file $dirfilename\" style=\"cursor: pointer; cursor: hand;\">Download</td>\n";
			} // end if else

			echo "</tr>\n\n";
		} // End if elseif

	} // End for

	if ($rowcolor == 0) { // There are no subdirectories or files
		$rowcolor = $rowcolor + 1; // =1
		if ($rowcolor % 2 == 1) { echo "<tr class=\"browse_rows_odd\">\n"; }
		if ($rowcolor % 2 == 0) { echo "<tr class=\"browse_rows_even\">\n"; }
		echo "<td style=\"text-align: center;\" colspan=\"$dir_colspan\"><br />";
		if ($directoriesorfiles=="directories") { echo "No subdirectories"; }
		if ($directoriesorfiles=="files")       { echo "No files"; }
		echo "<br /> <br /></td>\n\n";
		echo "</tr>\n";
	}

	echo "</table>\n\n\n";

	echo "</form>\n"; // This closes or the form "ListOfDirectoriesForm", or the form "ListOfFilesForm"

	echo "<p> </p>\n";

} // End function printdirfilelist
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printLocationActions($directory) {

// --------------
// This function prints the ftp server and a text box with the directory
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $net2ftp_ftpserver;


// -------------------------------------------------------------------------
// Replace ' by \' in $directory and $dirfilename to avoid javascript errors if 
// these variables contain single quotes (they may not contain double quotes).
// -------------------------------------------------------------------------
	$directory_js = str_replace("'", "\'", $directory);

	if (strlen($directory)>0) { $printdirectory = $directory; }
	else                      { $printdirectory = "/"; }


// -------------------------------------------------------------------------
// Print form
// -------------------------------------------------------------------------
	echo "<form name=\"GotoForm\" id=\"GotoForm\" action=\"" . printPHP_SELF("no") . "\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"main\" />\n";
	printLoginInfo();
	echo "<div style=\"text-align: center; margin-top: 20px; margin-bottom: 20px;\">\n";
	echo "<a title=\"Enter a directory in the textbox and press ENTER\" style=\"font-size: 120%;\">$net2ftp_ftpserver</a>\n";
	echo "<input type=\"text\" name=\"directory\" value=\"$printdirectory\" size=\"40\" />\n";
	printDirectoryTreeLink($directory, "GotoForm.directory");
	echo "</div>\n";


	echo "<div style=\"text-align: center; margin-top: 20px; margin-bottom: 20px;\">\n";
	echo "<input type=\"button\" class=\"button\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'browse', 'main');\"         title=\"Refresh this page to see the latest changes\" value=\"Refresh\" />\n";
	echo "<input type=\"button\" class=\"button\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'newdirectory');\" title=\"Make a new subdirectory in directory $printdirectory\" value=\"New subdir\" />\n";
	echo "<input type=\"button\" class=\"button\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'newfile');\"      title=\"Create a new file in directory $printdirectory\" value=\"New file\" />\n";
	echo "<input type=\"button\" class=\"button\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'uploadfile');\"   title=\"Upload new files in directory $printdirectory\" value=\"Upload files\" />\n";
//	echo "<input type=\"button\" class=\"button\" onClick=\"submitListOfDirectoriesForm('$directory_js', '', 'manage', 'advanced');\"     title=\"Go to the advanced functions\" value=\"Advanced\" />\n";
	echo "<input type=\"button\" class=\"button\" onClick=\"submitListOfDirectoriesForm('', '', 'printloginform', '');\"                  title=\"Logout from net2ftp\" value=\"Logout\" />\n";
	echo "</div>\n";
	echo "</form>\n";

	echo "<p> </p>\n";

} // end printLocationActions
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printURL($directory, $file, $htmltags) {

// --------------
// This function prints the URL of the files in the Browse view
// Given the FTP server (ftp.name.com),
//       the directory and file (/directory/file.php)
// It has to return
//       http://www.name.com/directory/file.php
// $htmltags indicates whether the url should be returned enclosed in HTML tags or not
// --------------

	global $net2ftp_ftpserver, $net2ftp_username;


// -------------------------------------------------------------------------
// Convert single quotes from ' to &#039;
// -------------------------------------------------------------------------
	$directory = htmlspecialchars($directory, ENT_QUOTES);
	$file = htmlspecialchars($file, ENT_QUOTES);


// -------------------------------------------------------------------------
// "ftp.membres.lycos.fr" -----> "http://membres.lycos.fr/username"
// -------------------------------------------------------------------------
	if (ereg("ftp.membres.lycos.fr", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://membres.lycos.fr/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "ftpperso.free.fr" -----> "http://username.free.fr"
// -------------------------------------------------------------------------
	elseif (ereg("ftpperso.free.fr", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://" . $net2ftp_username . ".free.fr" . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "web.wanadoo.be" -----> "http://web.wanadoo.be/username"
// -------------------------------------------------------------------------
	elseif (ereg("web.wanadoo.be", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://web.wanadoo.be/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "perso-ftp.wanadoo.fr" -----> "http://perso.wanadoo.fr/username"
// -------------------------------------------------------------------------
	elseif (ereg("perso-ftp.wanadoo.fr", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://perso.wanadoo.fr/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "ftp.wanadoo.es" -----> "http://perso.wanadoo.es/username"
// -------------------------------------------------------------------------
	elseif (ereg("ftp.wanadoo.es", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://perso.wanadoo.es/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// wanadoo uk
// "uploads.webspace.freeserve.net" -----> "http://www.username.freeserve.co.uk"
// -------------------------------------------------------------------------
	elseif (ereg("uploads.webspace.freeserve.net", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://www." . $net2ftp_username . ".freeserve.co.uk" . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "home.wanadoo.nl" -----> "http://home.wanadoo.nl/username"
// -------------------------------------------------------------------------
	elseif (ereg("home.wanadoo.nl", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://home.wanadoo.nl/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "home.planetinternet.be" -----> "http://home.planetinternet.be/~username"
// -------------------------------------------------------------------------
	elseif (ereg("home.planetinternet.be", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://home.planetinternet.be/~" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "home.planet.nl" -----> "http://home.planet.nl/~username"
// -------------------------------------------------------------------------
	elseif (ereg("home.planet.nl", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://home.planet.nl/~" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "users.skynet.be" -----> "http://users.skynet.be/username"
// -------------------------------------------------------------------------
	elseif (ereg("users.skynet.be", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://users.skynet.be/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "ftp.xs4all.nl/WWW/directory" -----> "http://www.xs4all.nl/~username/directory"
// -------------------------------------------------------------------------
	elseif (ereg("ftp.xs4all.nl", $net2ftp_ftpserver, $regs)) {
		if (strlen($directory) < 4) { 
			if ($htmltags == "no") { return "javascript: alert('This file is not accessible from the web');"; }
			else { return "<a title=\"This file is not accessible from the web\" onClick=\"alert('This file is not accessible from the web');\">$file</a>"; }
		}
		else {
			// Transform directory from /WWW/dir to /dir  --> remove the first 4 characters
			$directory = substr($directory, 4);
			$URL = "http://www.xs4all.nl/~" . $net2ftp_username . $directory . "/" . $file;

			if ($htmltags == "no") { return $URL; }
			else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
		} // end if else strlen
	}

// -------------------------------------------------------------------------
// "ftp.server.com/file" -----> "http://www.server.com/file"
// -------------------------------------------------------------------------
	elseif (ereg("ftp.(.+)(.{2,4})", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://www." . $regs[1] . $regs[2] . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "http://192.168.0.1/file" can be determined using "192.168.0.1/file":
// -------------------------------------------------------------------------
	else { 
		$URL = "http://" . $net2ftp_ftpserver. $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}


} // end printURL
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **    
function printDirectoryTreeLink($directory, $FormAndFieldName) {

// --------------
// This function prints a link
// --------------

	$directory_js = str_replace("'", "\'", $directory);

	echo "<a href=\"javascript:createDirectoryTreeWindow('$directory_js', '$FormAndFieldName');\" style=\"font-size: 80%\">List</a>\n";

} // End function printDirectoryTreeLink

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **    

function printDirectorySelect($directory, $nicelist, $FormAndFieldName) {

// $FormAndFieldName can be for example GotoForm.directory

// -------------------------------------------------------------------------
// Start select
// -------------------------------------------------------------------------
	echo "<form name=\"DirectoryTreeForm\" id=\"DirectoryTreeForm\"  action=\"" . printPHP_SELF("no") . "\" method=\"post\"/>\n";

	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"popup\" />\n";
	echo "<input type=\"hidden\" name=\"updirectory\" value=\"" . upDir($directory) . "\"  />\n";
	echo "<input type=\"hidden\" name=\"FormAndFieldName\" value=\"$FormAndFieldName\"/>\n";

	echo "<input type=\"text\" name=\"directory\" value=\"$directory\"/> &nbsp; \n";

	echo "<input type=\"button\" class=\"smallbutton\" value=\"Choose\" onClick=\"opener.document.$FormAndFieldName.value=document.DirectoryTreeForm.directory.value; self.close();\" /><br /><br />\n";

	echo "<div style=\"font-size: 80%;\">Double-click to go to a subdirectory:</div><br />\n";

	echo "<select name=\"DirectoryTreeSelect\" id=\"DirectoryTreeSelect\" size=\"20\" style=\"width: 200px;\" onDblClick=\"submitDirectoryTreeForm();\">\n";
	echo "<option value=\"up\" selected>Up</option>\n";


// -------------------------------------------------------------------------
// Loop
// -------------------------------------------------------------------------

	for ($i=1; $i<=count($nicelist); $i++) {
		$dir_or_file = $nicelist[$i][0];
		$dirfilename = $nicelist[$i][1];
//		$dirfilesize = $nicelist[$i][2];
//		$dirfileowner = $nicelist[$i][3];
//		$dirfilegroup = $nicelist[$i][4];
//		$dirfilepermissions = $nicelist[$i][5];
//		$dirfilemtime = $nicelist[$i][6];

		if (($dir_or_file == "d" || $dir_or_file == "l") && ($dirfilename != "." && $dirfilename != "..")) {

			echo "<option value=\"$dirfilename\">$dirfilename</option>\n";

		} // end if

	} // end for

// -------------------------------------------------------------------------
// End select
// -------------------------------------------------------------------------

	echo "</select>\n";

	echo "<br />\n";
//	echo "<input type=\"button\" class=\"smallbutton\" value=\"Browse\"/ onClick=\"submitDirectoryTreeForm();\">\n";
//	echo "<input type=\"button\" class=\"smallbutton\" value=\"Close\" onClick=\"self.close();\" /><br /><br />\n";
	echo "</form>\n";

} // End function printDirectorySelect

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************

?>