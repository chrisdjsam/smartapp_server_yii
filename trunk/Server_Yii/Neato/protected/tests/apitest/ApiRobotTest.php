<?php
class ApiRobotTest extends ApiTestBase {

	protected static $robot_serial_no;
	protected static $robot_name;
	protected static $email;
	protected static $user_name;
	protected static $password;
	protected static $profile;

	public static function setUpBeforeClass(){
		self::$robot_serial_no = 'sl_'.time();
		self::$robot_name = 'Vacum cleaner';
		self::$user_name = 'user_'.time();
		self::$email = self::$user_name.'@neatorobotics.com';
		self::$password = self::$user_name.'_password';
		self::$profile = array('name'=>self::$robot_name.'_new');
	}

	public function testCreate() {
		$api = 'robot.create';
		$post = array('serial_number' => self::$robot_serial_no,
				'name' => self::$robot_name,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("Robot created successfully.", $contents->result->message);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("This robot serial number already exists.", $contents->message);
	}

	public function testGetDetails() {
		$api = 'robot.get_details';
		$post = array('serial_number' => self::$robot_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(self::$robot_serial_no, $contents->result->serial_number);
		$this->assertEquals(self::$robot_name, $contents->result->name);
		
		$rb_serial_no = "sl_no_".time();
		$post = array('serial_number' => $rb_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("Robot serial number does not exist", $contents->message);
	}
	
//	public function testSetProfileDetails() {
//		$api = 'robot.set_profile_details';
//		$post = array('serial_number' => self::$robot_serial_no, 'profile'=> self::$profile);
//		$contents = $this->sendApi($api, $post);
//		$this->assertEquals("1", $contents->result);
//		
//		$api = 'robot.get_details';
//		$post = array('serial_number' => self::$robot_serial_no);
//		$contents = $this->sendApi($api, $post);
//		$this->assertEquals(self::$robot_serial_no, $contents->result->serial_number);
//		$this->assertEquals(self::$profile['name'], $contents->result->name);
//
//		$api = 'robot.set_profile_details';
//		$post = array('serial_number' => self::$robot_serial_no, 'profile'=> array('name'=>self::$robot_name));
//		$contents = $this->sendApi($api, $post);
//		$this->assertEquals("1", $contents->result);		
//	}

	public function testGetAssociatedUsers() {
		$api = 'robot.get_associated_users';
		$post = array('serial_number' => self::$robot_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertCount(0, $contents->result);
	}

	public function testAssociateUser() {
		$api = 'user.create';
		$account_type = 'Native';
		$post = array('name' => self::$user_name,
				'password' => self::$password,
				'email' => self::$email,
				'account_type' => $account_type,
		);
		$contents = $this->sendApi($api, $post);

		$api = 'robot.set_user';
		$post = array('email' => self::$email,
				'serial_number' => self::$robot_serial_no,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("Robot ownership established successfully.", $contents->result->message);

		$contents = $this->sendApi($api, $post);
		$this->assertEquals("This robot ownership relation already exists.", $contents->result->message);
	}

	public function testDisassociateUser() {
		$api = 'robot.disassociate_user';
		$post = array('email' => self::$email,
				'serial_number' => self::$robot_serial_no,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("Robot User association removed successfully.", $contents->result->message);

		$contents = $this->sendApi($api, $post);
		$this->assertEquals("There is no association between provided robot and user", $contents->result->message);
	}
	
	public function testDelete() {
		$api = 'robot.delete';
		$post = array('serial_number' => self::$robot_serial_no,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("You have deleted robot ".self::$robot_serial_no." successfully", $contents->result->message);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("Robot serial number does not exist", $contents->message);
	}
}
?>