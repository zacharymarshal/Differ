<?php

namespace Differ;

class DiffCommentFinder
{
	protected $diff;

	public function __construct($diff)
	{
		$this->diff = $diff;
	}

	public function allByFileAndLine()
	{
		$comments = $this->diff->comments()->find_many();
		$comments_by_file_and_line = array();
		foreach ($comments as $comment) {
			$comments_by_file_and_line[$comment->file][$comment->line_number][] = $comment;
		}
		return $comments_by_file_and_line;
	}
}