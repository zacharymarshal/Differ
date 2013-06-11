<?php

namespace Differ;

use \Model;

class Comment extends Model
{
	public static $_table = 'comments';
	public static $_id_column = 'comment_id';

	public function getCommentHtml()
	{
		return DifferMarkdown::defaultTransform($this->comment);
	}
}
