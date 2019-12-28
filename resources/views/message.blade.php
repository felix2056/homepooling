@section('pageTitle')
Messages - Pool Your Home
@endsection

@extends('layouts.app')

@section('content')
<main id="content" class="share_room">
	<div class="page-header" id="headerContent">
		<h2>Messages</h2>
	</div>
	<div class="threads_wrapper">
		<div>
			@foreach($threads as $thread)
				<div id="thread_{{$thread->id}}" class="thread_{{$loop->index}} thread @if($thread->hasUnread()) unread @endif">
					<div class="author_photo">
						<div class="profile_image">
							@if(isset($thread->getLastMessage()[0]->user->photo))
								<img src="{{$thread->getLastMessage()[0]->user->photo}}" alt="{{$thread->getLastMessage()[0]->user->name}}">
							@else
								<img src="/storage/img/profile_placeholder.png" alt="{{$thread->getLastMessage()[0]->user->name}}">
							@endif
						</div>
					</div>
					<div class="text_excerpt">
						<div>
							@if(isset($thread->getLastMessage()[0]->user->name))
								<h5>{{$thread->getLastMessage()[0]->user->name}}</h5>
							@endif
							@if(isset($thread->getLastMessage()[0]->text))
								<p>{{ str_limit($thread->getLastMessage()[0]->text,$limit=20,$end='...')}}</p>
							@endif
						</div>
						<div class="thread_timestamp">
							@if(isset($thread->updated_at))
								<p>{{date('j M',($thread->updated_at)->getTimeStamp())}}</p>
							@else
								<p>{{date('j M',($thread->created_at)->getTimeStamp())}}</p>
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>
		<div>
			<div class="messages_wrapper">
			</div>
			<div class="reply_wrapper">
				<form action="/messages/send" method="POST">
					{!! csrf_field() !!}
					<input type="text" class="message_reply" name="message_reply" placeholder="Write a reply, press return to send."/>
				</form>
			</div>
		</div>
	</div>
</main>

@endsection
 
@section('script')
    <script src="{{ asset('js/message.js') }}"></script>
@endsection