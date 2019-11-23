							<div role="tabpanel" class="tab-pane fade" id="accept" aria-labelledby="dropdown1-tab">
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
                        <div class="col-md-3 col-sm-6 col-xs-6 col">
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
											<a class="btn btn-lg btn-primary btn-block btn_submit" style="float:left;" href="#share" id="update_property">Save Property</a>
										@else
											<a class="btn btn-lg btn-primary btn-block btn_submit" style="float:left;" href="#share" id="to_package">Continue</a>
										@endif
									</div>
								</div>

