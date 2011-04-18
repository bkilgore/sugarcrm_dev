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




if(!is_admin($current_user)){
    sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']); 
}

require_once("modules/Configurator/metadata/SugarpdfSettingsdefs.php");
if(file_exists('custom/modules/Configurator/metadata/SugarpdfSettingsdefs.php')){
    require_once('custom/modules/Configurator/metadata/SugarpdfSettingsdefs.php');
}

if(!empty($_POST['save'])){
    // Save the logos
    $error=checkUploadImage();
    if(empty($error)){
        $focus = new Administration();
        foreach($SugarpdfSettings as $k=>$v){
            if($v['type'] == 'password'){
                if(isset($_POST[$k])){
                    $_POST[$k] = blowfishEncode(blowfishGetKey($k), $_POST[$k]);
                }
            }
        }
        if(!empty($_POST["sugarpdf_pdf_class"]) && $_POST["sugarpdf_pdf_class"] != PDF_CLASS){
            // clear the cache for quotes detailview in order to switch the pdf class.
            if(is_file($GLOBALS['sugar_config']['cache_dir'].'modules/Quotes/DetailView.tpl'))
                unlink($GLOBALS['sugar_config']['cache_dir'].'modules/Quotes/DetailView.tpl');
        }
        $focus->saveConfig();
        header('Location: index.php?module=Administration&action=index');
    }
}

if(!empty($_POST['restore'])){
    $focus = new Administration();    
    foreach($_POST as $key => $val) {
        $prefix = $focus->get_config_prefix($key);
        if(in_array($prefix[0], $focus->config_categories)) {
            $result = $focus->db->query("SELECT count(*) AS the_count FROM config WHERE category = '{$prefix[0]}' AND name = '{$prefix[1]}'");
            $row = $focus->db->fetchByAssoc( $result, -1, true );
            if( $row['the_count'] != 0){
                $focus->db->query("DELETE FROM config WHERE category = '{$prefix[0]}' AND name = '{$prefix[1]}'");
            }
        }
    }
    header('Location: index.php?module=Configurator&action=SugarpdfSettings');
}

echo getClassicModuleTitle(
        "Administration", 
        array(
            "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
           $mod_strings['LBL_PDFMODULE_NAME'],
           ), 
        true
        );

$pdf_class = array("TCPDF"=>"TCPDF","EZPDF"=>"EZPDF");

require_once('include/Sugar_Smarty.php');
$sugar_smarty = new Sugar_Smarty();

$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$sugar_smarty->assign("JAVASCRIPT",get_set_focus_js());
$sugar_smarty->assign("SugarpdfSettings", $SugarpdfSettings);
$sugar_smarty->assign("pdf_enable_ezpdf", PDF_ENABLE_EZPDF);
if(PDF_ENABLE_EZPDF == "0" && PDF_CLASS == "EZPDF"){
    $error = "ERR_EZPDF_DISABLE";
    $sugar_smarty->assign("selected_pdf_class", "TCPDF");
}else{
    $sugar_smarty->assign("selected_pdf_class", PDF_CLASS);
}
$sugar_smarty->assign("pdf_class", $pdf_class);

if(!empty($error)){
    $sugar_smarty->assign("error", $mod_strings[$error]);
}
if (!function_exists('imagecreatefrompng')) {
	$sugar_smarty->assign("GD_WARNING", 1);
}
else
	$sugar_smarty->assign("GD_WARNING", 0);

$sugar_smarty->display('modules/Configurator/tpls/SugarpdfSettings.tpl');

require_once("include/javascript/javascript.php");
$javascript = new javascript();
$javascript->setFormName("ConfigureSugarpdfSettings");
foreach($SugarpdfSettings as $k=>$v){
    if(isset($v["required"]) && $v["required"] == true)
        $javascript->addFieldGeneric($k, "varchar", $v['label'], TRUE, "");
}

echo $javascript->getScript();

function checkUploadImage(){
    $error="";
    $files = array('sugarpdf_pdf_header_logo'=>$_FILES['new_header_logo'], 'sugarpdf_pdf_small_header_logo'=>$_FILES['new_small_header_logo']);
    foreach($files as $k=>$v){
        if(empty($error) && isset($v) && !empty($v['name'])){
            $file_name = K_PATH_CUSTOM_IMAGES .'pdf_logo_'. basename($v['name']);
            if(file_exists($file_name))
                rmdir_recursive($file_name);
            if (!empty($v['error']))
                $error='ERR_ALERT_FILE_UPLOAD';
            if(!mkdir_recursive(K_PATH_CUSTOM_IMAGES))
               $error='ERR_ALERT_FILE_UPLOAD';
            if(empty($error)){
                if (!move_uploaded_file($v['tmp_name'], $file_name))
                    die("Possible file upload attack!\n");
                if(file_exists($file_name) && is_file($file_name)){
                    $img_size = getimagesize($file_name);
                    $filetype = $img_size['mime'];
                    if($filetype != 'image/jpeg' && !empty($_REQUEST['sugarpdf_pdf_class']) && $_REQUEST['sugarpdf_pdf_class'] == "EZPDF"){
                        $error='LBL_ALERT_TYPE_IMAGE_EZPDF';
                    }
                    else if($filetype != 'image/jpeg' && $filetype != 'image/png'){
                        $error='LBL_ALERT_TYPE_IMAGE';
                    }else{
                        $test=$img_size[0]/$img_size[1];
                        if (($test>10 || $test<1)){
                            //$error='LBL_ALERT_SIZE_RATIO_QUOTES';
                        }
                    }
                    if(!empty($error)){
                        rmdir_recursive($file_name);
                    }else{
                        $_POST[$k]='pdf_logo_'. basename($v['name']);
                    }
                }else{
                    $error='ERR_ALERT_FILE_UPLOAD';
                }
            }
        }
    }
    return $error;
}
?>
