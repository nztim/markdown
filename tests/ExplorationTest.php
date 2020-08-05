<?php declare(strict_types=1);

namespace NZTim\Markdown\Tests;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use NZTim\Markdown\YouTubeEmbed\YouTubeIframeExtension;
use PHPUnit\Framework\TestCase;

class ExplorationTest extends TestCase
{
    private function createConverter(): CommonMarkConverter
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());  // Adds Autolinks, Disallow Raw HTML, Strikethrough, Tables, Task Lists
        $environment->addExtension(new AttributesExtension());              // Allows addition of classes and attributes
        $environment->addExtension(new YouTubeIframeExtension());
        $config = [
            'renderer'                       => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break'      => "<br>",            // This is how to change line breaks into <br>
            ],
            'enable_em'                      => true,
            'enable_strong'                  => true,
            'use_asterisk'                   => true,
            'use_underscore'                 => true,
            'unordered_list_markers'         => ['-', '*', '+'],
            'html_input'                     => 'escape',       // Default is to allow all HTML
            'allow_unsafe_links'             => false,          // E.g. javascript:, file:, data:
            'max_nesting_level'              => INF,
            'youtube_iframe_width'           => 600,
            'youtube_iframe_height'          => 300,
            'youtube_iframe_allowfullscreen' => true,
        ];
        return new CommonMarkConverter($config, $environment);
    }

    /** @test */
    public function hello_world()
    {
        $converter = $this->createConverter();
        $result = $converter->convertToHtml('# Hello World!');
        $this->assertEquals("<h1>Hello World!</h1>\n", $result);
        $result = $converter->convertToHtml(<<<EOF
# Hello World!

Paragraph
with a break

EOF
        );
        $this->assertTrue(str_contains($result, 'Paragraph<br>with a break'));
    }

    /** @test */
    public function select_extensions()
    {
        $converter = $this->createConverter();
        $result = $converter->convertToHtml('Check out [Google](https://google.com){class="btn btn-outline-primary"}.');
        // <p>Check out <a class="btn btn-outline-primary" href="https://google.com">Google</a>.</p>\n
        $this->assertTrue(str_contains($result, '<a class="btn btn-outline-primary"'));
    }

    /** @test */
    public function autolinking()
    {
        $converter = $this->createConverter();
        $result = $converter->convertToHtml('Check out https://google.com, it looks cool.');
        // <p>Check out <a href="https://google.com">https://google.com</a>, it looks cool.</p>\n
        $this->assertTrue(str_contains($result, '<a href="https://google.com"'));
    }

    /** @test */
    public function escape_html()
    {
        $converter = $this->createConverter();
        $result = $converter->convertToHtml('Hax: <iframe> <script> <?php <wtf>');
        // <p>Hax: &lt;iframe&gt; &lt;script&gt; &lt;?php &lt;wtf&gt;</p>\n
        $this->assertTrue(str_contains($result, '&lt;iframe&gt; &lt;script&gt; &lt;?php &lt;wtf&gt;'));
    }

    /** @test */
    public function youtube_embed()
    {
        $converter = $this->createConverter();
        $result = $converter->convertToHtml('Check this out: [](https://www.youtube.com/watch?v=zpOULjyy-n8)');
        // <p>Check this out: <div class="video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/zpOULjyy-n8" frameborder="0" allowfullscreen="1" width="600" height="600"></iframe></div></p>\n
        $this->assertTrue(str_contains($result, '<div class="video embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/zpOULjyy-n8"'));
    }
}
