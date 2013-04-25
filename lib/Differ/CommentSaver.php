<?php

namespace Differ;

class CommentSaver
{
	protected $diff_id;
	protected $username;
	protected $comments;

	public function __construct($diff_id, $username, $comments)
	{
		$this->diff_id = $diff_id;
		$this->username = $username;
		$this->comments = $comments;
	}

	public function save()
	{
		$saved_comments = array();
		foreach ($this->comments as $file => $file_comments) {
			foreach ($file_comments as $line_number => $comment_text) {
				$comment = \Model::factory('Differ\Comment')->create();
				$comment->diff_id = $this->diff_id;
				$comment->file = $file;
				$comment->line_number = $line_number;
				$comment->username = $this->username;
				$comment->comment = $comment_text;
				$comment->created = date('Y-m-d h:i:s');
				$comment->save();
				$saved_comments[] = $comment;
			}
		}
		return $saved_comments;
	}
}