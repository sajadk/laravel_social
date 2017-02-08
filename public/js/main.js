$(function() {
	var post_id = 0;
	var postBodyElement = null;
	$('.edit').on('click',function(event){
		event.preventDefault();
		var postBody = $(this).parent().parent('article').find('p').text();
		postBodyElement = $(this).parent().parent('article').find('p');
		$('#post-body').val('');
		$('#post-body').val(postBody);
		post_id = $(this).parent().parent('article').data('id');
		$('#edit-modal').modal();
		//console.log($('#edit-form').serialize() + '&post_id=' + post_id);
	});
	$('#modal-save').on('click',function(event){
		$.ajax({
			type:'POST',
			url:urlEdit,
			data:$('#edit-form').serialize() + '&post_id='+ post_id,
			dataType: 'json',
			success:function(msg){
				console.log(msg['post-body']);
				postBodyElement.text(msg['post-body']);
				$('#edit-modal').modal('hide');
			},
			error: function(data){
				var errors = data.responseJSON;
				console.log(errors);
                // Render the errors with js ...
            }
        });
	});
	$('.like').on('click',function(event){
		var isLike = (event.target.previousElementSibling == null)? true:false;
		console.log(isLike);
		post_id = $(this).parent().parent('article').data('id');
		console.log(token);
		$.ajax({
			type:'POST',
			url:urlLike,
			data:{isLike : isLike, post_id: post_id , _token: token}
		})
		.done(function(){
			console.log('done');
			event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'You like this post' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this post' : 'Dislike';
			if (isLike) {
				event.target.nextElementSibling.innerText = 'Dislike';
			} else {
				event.target.previousElementSibling.innerText = 'Like';
			}
		});

	});
})

