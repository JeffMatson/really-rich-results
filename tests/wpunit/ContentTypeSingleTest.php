<?php

use Really_Rich_Results\Content_Types\Single;
use Really_Rich_Results\Factories\Data_Source;
use Really_Rich_Results\Data_Sources\WP_Post;
use Really_Rich_Results\Schema\Thing;

class ContentTypeSingleTest extends \Codeception\TestCase\WPTestCase
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

	public function testSetPrimaryContent() {
		$contentType  = new Single();
		$post         = static::factory()->post->create_and_get();
		$dataSource   = Data_Source::create( $post );

		$contentType->set_primary_content( $dataSource );

		$this->assertInstanceOf( WP_Post::class, $contentType->get_primary_content() );
	}

	public function testBuildSchema() {
		$contentType  = new Single();
		$post         = static::factory()->post->create_and_get();
		$dataSource   = Data_Source::create( $post );

		$contentType->set_primary_content( $dataSource );

		$this->assertInstanceOf( Thing::class, $contentType->build_schema() );
	}
}
