<?php
class ApiRobotMapTest extends ApiTestBase {

	protected static $robot_serial_no;
	protected static $robot_name;
	protected static $robot_map_id;
	protected static $robot_map_xml_data;
	protected static $robot_map_encoded_blob_data;

	public static function setUpBeforeClass(){
		self::$robot_serial_no = 'sl_'.time();
		self::$robot_name = 'Vacum cleaner';

		$api = 'robot.create';
		$post = array('serial_number' => self::$robot_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);

		self::$robot_map_encoded_blob_data = "R0lGODlhDgAOALMAAOUwVO+AlvfAy+MgRuEOOOc/YPjL1PKYqu9/leQqT+ESO+dAYf///98ALAAAAAAAACH5BAAAAAAALAAAAAAOAA4AAARGkEmDilIFGclPamCYHB0BAoIAgARpfOAiLSIVNkMQDHd1ywxayPKb3S7F4LGQFIIyzd4rBHQmNgfTLdTqwG4jzqRyyWwkEQA7";
		self::$robot_map_xml_data = "<xml_dummy>dummy</xml_dummy>";
	}

	public function testPostMapData() {
		$api = 'robot.post_map_data';
		$post = array('serial_number' => self::$robot_serial_no,
				'xml_data' => self::$robot_map_xml_data,
				'encoded_blob_data' => self::$robot_map_encoded_blob_data);
		$contents = $this->sendApi($api, $post);
		self::$robot_map_id = $contents->result->robot_map_id;

		$this->assertEquals(1, $contents->result->success);
		$this->assertEquals(1, $contents->result->xml_data_version);
		$this->assertEquals(1, $contents->result->blob_data_version);
	}

	public function testGetRobotMaps() {
		$api = 'robot.get_maps';
		$post = array('serial_number' => self::$robot_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertCount(1, $contents->result);

		$api = 'robot.create';
		$rb_serial_no = "sl_no_".time();
		$post = array('serial_number' => $rb_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);

		$api = 'robot.get_maps';
		$post = array('serial_number' => $rb_serial_no);
		$contents = $this->sendApi($api, $post);
		$this->assertCount(0, $contents->result);
	}

	public function testGetMapData() {
		$api = 'robot.get_map_data';
		$post = array('robot_map_id' => self::$robot_map_id);
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

	public function testUpdateMapData() {
		$api = 'robot.update_map_data';
		$post = array('map_id' => self::$robot_map_id,
				'xml_data_version' => 1,
				'xml_data' => self::$robot_map_xml_data,
				'blob_data_version' => 1,
				'encoded_blob_data' => self::$robot_map_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot map data.', $contents->result->message);
		
		$post = array('map_id' => self::$robot_map_id,
				'xml_data_version' => 2,
				'xml_data' => self::$robot_map_xml_data,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot map data.', $contents->result->message);
		
		$post = array('map_id' => self::$robot_map_id,
				'blob_data_version' => 2,
				'encoded_blob_data' => self::$robot_map_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('You have successfully updated robot map data.', $contents->result->message);
		
		$post = array('map_id' => self::$robot_map_id.time(),
				'xml_data_version' => 2,
				'xml_data' => self::$robot_map_xml_data,
				'blob_data_version' => 2,
				'encoded_blob_data' => self::$robot_map_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Robot map id does not exist', $contents->message);
		
		$post = array('map_id' => self::$robot_map_id);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Provide at least one data version(xml or blob).', $contents->message);
		
		$post = array('map_id' => self::$robot_map_id,
				'xml_data_version' => 1,
				'xml_data' => self::$robot_map_xml_data,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Version mismatch for xml data.', $contents->message);
		
		$post = array('map_id' => self::$robot_map_id,
				'blob_data_version' => 1,
				'encoded_blob_data' => self::$robot_map_encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals('Version mismatch for blob data.', $contents->message);
	}

	public function testDeleteMap() {
		$api = 'robot.delete_map';
		$post = array('robot_map_id' =>self::$robot_map_id);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("You have successfully deleted robot map data.",$contents->result->message);
	}
}

?>
