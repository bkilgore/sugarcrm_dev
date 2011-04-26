<?php
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


require_once("include/Expressions/Dependency.php");
require_once("include/Expressions/Trigger.php");
require_once("include/Expressions/Expression/Parser/Parser.php");
require_once("include/Expressions/Actions/ActionFactory.php");

class ActionFactoryTest extends Sugar_PHPUnit_Framework_TestCase
{
    var $removeCustomDir = false;
    
    protected function createCustomAction()
    {
        $actionContent = <<<EOQ
<?php
require_once("include/Expressions/Actions/AbstractAction.php");

class TestCustomAction extends AbstractAction{
    function __construct(\$params) { }
    static function getJavascriptClass() { return ""; }
    function getJavascriptFire() { return ""; }
    function fire(&\$target){}
    function getDefinition() {
        return array(   
            "action" => \$this->getActionName(), 
            "target" => "nothing"
        );
    }
    
    static function getActionName() {
        return "testCustomAction";
    }
}
EOQ;
        if (!is_dir("custom/" . ActionFactory::$action_directory)) {
            sugar_mkdir("custom/" . ActionFactory::$action_directory, null, true);
            $this->removeCustomDir = true;
        }
        file_put_contents("custom/" . ActionFactory::$action_directory . "/testCustomAction.php", $actionContent);
    }
    
    protected function removeCustomAction()
    {
        unlink("custom/" . ActionFactory::$action_directory . "/testCustomAction.php");
        if ($this->removeCustomDir)
            unlink("custom/" . ActionFactory::$action_directory);
    }
    
    public function testGetNewAction()
    {
        $sva = ActionFactory::getNewAction('SetValue',
            array(
                'target' => 'name', 
                'value' => 'strlen($name)'
            )
        );
        $this->assertType("SetValueAction", $sva);
    }
    
    public function testLoadCustomAction()
    {
        
        $this->createCustomAction();
        ActionFactory::buildActionCache(true);
        $customAction = ActionFactory::getNewAction('testCustomAction', array());
        $this->assertType("TestCustomAction", $customAction);
        $this->removeCustomAction();
        ActionFactory::buildActionCache(true);
    }
}