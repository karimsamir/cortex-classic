<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Cortex\Boards\Models\Board;
use Cortex\Boards\Models\Post;
use Cortex\Boards\Models\Comment;
use Cortex\Boards\Policies\PostPolicy;
use Cortex\Boards\Policies\CommentPolicy;

class PostCommentPolicyTest extends TestCase
{
    public function test_board_owner_can_delete_any_post()
    {
        $board = new Board();
        $board->id = 1;
        $board->owner_id = 7;
        $board->owner_type = 'Member';

        $post = new Post();
        $post->board_id = 1;
        $post->setRelation('board', $board);

        $user = new class {
            public function getKey() { return 7; }
            public function getMorphClass() { return 'Member'; }
        };

        $policy = new PostPolicy();

        $this->assertTrue($policy->delete($user, $post));
    }

    public function test_author_can_delete_own_post()
    {
        $post = new Post();
        $post->created_by_id = 8;
        $post->created_by_type = 'Member';

        $user = new class {
            public function getKey() { return 8; }
            public function getMorphClass() { return 'Member'; }
        };

        $policy = new PostPolicy();

        $this->assertTrue($policy->delete($user, $post));
    }

    public function test_board_owner_can_delete_any_comment_on_his_post()
    {
        $board = new Board();
        $board->id = 2;
        $board->owner_id = 11;
        $board->owner_type = 'Member';

        $post = new Post();
        $post->id = 5;
        $post->setRelation('board', $board);

        $comment = new Comment();
        $comment->post_id = 5;
        $comment->setRelation('post', $post);

        $user = new class {
            public function getKey() { return 11; }
            public function getMorphClass() { return 'Member'; }
        };

        $policy = new CommentPolicy();

        $this->assertTrue($policy->delete($user, $comment));
    }
}
