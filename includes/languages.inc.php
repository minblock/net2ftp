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
function getLanguageArray() {


// --------------
// This function returns an array of languages
// --------------

	$languageArray[1] = "English";
//	$languageArray[2] = "Dutch";
//	$languageArray[3] = "French";
//	$languageArray[4] = "German";

	return $languageArray;

} // End function getLanguageArray

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printLanguageSelect() {


// --------------
// This function prints a select with the available languages
// Language nr 1 is the default language
// --------------

	global $net2ftpcookie_language;

	$languageArray = getLanguageArray();

	echo "<select name=\"input_language\" id=\"input_language\">\n";

// There is no cookie information; set the default language
	if ($net2ftpcookie_language == "") { 
		echo "<option value=\"1\" selected>" . $languageArray[1] . "</option>\n"; 
		for ($i=2; $i<=sizeof($languageArray); $i=$i+1) {
			echo "<option value=\"$i\">" . $languageArray[$i] . "</option>\n";
		} // end for
	} // end if

// The cookie contains the last language used; preselect that one
	else {
		for ($i=1; $i<=sizeof($languageArray); $i=$i+1) {
			if ($i == $net2ftpcookie_language) { $selected = "selected"; }
			else { $selected = ""; }
			echo "<option value=\"$i\" $selected>" . $languageArray[$i] . "</option>\n";
		} // end for
	} // end else

	echo "</select>\n";

} // End function printLanguageSelect

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

//function getText($language) {


//} // end  function getText

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************

?>