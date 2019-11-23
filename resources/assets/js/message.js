var formData=new FormData();
function fetchMessages(thread_id){
	formData.append('thread_id',thread_id);
	$.ajax({
		type:'POST',
		url:'/messages/fetch',
		data:formData,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend:function(){
			$('.message').fadeOut(200);
// 			$('.messages_wrapper').fadeOut(300);
		},
		success:function(d){
			$('.messages_wrapper').attr('class','').attr('class','messages_wrapper thread_'+thread_id).html(d).fadeIn(500);
			$('.message').hide().fadeIn(500);
			$('.thread').removeClass('active');
			$('#thread_'+thread_id).removeClass('unread').addClass('active');
			$('#thread_'+thread_id+'_header').removeClass('unread');
			$('.messages_wrapper').scrollTop($('.messages_wrapper')[0].scrollHeight);
			if($('.preview.thread.unread').length>0){
				$('.incoming').addClass('unread');
			}else{
				$('.incoming').removeClass('unread');
			}
		},
		error:function(err){
		},
		contentType:false,
		processData:false
	});
}
function sendMessage(text,thread_id){
	formData.append('text',text);
	if(typeof thread_id === 'number') formData.append('thread_id',thread_id);
	$.ajax({
		type:'POST',
		url:'/messages/reply',
		data:formData,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend:function(){
			$('.message').fadeOut(200);
		},
		success:function(d){
			var monthNames = [
				"Jan", "Feb", "Mar",
				"Apr", "May", "Jun", "Jul",
				"Aug", "Sep", "Oct",
				"Nov", "Dec"
			];
			$('.messages_wrapper').attr('class','').attr('class','messages_wrapper thread_'+thread_id).html(d).fadeIn(500);
			var photo=$(d).filter(':last').find('.author_photo .profile_image img').attr('src');
			var name=$(d).filter(':last').find('.author h5').html();
			var timestamp=$(d).filter(':last').find('.timestamp p').html();
			var dat=new Date(Date.parse(timestamp));
			var updated_at=dat.getDate()+' '+monthNames[dat.getMonth()];
			
			$('#thread_'+thread_id+'_header').remove();
			var content_header=$('.messages_preview .scrollable').html();
			var div_header=$('<li><div id="thread_'+thread_id+'_header" class="preview thread"><div class="author_photo"><div class="profile_image"><img src="'+photo+'"></div></div><div class="text_excerpt"><div><a href="/messages"><h5>'+name+'</h5><p>'+text.substring(0,20)+'...'+'</p></a></div><div class="thread_timestamp"><a href="/messages"><p>'+updated_at+'</p></a></div></div></div></li>');
			
			$('.messages_preview .scrollable').html('').append(div_header);
			$('.messages_preview .scrollable').append(content_header);

			$('.message').hide().fadeIn(500);
			$('#thread_'+thread_id).removeClass('unread');
			$('.messages_wrapper').scrollTop($('.messages_wrapper')[0].scrollHeight);
			$('#thread_'+thread_id).remove();
			var content_body=$('.threads_wrapper > div:first-of-type').html();
			var div_body=$('<div id="thread_'+thread_id+'" class="thread active"><div class="author_photo"><div class="profile_image"><img src="'+photo+'"></div></div><div class="text_excerpt"><div><h5>'+name+'</h5><p>'+text.substring(0,20)+'...'+'</p></div><div class="thread_timestamp"><p>'+updated_at+'</p></div></div></div>');

			$('.threads_wrapper > div:first-of-type').html('').append(div_body);
			$('.threads_wrapper > div:first-of-type').append(content_body);
			$('.thread').not('.preview').unbind('click').click(function(e){
				e.preventDefault()
				var thread_id=$(this).attr('id').split('_')[1];
				fetchMessages(thread_id);
			});
		},
		error:function(err){
		},
		contentType:false,
		processData:false
	});
}
$(document).ready(function(){
	$('.thread').not('.preview').click(function(e){
		var thread_id=$(this).attr('id').split('_')[1];
		fetchMessages(thread_id);
	});
	$('.thread_0').trigger('click');
	$('.message_reply').keypress(function(e) {
		if(e.which == 13) {
			e.preventDefault();
			var thread_id=$('.messages_wrapper').attr('class').split(' ')[1].split('_')[1];
                        if($('.message_reply').val()){
                            sendMessage($('.message_reply').val(),thread_id);
                            $('.message_reply').val('');
                        }
		}
	});
});
