<?php

namespace NZTim\Markdown\YouTubeEmbed;

interface YouTubeUrlInterface
{
    public function getVideoId(): string;
    public function getStartTimestamp(): ?string;
}
