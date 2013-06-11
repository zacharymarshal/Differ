<?php

namespace Differ;

use \Michelf\MarkdownExtra;

/**
 * A Differ Flavored Markdown which differs from the derfault Markdown:
 * 	<li> single new lines are treated as real line break </li>
 *
 * @package Differ
 */
class DifferMarkdown extends MarkdownExtra
{
	/**
	 * Override the default doHardBreaks to treats newlines 
	 * in paragraph-like content as real line breaks.
	 *
	 * @see \Michelf\Markdown::doHardBreaks()
	 */
	protected function doHardBreaks($text)
	{
		# Do hard breaks:
		return preg_replace_callback('/ {2,}\n|\n{1}/', 
			array(&$this, '_doHardBreaks_callback'), $text);
	}
}
