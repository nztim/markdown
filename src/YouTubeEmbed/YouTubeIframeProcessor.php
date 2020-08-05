<?php

namespace NZTim\Markdown\YouTubeEmbed;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Inline\Element\Link;

final class YouTubeIframeProcessor {

    /** @var array|YouTubeUrlParserInterface[] */
	private array $youTubeUrlParsers = [];

	public function __construct(array $youTubeUrlParsers) {
		foreach ($youTubeUrlParsers as $parser) {
			if (!($parser instanceof YouTubeUrlParserInterface)) {
				throw new \TypeError();
			}
		}
		$this->youTubeUrlParsers = $youTubeUrlParsers;
	}

	public function __invoke(DocumentParsedEvent $e) {
		$walker = $e->getDocument()->walker();
		while ($event = $walker->next()) {
			if ($event->getNode() instanceof Link && !$event->isEntering()) {
				/** @var Link $link */
				$link = $event->getNode();
				foreach ($this->youTubeUrlParsers as $youTubeParser) {
					$youTubeUrl = $youTubeParser->parse($link->getUrl());
					if ($youTubeUrl === null) {
						continue;
					}
					$link->replaceWith(new YouTubeIframe($youTubeUrl));
				}
			}
		}
	}
}
