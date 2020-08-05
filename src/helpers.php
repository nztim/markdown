<?php

function markdown(string $content): string
{
    return app('nztim-markdown-converter')->convertToHtml($content);
}
