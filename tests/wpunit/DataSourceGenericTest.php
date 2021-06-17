<?php

use Really_Rich_Results\Data_Sources\Generic;

class DataSourceGenericTest extends \Codeception\TestCase\WPTestCase {

    /**
     * @var \WpunitTester
     */
    protected $tester;
    
    public function setUp(): void {
        // Before...
        parent::setUp();

        // Your set up methods here.
    }

    public function tearDown(): void {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
    }

    public function testType() {
        $dataSource = new Generic( 'RichResultsTest' );
        $this->assertEquals( 'RichResultsTest', $dataSource->get_type() );

        $dataSource->set_type( 'SomethingElse' );
        $this->assertEquals( 'SomethingElse', $dataSource->get_type() );
    }

    public function testSchemaProperty() {
        $dataSource = new Generic();
        $dataSource->set_schema_property( 'examplePropName', 'exampleValue' );
        $this->assertEquals( 'exampleValue', $dataSource->get_schema_property( 'examplePropName' ) );

        $dataSource->set_schema_property( 'examplePropName', 'somethingElse' );
        $this->assertEquals( 'somethingElse', $dataSource->get_schema_property('examplePropName') );
    }
}
