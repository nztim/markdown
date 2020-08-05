<?php

namespace NZTim\Markdown\YouTubeEmbed;

interface YouTubeUrlParserInterface
{
    public function parse(string $url): ?YouTubeUrlInterface;
}
