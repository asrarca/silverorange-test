<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {
        $rows = [];
        foreach ($context->posts as $post) {
            $rows[] = '<li>
                <a href="/posts/' . $post->id . '">' . $post->title . '</a>
                <div class="author">by ' . $post->author . '</div>
                <div class="date">' . date('F j Y', strtotime($post->created_at)) . '</div>
            </li>';
        }
        $list = implode('', $rows);
        return <<<HTML
            <h1>$context->content Posts</h1>
            <ul class="post-list">
                {$list}
            </ul>
            HTML;
    }
}
