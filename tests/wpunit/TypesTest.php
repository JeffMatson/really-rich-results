<?php

class TypesTest extends \Codeception\TestCase\WPTestCase
{
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
        $schema_object = new \Really_Rich_Results\Schema\Thing();

        // Check a valid schema object.
        $this->assertEquals( \Really_Rich_Results\Types::is_schema_object( $schema_object ), true );
        // Check an empty class.
        $this->assertEquals( \Really_Rich_Results\Types::is_schema_object( new stdClass() ), false );
        // Check a boolean
        $this->assertEquals( \Really_Rich_Results\Types::is_schema_object( true ), false );
    }

    public function testIsContentType() {
        $content_type = new \Really_Rich_Results\Content_Types\Single();

        // Check an empty single content type.
        $this->assertEquals( \Really_Rich_Results\Types::is_content_type( $content_type ), true );
        // Check an empty class.
        $this->assertEquals( \Really_Rich_Results\Types::is_content_type( new stdClass() ), false );
        // Check a boolean
        $this->assertEquals( \Really_Rich_Results\Types::is_content_type( true ), false );
    }

    public function testIsDataSource() {
        $data_source = new \Really_Rich_Results\Data_Sources\Generic();

        // Check a generic data source.
        $this->assertEquals( \Really_Rich_Results\Types::is_data_source( $data_source ), true );
        // Check an empty class.
        $this->assertEquals( \Really_Rich_Results\Types::is_data_source( new stdClass() ), false );
        // Check a boolean
        $this->assertEquals( \Really_Rich_Results\Types::is_data_source( true ), false );
    }
}
