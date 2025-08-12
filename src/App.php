<?php

namespace silverorange\DevTest;

class App
{
    protected \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function run($cli_args = []): bool
    {
        if (!empty($cli_args)) {
            if (isset($cli_args[1])) {
                $cli_script = match ($cli_args[1]) {
                    // import-posts is an alias to ImportPosts, for those that
                    // prefer lowercased cli arguments (like me)
                    'ImportPosts', 'import-posts' => new Cli\ImportPosts($this->db),
                    // more scripts can be added here, with aliases, e.g.
                    // 'ExampleScript', 'example-script' => new Cli\ExampleScript($this->db),
                    default => null,
                };
                if (!empty($cli_script)) {
                    $cli_script->run();
                    $cli_script->finish();
                    return true;
                }
            }

            // show a useful message if the user didn't invoke the cli properly
            $scripts = self::getScripts();
            echo "Usage: php src/index.php " . (implode('|', $scripts)) . "\n";
        }
        else {
            $path = is_string($_SERVER['REQUEST_URI'])
                ? $_SERVER['REQUEST_URI']
                : '';

            // Serve static assets.
            if (preg_match('@^/(assets|images|product-images)(/|$)@', $path) === 1) {
                return false;
            }

            $controller = $this->getController($path);
            $context = $controller->getContext();
            $template = $controller->getTemplate();

            $controller->sendHeaders();

            echo $template->render($context);
        }

        return true;
    }

    protected function getController(string $path): Controller\Controller
    {
        $controller = new Controller\NotFound($this->db, []);

        // TODO: Do stuff like parse query params from $_GET here if required.

        // Switch to set up different context data for different URLs.
        if (preg_match('@^/?$@', $path) === 1) {
            $controller = new Controller\Root($this->db, []);
        } elseif (preg_match('@^/posts/?$@', $path) === 1) {
            $controller = new Controller\PostIndex($this->db, []);
        } elseif (preg_match('@^/posts/([a-f0-9-]+)/?$@', $path, $params) === 1) {
            array_shift($params);
            $controller = new Controller\PostDetails($this->db, $params);
        } elseif (preg_match('@^/checkout/?$@', $path) === 1) {
            $controller = new Controller\Checkout($this->db, []);
        }

        return $controller;
    }



    /**
     * Get the non-abstract cli class files
     *
     * Cli.php is removed by setting its name to a blank string, then
     * calling array_filter.
     */
    public static function getScripts() {
        $files = glob(APP_SRC .'/Cli/*.php');
        $scripts = array_values(array_filter(array_map(function($item) {
            $item = basename($item);
            $item = str_replace(['Cli.php', '.php'], ['', ''], $item);
            return $item;
        }, $files)));
        return $scripts;
    }

}
