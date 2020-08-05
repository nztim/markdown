<?php

namespace NZTim\Markdown\YouTubeEmbed;

use League\CommonMark\Inline\Element\AbstractInline;

final class YouTubeIframe extends AbstractInline
{
    private YouTubeUrlInterface $url;

    public function __construct(YouTubeUrlInterface $youTubeUrl)
    {
        $this->url = $youTubeUrl;
    }

    public function getUrl(): YouTubeUrlInterface
    {
        return $this->url;
    }

}
