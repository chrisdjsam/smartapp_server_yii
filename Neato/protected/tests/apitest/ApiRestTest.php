<?php
class ApiRestTest extends ApiTestBase {

	public function testGetApiVersion() {
		$api = 'site.get_api_version';
		$post = array();
		$contents = $this->sendApi($api, $post);
		
		$this->assertEquals(1, $contents->result);
	}
}
?>