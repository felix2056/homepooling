							<div role="tabpanel" class="tab-pane fade active in" id="home" aria-labelledby="home-tab"> 
								<h4 style="color: #333">Address Location</h4>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="row">
											<div class="col-lg-9 col-sm-8 col-xs-8" style="padding-right: 0;">
												<span class=" glyphicon glyphicon-map-marker custom-fixed-icon" style="position: absolute; font-size: 22px; left: 25px; top: 10px; color: #049EE3" aria-hidden="true"></span>
												<input style="border-top-right-radius: 0; border-bottom-right-radius: 0;" name ="address_long" id="autocomplete" autocomplete="false" type="text" class="custom-input form-control" placeholder="Type the address of the property" @isset($property) @isset($property->location) value="{{$property->location}}" @else value="{{$property->address}}"  @endisset @endisset />
												
												<input type="hidden" name="lat" id="lat" @isset($property) value="{{$property->lat}}" @endisset />
												<input type="hidden" name="long" id="long" @isset($property) value="{{$property->long}}" @endisset />

												<input type="hidden" name="address" id="address" @isset($property) value="{{$property->address}}" @endisset />
												<input type="hidden" name="town" id="town" @isset($property) value="{{$property->town}}" @endisset />
												<input type="hidden" name="postal_code" id="postal_code" @isset($property) value="{{$property->postal_code}}" @endisset />

											</div>
											<div class="col-lg-3 col-sm-4 col-xs-4" style="padding-left: 0">
												<button style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 44px" class="address btn btn-default custom-btn btn-block">Find</button>
											</div>
										</div>
                    <div id="gmaps" class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="width:100%;height:256px;margin:10px 0;"></div>
									</div>
									<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
										<h4 style="color: #333; margin-top: 20px">Property Type</h4>
										<div class="row">
											<div class="text-left">
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="property_type" type="radio" id="type1" value="house" @if(isset($property) && $property->property_type==='house') checked="checked" @endif ><label class="btn btn-block btn-light btn-lg" for="type1">House</label>
												</div>
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="property_type" type="radio" id="type2" value="apartment" @if(isset($property) && $property->property_type==='apartment') checked="checked" @endif><label class="btn btn-block btn-light btn-lg" for="type2">Apartment</label>
												</div>
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="property_type" type="radio" id="type3" value="other"
													@if(isset($property) && $property->property_type==='other') checked="checked" @endif ><label class="btn btn-block btn-light btn-lg" for="type3">Other</label>
												</div>
											</div>
										</div>
									</div>
                  <h4 class="col-lg-12 col-sm-12 col-xs-12 col-md-12"  style="color: #333">Upload Images</h4>
                  <p class="col-lg-12 col-sm-12 col-xs-12" style="color: #666">You may upload a total of 9 images</p>
                  <div class="col-lg-12  col-sm-12  col-xs-12" style="margin-bottom:10px;">
                    <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12 text-center" >
                        <input type="file" id="uploadfile" name="photo[]" style="display: none" multiple="" />
                        <div class="text-center" id="dropArea">
                          <span class="glyphicon glyphicon-picture" style="font-size: 30px;" aria-hidden="true"></span>
                          <p>Drag your Images here</p>                                  
                          <p>Or click to upload</p>
                        </div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="img-preview">
                        @if(isset($property) && $property->images())
                          @foreach($property->images as $image)
                            <div id="existing_{{$image->id}}" class="existing custom-thumb img-responsive col-lg-4 col-xs-12 col-md-4 col-sm-4" style="background-image:url({{$image->url}})"><span class="remove_image"><i class="fa fa-close"></i></span></div>
                          @endforeach
                        @endif
                      </div>
                    </div>
                  </div>
									<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
										<h4 style="color: #333">You are a</h4>
										<div class="row">
											<div class="">
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="user_type" type="radio" id="utype1" value="livein" @if(isset($property) && $property->user_type==='livein') checked="checked" @endif ><label class="btn btn-block btn-default btn-lg" for="utype1">Live-in landlord</label>
												</div>
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="user_type" type="radio" id="utype2" value="liveout" @if(isset($property) && $property->user_type==='liveout') checked="checked" @endif ><label class="btn btn-block btn-default btn-lg" for="utype2">Live-out landlord</label>
												</div>
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="user_type" type="radio" id="utype3" value="flatmate" @if(isset($property) && $property->user_type==='flatmate') checked="checked" @endif ><label class="btn btn-block btn-default btn-lg" for="utype3">Current tenant / flatmate</label>
												</div>
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="user_type" type="radio" id="utype4" value="agent" @if(isset($property) && $property->user_type==='agent') checked="checked" @endif ><label class="btn btn-block btn-default btn-lg" for="utype4">Agent</label>
												</div>
												<div class="col-md-4" style="margin-bottom: 10px">
													<input name="user_type" type="radio" id="utype5" value="former" @if(isset($property) && $property->user_type==='former') checked="checked" @endif ><label class="btn btn-block btn-default btn-lg" for="utype5">Former flatmate</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
                    <h4 style="color: #333">Amenities</h4>
										<div class="row">
											<div class="amenities">
												@foreach($amenities as $am)
													<div class="col-md-4 col-sm-6 col-xs-6 col">
														<input id="{{'am'.$am->id}}" name="amenities[]" type="checkbox" class="amenity_check {{$am->name}}" value="{{$am->id}}" @if( isset($property) && in_array($am->id, $p_amenities) ) checked="checked" @endif>
														<label for="{{'am'.$am->id}}" class="btn-block"><span><img style="height: 30px" src="{{$am->icon}}"/></span>{{$am->label}}</label>
													</div>
												@endforeach
											</div>
										</div>
									</div>

                  <div class="col-lg-4 col-sm-4 col-xs-4 col-md-4 epc" style="margin-top: 0px">
                    <h4 style="color: #333">
                      EPC Energy Score
                    </h4>
                    <div class="sa-lable">
                      <select class="epc_select sa-select" name="epc" style="border: 1px solid #D9DEE2; color: #333">
                        <option name="epc" value="a" @if(isset($property) && $property->epc==='a') checked="checked" @endif >A</option>
                        <option name="epc" value="b"  @if(isset($property) && $property->epc==='b') checked="checked" @endif >B</option>
                        <option name="epc" value="c" @if(isset($property) && $property->epc==='c') checked="checked" @endif >C</option>
                        <option name="epc" value="d" @if(isset($property) && $property->epc==='d') checked="checked" @endif >D</option>
                        <option name="epc" value="e" @if(isset($property) && $property->epc==='e') checked="checked" @endif >E</option>
                        <option name="epc" value="f" @if(isset($property) && $property->epc==='f') checked="checked" @endif >F</option>
                        <option name="epc" value="g" @if(isset($property) && $property->epc==='g') checked="checked" @endif >G</option>
                      </select>
                    </div>
                  </div>
                  @if(false)
                  <div class="col-lg-4 col-sm-4 col-xs-4 col-md-4 tab_section contract_wrapper epcert" style="margin-top: 0px">
                    <h4  style="color: #333; ">Energy Performance Certificate</h4>
                    <div class="">
                      <div class="contract_div">
                        <input id="epcert" name="epcert" type="file">
                        <label class="btn btn-default custom-btn btn-block" for="epcert">Upload Certificate</label>
                        <div class="epcert_name"><p>
                        @if(isset($property) && isset($property->epcert))
                          {{explode('/',$property->epcert->url)[substr_count($property->epcert->url,'/')]}}
                        @endif
                        </p></div>
                      </div>
                    </div>
                  </div>
                  @endif
                  <div class="col-lg-12 col-xs-12 col-sm-4 col-md-4" style="margin-top: 6em">
                    <div class="row">
                      <div class="col-lg-4">
                        <a class="btn btn-lg btn-primary btn-block" href="#room" id="to_tab_2">Continue</a>
                      </div>
                    </div>
                  </div>
								</div>
							</div> 
