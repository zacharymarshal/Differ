<?php

namespace Differ;

use \Model;
use dflydev\markdown\MarkdownExtraParser as MarkdownParser;

class Diff extends Model
{
	public static $_table = 'diffs';
	public static $_id_column = 'diff_id';

	public function getCommentHtml()
	{
		$parser = new MarkdownParser();
		return $parser->transformMarkdown($this->comment);
	}

	public function comments()
	{
		return $this->has_many('\Differ\Comment', 'diff_id');
	}

	public function diffs()
	{
		return $this->has_many('\Differ\Diff', 'parent_diff_id');
	}
}