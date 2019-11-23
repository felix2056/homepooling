require('./bootstrap');

var stop=false;

if(Echo){
	Echo.private('App.User.'+$('.link[data-userid]').data('userid')).notification((notification)=>{
		$('#thread_'+notification.thread_id+'_header').remove();
		var content_header=$('.messages_preview .scrollable').html();
		var div_header=$('<li><div id="thread_'+notification.thread_id+'_header" class="preview unread thread"><div class="author_photo"><div class="profile_image"><img src="'+notification.photo+'"></div></div><div class="text_excerpt"><div><a href="/messages"><h5>'+notification.name+'</h5><p>'+notification.text.substring(0,20)+'...'+'</p></a></div><div class="thread_timestamp"><a href="/messages"><p>'+notification.updated_at+'</p></a></div></div></div></li>');
		
		$('.messages_preview .scrollable').html('').append(div_header);
		$('.messages_preview .scrollable').append(content_header);

		if($('.preview.thread.unread').length>0){
			$('.incoming').addClass('unread');
		}
		
		if($('.threads_wrapper')){
			if($('#thread_'+notification.thread_id).hasClass('active')){
				fetchMessages(notification.thread_id);
			}
			$('#thread_'+notification.thread_id).remove();
			var content_body=$('.threads_wrapper > div:first-of-type').html();
			var div_body=$('<div id="thread_'+notification.thread_id+'" class="unread thread"><div class="author_photo"><div class="profile_image"><img src="'+notification.photo+'"></div></div><div class="text_excerpt"><div><h5>'+notification.name+'</h5><p>'+notification.text.substring(0,20)+'...'+'</p></div><div class="thread_timestamp"><p>'+notification.updated_at+'</p></div></div></div>');

			$('.threads_wrapper > div:first-of-type').html('').append(div_body);
			$('.threads_wrapper > div:first-of-type').append(content_body);

		}
		$('.thread').not('.preview').unbind('click').click(function(e){
			e.preventDefault()
			var thread_id=$(this).attr('id').split('_')[1];
			fetchMessages(thread_id);
		});
	})
}

$(document).ready(function(){
	
	//        class logger, don't delete
//        var classArr=[];
//        function unique(list) {
//            var result = [];
//            $.each(list, function(i, e) {
//              if ($.inArray(e, result) == -1) result.push(e);
//            });
//            return result;
//        }
//        $('*').each(function(){
//            if($(this).attr('class')){
//                classArr.push($(this).parents().length+' '+$(this).attr('class'));
//            }
//        });
//        classArr=unique(classArr);
//        console.log(classArr.join().replace(',',"\n"));
	
	$('[data-toggle="datepicker"]').datepicker({
		format:'dd/mm/yyyy'
	});
	
	if($('.preview.thread.unread').length>0){
		$('.incoming').addClass('unread');
	}
	if($('.alert.floating').length>0){
		setTimeout(function(){
			$('.alert.floating').fadeOut(1000,function(){
					$('.alert.floating').remove();
			});
		},5000);
	}
	$('.package button').click(function(e){
		e.preventDefault();
	})
	$('.package div[type="submit"]').click(function(){
		$(this).parent().parent().parent().submit();
	});
});
