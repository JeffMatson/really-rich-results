<?php

use Really_Rich_Results\Data_Sources\WP_Comment;
use Really_Rich_Results\Data_Sources\Generic;
use Really_Rich_Results\Data_Sources\WP_User;
use Really_Rich_Results\Factories\Data_Source as Data_Source_Factory;

class DataSourceWPPostTest extends \Codeception\TestCase\WPTestCase
{
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	private $commentCount = 5;

	public function setUp(): void {
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	private function createPost( $with = array() ) {
		$post = static::factory()->post->create_and_get( array(
			'post_type'   => 'post',
			'post_author' => 1,
		));

		if ( in_array( 'comments', $with ) ) {
			static::factory()->comment->create_post_comments( $post->ID, $this->commentCount );
		}

		if ( in_array( 'featured_image', $with ) ) {
			$attachment = static::factory()->attachment->create_upload_object( codecept_data_dir() . 'test-image.png', $post->ID );
			set_post_thumbnail( $post->ID, $attachment );
		}

		return Data_Source_Factory::create( $post );
	}

	public function testInvalidSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertNull( $dataSource->get_schema_property('invalid_prop_name') );
	}

	public function testDefaultSchemaType() {
		$dataSource = $this->createPost();
		$this->assertEquals( 'Article', $dataSource->get_schema_type() );
	}

	public function testShowAuthor() {
		$dataSource = $this->createPost();
		$this->assertTrue( $dataSource->show_author() );
	}

	public function testIdSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsNumeric( $dataSource->get_schema_property('@id') );
	}

	public function testAbstractSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('abstract') );
	}

	public function testArticleBodySchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('articleBody') );
	}

	public function testArticleSectionSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertContainsOnly( 'string', $dataSource->get_schema_property('articleSection') );
	}

	public function testAuthorSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertInstanceOf( WP_User::class, $dataSource->get_schema_property('author') );
	}

	public function testCommentSchemaProp() {
		$dataSource = $this->createPost( array( 'comments' ) );
		$comments = $dataSource->get_schema_property('comment');

		$this->assertCount( $this->commentCount, $comments );
		$this->assertContainsOnlyInstancesOf( WP_Comment::class, $comments );
	}

	public function testCommentCountSchemaProp() {
		$dataSource = $this->createPost( array( 'comments' ) );
		$this->assertEquals( $this->commentCount, $dataSource->get_schema_property('commentCount') );
	}

	public function testDateCreatedSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('dateCreated') );
	}

	public function testDateModifiedSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('dateModified') );
	}

	public function testDatePublishedSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('datePublished') );
	}

	public function testDescriptionSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('description') );
	}

	public function testDiscussionUrlSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertNull( $dataSource->get_schema_property('discussionUrl') );

		$dataSource = $this->createPost(array('comments'));
		$this->assertIsString( $dataSource->get_schema_property('discussionUrl') );
	}

	public function testHeadlineSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('headline') );
	}

	public function testImageSchemaProp() {
		$dataSource = $this->createPost( array('featured_image') );
		$this->assertIsString( $dataSource->get_schema_property('image') );
	}

	public function testMainEntityOfPageSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('mainEntityOfPage') );
	}

	public function testNameSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('name') );
	}

	public function testPublisherSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertInstanceOf( Generic::class, $dataSource->get_schema_property('publisher') );
	}

	public function testTextSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsString( $dataSource->get_schema_property('text') );
	}

	public function testThumbnailUrlSchemaProp() {
		$dataSource = $this->createPost( array('featured_image') );
		$this->assertIsString( $dataSource->get_schema_property('thumbnailUrl') );
	}

	public function testWordCountSchemaProp() {
		$dataSource = $this->createPost();
		$this->assertIsNumeric( $dataSource->get_schema_property('wordCount') );
	}
}
