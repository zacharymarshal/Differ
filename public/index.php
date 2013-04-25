<?php

require '../lib/limonade.php';
require '../vendor/autoload.php';

use \Differ\Parser;
use \Differ\DiffCommentFinder;
use \Differ\NotificationSender;
use \Differ\DiffCreator;
use \Differ\CommentSaver;

use \Model;

ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

function configure()
{
	option('views_dir', '../views');

	$core_config = include('../config/core.php');
	option('env', $core_config['environment']);
	option('debug', $core_config['debug']);
	option('base_uri', $core_config['base_uri']);
	option('url', $core_config['url']);

	$database_config = include('../config/database.php');

	ORM::configure($database_config['dsn']);
	ORM::configure('username', $database_config['username']);
	ORM::configure('password', $database_config['password']);
}

function before()
{
	layout('layouts/default.html.php');
}

dispatch('/', function() {
	return html('index.html.php');
});

dispatch_post('/', function() {
	$url = option('url');
	$fields = $_POST;
	$file = $_FILES["diff"]["tmp_name"];
	$fields['diff'] = $file;

	$creator = new DiffCreator($fields);
	$diff_id = $creator->create();

	$sender = new NotificationSender(
		$diff_id,
		$_POST['username'],
		<<<MSG
Diff #{$diff_id} has been uploaded and is ready for review

{$url}/{$diff_id}
MSG
	);

	$sender->send();

	return "{$url}/{$diff_id}";
});

dispatch('/:diff_id', function($diff_id) {
	$username = (isset($_SESSION['username']) ? $_SESSION['username'] : '');
	$diff = Model::factory('Differ\Diff')->find_one($diff_id);
	$parser = new Parser("../data/diffs/{$diff_id}.diff");
	$comments = new DiffCommentFinder($diff);

	set('diff', $diff);
	set('child_diffs', $diff->diffs()->find_many());
	set('diff_id', $diff_id);
	set('username', $username);
	set('files', $parser->parse());
	set('comments', $comments->allByFileAndLine());

	return html('diff.html.php');
});

dispatch_post('/:diff_id', function($diff_id) {
	$url = option('url');
	$_SESSION['username'] = $_POST['username'];
	$saver = new CommentSaver($diff_id, $_POST['username'], $_POST['comments']);
	$saved_comments = $saver->save();
	// TODO: Move this into some sort of comment mailer object
	foreach ($saved_comments as $comment) {
		$sender = new NotificationSender(
			$comment->diff_id,
			$comment->username,
			<<<MSG
{$comment->username} #{$comment->diff_id} has commented on {$comment->file}, line {$comment->line_number}:

{$comment->comment}

{$url}/{$comment->diff_id}
MSG
		);
		$sender->send();
	}
	
	if (IS_AJAX) {
		return 'ok';
	}
	return redirect_to($diff_id);
});

dispatch('/show/:diff_id', function ($diff_id) {
	// TODO: add password protection
	// Yup
	$diff_id = intval($diff_id);
	readfile("../data/diffs/$diff_id.diff");
});

run();
