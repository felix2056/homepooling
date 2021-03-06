							<div role="tabpanel" class="tab-pane fade" id="room" aria-labelledby="room-tab">
								<div class="tab_title">
									<h4 style="color: #333; padding: 0;">Rooms</h4>
									<span style="color: #333">How many rooms are there? Which are <strong><span style="color: #049EE3">occu</span><span style="color: #E67EB6">pied</span></strong>? which are <strong style="color: #42E695">vacant</strong>?</span>
								</div>
								<div class="tab_section" style="margin-top: 10px;">
									<div class="select_wrapper">
										<select id="rooms_no" name="rooms_no">
										@if(isset($property))
											@for($i = 0; $i < 10; $i++)
												<option name="rooms_no" value="{{$i+1}}" {{ $property->rooms_no == ($i+1) ? 'checked selected' : ''}}>{{ $i+1 }}</option>
											@endfor
										@else
											@for($i = 0; $i < 10; $i++)
												<option name="rooms_no" value="{{$i+1}}" >{{ $i+1 }}</option>
											@endfor
										@endif
										</select>
										<span style="color: #333"># of Rooms</span>
									</div>
									<div class="checkbox_wrapper">
										<input {{ (isset($property->price) || isset($property->deposit) || isset($property->bills)) ? 'checked selected' : ''}} id="rooms_same_data" name="rooms_same_data" type="checkbox" value="1" /><label for="rooms_same_data" style="color: #333">Rooms have the same price</label>
									</div>
								</div>
								<div class="tab_section room_wrapper">
								</div>
								<div class="tab_section pricing_wrapper">
									<div class="section_title">
										<h4 style="color: #333">Pricing</h4>
									</div>
									<div class="room_row prices main_price">
										<div class="input-group room_input_wrapper">
											<div class="input-group-addon"><i class="fa fa-eur"></i></div>
											<input class="number_input form-control" type="text" name="room_price_main" placeholder="Price per month" value="{{isset($property->price) ? $property->price : ''}}">
										</div>
										<div class="input-group room_input_wrapper">
											<div class="input-group-addon"><i class="fa fa-eur"></i></div>
											<input class="number_input form-control" type="text" name="room_deposit_main" placeholder="Deposit total" value="{{isset($property->deposit) ? $property->deposit : ''}}">
										</div>
										<div class="input-group room_input_wrapper">
											<div class="input-group-addon"><i class="fa fa-eur"></i></div>
											<input class="number_input form-control" type="text" name="room_bills_main" placeholder="Monthly bills" value="{{isset($property->bills) ? $property->bills : ''}}">
										</div>
									</div>
								</div>
								<div style="margin-top: 6em;">
									<div class="clearfix">
										<a class="btn btn-lg btn-primary btn-block btn_next" style="float:left;" href="#accept" id="to_tab_3">Continue</a>
									</div>
								</div>
							</div> 
