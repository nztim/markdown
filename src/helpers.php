<?php

use NZTim\Markdown\ParsedownExtraWithYouTubeEmbed;

function markdown(string $content): string
{
    /** @var ParsedownExtraWithYouTubeEmbed $converter */
    $converter = app('nztim-markdown-converter');
    return $converter->text($content);
}
