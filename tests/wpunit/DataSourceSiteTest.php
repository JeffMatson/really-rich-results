<?php

class DataSourceSiteTest extends \Codeception\TestCase\WPTestCase
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
	public function testMainSiteConfigIsDataSource() {
		$main = \Really_Rich_Results\Main::get_instance();
		$data_source = $main->get_site_config();
		
		$this->assertInstanceOf(\Really_Rich_Results\Data_Sources\Site::class, $data_source);
	}

	public function testGettingNameProperty() {
		$main = \Really_Rich_Results\Main::get_instance();
		$data_source = $main->get_site_config();

		$this->assertEquals('Really Rich Results Test', $data_source->get_schema_property('name') );
	}
}
