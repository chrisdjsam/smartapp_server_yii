<?php
class ApiRobotAtlasTest extends ApiTestBase {

	protected static $robot_serial_no;
	protected static $robot_name;
	protected static $atlas_id;
	protected static $robot_atlas_xml_data;
	protected static $xml_data_version;
	protected static $encoded_blob_data;

	public static function setUpBeforeClass(){
		self::$robot_serial_no = 'sl_'.time();
		self::$robot_name = 'Vacum cleaner';
		self::$encoded_blob_data = "R0lGODlhDgAOALMAAOUwVO+AlvfAy+MgRuEOOOc/YPjL1PKYqu9/leQqT+ESO+dAYf///98ALAAAAAAAACH5BAAAAAAALAAAAAAOAA4AAARGkEmDilIFGclPamCYHB0BAoIAgARpfOAiLSIVNkMQDHd1ywxayPKb3S7F4LGQFIIyzd4rBHQmNgfTLdTqwG4jzqRyyWwkEQA7";
		self::$robot_atlas_xml_data= "<xml_dummy>dummy</xml_dummy>";
		self::$xml_data_version = 0;
		$api = 'robot.create';
		$post = array('serial_number' => self::$robot_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);
	}
	public  function testGetRobotAtlasData(){
		$api = 'robot.get_atlas_data';
		$post = array('serial_number' => self::$robot_serial_no,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Robot atlas does not exist for this robot", $contents->message);
		
		self::testAddAtlas();
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(self::$atlas_id, $contents->result->atlas_id);
		self::testDeleteRobotAtlas();
	}
	
	public function testAddAtlas() {
		$api = 'robot.add_atlas';
		$post = array('serial_number' => self::$robot_serial_no,
				'xml_data' => self::$robot_atlas_xml_data,
				);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals(1, $contents->result->xml_data_version);
		$this->assertEquals("You have successfully added Robot Atlas", $contents->result->message);

		self::$atlas_id = $contents->result->atlas_id;
		self::$xml_data_version = $contents->result->xml_data_version;
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Robot can have only one atlas", $contents->message);
	}

	public function testUpdateOrAddRobotAtlasData(){
		$api = 'robot.update_atlas';
		$post = array('serial_number' => self::$robot_serial_no,
				'atlas_id' => 0,
				'delete_grids' => 0,
				'xml_data_version'=> self::$xml_data_version,
				'xml_data'=> self::$robot_atlas_xml_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Robot can have only one atlas", $contents->message);

		self::testDeleteRobotAtlas();
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("You have successfully added Robot Atlas", $contents->result->message);

		$post['atlas_id'] = $contents->result->atlas_id;
		$post['xml_data_version'] = $contents->result->xml_data_version;
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals($post['xml_data_version'] + 1, $contents->result->xml_data_version);
		$this->assertEquals("You have successfully updated robot atlas data.", $contents->result->message);
		
		self::$atlas_id = $contents->result->atlas_id;
		self::$xml_data_version = $contents->result->xml_data_version;
		
		$api_grid_image = 'robot.post_grid_image';
		$post_grid_image = array('id_atlas' => self::$atlas_id,
				'id_grid' => time(),
				'encoded_blob_data' => self::$encoded_blob_data,
		);
		$contents_grid_image = $this->sendApi($api_grid_image, $post_grid_image);
		$this->assertEquals(0, $contents_grid_image->status);
		
		$post['delete_grids'] = 1;
		$post['xml_data_version'] = $contents->result->xml_data_version;
		$contents = $this->sendApi($api, $post);
		$this->assertEquals("You have successfully deleted 1 grids, You have successfully updated robot atlas data.", $contents->result->message);
	}
	public function testGetAtlasGridMetadata(){
		$api = 'robot.get_atlas_grid_metadata';
		$post = array('id_atlas' => self::$atlas_id,
		);
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
	}
	
	public function testDeleteRobotAtlas(){
		$api = 'robot.delete_atlas';
		$post = array('atlas_id' => self::$atlas_id,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("You have successfully deleted robot atlas.", $contents->result->message);
	}
	
	public static function tearDownAfterClass() {
		$api = 'robot.delete';
		$post = array('serial_number' => self::$robot_serial_no,
		);
	self::sendApi($api, $post);
	}
}

?>



