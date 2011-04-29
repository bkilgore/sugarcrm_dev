<?php

class ConnectorsTestUtility {
    static function rmdirr($dirname) {
	    // Sanity check
	    if (!file_exists($dirname)) {
	        return false;
	    }

	    // Simple delete for a file
	    if (is_file($dirname) || is_link($dirname)) {
	        return unlink($dirname);
	    }

	    // Loop through the folder
	    $dir = dir($dirname);
	    while (false !== $entry = $dir->read()) {
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }

	        // Recurse
	        self::rmdirr("$dirname/$entry");
	    }

	    // Clean up
	    $dir->close();
	    return rmdir($dirname);
	}
}
