<?php

use Really_Rich_Results\Data_Sources\WP_Comment;
use Really_Rich_Results\Factories\Data_Source as Data_Source_Factory;

class WPPostDataSourceTest extends \Codeception\TestCase\WPTestCase
{
    /**
     * @var \WpunitTester
     */
    protected $tester;

    protected $postDataSource;

    protected $createdPost;
    
    public function setUp(): void {
        // Before...
        parent::setUp();

        // Set up post data source.
        $this->createdPost = static::factory()->post->create_and_get();
        $this->postDataSource = Data_Source_Factory::create( $this->createdPost );
    }

    public function tearDown(): void {
        // Reset post data source.
        $this->postDataSource = null;
        $this->createdPost = null;

        // Then...
        parent::tearDown();
    }

    public function testInvalidProp() {
        $this->assertNull( $this->postDataSource->get_schema_property( 'invalid' ) );
    }

    public function testIdProp() {
        $postId = $this->createdPost->ID;
        $this->assertEquals( $this->postDataSource->get_schema_property('@id'), $postId );
    }

    public function testHeadlinePropIsTitle() {
        $postTitle = apply_filters( 'the_title', $this->createdPost->post_title );
        $this->assertEquals( $this->postDataSource->get_schema_property('headline'), $postTitle );
    }

    public function testAbstractPropIsExcerpt() {
        $excerpt = get_the_excerpt( $this->createdPost );
        $this->assertEquals( $this->postDataSource->get_schema_property('abstract'), $excerpt );
    }

    public function testDescriptionPropIsExcerpt() {
        $excerpt = get_the_excerpt( $this->createdPost );
        $this->assertEquals( $this->postDataSource->get_schema_property('description'), $excerpt );
    }

    public function testCommentsPropIsValid() {
        $comment_count = 5;
        $post = static::factory()->post->create_and_get();
        static::factory()->comment->create_post_comments( $post->ID, $comment_count );
        
        $data_source = Data_Source_Factory::create( $post );
        $comments_prop = $data_source->get_schema_property('comment');

        $this->assertCount( $comment_count, $comments_prop );
        $this->assertContainsOnlyInstancesOf( WP_Comment::class, $comments_prop );
    }
}
