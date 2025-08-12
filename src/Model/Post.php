<?php

namespace silverorange\DevTest\Model;

class Post
{
    public string $id;
    public string $title;
    public string $body;
    public string $created_at;
    public string $modified_at;
    public string $author;

    /**
     * Returns the body as HTML instead of Markdown
     */
    public function getBodyHtml()
    {
        $parsedown = new \Parsedown();
        return $parsedown->text($this->body);
    }
}
