<?php
	
	require_once 'person.php';

	class PersonTest extends PHPUnit_Framework_TestCase{
		public $test;

		public function setUp(){
			$this->test = new Person("Luis");
		}

		public function testName(){
			$luis = $this->test->getName();
			$this->assertTrue($luis == "Luis");
		}
	}

?>
