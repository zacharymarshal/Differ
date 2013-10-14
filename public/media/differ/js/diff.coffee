$ ->
	$(document).on 'click', '.comment-cancel', ->
		$(this).parent('td').parent('tr#line-comment_template').prev('tr.line-comments').show()
		$(this).parents('tr').remove()

	$('.line').click (e) ->
		info = $(e.target).parents('tr').data()
		comment = $('#line-comment_template').clone()
		comment_textarea = comment.find('textarea')
		name = "comments[#{info.file}][#{info.line_number}][comment]"
		comment_textarea.attr('name', name).text('')
		comment.find('.btn-submit-comment').val('Add Comment')
		comment.insertAfter($(e.target).parent('tr')).show()

	$('.edit-comment').click (e) ->
		info = $(this).data()
		comment = $('#line-comment_template').clone()
		comment_textarea = comment.find('textarea')
		comment_id = comment.find('.comment-id-input')
		comment.find('.btn-submit-comment').val('Save')
		comment_textarea.attr('name', "comments[#{info.file}][#{info.line_number}][comment]")
		comment_textarea.text($.trim(info.comment_text))
		comment_id.attr('name', "comments[#{info.file}][#{info.line_number}][id]")
		comment_id.val(info.comment_id)
		comment_line = $(this).parent('td').parent('tr.line-comments')
		comment.insertAfter(comment_line).show()
		comment_line.hide()
