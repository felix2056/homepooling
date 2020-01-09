				@foreach($properties as $property)
				<?php
					$diff = $now->diffInDays($property->created_at);
					$early_access = $property->early_access;
				?>
				<div class="listing-block  {{ isset($forShow) ? '' : 'nonVis' }}">
					<!-- col-xs-12 col-sm-6 col-md-4 -->
					<a href="/properties/{{$property->id}}" class="listing-image">
						@if(isset($property->images) && $preview = $property->images()->first())
							<?php
								$ext = '.'.pathinfo('/storage/img/'.$preview->url, PATHINFO_EXTENSION);
								$previewThumb = str_replace(basename('/storage/img/'.$preview->url), 'thumbs/'.basename('/storage/img/'.$preview->url, $ext).'_th'.$ext, '/storage/img/'.$preview->url);
							?>
							<img src="{{ file_exists(public_path($previewThumb)) ? $previewThumb : '/storage/img/'.$preview->url }}">
						@else
							<img src="/storage/img/placeholder.png">
						@endif
						@if($diff < 7 && $early_access == 1)
							<span class="listing-label label-early">Early</span> @elseif($diff < 7) <span class="listing-label label-new">New</span>
						@endif
						<span class="listing-price">&#8364;{{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}</span>
						@if(isset($property->highlighted) && $property->highlighted == 1)
							<div class="featured-label">
								<div class="ribbon"><span>{{ $property->status }}</span></div>
							</div>
						@endif
					</a>
					<div class="listing-visitors">
						<a href="#" class="visitor visitor-main" style="background-image:url({{ isset($property->user->photo) ? $property->user->photo : '/storage/img/profile_placeholder.png'}})"><!--<span class="blue_circle"></span>--></a>
						@foreach($property->rooms as $room)
							@if($room->occupants > 0 && $room->lgbt > 0)
								@if($room->beds > $room->occupants)
									<a href="#" class="visitor" style="background-image:unset;
									background: #76c36a;background: -moz-linear-gradient(-45deg, #76c36a 0%, #76c36a 50%, #ffff00 61%, #e00000 72%, #00ffff 82%, #0000ff 92%, #ee82ee 100%);background: -webkit-linear-gradient(-45deg,#76c36a 0%, #76c36a 50%,#ffff00 61%,#e00000 72%,#00ffff 82%,#0000ff 92%,#ee82ee 100%);background: linear-gradient(135deg,#76c36a 0%, #76c36a 50%,#ffff00 61%,#e00000 72%,#00ffff 82%,#0000ff 92%,#ee82ee 100%);"><i class="fa fa-transgender"></i></a>
								@else
									<a href="#" class="visitor" style="background-image:unset;background: red;background: -webkit-linear-gradient(-45deg, orange , yellow, red, cyan, blue, violet); background: -o-linear-gradient(-45deg, orange, yellow, red, cyan, blue, violet);background: -moz-linear-gradient(-45deg, orange, yellow, red, cyan, blue, violet);background: linear-gradient(135deg, orange , yellow, red, cyan, blue, violet);"><i class="fa fa-transgender"></i></a>
								@endif
							@elseif($room->occupants > 0 && $room->female > 0)
								@if($room->beds > $room->occupants)
									<a href="#" class="visitor" style="background-image:unset;background: #76c36a;background: -moz-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#ff6969 50%, #ff6969 100%); background: -webkit-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#ff6969 50%,#ff6969 100%); background: linear-gradient(135deg, #76c36a 0%,#76c36a 50%,#ff6969 50%,#ff6969 100%);"><i class="fa fa-venus"></i></a>
								@else
									<a href="#" class="visitor" style="background-image:unset;background-color: #ff6969;"><i class="fa fa-venus"></i></a>
								@endif
							@elseif($room->occupants > 0 && $room->male > 0)
								@if($room->beds > $room->occupants)
									<a href="#" class="visitor" style="background-image:unset;background:#009ee3;background: -moz-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#009ee3 50%, #009ee3 100%); background: -webkit-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#009ee3 50%,#009ee3 100%); background: linear-gradient(135deg, #76c36a 0%,#76c36a 50%,#009ee3 50%,#009ee3 100%);"><i class="fa fa-mars"></i></a>
								@else
									<a href="#" class="visitor" style="background-image:unset;background-color: #009ee3;"><i class="fa fa-venus"></i></a>
								@endif
							@else
								<a href="#" class="visitor empty"><i class="fa fa-genderless"></i></a>
							@endif
						@endforeach
					</div>
					<div class="listing-info" style="min-height: 100px">
						<a href="/properties/{{$property->id}}">{{ $property->address }}</a>
						<span>{{ $property->postal_code.' '.$property->town }}</span>
						<div class="contactable">
							@if($diff > 7 || $early_access == 1 || (\Auth::check() && \Auth::user()->early_bird == 1))
								<i class="fa fa-comment-o" style="font-size:2em;color:#3a3a3a"></i>
								<i class="fa fa-check" style="color:#42e695"></i>
							@else
								<!-- <i class="fa fa-close" style="color:#993b38"></i> -->
								<img src="/storage/icons/earlyicon.png" class="early-icon-msg">
							@endif
						</div>
					</div>
				</div>
				@endforeach
 
