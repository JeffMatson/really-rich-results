<?php

use Really_Rich_Results\Main;
use Really_Rich_Results\Data_Sources\Site;
use Really_Rich_Results\Data_Sources\Generic;

class DataSourceSiteTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	
	public function setUp(): void {
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	// Tests
	public function testMainSiteConfigIsDataSource() {
		$main       = Main::get_instance();
		$dataSource = $main->get_site_config();
		
		$this->assertInstanceOf( Site::class, $dataSource );
	}

	public function testAbstractSchemaProp() {
		$dataSource = new Site();
		$this->assertEquals( get_bloginfo('description'), $dataSource->get_schema_property('abstract') );
	}

	public function testImageSchemaProp() {
		$dataSource = new Site();

		if ( has_custom_logo() || ! empty( get_header_image() ) ) {
			$this->assertInstanceOf( Generic::class, $dataSource->get_schema_property('image') );
		} else {
			$this->assertNull( $dataSource->get_schema_property('image') );
		}
	}

	public function testNameSchemaProp() {
		$dataSource = new Site();
		$this->assertEquals( get_bloginfo('name'), $dataSource->get_schema_property('name') );
	}

	public function testOrganizationSchemaProp() {
		$dataSource   = new Site();
		$organization = $dataSource->get_schema_property('organization');

		$this->assertInstanceOf( Generic::class, $organization );
		$this->assertInstanceOf( Generic::class, $organization->get_schema_property('address') );
		$this->assertIsString( $organization->get_schema_property('name') );
		$this->assertIsString( $organization->get_schema_property('legalName') );
		$this->assertIsString( $organization->get_schema_property('url') );
	}

	public function testPotentialActionSchemaProp() {
		$dataSource = new Site();
		$potentialAction = $dataSource->get_schema_property('potentialAction');

		$this->assertInstanceOf( Generic::class, $potentialAction );
		$this->assertIsString( $potentialAction->get_schema_property('url') );
		$this->assertIsString( $potentialAction->get_schema_property('target') );
		$this->assertIsString( $potentialAction->get_schema_property('query-input') );
	}

	public function testUrlSchemaProp() {
		$dataSource = new Site();
		$this->assertEquals( get_site_url(), $dataSource->get_schema_property('url') );
	}
}
