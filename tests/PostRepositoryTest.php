<?php

require_once __DIR__ . '/../src/Repositories/PostRepository.php';
require_once __DIR__ . '/../src/Models/Post.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use src\Repositories\PostRepository;

class PostRepositoryTest extends TestCase {

	private PostRepository $postRepository;

	/**
	 * Runs before each test
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->postRepository = new PostRepository();
        $this->postRepository->beginDbTransaction();
	}

	/**
	 * Runs after each test
	 */
	protected function tearDown(): void {
		parent::tearDown();
        $this->postRepository->rollBackTransaction();
	}

	public function testPostCreation() {
		$post = $this->postRepository->savePost('test', 'body');
		$this->assertEquals('test', $post->title);
		$this->assertEquals('body', $post->body);
	}

	public function testPostRetrieval() {
        $title = 'test';
        $body = 'body';

		// Create a record.
        $post = $this->postRepository->savePost($title, $body);

        // Test retrieving.
        $record = $this->postRepository->getPostById($post->id);
        $this->assertEquals($post->id, $record->id);
        $this->assertEquals($title, $record->title);
        $this->assertEquals($body, $record->body);
	}

    //composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader
	public function testPostUpdate() {
        $title = 'test';
        $body = 'body';
        $updatedTitle = "update test";
        $updatedBody = "update body";

        // Create a record.
        $post = $this->postRepository->savePost($title, $body);

        // Update a record.
        $updatedPost = $this->postRepository->updatePost($post->id, $updatedTitle, $updatedBody);

        // Test updating.
        $record = $this->postRepository->getPostById($post->id);
        $this->assertEquals($post->id, $record->id);
        $this->assertEquals($updatedTitle, $record->title);
        $this->assertEquals($updatedBody, $record->body);
	}

	public function testPostDeletion() {
        $title = 'test';
        $body = 'body';

        // Create a record.
        $post = $this->postRepository->savePost($title, $body);

        // Delete a record.
        $this->postRepository->deletePostById($post->id);

        // Test deleting.
        $record = $this->postRepository->getPostById($post->id);
        $this->assertFalse($record);
	}
}
