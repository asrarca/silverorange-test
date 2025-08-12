<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Model\Post;
use silverorange\DevTest\Template;

class PostIndex extends Controller
{
    /**
     * @var array<Post>
     */
    private array $posts = [];

    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Posts';
        $context->content = strval(count($this->posts));
        $context->posts = $this->posts;
        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\PostIndex();
    }

    protected function loadData(): void
    {
        // TODO: Load posts from database here.

        $sql = "SELECT posts.id, posts.title, posts.created_at, authors.full_name as author
            FROM posts LEFT JOIN authors ON posts.author = authors.id";
        $statement = $this->db->query($sql);
        if ($statement) {
            $posts = $statement->fetchAll(\PDO::FETCH_CLASS, Post::class);
            foreach ($posts as $post) {
                array_push($this->posts, $post);
            }
        }
    }
}
