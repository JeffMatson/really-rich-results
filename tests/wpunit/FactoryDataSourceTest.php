<?php

class FactoryDataSourceTest extends \Codeception\TestCase\WPTestCase
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
	public function testCreateDataSourceFromWP_Post() {
		$post = static::factory()->post->create_and_get();
		$post_data_source = \Really_Rich_Results\Factories\Data_Source::create( $post );
		
		$this->assertInstanceOf( \Really_Rich_Results\Data_Sources\WP_Post::class, $post_data_source);
	}

	public function testCreateDataSourceFromWP_User() {
		$user = static::factory()->user->create_and_get();
		$user_data_source = \Really_Rich_Results\Factories\Data_Source::create( $user );
		
		$this->assertInstanceOf( \Really_Rich_Results\Data_Sources\WP_User::class, $user_data_source);
	}

	public function testCreateDataSourceFromWP_Term() {
		$term = static::factory()->term->create_and_get();
		$term_data_source = \Really_Rich_Results\Factories\Data_Source::create( $term );
		
		$this->assertInstanceOf( \Really_Rich_Results\Data_Sources\WP_Term::class, $term_data_source);
	}

	public function testCreateDataSourceFromWP_Comment() {
		$comment = static::factory()->comment->create_and_get();
		$comment_data_source = \Really_Rich_Results\Factories\Data_Source::create( $comment );
		
		$this->assertInstanceOf( \Really_Rich_Results\Data_Sources\WP_Comment::class, $comment_data_source);
	}
}
