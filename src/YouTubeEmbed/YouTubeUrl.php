<?php

namespace NZTim\Markdown\YouTubeEmbed;

final class YouTubeUrl implements YouTubeUrlInterface
{
    private string $videoId;
    private ?string $startTimestamp;

    public function __construct(string $videoId, ?string $startTimestamp = null)
    {
        $this->videoId = $videoId;
        $this->startTimestamp = $startTimestamp;
    }

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function getStartTimestamp(): ?string
    {
        return $this->startTimestamp;
    }
}
