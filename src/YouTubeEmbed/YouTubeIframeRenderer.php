<?php

namespace NZTim\Markdown\YouTubeEmbed;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

final class YouTubeIframeRenderer implements InlineRendererInterface
{
    private string $width;
    private string $height;
    private bool $allowFullScreen;

    public function __construct(string $width, string $height, bool $allowFullScreen)
    {
        $this->width = $width;
        $this->height = $height;
        $this->allowFullScreen = $allowFullScreen;
    }

    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof YouTubeIframe)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }
        $src = "https://www.youtube.com/embed/{$inline->getUrl()->getVideoId()}";
        $startTimestamp = $inline->getUrl()->getStartTimestamp();
        if ($startTimestamp !== null) {
            $src .= "?start={$startTimestamp}";
        }
        $config = [
            'src'             => $src,
            'frameborder'     => 0,
            'allowfullscreen' => $this->allowFullScreen,
        ];
        if (intval($this->width) > 0 && intval($this->height > 0)) {
            $config['width'] = $this->width;
            $config['height'] = $this->width;
        }
        return '<div class="video embed-responsive embed-responsive-16by9">' . strval(new HtmlElement('iframe', $config)) . '</div>';
    }
}
