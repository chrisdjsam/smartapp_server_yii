<?php
class ApiGridImageTest extends ApiTestBase {

	protected static $robot_serial_no;
	protected static $robot_name;
	protected static $atlas_id;
	protected static $grid_id;
	protected static $id_grid_image;
	protected static $robot_atlas_xml_data;
	protected static $encoded_blob_data;
	protected static $blob_data_version;

	public static function setUpBeforeClass(){
		self::$robot_serial_no = 'sl_'.time();
		self::$robot_name = 'Vacum cleaner';
		self::$grid_id = self::$robot_serial_no."_image";
		self::$encoded_blob_data = "R0lGODlhDgAOALMAAOUwVO+AlvfAy+MgRuEOOOc/YPjL1PKYqu9/leQqT+ESO+dAYf///98ALAAAAAAAACH5BAAAAAAALAAAAAAOAA4AAARGkEmDilIFGclPamCYHB0BAoIAgARpfOAiLSIVNkMQDHd1ywxayPKb3S7F4LGQFIIyzd4rBHQmNgfTLdTqwG4jzqRyyWwkEQA7";
		self::$robot_atlas_xml_data= "<xml_dummy>dummy</xml_dummy>";
		$api = 'robot.create';
		$post = array('serial_number' => self::$robot_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);
		
		$api = 'robot.create';
		$post = array('serial_number' => self::$robot_serial_no,
				'name' => self::$robot_name,
		);
		self::sendApi($api, $post);
	
		$api = 'robot.add_atlas';
		$post = array('serial_number' => self::$robot_serial_no,
				'xml_data' => self::$robot_atlas_xml_data,
		);
		$contents = self::sendApi($api, $post);
		self::$atlas_id = $contents->result->atlas_id;
	}
	
	public function testPostGridImage() {
		$api = 'robot.post_grid_image';
		$post = array('id_atlas' => self::$atlas_id,
				'id_grid' => self::$grid_id,
				'encoded_blob_data' => self::$encoded_blob_data
				);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Combination of atlas id and grid id exist. Try updating for same.", $contents->message);
		
		self::$grid_id = self::$grid_id."1";
		$post['id_grid'] = self::$grid_id;
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		self::$blob_data_version = $contents->result->version;
	}

	public function testUpdateGridImage(){
		$api = 'robot.update_grid_image';
		$post = array(
				'id_atlas' => self::$atlas_id,
				'id_grid' => self::$grid_id,
				'blob_data_version'=> self::$blob_data_version,
				'encoded_blob_data'=> self::$encoded_blob_data
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals(self::$blob_data_version + 1, $contents->result->version);

		$post['id_grid'] = time();
		$post['blob_data_version'] = 0;
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals(1, $contents->result->version);
		
	}
	
	public function testDeleteGridImage(){
		$api = 'robot.delete_grid_image';
		$post = array('id_atlas' => self::$atlas_id,
				'id_grid' => self::$grid_id,
		);
		$contents = $this->sendApi($api, $post);
		$this->assertEquals(0, $contents->status);
		$this->assertEquals(true, $contents->result->success);
		$this->assertEquals("You have successfully deleted grid image.", $contents->result->message);

		$contents = $this->sendApi($api, $post);
		$this->assertEquals(-1, $contents->status);
		$this->assertEquals("Combination of atlas id and grid id does not exist", $contents->message);
		
	}
	
	public static function tearDownAfterClass() {
		$api = 'robot.delete';
		$post = array('serial_number' => self::$robot_serial_no,
		);
	self::sendApi($api, $post);
	}
}

?>



