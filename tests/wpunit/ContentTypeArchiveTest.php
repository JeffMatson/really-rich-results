<?php

use Really_Rich_Results\Content_Types\Archive;
use Really_Rich_Results\Factories\Data_Source;
use Really_Rich_Results\Data_Sources\WP_Post;
use Really_Rich_Results\Data_Sources\WP_Term;
use Really_Rich_Results\Schema\Thing;

class ContentTypeArchiveTest extends \Codeception\TestCase\WPTestCase
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

	public function testAddArchivePost() {
		$contentType = new Archive();
		$post         = static::factory()->post->create_and_get();
		$dataSource  = Data_Source::create( $post );

		$contentType->add_archive_post( $dataSource );

		$this->assertContainsOnlyInstancesOf( WP_Post::class, $contentType->get_archive_posts() );
	}

	public function testSetPrimaryContent() {
		$contentType = new Archive();
		$term         = static::factory()->term->create_and_get();
		$dataSource  = Data_Source::create( $term );

		$contentType->set_primary_content( $dataSource );

		$this->assertInstanceOf( WP_Term::class, $contentType->get_primary_content() );
	}

	public function testBuildSchema() {
		$postFactory = static::factory()->post;
		$termFactory = static::factory()->term;

		$contentType    = new Archive();
		$term           = $termFactory->create_and_get();
		$archivePostIds = $postFactory->create_many( 5 );

		$contentType->set_primary_content( Data_Source::create( $term ) );

		foreach ( $archivePostIds as $archivePostId ) {
			$archivePost = $postFactory->get_object_by_id( $archivePostId );
			$contentType->add_archive_post( Data_Source::create( $archivePost ) );
		}
		
		$schemaObject = $contentType->build_schema();

		$this->assertInstanceOf( Thing::class, $schemaObject );

		$schema = $schemaObject->get_schema();
		$this->assertArrayHasKey( 'mainEntity', $schema );
		$this->assertArrayHasKey( 'itemListElement', $schema['mainEntity'] );
		$this->assertCount( 5, $schema['mainEntity']['itemListElement'] );
	}
}
