var formData=new FormData();
function sendSMS(num){
	var id=$('#ver_num').attr('data-user');
	var token=$('meta[name="csrf-token"]').attr('content');
	var data={
		num:num
	};
	$.ajax({
		type:'POST',
		url:'/profiles/'+id+'/sms',
		data:data,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': token,
		},
		beforeSend:function(){
				if($('.phase_1 .alert')) $('.phase_1 .alert').remove();
		},
		success:function(d){
			if(d=='ok'){
				$('.phase_1').removeClass('active');
				$('.phase_2').addClass('active');
			}else{
				$('.phase_1 p').append('<div class="alert alert-danger" style="width:100%;margin-top:10px;">Something went wrong. Please, check the phone number and try again</div>');
				setTimeout(function(){
					$('.alert.alert-danger').fadeOut(500,function(){
							$('.alert.alert-danger').remove();
					});
				},5000);
			}
		},
		error:function(err){
		}
	});
}
function verifySMS(code){
	var id=$('#ver_cod').attr('data-user');
	var token=$('meta[name="csrf-token"]').attr('content');
	var data={
		code:code
	};
	$.ajax({
		type:'POST',
		url:'/profiles/'+id+'/verify',
		data:data,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': token,
		},
		beforeSend:function(){
				if($('.phase_1 .alert')) $('.phase_1 .alert').remove();
		},
		success:function(d){
			if(d=='ok'){
				$('.reveal_tooltip').remove();
				$('#verify_popup').removeClass('active').remove();
				$('#verify').unbind('click').addClass('disabled').addClass('verified').html('Verified<i class="fa fa-check"></i>');
				$('.verify').append('<div class="alert alert-success" style="width:100%;margin-top:10px;">Your account has been successfully verified</div>');
				setTimeout(function(){
					$('.alert.alert-success').fadeOut(500,function(){
							$('.alert.alert-success').remove();
					});
				},5000);
			}else if(d=='expired'){
				$('.phase_1').addClass('active')
				$('.phase_1 p').append('<div class="alert alert-danger" style="width:100%;margin-top:10px;">Your code has expired. Please, try again (codes sent have a validity period of 1 hour)</div>');
				$('.phase_2').removeClass('active');
				setTimeout(function(){
					$('.alert.alert-danger').fadeOut(500,function(){
							$('.alert.alert-danger').remove();
					});
				},5000);
			}else{
				$('.phase_1').addClass('active');
				$('.phase_1 p').append('<div class="alert alert-danger" style="width:100%;margin-top:10px;">Something went wrong. Please, try again</div>');
				$('.phase_2').removeClass('active');
				setTimeout(function(){
					$('.alert.alert-danger').fadeOut(500,function(){
							$('.alert.alert-danger').remove();
					});
				},5000);
			}
		},
		error:function(err){
		}
	});
}
 
$(document).ready(function(){
	$('#reveal_tooltip').click(function(e){
		let tooltip=$('#verify_tooltip');
		if(tooltip.hasClass('nonVis')){
			tooltip.children('i').last().click(function(ev){
				$('#verify_tooltip').addClass('nonVis');
				$(this).unbind('click');
			});
			tooltip.removeClass('nonVis');
		}else{
			tooltip.children('i').last().unbind('click');
			tooltip.addClass('nonVis');
		}
	});
	$('#verify').click(function(e){
		e.preventDefault();
		$('.phase_1 button').click(function(e){
			e.preventDefault();
			sendSMS($('#verify_number').val());
		});
		$('.phase_2 button').click(function(e){
			e.preventDefault();
			verifySMS($('#verify_code').val());
		});
		$('#verify_popup .profile_card').click(function(ev){
			ev.stopPropagation();
			return;
		});
		if(!$('#verify').hasClass('verified')){
			$('#verify_popup').addClass('active');
			$('.phase_1').addClass('active');
			$('#verify_popup').click(function(ev){
				$(this).removeClass('active');
				$(this).unbind('click');
				$('.phase_2 button').unbind('click');
				$('.phase_1 button').unbind('click');
				$('#verify_popup .profile_card').unbind('click');
			});
		}
	});
	$("label[for='profile_photo']").click(function () {
		$('#profile_photo').change(function () {
			$($(this)[0].files).each(function () {
				var file = $(this);
				formData=new FormData();
				formData.append('photo',file[0])
				var reader = new FileReader();
				reader.onload = function (e) {
					var img = $(".profile_image img");
					img.attr("src", e.target.result);
				};
				reader.readAsDataURL(file[0]);
				file.empty();
			});
		});
	});
});
