$ ->
	$(document).on 'click', '.comment-cancel', ->
		$(this).parents('tr').remove()

	$('.line').click (e) ->
		info = $(e.target).parents('tr').data()
		comment = $('#line-comment_template').clone()
		comment_textarea = comment.find('textarea')
		name = "comments[#{info.file}][#{info.line_number}]"
		comment_textarea.attr('name', name)
		comment.insertAfter($(e.target).parent('tr')).show()