<?php

use Really_Rich_Results\Types;
use Really_Rich_Results\Schema;
use Really_Rich_Results\Data_Sources;
use Really_Rich_Results\Content_Types;

class TypesTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	
	public function setUp(): void
	{
		// Before...
		parent::setUp();

		// Your set up methods here.
	}

	public function tearDown(): void
	{
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function testIsSchemaObject() {
		$schema_object = new Schema\Thing();

		// Check a valid schema object.
		$this->assertTrue( true, Types::is_schema_object( $schema_object ) );
		// Check an empty class.
		$this->assertFalse( Types::is_schema_object( new stdClass() ) );
		// Check a boolean
		$this->assertFalse( Types::is_schema_object( true ) );
	}

	public function testIsContentType() {
		$content_type = new Content_Types\Single();

		// Check an empty single content type.
		$this->assertTrue( Types::is_content_type( $content_type ) );
		// Check an empty class.
		$this->assertFalse( Types::is_content_type( new stdClass() ) );
		// Check a boolean
		$this->assertFalse( Types::is_content_type( true ) );
	}

	public function testIsDataSource() {
		$data_source = new Data_Sources\Generic();

		// Check a generic data source.
		$this->assertTrue( Types::is_data_source( $data_source ) );
		// Check an empty class.
		$this->assertFalse( Types::is_data_source( new stdClass() ) );
		// Check a boolean
		$this->assertFalse( Types::is_data_source( true ) );
	}
}
