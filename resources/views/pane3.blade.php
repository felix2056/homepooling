							<div role="tabpanel" class="tab-pane tab_edit fade" id="accept" aria-labelledby="dropdown1-tab">
                @if(false)
								<div class="tab_title">
									<h4 style="color: #30B0E8; padding: 0;">Extras</h4>
									<strong style="color: #40B6EA">Who is your ideal tenant ? How would you introduce the property? Which are the house rules?</strong>
								</div>
								<div class="tab_section minimum_wrapper">
									<div class="minimum_title">
										<h4 style="color: #30B0E8; padding: 0;">Minimum stay</h4>
									</div>
									<div class="minimum_input">
										<input class="form-control number_input" type="text" id="minimum_stay" name="minimum_stay"
											@if(isset($property) && $property->minimum_stay)
												value="{{$property->minimum_stay}}"
											@endif
										/>
										<label for="minimum_stay">months (leave blank for no minimum stay)</label>
									</div>
								</div>
                @endif
								<div class="tab_section acceptings_wrapper">
                  <h4 style="color: #333">Accepting</h4>
                  <div class="row">
                    <div class="acceptings">
                      @foreach($acceptings as $ac)
                        <div class="col-md-3 col-sm-4 col-xs-6 col">
                          <input id="{{'ac'.$ac->id}}" name="acceptings[]" type="checkbox" class="amenity_check {{$ac->name}}" value="{{$ac->id}}" @if( isset($property) && in_array($ac->id, $p_amenities) ) checked="checked" @endif>
                          <label for="{{'ac'.$ac->id}}" class="btn-block"><span><img style="height: 30px" src="{{$ac->icon}}"/></span>{{$ac->label}}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
								</div>
								<div class="tab_section description_wrapper">
									<h4 style="color: #333; ">Message from you</h4>
									<p style="color: #777; ">Tell us a little more about the property.</p>
									<div class="">
										<div class="description">
											<textarea name="description" class="form-control" rows="5">@if(isset($property)) {{$property->description}} @endif </textarea>
										</div>
									</div>
								</div>
								<div class="tab_section contract_wrapper">
									<h4  style="color: #333; ">Contract (Optional)</h4>
									<p style="color: #777; ">Upload contract (in .pdf format)</p>
									<div class="">
										<div class="contract_div">
											<input id="contract" name="contract" type="file">
											<label class="btn btn-default custom-btn" for="contract">Upload Contract</label>
											<div class="contract_name"><p>
											@if(isset($property) && $property->contract)
												{{explode('/',$property->contract->url)[substr_count($property->contract->url,'/')]}}
											@endif
											</p></div>
										</div>
									</div>
								</div>
								<div style="margin-top: 6em;">
									<div class="clearfix">
										@if(isset($property))
										<!-- Button trigger modal -->
										<button type="button" class="btn btn-lg btn-primary btn-block btn_submit" data-toggle="modal" data-target="#termAndConditions">
										  Save Property
										</button>
										@else
										<button type="button" class="btn btn-lg btn-primary btn-block btn_submit" data-toggle="modal" data-target="#termAndConditions">
										  Save Property
										</button>
										@endif
									</div>
								</div>

								<!-- Modal -->
								<div class="modal fade" id="termAndConditions" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
								  <div class="modal-dialog modal-dialog-centered" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
								        
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          <span aria-hidden="true">&times;</span>
								        </button>
								      </div>
								      <div class="modal-body">
								      	<div class="container" style="height: 60vh; overflow: auto;">
								      		<?php $terms = App\Setting::where('meta_key', 'terms')->pluck('meta_value');
								        	echo html_entity_decode($terms);
								      ?>
								      	</div>
								      </div>
								      <div class="modal-footer">
								      	<button type="button" class="btn btn-secondary" data-dismiss="modal">Disagree</button>
								      	@if(isset($property))
											<a class="tooltip-test btn btn-lg btn-primary btn-block btn_submit" style="float:left;" href="#share" id="update_property" title="clicking accept means you agree to all the terms">Accept</a>
										@else
											<a class="tooltip-test btn btn-lg btn-primary btn-block btn_submit" style="float:left;" href="#share" title="clicking accept means you agree to all the terms" id="to_package">Accept</a>
										@endif
								      </div>
								    </div>
								  </div>
								</div>