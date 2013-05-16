<?php

namespace Differ;

use \Model;
use \Michelf\MarkdownExtra;

class Comment extends Model
{
	public static $_table = 'comments';
	public static $_id_column = 'comment_id';

	public function getCommentHtml()
	{
		return MarkdownExtra::defaultTransform($this->comment);
	}
}