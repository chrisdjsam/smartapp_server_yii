<?php
class ApiUserTest extends ApiTestBase {
	
	protected static $user_auth_token;
	protected static $user_name;
	protected static $email;
	protected static $password;
	protected static $account_type;
	protected static $profile;
	protected static $attributes;
	
	protected static $robot_name;
	protected static $robot_serial_no;
	protected static $robot_serial_no_1;
	protected static $robot_serial_no_random;

	public static function setUpBeforeClass(){
		self::$user_name = 'user_name_'.time();
		self::$email = self::$user_name.'@neatorobotics.com';
		self::$password = self::$user_name.'_pswd';
		self::$account_type = 'Native';
		self::$profile = array('name' => self::$user_name.time(), 'Occupation' => 'Programmer');
		self::$attributes = array('operating_system'=>'Android', 'version'=>'4.0');

		self::$robot_name = "Robot_Name_".time();
		self::$robot_serial_no = "Robot_".time();
		self::$robot_serial_no_1 = "Robot_1_".time();
		self::$robot_serial_no_random = "Robot_random_".time();
	}
	
	public function testCreateUser() {
		$api = 'user.create';
		$post = array('name' => self::$user_name,
				'email' => self::$email,
				'password' => self::$password,
				'account_type' => self::$account_type
		);
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("0", $contents->status);
		$this->assertEquals(true, $contents->result->success);
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("-1", $contents->status);		
		
		$post['email'] = $name.'@neatorobotics@com';
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("-1", $contents->status);
		$this->assertEquals("The email address you have provided does not appear to be a valid email address.", $contents->message);
	}
	
	public function testGetUserAuthToken(){
		$api = 'auth.get_user_auth_token';
		$post = array('email' => self::$email,
				'password' => self::$password,
				'account_type' => self::$account_type
		);
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		self::$user_auth_token =  $contents->result;
		
		$post['account_type'] = 'Google';
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Account Type is not supported", $contents->message);

		$post['email'] = 'wrongUserName'.time().'@neatorobotics.com';
		$post['account_type'] = self::$account_type;
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("User could not be authenticated", $contents->message);

		$post['email'] = self::$email;
		$post['password'] = time();
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("User could not be authenticated", $contents->message);
	}	


	public function testChangePassword() {
		$api = 'user.change_password';
		$post = array('auth_token' => self::$user_auth_token,
				'password_old' => self::$password,
				'password_new' => self::$password."_new",
		);
	
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("0", $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("Your password is changed successfully.", $contents->result->message);
	
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("-1", $contents->status);
		$this->assertEquals("Old password does not match with user password.", $contents->message);
	
	}

	public function testForgetPassword() {
		$api = 'user.forget_password';
		
		$post = array('email' => "email",
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("-1", $contents->status);
		$this->assertEquals("Email does not exist.", $contents->message);
		
		$post = array('email' => self::$email,
		);
	
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("0", $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("New password is sent to your email.", $contents->result->message);

	}
	
	
	public function testSetUserAccountDetails(){
		$api = 'user.set_account_details';
		$post = array('email' => self::$email, //user_auth_token
				'auth_token' => self::$user_auth_token,
				'profile' => self::$profile
		);
		
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(0, $contents->status);
		
		unset($post['email']);
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(0, $contents->status);
		
		$post['auth_token'] = time();
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Method call failed the User Authentication", $contents->message);
	}


	public function testGetAttributes(){
		$api = 'user.get_attributes';
		$post = array(
				'auth_token' => self::$user_auth_token,
		);
	
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Attributes not found for this user", $contents->message);
	
		self::testSetAttributes();
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(0, $contents->status);
// 		$this->assertEquals(count(self::$attributes['operating_system']), count(json_decode($contents->result->user_attributes->operating_system)));
// 		$this->assertEquals(count(self::$attributes['version']), count(json_decode($contents->result->user_attributes->version)));
// 		'operating_system'=>'Android', 'version'=>'4.0'
	}
	

	public function testSetAttributes(){
		$api = 'user.set_attributes';
		$post = array(
				'auth_token' => self::$user_auth_token,
				'profile' => self::$attributes,
		);
	
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals("User attributes are set successfully.", $contents->result->message);
	
	}

	public function testGetUserAccountDetails(){
		$api = 'user.get_user_account_details';
		$post = array('email' => self::$email,
				'auth_token' => self::$user_auth_token
		);
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		
		unset($post['email']);
		$contents = $this->sendApi($api,$post);
		$this->assertEquals(0, $contents->status);
		
		$post['auth_token'] = time();
		$contents = $this->sendApi($api,	$post	);
		$this->assertEquals(-1, $contents->status);
	}

	public function testGetUserAssociatedRobots(){
		$api = 'user.get_associated_robots';
		$contents = $this->sendApi($api, array('email' => self::$email, 'auth_token' => self::$user_auth_token));
		$this->assertEquals(0, $contents->status);
		$this->assertCount(0, $contents->result);
		
		$api = 'robot.create';
		$this->sendApi($api, array('serial_number' => self::$robot_serial_no, 'name' => self::$robot_name));
		$this->sendApi($api, array('serial_number' => self::$robot_serial_no_1, 'name' => self::$robot_name));
		$this->sendApi($api, array('serial_number' => self::$robot_serial_no_random, 'name' => self::$robot_name));
		
		$api = 'robot.set_user';
		$this->sendApi($api, array('email' => self::$email, 'serial_number' => self::$robot_serial_no));
		$this->sendApi($api, array('email' => self::$email, 'serial_number' => self::$robot_serial_no_1));
		
		$api = 'user.get_associated_robots';
		$contents = $this->sendApi($api, array('email' => self::$email, 'auth_token' => self::$user_auth_token));
		$this->assertEquals(0, $contents->status);
		$this->assertCount(2, $contents->result);
	
		$contents = $this->sendApi($api, array('auth_token' => self::$user_auth_token));
		$this->assertEquals(0, $contents->status);
		$this->assertCount(2, $contents->result);
	}

	public function testUpdateUserAuthTokenExpiry(){
		$api = 'user.update_auth_token_expiry';
		$contents = $this->sendApi($api, array('auth_token' => self::$user_auth_token));
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("You have successfully updated auth token expiry date.", $contents->result->message);
	}
	
	public function testLogoutUserAuthToken(){
		$api = 'user.logout_auth_token';
		$contents = $this->sendApi($api, array('email' => self::$email, 'auth_token' => self::$user_auth_token));
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("You are successfully logged off.", $contents->result->message);

		$contents = $this->sendApi($api, array('auth_token' => self::$user_auth_token));
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals('Method call failed the User Authentication', $contents->message);
	}

	public function testDisassociateUserFromRobot(){
		$api = 'user.disassociate_robot';
		$contents = $this->sendApi($api, array('email' => self::$email, 'serial_number' => self::$robot_serial_no));
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("User Robot association removed successfully.", $contents->result->message);
		
		$contents = $this->sendApi($api, array('email' => self::$email, 'serial_number' => self::$robot_serial_no_random));
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("There is no association between provided user and robot", $contents->result->message);

		$contents = $this->sendApi($api, array('email' => self::$email));
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("User association with all robot removed successfully.", $contents->result->message);
		
		$contents = $this->sendApi($api, array('email' => self::$email, 'serial_number' => time()));
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Serial number does not exist", $contents->message);
	}
	

}

?>


