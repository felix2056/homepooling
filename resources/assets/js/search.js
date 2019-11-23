var pageNumber = 1;
function fetchProperties(){
	var data={};
	var names=[];
	$('input[type="checkbox"]:checked').each(function(){
		names.push($(this).attr('name'));
	});
	for (var i=0;i<names.length;i++){
		data[names[i]]=$('input[name="'+names[i]+'"]:checked').map(function(){
			return $(this).val();
		}).get();
	}

	$('input[type="radio"]:checked').each(function(){
		if($(this).val()!=='on') data[$(this).attr('name')]=$(this).val();
	});
	$('input,textarea,select').not(':checkbox').not(':radio').each(function(){
		if($(this).val() && $(this).val()!==''){
			data[$(this).attr('name')]=$(this).val();
		}
	});
	data['page']=pageNumber;
	$.ajax({
		type:'POST',
		url:'/properties/fetch',
		data:data,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend:function(){
			if(pageNumber==1) $('.search_list .listings-grid > *').fadeOut(300);
		},
		success:function(d){
			if(pageNumber==1) $('.search_list .listings-grid').html('');
			$('.search_list .listings-grid').append(d.html);
			setTimeout(function(){
				$('.nonVis').removeClass('nonVis');
			},300);
			pageNumber++;
			
			if(typeof deleteMarkers === 'function') deleteMarkers();
			if(typeof google !== 'undefined'){
				for(var i=0;i<d.geo.length;i++){
                                    var latLong = new google.maps.LatLng(d.geo[i][0], d.geo[i][1]);
                                    var mark = new google.maps.Marker({
                                        position: latLong,
                                        map: map,
                                        icon: '/storage/img/money-marker.png',
                                        label:{
                                            text: d.geo[i][2]+' €',
                                            fontSize:'16px',
                                            fontFamily:'font-proxima-regular'
                                        },
                                        url:d.geo[i][3]
                                    });
                                    markers.push(mark);
                                    google.maps.event.addListener(mark, 'click', function() {
                                        window.location.href = mark.url;
                                    });
				}
				showMarkers();
			}
			stop=d.stop;
		}
	});
}
// wanted list/search
function fetchAds(){
	var data={};
	var names=[];
	data['page']=pageNumber;
	$('input[type="checkbox"]:checked').each(function(){
		names.push($(this).attr('name'));
	});
	for (var i=0;i<names.length;i++){
		data[names[i]]=$('input[name="'+names[i]+'"]:checked').map(function(){
			return $(this).val();
		}).get();
	}

	$('input[type="radio"]:checked').each(function(){
		if($(this).val()!=='on') data[$(this).attr('name')]=$(this).val();
	});
	$('input,textarea,select').not(':checkbox').not(':radio').each(function(){
		if($(this).val() && $(this).val()!==''){
			data[$(this).attr('name')]=$(this).val();
		}
	});
	$.ajax({
		type:'POST',
		url:'/wanteds/fetch',
		data:data,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend:function(){
			if(pageNumber==1) $('#wanted_listing_results > *').fadeOut(300);
		},
		success:function(d){
			if(pageNumber==1) $('#wanted_listing_results').html('');
			$('#wanted_listing_results').append(d.html);
			setTimeout(function(){
				$('.nonVis').removeClass('nonVis');
			},300);
			pageNumber++;
			stop=d.stop;
		}
	});
}

// invites page
function fetchUsers(){
	var token=$('meta[name="csrf-token"]').attr('content');
	var data={};
	data['page']=pageNumber;
	if($('#gender').val()!=null && $('#gender').val()!='all') data['gender']=$('#gender').val();
	if($('#age').val()!=null && $('#age').val()!='all') data['age']=$('#age').val();
	$.ajax({
		type:'POST',
		url:'/profiles/fetch',
		data:data,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': token,
		},
		beforeSend:function(){
			
		},
		success:function(d){
			if($('.invite_results > *').length>0){
				$('.invite_results > *').fadeOut(300,function(){
					$('.invite_results').html('').append(d.html);
					setTimeout(function(){
						$('.nonVis').removeClass('nonVis');
					},300);
					pageNumber++;
					stop=d.stop;
				});
			}else{
				$('.invite_results').html('').append(d.html);
				setTimeout(function(){
					$('.nonVis').removeClass('nonVis');
				},300);
				pageNumber++;
				stop=d.stop;
			}
		},
		error:function(err){
		}
	});
} 
$(document).ready(function(){
	if($('#content.invites').length>0){
		fetchUsers();
		let flag=false;
		$(window).scroll(function() {
			if(!stop&&!flag){
// 				if($(window).scrollTop() + $(window).height() == $(document).height()) {
				if($('.invite_buttons').scrollTop() < $(window).height()) {
					fetchUsers();
					flag=true;
				}
			}
			setTimeout(function(){
				flag=false;
			},300);
		});
		$('.invite_search select').change(fetchUsers);
	}

	$('#invite_all').click(function(e){
		e.preventDefault();
		$('input.invited').trigger('click');
		$('#invite_list').submit();
	});
	if($('.search_list .listings-grid').length>0){
		fetchProperties();
		$(window).scroll(function() {
			if(!stop){
				if($(window).scrollTop() + $(window).height() == $(document).height()) {
					fetchProperties();
				}
			}
		});
		$('#search_bar input#lat').change(function(){
			pageNumber=1;
			fetchProperties();
		});
		$('#search_bar input').not('#autocomplete').change(function(e){
			pageNumber=1;
			e.preventDefault();
			fetchProperties();
		});
		$('button.reset_form').click(function(e){
			pageNumber=1;
			e.preventDefault();
			$('#search_bar input#lat').unbind('change');
			$('#search_bar input').not('#autocomplete').unbind('change');

			$('#search_bar input[type="checkbox"]:checked').trigger('click');
			$('#search_bar input.default').trigger('click');
			$('.fake_dropdown').removeClass('bold');

			$('#search_bar input#lat').change(function(){
				pageNumber=1;
				fetchProperties();
			});
			$('#search_bar input').not('#autocomplete').not('#lat').change(function(e){
				pageNumber=1;
				e.preventDefault();
				fetchProperties();
			});
			$('#search_bar input#budget_0').trigger('change');
		});
	}
	if($('#wanted_listing_results').length>0){
		fetchAds();
		$(window).scroll(function() {
			if(!stop){
				if($(window).scrollTop() + $(window).height() == $(document).height()) {
					fetchAds();
				}
			}
		});
		$('#search_bar input#lat').change(function(){
			pageNumber=1;
			fetchAds();
		});
		$('#search_bar input').not('#autocomplete').change(function(e){
			pageNumber=1;
			e.preventDefault();
			fetchAds();
		});
		$('button.reset_form').click(function(e){
			pageNumber=1;
			e.preventDefault();

			$('#search_bar input#lat').unbind('change');
			$('#search_bar input').not('#autocomplete').unbind('change');

			$('#search_bar input[type="checkbox"]:checked').trigger('click');
			$('#search_bar input.default').trigger('click');
			$('.fake_dropdown').removeClass('bold');

			$('#search_bar input#lat').change(function(){
				pageNumber=1;
				fetchAds();
			});
			$('#search_bar input').not('#autocomplete').not('#lat').change(function(e){
				pageNumber=1;
				e.preventDefault();
				fetchAds();
			});

			$('#search_bar input#budget_0').trigger('change');
		});
	}
	
	$('.fake_pane input').change(function(e){
		let dropdown = $(e.target).parent().siblings('.fake_dropdown');
		if($(this).val() != 'on'){
			dropdown.addClass('bold');
		}else{
			dropdown.removeClass('bold');
		} 
	});
	$('.fake_pane input[type="checkbox"]').change(function(e){
		let dropdown = $(e.target).parent().siblings('.fake_dropdown');
		if($('.fake_pane input[type="checkbox"]:checked').length>0){
			dropdown.addClass('bold');
		}else{
			dropdown.removeClass('bold');
		} 
	});
	$('.fake_dropdown').click(function(e){
		var el=e.target.parentNode.children[1];
		if(!$(el).hasClass('showing')){
			$('.showing').removeClass('showing');
			$(el).addClass('showing');
		}else{
			$('.showing').removeClass('showing');
		}
		if($(this).hasClass('rotated')){
			$('.fake_dropdown').removeClass('rotated');
		}else{
			$('.fake_dropdown').removeClass('rotated');
			$(this).addClass('rotated');
		}	
	});
	$('button.btn-more').click(function(e){
		if($(this).hasClass('rotated')){
			$('#search_bar.opened').removeClass('opened').addClass('more');
			$(this).removeClass('rotated');
		}else{
			$('#search_bar.more').removeClass('more').addClass('opened');
			$(this).addClass('rotated');
		}
		
	});
});
