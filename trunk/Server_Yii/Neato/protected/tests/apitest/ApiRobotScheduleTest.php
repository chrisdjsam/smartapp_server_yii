<?php
class ApiRobotScheduleTest extends ApiTestBase {

	protected static $robot_serial_no;
	protected static $robot_name;
	protected static $robot_schedule_id;
	protected static $robot_schedule_type_value_for_basic;
	protected static $robot_schedule_type_value_for_advance;
	protected static $robot_schedule_xml_data;
	protected static $robot_schedule_encoded_blob_data;

	public static function setUpBeforeClass(){
		self::$robot_serial_no = 'sl_'.time();
		self::$robot_name = 'Vacum cleaner';

		$api = 'robot.create';
		$post = array('serial_number' => self::$robot_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);

		self::$robot_schedule_type_value_for_basic = "Basic";
		self::$robot_schedule_type_value_for_advance = "Advanced";
		self::$robot_schedule_encoded_blob_data = "R0lGODlhDgAOALMAAOUwVO+AlvfAy+MgRuEOOOc/YPjL1PKYqu9/leQqT+ESO+dAYf///98ALAAAAAAAACH5BAAAAAAALAAAAAAOAA4AAARGkEmDilIFGclPamCYHB0BAoIAgARpfOAiLSIVNkMQDHd1ywxayPKb3S7F4LGQFIIyzd4rBHQmNgfTLdTqwG4jzqRyyWwkEQA7";
		self::$robot_schedule_xml_data = "<xml_dummy>dummy</xml_dummy>";
	}

	public function testPostScheduleData() {
		$api = 'robotschedule.post_data';
		$post = array('serial_number' => self::$robot_serial_no,
				'schedule_type' => self::$robot_schedule_type_value_for_advance,
				'xml_data' => self::$robot_schedule_xml_data,
				'encoded_blob_data' => self::$robot_schedule_encoded_blob_data);
		$contents = $this->sendApi($api, $post);
		self::$robot_schedule_id = $contents->result->robot_schedule_id;

		$this->assertEquals(1, $contents->result->success);
		$this->assertEquals(self::$robot_schedule_type_value_for_advance, $contents->result->schedule_type);
		$this->assertEquals(1, $contents->result->xml_data_version);
		$this->assertEquals(1, $contents->result->blob_data_version);

		$post = array('serial_number' => self::$robot_serial_no,
				'schedule_type' => self::$robot_schedule_type_value_for_advance.'_xyz',
				'xml_data' => self::$robot_schedule_xml_data,
				'encoded_blob_data' => self::$robot_schedule_encoded_blob_data);
		$contents = $this->sendApi($api, $post);

		$this->assertEquals("Robot schedule type is  not valid", $contents->message);
	}

	public function testGetRobotSchedules() {
		$api = 'robotschedule.get_schedules';
		$post = array('serial_number' => self::$robot_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertCount(1, $contents->result);

		$api = 'robot.create';
		$rb_serial_no = "sl_no_".time();
		$post = array('serial_number' => $rb_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);

		$api = 'robotschedule.get_schedules';
		$post = array('serial_number' => $rb_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertCount(0, $contents->result);
	}

	public function testGetScheduleData() {
		$api = 'robotschedule.get_data';
		$post = array('robot_schedule_id' => self::$robot_schedule_id);
		$contents = $this->sendApi($api, $post);

		$have_xml_data_url = false;
		if($contents->result->xml_data_url){
			$have_xml_data_url = true;
		}
		$this->assertTrue($have_xml_data_url);

		$have_blob_data_url = false;
		if($contents->result->blob_data_url){
			$have_blob_data_url = true;
		}
		$this->assertTrue($have_blob_data_url);
	}

	public function testUpdateScheduleData() {
		$api = 'robotschedule.update_data';
		$post = array('robot_schedule_id' => self::$robot_schedule_id,
				'xml_data_version' => 1,
				'xml_data' => self::$robot_schedule_xml_data,
				'blob_data_version' => 1,
				'encoded_blob_data' => self::$robot_schedule_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot schedule data.', $contents->result->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id,
				'xml_data_version' => 2,
				'xml_data' => self::$robot_schedule_xml_data,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot schedule data.', $contents->result->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id,
				'blob_data_version' => 2,
				'encoded_blob_data' => self::$robot_schedule_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot schedule data.', $contents->result->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id,
				'schedule_type' => self::$robot_schedule_type_value_for_basic,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot schedule data.', $contents->result->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id.time(),
				'xml_data_version' => 2,
				'xml_data' => self::$robot_schedule_xml_data,
				'blob_data_version' => 2,
				'encoded_blob_data' => self::$robot_schedule_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Robot schedule id does not exist', $contents->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Provide at least one data version(xml or blob) or schedule type.', $contents->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id,
				'xml_data_version' => 1,
				'xml_data' => self::$robot_schedule_xml_data,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Version mismatch for schedule xml data.', $contents->message);

		$post = array('robot_schedule_id' => self::$robot_schedule_id,
				'blob_data_version' => 1,
				'encoded_blob_data' => self::$robot_schedule_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Version mismatch for schedule blob data.', $contents->message);
	}

	public function testDeleteSchedule() {
		$api = 'robotschedule.delete_data';
		$post = array('robot_schedule_id' =>self::$robot_schedule_id);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("You have successfully deleted robot schedule data.", $contents->result->message);
	}
}

?>
