<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// takes a file as an argument and parses the stuff as text;

function write_array_to_file( $the_name, $the_array, $the_file ) {
	
    $the_string =   "<?php\n" .
'\n
if(empty(\$GLOBALS["sugarEntry"])) die("Not A Valid Entry Point");
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
' .


                    "\n \$$the_name = " .
                    var_export_helper( $the_array ) .
                    ";\n?>\n";

    if( $fh = @sugar_fopen( $the_file, "w" ) ){
        fputs( $fh, $the_string);
        fclose( $fh );
        return( true );
    }
    else{
        return( false );
    }
}

function var_export_helper($tempArray) { 	 
 		if(!is_array($tempArray)){
 			return var_export($tempArray, true);	
 		}
         $addNone = 0; 	 
  	 
         foreach($tempArray as $key=>$val) 	 
         { 	 
                 if($key == '' && $val == '') 	 
                         $addNone = 1; 	 
         } 	 
  	 
         $newArray = var_export($tempArray, true); 	 
  	 
         if($addNone) 	 
         { 	 
                 $newArray = str_replace("array (", "array ( '' => '',", $newArray); 	 
         } 	 
  	 
         return $newArray;
 }

function grabFiles($url) {
	$dh = fsockopen($url, 80);
	while($fileName = readdir($dh)) {
		if(is_dir($url.$fileName)) {
			grabFiles($url.$fileName);
		}
		
		$fh = sugar_fopen($url.$fileName, "r");
		
		$fileContent = fread($fh, filesize($url.$fileName));
		
		$writeFile = "./{$fileName}";
		$fhLocal = sugar_fopen($writeFile, "w");
		
		fwrite($writeFile, $fileContent);
	}
}

///////////////////////////////////////////////////////////////////////////////
////	START CODE

while($file = readdir($dhUnicode)) {
	if(is_dir($file)) {
		$dhUniDeep = opendir("http://www.unicode.org/Public/MAPPINGS/OBSOLETE/EASTASIA/{$file}");
		
	}
}







$dh = opendir("./");
$search = array(" ", "  ", "   ", "    ");
$replace = array("\t","\t","\t","\t");


if(is_resource($dh)) {
	while($inputFile = readdir($dh)) {
		if(strpos($inputFile, "php")) {
			continue;
		}
		
		$inputFileVarSafe = str_replace("-","_",$inputFile);
		$outputFile = $inputFileVarSafe.".php";
		
		$fh = sugar_fopen($inputFile, "r");
		if(is_resource($fh)) {
			$charset = array();
			while($line = fgets($fh)) {
				$commentPos = strpos($line, "#");
				if($commentPos == 0) {
					continue; // skip comment strings
				}
				

				$exLine = str_replace($search, $replace, $line);
				$exLine = explode("\t", $line);


				$count = count($exLine);
				if($count < 2) {
					echo "count was {$count} :: file is {$inputFile} :: Error parsing line: {$line}\r";
					continue; // unexpected explode
				}
				
				// we know 0 is charset encoding
				// we know 1 is unicode in hex
				$countExLine = count($exLine);
				for($i=1; $i<$countExLine; $i++) {
					$exLine[$i] = trim($exLine[$i]);
					if($exLine[$i] != "") {
						$unicode = $exLine[$i];
						break 1;
					}
				}
				$charset[$exLine[0]] = $unicode;
				
			}
			
			if(count($charset) > 0) {
				write_array_to_file($inputFileVarSafe, $charset, $outputFile);
			}
			
		} else {
			echo "Error occured reading line from file!\r";
		}
		
	}	
} else {
	die("no directory handle");
}




echo "DONE\r";
?>