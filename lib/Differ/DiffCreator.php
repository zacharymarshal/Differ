<?php

namespace Differ;

use \Model;

class DiffCreator
{
	private $fields;
	
	public function __construct($fields)
	{
		$this->fields = $fields;
	}

	/**
	 * Create the diff in the database save the file etc
	 */
	public function create()
	{
		$diff = Model::factory('Differ\Diff')->create();

		$diff->created  = date('Y-m-d h:i:s');
		$diff->username = $this->fields['username'];
		$diff->comment  = $this->fields['comment'];
		$diff->notify_address = $this->fields['notify_address'];

		if ($this->fields['parent_diff_id']) {
			$diff->parent_diff_id = $this->fields['parent_diff_id'];
		}

		if ($password = $this->fields['password']) {
			$password = sha1("diffs are so awesome!!" . $password);
			$diff->password = $password;
		}

		$diff->save();

		if (!copy($this->fields['diff'], "../data/diffs/{$diff->diff_id}.diff")) {
			throw new \Exception("Could not copy diff; permission issue?");
		}

		// do stuff with the file

		return $diff->diff_id;
	}
}
