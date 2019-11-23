<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Notifications\MessageNotification;
use Carbon\Carbon;
use App\Http\Requests\MessageSRequest;
use App\Http\Requests\SanitizeRequest;

class MessageController extends Controller
{
	private function buildMessage($message){
		$response='<div class="message';
		if(\Auth::check() && \Auth::user()->id==$message->user_id)  $response.=' in';
		$response.='">';
		$response.='<div class="author_photo"><div class="profile_image">';
		if($message->user->photo){
			$response.='<img src="'.$message->user->photo.'" alt="'.$message->user->name.'">';
		}else{
			$response.='<img src="/storage/img/profile_placeholder.png" alt="'.$message->user->name.'">';
		}
		$response.='</div></div><div class="message_inner"><div class="message_details"><div class="author">';
		if($message->user->name){
			$response.='<h5>'.$message->user->name.'</h5>';
		}
		$response.='</div><div class="timestamp">';
		if($message->created_at){
		
			$response.='<p>'.(\Carbon\Carbon::parse($message->created_at)->format('j M Y H:i:s')).'</p>';
		}
		$response.='</div></div><div class="text">';
		if($message->text){
			$response.='<p>'.$message->text.'</p>';
		}
		$response.='</div></div></div>';
		if(\Auth::check() && \Auth::user()->id!=$message->user_id && $message->seen==0){ 
			$message->seen=1; 
			$message->save();
		}
		return $response;
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index()
	{
		$user=\Auth::user();
		$threads=\App\Thread::where('user_id_1',$user->id)->orWhere('user_id_2',$user->id)->orderBy('created_at','DESC')->orderBy('updated_at','DESC')->get();
		return view('message',compact('threads','user'));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function fetch(Request $request){
		if($request->has('thread_id')){
			$id=$request->thread_id;
			$thread=\App\Thread::where('id',$id)->with('messages')->first();
			$messages=$thread->messages;
			$response='';
			foreach($messages as $message){
				$response.=$this->buildMessage($message);
			}
			return $response;
		}
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create(\App\User $user){
		return view('create_message',compact('user'));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reply(SanitizeRequest $request)
    {
        if($request->has('thread_id')){
			$user=\Auth::user();
			$message=new \App\Message;
			$id=$request->thread_id;
			$thread=\App\Thread::where('id',$id)->first();
			
			if($thread->user_id_2==$user->id){
				$to=\App\User::find($thread->user_id_1);
			}else{
				$to=\App\User::find($thread->user_id_2);
			}
			
			$message->user_id=$user->id;
			$message->thread_id=$request->thread_id;
			$message->text=$request->text;
			$message->seen=0;
			$thread->messages()->save($message);
			
			$to->notify(new MessageNotification($to,$message));
			
			$messages=$thread->messages;
			$response='';
			foreach($messages as $mess){
				$response.=$this->buildMessage($mess);
			}
			return $response;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
	public function store(MessageSRequest $request){
		$user_id_1=\Auth::user()->id;
		$user_id_2=$request->user_id;
		$receiver=\App\User::where('id',$user_id_2)->first();
		if($receiver->msg_in_remain>0){
			$thread=new \App\Thread;
			$thread->user_id_1=$user_id_1;
			$thread->user_id_2=$user_id_2;
			$thread->save();

			$message=new \App\Message;
			$message->thread_id=$thread->id;
			$message->user_id=\Auth::user()->id;
			$message->text=$request->text;
			$message->seen=0;
			$thread->messages()->save($message);
			if($receiver->msg_in_remain < 9000) $receiver->msg_in_remain=$receiver->msg_in_remain-1;
			$receiver->save();
			
			$receiver->notify(new MessageNotification($receiver,$message));
		}
		
		
		return redirect('/messages')->with('message','Message sent!');
	}
}
