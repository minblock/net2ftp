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
function getSkinArray() {


// --------------
// This function returns an array of skin names, file names, ...
// --------------

	$skinArray[1]['name'] = "Default";
	$skinArray[1]['css'] = "skin1-default.css";

	$skinArray[2]['name'] = "Blue";
	$skinArray[2]['css'] = "skin2-blue.css";

	$skinArray[3]['name'] = "Low contrast grey";
	$skinArray[3]['css'] = "skin3-grey.css";

	return $skinArray;

} // End function getSkinArray

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printSkinSelect() {


// --------------
// This function prints a select with the available skins
// Skin nr 1 is the default skin
// --------------

	global $net2ftpcookie_skin;

	$skinArray = getSkinArray();

// -------------------------------------------------------------------------
// If there is only one skin, do not print the select, but print a hidden input field
// -------------------------------------------------------------------------
	if (sizeof($skinArray) == 1) {
		echo "<input type=\"hidden\" name=\"input_skin\" value=\"1\">\n";
	}

// -------------------------------------------------------------------------
// If there are more than one skin, print the select
// Pre-select the skin that was last used (using the cookie value)
// -------------------------------------------------------------------------
	else {
		echo "<select name=\"input_skin\" id=\"input_skin\">\n";

// There is no cookie information; set the default skin
		if ($net2ftpcookie_skin == "") { 
			echo "<option value=\"1\" selected>" . $skinArray[1]['name'] . "</option>\n"; 
			for ($i=2; $i<=sizeof($skinArray); $i=$i+1) {
				echo "<option value=\"$i\">" . $skinArray[$i]['name'] . "</option>\n";
			} // end for
		} // end if

// The cookie contains the last skin used; preselect that one
		else {
			for ($i=1; $i<=sizeof($skinArray); $i=$i+1) {
				if ($i == $net2ftpcookie_skin) { $selected = "selected"; }
				else { $selected = ""; }
				echo "<option value=\"$i\" $selected>" . $skinArray[$i]['name'] . "</option>\n";
			} // end for
		} // end else

		echo "</select>\n";
	}

} // End function printSkinSelect

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function getBrowseColors($skin, $what) {

	if ($skin == "1" || $skin == "2") {
		if     ($what == "heading_fontcolor")   { return "#000000"; }
		elseif ($what == "heading_bgcolor")     { return "#CCCCFF"; }
		elseif ($what == "rows_fontcolor_odd")  { return "#000000"; }
		elseif ($what == "rows_bgcolor_odd")    { return "#FFFFFF"; }
		elseif ($what == "rows_fontcolor_even") { return "#000000"; }
		elseif ($what == "rows_bgcolor_even")   { return "#E0E0E0"; }
		elseif ($what == "cursor_fontcolor")    { return "#000000"; }
		elseif ($what == "cursor_bgcolor")      { return "#9999FF"; }
		elseif ($what == "border_color")        { return "#000000"; }
	}
	elseif ($skin == "3") {
		if     ($what == "heading_fontcolor")   { return "#000000"; }
		elseif ($what == "heading_bgcolor")     { return "#EEEEEE"; }
		elseif ($what == "rows_fontcolor_odd")  { return "#000000"; }
		elseif ($what == "rows_bgcolor_odd")    { return "#FFFFFF"; }
		elseif ($what == "rows_fontcolor_even") { return "#000000"; }
		elseif ($what == "rows_bgcolor_even")   { return "#EEEEEE"; }
		elseif ($what == "cursor_fontcolor")    { return "#000000"; }
		elseif ($what == "cursor_bgcolor")      { return "#DDDDDD"; }
		elseif ($what == "border_color")        { return "#000000"; }
	}
	else {
		if     ($what == "heading_fontcolor")   { return "#000000"; }
		elseif ($what == "heading_bgcolor")     { return "#CCCCFF"; }
		elseif ($what == "rows_fontcolor_odd")  { return "#000000"; }
		elseif ($what == "rows_bgcolor_odd")    { return "#FFFFFF"; }
		elseif ($what == "rows_fontcolor_even") { return "#000000"; }
		elseif ($what == "rows_bgcolor_even")   { return "#E0E0E0"; }
		elseif ($what == "cursor_fontcolor")    { return "#000000"; }
		elseif ($what == "cursor_bgcolor")      { return "#9999FF"; }
		elseif ($what == "border_color")        { return "#000000"; }
	}

} // end  function getBrowseColors

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************

?>