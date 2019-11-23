
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });
var formData=new FormData();
var images_tbu=[];
var images_tbd=[];
var dropzone=document.getElementById('dropArea');
var pageNumber = 1;
var stop=false;

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ( (charCode > 31 && charCode < 48) || charCode > 57) {
		return false;
	}
	return true;
}

// [PROPERTY EDIT AND SHARE, ADMIN PROPERTY] room object: used to build and update dinamically rooms boxes
function Room(id,beds,occupants,males,females,queers,has_bathroom,price,bills,deposit,available_from,available_to){
	this.id=id;
	this.beds=beds;
	this.males=males;
	this.females=females;
	this.queers=queers;
	this.occupants=occupants;
	this.has_bathroom=has_bathroom;
	this.price=price;
	this.bills=bills;
	this.deposit=deposit;
	this.available_from=available_from;
	this.available_to=available_to;
	
	this.render=function(){
		var box=$('<div id="room_'+id+'" class="room_box"></div>');
		var title=$('<div class="room_row"><h4>Room #'+this.id+'</h4></div>');
		
// 		aggiungo il titolo
		box.append(title);
		
		var choice_row=$('<div class="room_row">');
		var select_wrapper=$('<div class="select_wrapper"></div>');
		var select=$('<select class="beds_no" name="beds_no_'+this.id+'">');
		for(var i=0;i<10;i++){
			if(this.beds===(i+1)){
				var option=$('<option checked="checked" name="beds_no_'+this.id+'" value="'+(i+1)+'">'+(i+1)+'</option>');
			}else{
				var option=$('<option name="beds_no_'+this.id+'" value="'+(i+1)+'">'+(i+1)+'</option>');
			}
			select.append(option);
		}
		select.change(function(){
			var id=$(this).attr('name').split('_')[2];
			var val=$(this).val();
			
			$('#room_'+id+' .occupieds').html('');
			for(var i=0;i<val;i++){
				$('#room_'+id+' .occupieds').append($('<div class="occGender occ_'+(i+1)+'"><div class="choice choice_'+(i+1)+'"><i class="fa fa-genderless"></i></div><div class="inGender"><input type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="male" id="room_'+id+'_occ_'+(i+1)+'_m" value="m"><label class="male" for="room_'+id+'_occ_'+(i+1)+'_m"><i class="fa fa-mars"></i><span>Occupied by a male</span></label><input type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="female" id="room_'+id+'_occ_'+(i+1)+'_f" value="f"><label class="female" for="room_'+id+'_occ_'+(i+1)+'_f"><i class="fa fa-venus"></i><span>Occupied by a female</span></label><input type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="lgbt" id="room_'+id+'_occ_'+(i+1)+'_q" value="q"><label class="lgbt" for="room_'+id+'_occ_'+(i+1)+'_q"><i class="fa fa-transgender"></i><span>Occupied by a LGBTQ+</span></label><input checked type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="free" id="room_'+id+'_occ_'+(i+1)+'_free" value="free"><label class="free" for="room_'+id+'_occ_'+(i+1)+'_free"><i class="fa fa-genderless"></i><span>Vacant</span></label></div></div>'));
			}
			$('.occGender input').change(function(){
				var choice=$(this).prop('id');
				var genderico=$('label[for="'+choice+'"]').html();
				if($(this).val()==='m') {
					$(this).parent().parent().removeClass('male').removeClass('female').removeClass('lgbt').addClass('male')
				} else if($(this).val()==='f'){
					$(this).parent().parent().removeClass('male').removeClass('female').removeClass('lgbt').addClass('female');
				} else if($(this).val()==='q'){
					$(this).parent().parent().removeClass('male').removeClass('female').removeClass('lgbt').addClass('lgbt');
				} else {
					$(this).parent().parent().removeClass('male').removeClass('female').removeClass('lgbt');
				}
				$(this).parent().parent().find('.choice').html(genderico);
			});
		});
		var bedspan=$('<span># of Beds</span>');
		select_wrapper.append(select);
		select_wrapper.append(bedspan);
		
		var checkbox_wrapper=$('<div class="checkbox_wrapper"></div>');
		if(this.occupants>0){
			var checkbox_input=$('<input checked id="room_occupied_'+this.id+'" class="room_occupied" name="room_occupied_'+this.id+'" type="checkbox" value="1" /><label for="room_occupied_'+this.id+'">Occupied?</label>');
		}else{
			var checkbox_input=$('<input id="room_occupied_'+this.id+'" class="room_occupied" name="room_occupied_'+this.id+'" type="checkbox" value="1" /><label for="room_occupied_'+this.id+'">Occupied?</label>');
		}
		checkbox_input.change(function(){
			var oid=$(this).attr('name').split('_')[2];
			if($(this).prop('checked')){
				$('#room_'+oid+' .occupieds').fadeIn();
			}else{
				$('#room_'+oid+' .occupieds').fadeOut();
			}
		});
		checkbox_wrapper.append(checkbox_input);
		
		choice_row.append(select_wrapper).append(checkbox_wrapper);
		
// 		aggiungo la scelta del numero di letti e il checkbox sui letti occupati
		box.append(choice_row);
		
		for(var i=0;i<occupants;i++){
			var occGender=$('<div class="occGender occ_'+(i+1)+'"></div>');
			var choice=$('<div class="choice choice_'+(i+1)+'"><i class="fa fa-genderless"></i></div>');
			var inGender=$('<div class="inGender"></div>');
			var male=$('<input type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="male" id="room_'+id+'_occ_'+(i+1)+'_m" value="m"><label class="male" for="room_'+id+'_occ_'+(i+1)+'_m"><i class="fa fa-mars"></i><span>Occupied by a male</span></label>');
			var female=$('<input type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="female" id="room_'+id+'_occ_'+(i+1)+'_f" value="f"><label class="female" for="room_'+id+'_occ_'+(i+1)+'_f"><i class="fa fa-venus"></i><span>Occupied by a female</span></label>');
			var queer=$('<input type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="lgbt" id="room_'+id+'_occ_'+(i+1)+'_q" value="q"><label class="lgbt" for="room_'+id+'_occ_'+(i+1)+'_f"><i class="fa fa-transgender"></i><span>Occupied by a LGBTQ+ person</span></label>');
			var free=$('<input checked type="radio" name="room_'+id+'_occ_'+(i+1)+'" class="free" id="room_'+id+'_occ_'+(i+1)+'_free" value="free"><label class="free" for="room_'+id+'_occ_'+(i+1)+'_free"><i class="fa fa-genderless"></i><span>Vacant</span></label>');
			
			if(this.males>0){
				while(this.males>0){
					male.attr('checked','checked');
					this.males--;
				}
			}else if(this.females>0){
				while(this.females>0){
					female.attr('checked','checked');
					this.females--;
				}
			}else if(this.queers>0){
				while(this.queers>0){
					queer.attr('checked','checked');
					this.queers--;
				}
			}
			
			occGender.append(choice).append(inGender).append(male).append(female).append(queer).append(free);
		}
		
		var occupieds_div=$('<div class="room_row occupieds"></div>').hide();
// 		aggiungo i dettagli sugli occupanti
		box.append(occupieds_div);

		if(this.has_bathroom){
			var bathroom=$('<div class="room_row"><div class="bathroom_wrapper checkbox_wrapper"><input checked="checked" id="has_bathroom_'+this.id+'" class="room_bathroom" name="has_bathroom_'+this.id+'" type="checkbox" value="1" /><label for="has_bathroom_'+this.id+'">Has Bathroom?</label></div></div>');
		}else{
			var bathroom=$('<div class="room_row"><div class="bathroom_wrapper checkbox_wrapper"><input id="has_bathroom_'+this.id+'" class="room_bathroom" name="has_bathroom_'+this.id+'" type="checkbox" value="1" /><label for="has_bathroom_'+this.id+'">Has Bathroom?</label></div></div>');
		}

// 		aggiungo il checkbox sul bagno
		box.append(bathroom);

		var prices_div=$('<div class="room_row prices"></div>');
		var room_input_wrapper=$('<div class="room_input_wrapper"></div>');
		var fa=$('<i class="fa fa-eur"></i>');
		
		var input=[];
		input[0]=$('<input class="number_input" name="room_price_'+this.id+'" placeholder="Price per month" type="text" value="'+this.price+'">').keypress(function(e){
			return isNumber(e);
		});
		input[1]=$('<input class="number_input" name="room_deposit_'+this.id+'" placeholder="Deposit Total" type="text" value="'+this.deposit+'">').keypress(function(e){
			return isNumber(e);
		});
		input[2]=$('<input class="number_input" name="room_bills_'+this.id+'" placeholder="Monthly Bills" type="text" value="'+this.bills+'">').keypress(function(e){
			return isNumber(e);
		});
		room_input_wrapper.append(fa);
		if(!$('#rooms_same_data').prop('checked')&&!$('#rooms_same_data').attr('checked')) {
			for(var i=0;i<3;i++){
				var billz=room_input_wrapper.clone().append(input[i]);
				prices_div.append(billz);
			}
		}
// 		aggiungo i div per i prezzi
		box.append(prices_div);
		
		var avails_row=$('<div class="room_row avails"></div>');								
		var from_div=$('<div class="avail_date_wrapper"><label>Avalaible from:</label><div class="input-group date datepicker" ><input name="avail_from_'+this.id+'" type="text" data-toggle="datepicker" class="form-control" value="'+(this.available_from)+'" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>');
		var to_div=$('<div class="avail_date_wrapper"><label>Avalaible to:</label><div class="input-group date datepicker"><input name="avail_to_'+this.id+'" type="text" data-toggle="datepicker" class="form-control" value="'+(this.available_to)+'" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>');
		avails_row.append(from_div);
		avails_row.append(to_div);

// 		aggiungo gli input per le date
		box.append(avails_row);

		
// 		if($('#rooms_same_data').prop('checked')){ console.log('dm'); $('#rooms_same_data').trigger('change');}

		return box;
	}
	this.update=function(){

		$('#room_'+this.id+' .beds_no option[value="'+this.beds+'"]').attr('checked','checked').prop('selected',true).trigger('change');

		if(this.occupants>0){
			$('#room_'+this.id+' #room_occupied_'+this.id).attr('checked','checked').prop('selected',true);
			for(var i=0;i<this.beds;i++){
				if(this.males>0){
					while(this.males>0){
						$('#room_'+this.id+'_occ_'+(i+1)+'_m').attr('checked','checked').trigger('change');
						this.males--;
					}
				}else if(this.females>0){
					while(this.females>0){
						$('#room_'+this.id+'_occ_'+(i+1)+'_f').attr('checked','checked').trigger('change')
						this.females--;
					}
				}else if(this.queers>0){
					while(this.queers>0){
						$('#room_'+this.id+'_occ_'+(i+1)+'_q').attr('checked','checked').trigger('change');
						this.queers--;
					}
				}else{
					$('#room_'+this.id+'_occ_'+(i+1)+'_free').attr('checked','checked').trigger('change');
				}
			}
		}
		$('#room_'+this.id+' #room_occupied_'+this.id).trigger('change');
		if(this.has_bathroom) $('#has_bathroom_'+this.id).attr('checked','checked'); 
		if(this.price) $('input[name="room_price_'+this.id+'"]').val(this.price);
		if(this.deposit) $('input[name="room_deposit_'+this.id+'"]').val(this.deposit);
		if(this.bills) $('input[name="room_bills_'+this.id+'"]').val(this.bills);
		$('input[name="avail_from_'+this.id+'"]').val(this.available_from);
		$('input[name="avail_to_'+this.id+'"]').val(this.available_to);
	}
}
// [PROPERTY EDIT AND SHARE, ADMIN PROPERTY]
if(dropzone){
	dropzone.ondragover=function(ev){
		ev.preventDefault();
		dropzone.style.borderColor = "red";
	};

	dropzone.ondrop=function(e){
		e.preventDefault();

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		var i = 0;
		$(files).each(function (e) {
			var file=$(this);
			images_tbu.push(file[0]);
			var reader = new FileReader();
			reader.onload = function (e) {
				var img = $("<div></div>");
				var span=$('<span class="remove_image"><i class="fa fa-close"></i></span>');
				img.attr("class", "new_image custom-thumb img-responsive col-lg-4 col-xs-12 col-md-4 col-sm-4");
				img.attr("style", 'background-image:url('+e.target.result+')');
				img.attr('data-name',file[0].name);
				img.append(span);
				$("#img-preview").append(img);
				$('.remove_image').unbind('click').click(previewRemover);
			};
			reader.readAsDataURL(files[i]);
			i++;
		});
	};
}
// [PROPERTY EDIT AND SHARE, ADMIN PROPERTY]
function PriceInput(id,inp){
	this.getInputs=function(){
		var wrapper=$('<div class="room_input_wrapper"></div>');
		var input=[];
		var fa=$('<i class="fa fa-eur"></i>');
		input[0]=$('<input class="number_input" name="room_price_'+id+'" placeholder="Price per month" type="text">').keypress(function(e){
			return isNumber(e);
		});
		input[1]=$('<input class="number_input" name="room_deposit_'+id+'" placeholder="Deposit Total" type="text">').keypress(function(e){
			return isNumber(e);
		});
		input[2]=$('<input class="number_input" name="room_bills_'+id+'" placeholder="Monthly Bills" type="text">').keypress(function(e){
			return isNumber(e);
		});
		wrapper.append(fa,input[inp]);
		return wrapper;
	
	}
}

												
// [PROPERTY EDIT AND SHARE, ADMIN PROPERTY]											
function updateRoomBoxes(room_number_from,room_number_to){
	var wrapper=$('.room_wrapper');
	if(room_number_from>room_number_to){
		var diff=room_number_from-room_number_to;
		for(var i=diff;i>0;i--){
			$('.room_box').not('.tbr').last().fadeOut(i*200).addClass('tbr');
		}
	}else if(room_number_from<room_number_to){
		var diff=room_number_to-room_number_from;
		for(var i=0;i<diff;i++){
			var room=new Room((room_number_from+i+1),1,0,0,0,0,0,'','','','','');
			var div=room.render();
			div.hide().fadeIn(i*200).appendTo(wrapper);
		}
	}
	$('.beds_no').trigger('change');
	$('.number_input').keypress(function(e){
		return isNumber(e);
	});
	$('[data-toggle="datepicker"]').datepicker({
		format:'dd/mm/yyyy'
	});

}
// [PROPERTY EDIT(?) AND SHARE, ADMIN PROPERTY]
function sendFormData(formData,url){
	$('input[type="checkbox"]:checked').each(function(){
			formData.append($(this).attr('name'),$(this).val());
	});
	$('input[type="radio"]:checked').each(function(){
			formData.append($(this).attr('name'),$(this).val());
	});
	$('input,textarea,select').not(':checkbox').not(':radio').each(function(){
		if($(this).val() && $(this).val()!==''){
			formData.append($(this).attr('name'),$(this).val());
		}
	});
	if(images_tbu.length>0){
		for(var i=0;i<images_tbu.length;i++){
			formData.append('photo[]',images_tbu[i]);
		}
	}
	if(images_tbd.length > 0){
		for(var i=0;i<images_tbd.length;i++){
			formData.append('photo_tbd[]',images_tbd[i]);
		}
	}

	$.ajax({
		type:'POST',
		url:url,
		data:formData,
		success:function(d){
			formData=null;
			window.location.href='/properties/'+d+'/packages';
		},
		error:function(err){
		},
		contentType:false,
		processData:false
	});
}
// [PROPERTY EDIT AND SHARE(?), ADMIN PROPERTY]
function sendFormData_update(formData,url){
	$('input[type="checkbox"]:checked').each(function(){
			formData.append($(this).attr('name'),$(this).val());
	});
	$('input[type="radio"]:checked').each(function(){
			formData.append($(this).attr('name'),$(this).val());
	});
	$('input,textarea,select').not(':checkbox').not(':radio').each(function(){
		if($(this).val() && $(this).val()!==''){
			formData.append($(this).attr('name'),$(this).val());
		}
	});
	if(images_tbu.length>0){
		for(var i=0;i<images_tbu.length;i++){
			formData.append('photo[]',images_tbu[i]);
		}
	}
	if(images_tbd.length > 0){
		for(var i=0;i<images_tbd.length;i++){
			formData.append('photo_tbd[]',images_tbd[i]);
		}
	}

	$.ajax({
		type:'POST',
		url:url,
		data:formData,
		success:function(d){
			formData=null;
// 			location.reload()
			window.location.href='/properties/'+d.id;
		},
		error:function(err){
		},
		contentType:false,
		processData:false
	});
}
function fave(property_id){
	var url='/properties/'+property_id+'/fave'
	$.ajax({
		type:'POST',
		url:url,
		context:document.body,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success:function(d){
			location.reload()
// 			if(d.done===true){
// // 				$('#favorite_'+property_id).addClass('favorited').html('Added to favorites <i class="fa fa-heart"></i>');
// 			}else{
// 				$('#favorite_'+property_id).removeClass('favorited').html('Add to favorites <i class="fa fa-heart-o"></i>');
// 			} 
		},
		error:function(err){
		},
		contentType:false,
		processData:false
	});
}
// [PROPERTY LIST/SEARCH]
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

// messages page
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
// property share/edit
function perRoomPricing(enabled,init){
	if(enabled){
		for(var i=0;i<$('.room_box').length;i++){
			var id=i+1;
			for(var p=0;p<3;p++){
				var pi=new PriceInput(id,p);
				var inputs=pi.getInputs();
				$('#room_'+id+' .room_row.prices').append(inputs);
				
			}
		}
		$('.pricing_wrapper').fadeOut();
	}else{
		$('.room_row.prices').not('.main_price').html('');
		$('.pricing_wrapper').fadeIn();
	}
// 	$('#rooms_same_data').trigger('change');
}

function previewRemover(e){
		var img_preview=$(e.target).parent().parent();
		if(img_preview.attr('id')) {var img_id=img_preview.attr('id').split('_')[1];}
		if(img_preview.attr('data-name')) {var img_name=img_preview.attr('data-name');}
		
// 		controllo se ho cliccato la x o il suo contenitore, e rimuovo la preview
		if(img_preview.attr('id')!=='img-preview'){

// 		rimuovo l'immagine -> se è nuova la tolgo dall'array, se è vecchia l'aggiungo all'array dei file da rimuovere
			if(!img_id){
				//immagine nuova
				for(var i=0;i<images_tbu.length;i++){
					if(images_tbu[i].name===img_name){
						var el=images_tbu.splice(i,1);
					}
				}
			}else{
				//immagine vecchia
				images_tbd.push(img_id);
			}
			img_preview.remove();
		} else {
			var img_preview=$(e.target).parent();
			if(img_preview.attr('id')) {var img_id=img_preview.attr('id').split('_')[1];}
			if(img_preview.attr('data-name')) {var img_name=img_preview.attr('data-name');}

			if(!img_id){
				//immagine nuova
				for(var i=0;i<images_tbu.length;i++){
					if(images_tbu[i].name===img_name){
						var el=images_tbu.splice(i,1);
					}
				}
			}else{
				//immagine dal db
				images_tbd.push(img_id);
			}
			
			img_preview.remove();
		}
}

// 	all pages
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
//        class logger
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

// 	all pages
	
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
	
// 	profile edit page
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
	
// 	invites page
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
// 	wanteds edit/create
	
	$('#range_rad').change(function(){
		$('#autocomplete').trigger('click');
		cityCircle.setRadius(+document.getElementById('range_rad').value*1000);
	});
	
//  wanted ads/search
	
// 	listing / search form (i click anche su wanted ads/search)
	
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
	
// 	message pages
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
// 	property view
	$('.bxslider').bxSlider({
		'pager':false
	});
	$('.favorite').click(function(e){
		e.preventDefault();
		var property_id=$(this).attr('id').split('_')[1];
		fave(property_id);
	});
        if($('#sticky_buttons').length>0){
            var sticky_width=$('#sticky_buttons').outerWidth();
            var sticky_right=$(window).width() - ($('#sticky_buttons').offset().left + sticky_width);
            $(window).resize(function(){
                    sticky_width=$('#sticky_buttons').outerWidth();
                    sticky_right=$(window).width() - ($('#sticky_buttons').offset().left + sticky_width);
            });
            $(window).scroll(function() {
                    if($('#sticky_buttons').offset().top-$(window).scrollTop() <= 25) {
                            $('#sticky_buttons').addClass('fixed_div').css({'width':sticky_width,'right':sticky_right});
                    }
                    if($('#sticky_buttons_wrapper').offset().top-$(window).scrollTop() > 20) {
                            if($('#sticky_buttons').hasClass('fixed_div')) $('#sticky_buttons').removeClass('fixed_div').removeAttr('style');
                    }
            });
        }
	
// 	property share/edit pages
	$('.remove_image').unbind('click').click(previewRemover);
	$('.number_input').keypress(function(e){
		return isNumber(e);
	});
	$('#to_package').click(function(e){
		e.preventDefault();
		sendFormData(formData,'/properties');
	})
	$('#update_property').click(function(e){
		e.preventDefault();
		sendFormData_update(formData,'/properties/'+$('#property_id').val());
	})
	$('[data-toggle="datepicker"]').datepicker({
		format:'dd/mm/yyyy'
	});
	$('#to_tab_1').click(function(e){
		e.preventDefault();
		$('.nav-tabs a[href="#home"]').tab('show');
	});
	$('#to_tab_2').click(function(e){
		e.preventDefault();
		$('.nav-tabs a[href="#room"]').tab('show');
	});
	$('#to_tab_3').click(function(e){
		e.preventDefault();
		$('.nav-tabs a[href="#accept"]').tab('show');
	});
	if($('#rooms_same_data').prop('checked')||$('#rooms_same_data').attr('checked')) {
		perRoomPricing(false,true);
	}else{
		perRoomPricing(true,true);
	}

	$('.tbr').remove();
	
	// valore preso dal <select>
	var room_number_to=$('#rooms_no').val();
	// numero di box attualmente in pagina
	var room_number_from=$('.room_box').length;

	updateRoomBoxes(room_number_from,room_number_to);
	if($('.room-data').length>0){
		var datarray={};
		var ids=[];
		$('.room-data').each(function(){
			var id=$(this).attr('name').split('-')[1];
			datarray[id]={};
			ids.push(id);
		});
		var unique = [];
		$.each(ids, function(i, el){
			if($.inArray(el, unique) === -1) unique.push(el);
		});	
		$('.room-data').each(function(){
			var id=$(this).attr('name').split('-')[1];
			var name=$(this).attr('name').split('-')[0];
			datarray[id][name]=$(this).val().replace('[','').replace(']','');
		});
		for(var i=1;i<=unique.length;i++){
			var stanza=new Room(i,datarray[i]['beds'],datarray[i]['occupants'],datarray[i]['males'],datarray[i]['females'],datarray[i]['queers'],datarray[i]['has_bathroom'],datarray[i]['price'],datarray[i]['bills'],datarray[i]['deposit'],datarray[i]['available_from'],datarray[i]['available_to']);
			stanza.update();
		}
	}
	
	$('#rooms_same_data').change(function(){
		if($(this).prop('checked')){
			perRoomPricing(false,false);
		}else{
			perRoomPricing(true,false);
		}
	});
	$('#rooms_no').change(function(){
		$('.tbr').remove();
		room_number_to=$(this).val();
		room_number_from=$('.room_box').length;

		updateRoomBoxes(room_number_from,room_number_to);
	});

	$('#contract').change(function(){
		$($(this)[0].files).each(function () {
			var file = $(this);
			formData.append('contract',file[0]);
			$('.contract_name p').html('').html(file[0].name);
		});
	});
	$("#dropArea").click(function () {
		$('#uploadfile').trigger('click');
		$('#uploadfile').change(function () {

			$($(this)[0].files).each(function () {
				var file = $(this);
				images_tbu.push(file);
				var reader = new FileReader();
				reader.onload = function (e) {
					var img = $("<div></div>");
					var span=$('<span class="remove_image"><i class="fa fa-close"></i></span>');
					img.attr("class", "new_image custom-thumb img-responsive col-lg-4 col-xs-12 col-md-4 col-sm-4");
					img.attr("style", 'background-image:url('+e.target.result+')');
					img.attr('data-name',file.name);
					img.append(span);
					$("#img-preview").append(img);
					$('.remove_image').unbind('click').click(previewRemover);
				};
				reader.readAsDataURL(file);
				file.empty();
			});
		});
	});

// 	profile edit page
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
	// property/wanted page delete + review delete (profile page)
	$('form button.btn-delete').click(function(e){
		e.preventDefault();
		if(confirm('Are you sure you want to delete this item? (Warning: This action cannot be undone)')){
			$(this).parent().submit();
		}else{
			return false;
		}
	});


	$('button.address').click(function(e){
		e.preventDefault();
		findAddress();
	});
// 	$('input[name="package"]').change(function(){
// 		$('form#package_form').submit();
// 	});
	$('.package button').click(function(e){
		e.preventDefault();
	})
	$('.package div[type="submit"]').click(function(){
		$(this).parent().parent().parent().submit();
	});
	
	
});
