<?php

namespace Differ;

use \Model;
use dflydev\markdown\MarkdownExtraParser as MarkdownParser;

class Comment extends Model
{
	public static $_table = 'comments';
	public static $_id_column = 'comment_id';

	public function getCommentHtml()
	{
		$parser = new MarkdownParser();
		return $parser->transformMarkdown($this->comment);
	}
}