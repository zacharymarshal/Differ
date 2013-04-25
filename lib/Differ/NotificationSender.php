<?php

namespace Differ;

class NotificationSender
{
	private $diff_id;
	private $from_username;
	private $email_content;

	public function __construct($diff_id, $from_username, $email_content) {
		$this->diff_id = $diff_id;
		$this->diff = \Model::factory('Differ\Diff')->find_one($diff_id);
		$this->from_username = $from_username;
		$this->email_content = $email_content;
	}

	private function getEmails() {
		$users = \ORM::for_table('comments')->raw_query(
			"
				SELECT DISTINCT username
				FROM comments
				WHERE
					diff_id = :diff_id
					AND username <> :username
					AND username IS NOT NULL
					AND username <> ''
			",
			array('diff_id' => $this->diff_id, 'username' => $this->from_username)
		)->find_many();

		$emails = array();

		foreach ($users as $user) {
			if (strstr($user->username, '@') && strstr($user->username, '.')) {
				$emails[] = $user->username;
			}
		}

		foreach (preg_split('/(\s|,)+/', trim($this->diff->notify_address)) as $email) {
			if (strstr($email, '@') && strstr($email, '.') && $email != $this->from_username) {
				$emails[] = $email;
			}
		}

		return $emails;
	}

	public function send() {
		$emails = $this->getEmails();

		if (empty($emails)) {
			return false;
		}

		mail(
			implode(',',$emails),
			"Differ: #{$this->diff_id}",
			$this->email_content
		);
	}
}
