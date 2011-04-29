<?php
require_once 'modules/Import/ImportCacheFiles.php';

class SugarTestImportUtilities
{
    public static  $_createdFiles = array();

    private function __construct() {}

    public function __destruct()
    {
        self::removeAllCreatedFiles();
    }

    public static function createFile(
        $lines = 2000,
        $columns = 3
        ) 
    {
        $filename = $GLOBALS['sugar_config']['import_dir'].'test'.date("YmdHis");
        $fp = fopen($filename,"w");
        for ($i = 0; $i < $lines; $i++) {
            $line = array();
            for ($j = 0; $j < $columns; $j++)
                $line[] = "foo{$i}{$j}";
            fputcsv($fp,$line);
        }
        fclose($fp);
        
        self::$_createdFiles[] = $filename;
        
        return $filename;
    }
	
    public static function createFileWithEOL(
        $lines = 2000,
        $columns = 3
        ) 
    {
        $filename = $GLOBALS['sugar_config']['import_dir'].'test'.date("YmdHis");
        $fp = fopen($filename,"w");
        for ($i = 0; $i < $lines; $i++) {
            $line = array();
            for ($j = 0; $j < $columns; $j++) {
            	// test both end of lines: \r\n (windows) and \n (unix)
                $line[] = "start{$i}\r\n{$j}\nend";
            }
            fputcsv($fp,$line);
        }
        fclose($fp);
        
        self::$_createdFiles[] = $filename;
        
        return $filename;
    }
	
    public static function createFileWithWhiteSpace() 
    {
        $filename = $GLOBALS['sugar_config']['import_dir'].'testWhiteSpace'.date("YmdHis");
        $contents = <<<EOTEXT
account2,foo bar
EOTEXT;
        file_put_contents($filename, $contents);
        
        self::$_createdFiles[] = $filename;
        
        return $filename;
    }
    
    public static function removeAllCreatedFiles()
    {
        foreach ( self::$_createdFiles as $file ) {
            @unlink($file);
            $i = 0;
            while(true) {
                if ( is_file($file.'-'.$i) ) 
                    unlink($file.'-'.$i++);
                else 
                    break;
            }
        }
        ImportCacheFiles::clearCacheFiles();
    }
}
