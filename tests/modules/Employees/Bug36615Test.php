<?php
require_once 'modules/Users/User.php';
require_once 'modules/Employees/EmployeeStatus.php';
require_once 'SugarTestUserUtilities.php';


class Bug36615Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $focus;
	var $current_user;
	var $view;
	//var $EMPLOYEE_STATUS = "<select name='employee_status'>option value='Acitve' selected=''>Active</option><option value='Terminated'>Terminated</option><option value='Leave of Absence'>Leave of Absence</option>";
	var $emplsts;
	var $sugar_config;

	public function setUp()
	{

		$this->current_user = new User();
		$this->focus = SugarTestUserUtilities::createAnonymousUser();
		$GLOBALS['app_list_strings'] = return_application_language($GLOBALS['current_language']);
		global $sugar_config;
    	$sugar_config['default_user_name'] = $this->focus->user_name;
    	global $app_list_strings;
   		$app_list_strings = return_app_list_strings_language($GLOBALS['current_language']);

	}


	public function tearDown()
	{

	}


	public function testEmployeeStatusAdminEditView()
	{

		$this->current_user->retrieve('1');
		$GLOBALS['current_user'] = $this->current_user;

		$this->view = "EditView";

		$this->emplsts = getEmployeeStatusOptions($this->focus, 'employee_status', '', $this->view);

		//On EditView and admin user, employee_status must not be blank.
		$this->assertNotEquals( $this->emplsts, '');


	}

	public function testEmployeeStatusAdminDeatilView()
	{

		$this->current_user->retrieve('1');
		$GLOBALS['current_user'] = $this->current_user;

		$this->view = "DetailView";

		//setting employee_status to Active. On DetailedView for this user, admin should not see a blank string.
		$this->focus->employee_status = "Active";

		$this->emplsts = getEmployeeStatusOptions($this->focus, 'employee_status', '', $this->view);


		$this->assertNotEquals( $this->emplsts, '');


	}


	public function testEmployeeStatusRegularUserDeatilView()
	{

		$GLOBALS['current_user'] = $this->current_user;

		$this->view = "DetailView";

		$this->current_user->employee_status = "Active";

		$this->emplsts = getEmployeeStatusOptions($this->current_user, 'employee_status', '', $this->view);

		$this->assertEquals( $this->emplsts, 'Active');


	}

	public function testEmployeeStatusRegularUserEditView()
	{

		$GLOBALS['current_user'] = $this->current_user;

		$this->view = "EditView";

		$this->current_user->employee_status = "Active";

		$this->emplsts = getEmployeeStatusOptions($this->current_user, 'employee_status', '', $this->view);

		$this->assertEquals( $this->emplsts, 'Active');


	}

	public function testEmployeeStatusAfterUserEdit()
	{

	//Stub

		//Need to simulate the sitation described in the bug:
		//A regular user edits its own employee page. After clicking Save, the employee_status field is blank.


	}
}
