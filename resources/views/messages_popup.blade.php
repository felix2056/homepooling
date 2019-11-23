<?php
	$conversations = collect();
	if(auth()->check()){
		$user = auth()->user();
		$conversations = \App\Message::with(['user', 'receiver'])->where('user_id', $user->id)->orWhere('receiver_id', $user->id)->orderBy('created_at', 'desc')->get();
	}
	$ctLoaded 	= [];
	$months 	= ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>
<script type="text/javascript">
	window.myInfo = {
		photo: @if(auth()->check()) "{{ $user->photo }}" @else '' @endif,
		name: @if(auth()->check()) "{{ $user->name.' '.$user->family_name }}" @else '' @endif
	};
</script>
<div class="messages-popup">
	<div class="messages-wrapper">
		<!-- List -->
		<div class="cl-window show">
			<div class="chat-header">
				<span>Messages</span>
				<span>
					<span class="close-chat">&times;</span>
				</span>
			</div>
			<div class="contacts-list">
				<!-- ctlist loader -->
				<div class="lds-css ng-scope ctlist-loader">
					<div style="width:100%;height:100%" class="lds-eclipse">
						<div></div>
					</div>
				</div>
				<div class="no-convs">No conversations yet!</div>
				<!-- contact -->
				@if(auth()->check())
					@foreach($conversations as $c)
						@if($c->receiver_id)
							<?php
								$me 	= $c->user_id == $user->id;
								$msgCt 	= $me ? $c->receiver : $c->user;
								$cDate 	= \Carbon\Carbon::parse($c->created_at);
							?>
							@if(!in_array($msgCt->id, $ctLoaded))
								<div class="contact{{ $c->user_id != $user->id && $c->seen == 0 ? ' notify' : '' }}" data-id="{{ $msgCt->id }}">
									<div class="ct-avatar-cont">
										<div class="ct-avatar" style="background-image: url('{{ $msgCt->photo }}');"></div>
									</div>
									<div class="ct-info">
										<div class="name">{{ $msgCt->name.' '.$msgCt->family_name }}</div>
										<div class="latest-msg">{{ ($me ? 'You: ' : '') . $c->text }}</div>
									</div>
									<div class="date">{{ $cDate->day.' '.$months[$cDate->month] }}</div>
								</div>
								<?php array_push($ctLoaded, $msgCt->id); ?>
							@endif
						@endif
					@endforeach
				@endif
			</div>
		</div>
		<!-- DMs -->
		<div class="dm-window">
			<div class="chat-header">
				<span class="back-to-contact-list">
					<i class="fa fa-angle-left"></i>
				</span>
				<span class="user-name">Sarah DeWobble</span>
				<span>
					<span class="close-chat">&times;</span>
				</span>
			</div>
			<div class="messages-list">
				<!-- conversation loader -->
				<div class="lds-css ng-scope conv-loader">
					<div style="width:100%;height:100%" class="lds-eclipse">
						<div></div>
					</div>
				</div>
				<div class="error">Error loading conversation!</div>
				<div class="no-messages">No messages yet!</div>
				<!-- Message -->
				<?php /*<div class="message" data-id="2">
					<div class="m-avatar-cont">
						<div class="m-avatar" style="background-image: url('http://localhost:8000/storage/img/xydKv5Y8ZSPWfBcBvEfICuNFy7BPp8nDbzoX9FM5.jpeg');"></div>
					</div>
					<div class="m-info">
						<div class="name clearfix">Rupinder Singh 4<span class="timestamp">7/10/2015 14:40</span></div>
						<div class="msg">Ooooh Hellooo, let’s share a homepool Un Poco Poco Lorem Ipsum...</div>
					</div>
				</div>*/ ?>
			</div>
			<div class="typing-area">
				<div class="typing-inp-cont">
					<input type="text" id="typedText" name="text">
				</div>
				<div class="send-btn">
					<button type="button" id="sendMyText">SEND</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $clr = '#1F9BD7'; ?>
<style type="text/css">
	body.overfhid {
		overflow: hidden !important;
	}
	.messages-popup.flex {
		display: flex;
	}
	.messages-popup {
		display: none;
		justify-content: center;
		align-items: center;
		background: rgba(0,0,0,.2);
		height: 100%;
		width: 100%;
		position: fixed;
		z-index: 1111;
	}
	.messages-wrapper {
		display: inline-block;
		height: 100vh;
		width: 100%;
		background: #fff;
		position: relative;
		overflow: hidden;
	}
	.messages-popup .cl-window .contacts-list {
		height: calc(100vh - 50px);
		overflow: auto;
		position: relative;
		border-radius: 0;
	}
	.messages-popup .cl-window, .messages-popup .dm-window {
		/*display: none;*/
		position: absolute;
		width: 100%;
		transition: all 0.4s ease;
	}
	.messages-popup .cl-window.show, .messages-popup .dm-window.show {
		/*display: block;*/
	}
	.messages-popup .cl-window {
		left: -100%;
	}
	.messages-popup .dm-window {
		right: -100%;
	}
	.messages-popup .cl-window.show {
		left: 0%;
	}
	.messages-popup .dm-window.show {
		right: 0;
	}
	.messages-popup .chat-header {
		padding: 10px 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 20px;
		background: {{ $clr }};
		/*background: rgb(99, 154, 212);*/
		color: #fff;
		/*border-top-left-radius: 20px;
		border-top-right-radius: 20px;*/
		opacity: 0.5;
		transition: opacity 0.4s ease;
	}
	.messages-popup .dm-window.show .chat-header, .messages-popup .cl-window.show .chat-header {
		opacity: 1;
	}
	.messages-popup .close-chat {
		display: inline-flex;
		justify-content: center;
		align-items: flex-end;
		border-radius: 50%;
		height: 30px;
		width: 30px;
		border: 1px solid #fff;
		font-weight: 800;
		font-size: 25px;
		cursor: pointer;
	}
	.messages-popup .back-to-contact-list i {
		font-size: 26px;
		font-weight: 800;
		padding: 0 10px;
		cursor: pointer;
	}
	.contacts-list .contact .ct-avatar-cont, .messages-list .message .m-avatar-cont {
		width: 20%;
		text-align: center;
	}
	.contacts-list .contact, .messages-list .message {
		display: flex;
		padding: 10px;
		justify-content: center;
		align-items: center;
		cursor: pointer;
	}
	.messages-list .message {
		cursor: default;
		background: #fff;
		margin: 0;
	}
	.messages-list .message.me {
		background: #F4F7FB;
	}
	.contacts-list .contact.notify .ct-avatar::after {
		content: "";
		display: inline-block;
		width: 15px;
		height: 15px;
		border-radius: 50%;
		background: red;
		position: absolute;
		right: 0px;
		bottom: 0;
	}
	.contacts-list .contact:hover {
		background: rgb(99, 154, 212, 0.1);
	}
	.contacts-list .contact + .contact, .messages-list .message + .message {
		border-top: 1px solid #ccc;
	}
	.contacts-list .contact .ct-info, .messages-list .message .m-info {
		width: 60%;
		font-size: 12px;
		padding: 0 10px;
		line-height: 18px;
	}
	.messages-list .message.ongoing {
		opacity: 0.7;
	}
	.messages-list .message .failed {
		font-weight: 800;
		color: red;
	}
	.messages-list .message .m-info {
		width: 80%;
	}
	.contacts-list .contact .ct-info .name, .messages-list .message .m-info .name {
		font-size: 13px;
		font-weight: 800;
	}
	.messages-list .message .m-info .name .timestamp {
		float: right;
		font-weight: normal;
		font-size: 11px;
	}
	.contacts-list .contact .date, .messages-list .message .date {
		width: 20%;
		text-align: center;
		font-weight: 600;
	}
	.contacts-list .contact .ct-avatar-cont .ct-avatar, .messages-list .message .m-avatar-cont .m-avatar {
		height: 55px;
		width: 55px;
		display: inline-block;
		border: 1px solid {{ $clr }};
		border-radius: 50%;
		background-repeat: no-repeat;
		background-position: center center;
		background-size: cover;
		position: relative;
	}
	.dm-window .messages-list {
		height: calc(100vh - 116px);
		overflow: auto;
		position: relative;
	}
	.dm-window .messages-list .error, .dm-window .messages-list .no-messages, .cl-window .contacts-list .no-convs {
		display: none;
		text-align: center;
		padding: 10px;
		color: red;
	}
	.dm-window .typing-area {
		display: flex;
		justify-content: center;
		align-items: center;
		background: rgb(235, 248, 252);
		padding: 15px;
	}
	.dm-window .typing-inp-cont {
		width: calc(100% - 80px);
		padding-right: 20px;
	}
	.dm-window .send-btn {
		width: 80px;
	}
	.dm-window #sendMyText {
		background: {{ $clr }};
		color: #fff;
		padding: 10px 21px;
		border: none;
		border-radius: 20px;
	}
	.dm-window .typing-inp-cont input {
		width: 100%;
		padding: 8px;
		font-size: 12px;
		border: 0.5px solid {{ $clr }};
		border-radius: 4px;
	}
	.dm-window .typing-inp-cont input:focus {
		outline: 1px solid {{ $clr }};
	}
	@media screen and (min-width: 551px){
		.messages-wrapper {
			max-width: 550px;
			max-height: 700px;
			display: inline-block;
			height: 90vh;
			width: 90%;
			border-radius: 20px;
		}
		.messages-popup .cl-window .contacts-list {
			height: calc(90vh - 50px);
			max-height: 640px;
			border-bottom-right-radius: 20px;
			border-bottom-left-radius: 20px;
		}
		.dm-window .messages-list {
			height: calc(90vh - 116px);
			max-height: 580px;
		}
		.dm-window .typing-area {
			border-bottom-left-radius: 20px;
			border-bottom-right-radius: 20px;
		}
	}
	@media screen and (min-width: 768px){
		.messages-wrapper {
			height: 70vh;
			width: 60%;
		}
		.messages-popup .cl-window .contacts-list {
			height: calc(70vh - 50px);
		}
		.dm-window .messages-list {
			height: calc(70vh - 116px);
		}
	}
	@media screen and (min-width: 1025px){
		.messages-wrapper {
			width: 40%;
		}
	}
	/* Loader */
	@keyframes lds-eclipse {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		50% {
			-webkit-transform: rotate(180deg);
			transform: rotate(180deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	@-webkit-keyframes lds-eclipse {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		50% {
			-webkit-transform: rotate(180deg);
			transform: rotate(180deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	.lds-css {
		text-align: center;
		position: absolute;
		height: 100%;
		width: 100%;
		display: none;
		justify-content: center;
		align-items: center;
	}
	.lds-css.vis {
		display: flex;
	}
	.lds-eclipse {
		position: relative;
	}
	.lds-eclipse div {
		position: absolute;
		-webkit-animation: lds-eclipse 1s linear infinite;
		animation: lds-eclipse 1s linear infinite;
		width: 100px;
		height: 100px;
		top: 20px;
		left: 20px;
		border-radius: 50%;
		box-shadow: 0 4px 0 0 #93dbe9;
		-webkit-transform-origin: 50px 50px;
		transform-origin: 50px 50px;
	}
	.lds-eclipse {
		width: 100px !important;
		height: 100px !important;
		-webkit-transform: translate(-50px, -50px) scale(1) translate(50px, 50px);
		transform: translate(-50px, -50px) scale(1) translate(50px, 50px);
	}
</style>
</style>