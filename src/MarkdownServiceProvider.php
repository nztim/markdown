<?php declare(strict_types=1);

namespace NZTim\Markdown;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use NZTim\Markdown\YouTubeEmbed\YouTubeIframeExtension;

class MarkdownServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the singleton
        $this->app->singleton('nztim-markdown-converter', function () {
            $environment = Environment::createCommonMarkEnvironment();
            $environment->addExtension(new GithubFlavoredMarkdownExtension());  // Adds Autolinks, Disallow Raw HTML, Strikethrough, Tables, Task Lists
            $environment->addExtension(new AttributesExtension());              // Allows addition of classes and attributes
            $environment->addExtension(new YouTubeIframeExtension());
            $config = [
                'renderer'                       => [
                    'block_separator' => "\n",
                    'inner_separator' => "\n",
                    'soft_break'      => "<br>",                    // Change line breaks into <br>
                ],
                'enable_em'                      => true,
                'enable_strong'                  => true,
                'use_asterisk'                   => true,
                'use_underscore'                 => true,
                'unordered_list_markers'         => ['-', '*', '+'],
                'html_input'                     => 'escape',       // Default is to allow all HTML
                'allow_unsafe_links'             => false,          // E.g. javascript:, file:, data:
                'max_nesting_level'              => INF,
                'youtube_iframe_width'           => 0,              // 0 means skip the attribute
                'youtube_iframe_height'          => 0,              // 0 means skip the attribute
                'youtube_iframe_allowfullscreen' => true,
            ];
            return new CommonMarkConverter($config, $environment);
        });
    }

    public function boot()
    {
        $blade = app(BladeCompiler::class);
        $blade->directive('markdown', function ($string) {
            return "<?php echo markdown($string); ?>";
        });
    }
}
