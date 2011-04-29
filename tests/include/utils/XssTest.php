<?php
require_once 'include/utils.php';

class XssTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function xssData()
    {
        return array(
            array("some data", "some data"),

            array("test <a href=\"http://www.digitalbrandexpressions.com\">link</a>", "test <a href=\"http://www.digitalbrandexpressions.com\">link</a>"),
            array("some data<script>alert('xss!')</script>", "some data<>alert('xss!')</>"),
            array("some data<script src=\" http://localhost/xss.js\"></script>", "some data< src=\" http://localhost/xss.js\"></>"),
            array("some data<applet></applet><script src=\" http://localhost/xss.js\"></script>", "some data<></>< src=\" http://localhost/xss.js\"></>"),
            );
    }

    protected function clean($str) {
        $potentials = clean_xss($str, false);
        if(is_array($potentials) && !empty($potentials)) {
             foreach($potentials as $bad) {
                 $str = str_replace($bad, "", $str);
             }
        }
        return $str;
    }

    /**
     * @dataProvider xssData
     */
    public function testXssFilter($before, $after)
    {
        $this->assertEquals($after, $this->clean($before));
    }

    /**
     * @dataProvider xssData
     */
    public function testXssFilterBean($before, $after)
    {
        $bean = new EmailTemplate();
		$bean->body_html = to_html($before);
        $bean->cleanBean();
        $this->assertEquals(to_html($after), $bean->body_html);
    }
}
