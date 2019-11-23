<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use Carbon\Carbon;
use App\Notifications\MessageNotification;

class ConversationController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $r)
    {
        if($r->to && $r->text){
            $user       = auth()->user();
            $receiver   = User::where('id', $r->to)->first();
            if($receiver){
                $msg = $user->msgs_all()->create([
                    'user_id'       => $user->id,
                    'receiver_id'   => $receiver->id,
                    'text'          => $r->text,
                    'seen'          => 0,
                    'deleted'       => 0
                ]);
                $msg->load([
                    'user' => function($q){
                        $q->select('id', 'name', 'family_name', 'photo');
                    },
                    'receiver' => function($q){
                        $q->select('id', 'name', 'family_name', 'photo');
                    }
                ]);
                $receiver->notify(new MessageNotification($receiver, $msg));
                return response()->json(['success' => true, 'msg' => $msg, 'rId' => $r->rId], 200);
            }
        }
    }

    public function show($id)
    {
        $user           = auth()->user();
        $conversation   = $user->msgs_all()->with([
            'user' => function($q){
                $q->select('id', 'name', 'family_name', 'photo');
            },
            'receiver' => function($q){
                $q->select('id', 'name', 'family_name', 'photo');
            }
        ])->where('receiver_id', $id)->orWhere('user_id', $id)->orderBy('created_at')->get();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $conversation->map(function($i, $key) use ($user, $months){
            $dt         = Carbon::parse($i['created_at']);
            $i['time']  = $dt->day.'/'.$dt->month.'/'.$dt->year.' '.date("g:ia", strtotime($i['created_at']));
            $i['me']    = $i->user_id == $user->id;
            return $i;
        });
        // Mark messages from this user as seen
        Message::where('user_id', $id)->where('receiver_id', $user->id)->update(['seen' => 1]);
    
        return response()->json(['success' => true, 'conversation' => $conversation, 'convWith' => $id], 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $r, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}