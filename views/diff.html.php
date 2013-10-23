<div class="row">
	<div class="span12">
		<p><?php echo $diff->getCommentHtml() ?></p>
		<form action="<?php echo url_for() . "?/{$diff_id}" ?>" method="post">
			<div class="row">
				<div class="span">
					<input type="text" placeholder="username" name="username" value="<?php echo $username ?>" />
				</div>
				<div class="span pull-right">
					<a id="download-diff" class="btn" href="<?php echo url_for() . "?/download/{$diff_id}" ?>" title="Download Raw Diff"><i class="icon-download"></i></a>
				</div>
			</div>
			<?php foreach ($files as $filename => $file): ?>
				<table class="table table-condensed table-bordered" style="border-collapse: collapse">
				<?php foreach ($file['lines'] as $line_number => $line): ?>
					<tr class="line" data-line_number="<?php echo $line_number ?>" data-file="<?php echo $file['filename'] ?>">
						<td class="line_numbers"><?php echo $line_number ?></td>
						<td class="diff-line <?php echo "diff-line-{$line['type']}" ?>"><pre><?php echo htmlentities($line['line']) ?></pre></td>
					</tr>
					<?php if (isset($comments[$file['filename']][$line_number])): ?>
						<?php foreach ($comments[$file['filename']][$line_number] as $comment): ?>
						<tr class="line-comments">
							<td class="line_numbers">
								<i class="icon-comment"></i>
								<a href="#" title="Edit Comment" class="edit-comment" data-line_number="<?php echo $line_number ?>" data-file="<?php echo $file['filename'] ?>" data-comment_text="<?php echo $comment->comment; ?>" data-comment_id="<?php echo $comment->comment_id; ?>"><i class="icon-edit"></i></a>
							</td>
							<td>
								<p>
									<strong><?php echo $comment->username ?></strong> <em><?php echo $comment->created ?></em>
								</p>
								<p><?php echo $comment->getCommentHtml() ?></p>
							</td>
						</tr>
						<?php endforeach ?>
					<?php endif ?>
				<?php endforeach ?>
					<tr id="line-comment_template" style="display: none">
						<td colspan="2" class="line-comments">
							<div class="row-fluid">
								<textarea class="comment-textarea span12"></textarea>
							</div>
							<input type="hidden" class="comment-id-input" />
							<input type="submit" class="btn btn-success btn-submit-comment" value="Add Comment" /> <a href="javascript:;" class="comment-cancel">cancel</a>
						</td>
					</tr>
				</table>
			<?php endforeach ?>
		</form>
	</div>
</div>
<div id="myModal" class="modal hide fade">
	<div class="modal-body">
		<p>Please provide an email or a username.</p>
		<input id="commenter" type="text" placeholder="username">
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Never mind</a>
		<a href="#" class="btn btn-primary" data-dismiss="modal" id="set-email">Set</a>
	</div>
</div>
<script type="text/javascript" src="<?php echo url_for('media/differ/js/diff.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo url_for('media/differ/css/diff.css') ?>">
