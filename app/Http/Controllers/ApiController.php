<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function get_users(){
        $users = User::all();
        return response()->json($users);
    }
    public function filter_messages(Request $request){
        $user_to = $request->input('user_to');
        $user_from = $request->input('user_from');

        $conversation = Conversation::whereIn("user1_id", [$user_to, $user_from])->whereIn("user2_id", [$user_to, $user_from])->first();
        $messages = Message::where("conversation_id", $conversation->id)->get();
        return response()->json($messages);
    }
    public function send_message(Request $request){
        $message = new Message();
        $message->content = $request->input('message_user');
        $message->conversation_id = $request->input('conversation_id');
        $message->user_id = $request->input('user_id');
        $message->save();
        $messages = Message::where("conversation_id", 1)->get();
        return response()->json($messages);
    }
}
