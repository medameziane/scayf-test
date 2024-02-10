<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatComponent extends Component
{
    public $chat_user_active = "", $user_to, $conversation, $message_user, $search_user_list, $chat_user;

    public function get_conversation($user_to)
    {
        $this->chat_user = User::where("id", $user_to)->select("id", "name", "is_online")->first();
        $this->chat_user_active = $this->chat_user->name;
        $this->conversation = Conversation::whereIn("user1_id", [$user_to, Auth::user()->id])->whereIn("user2_id", [$user_to, Auth::user()->id])->first();

        !$this->conversation ? Conversation::create([
            "user1_id" => $user_to,
            "user2_id" => Auth::user()->id,
        ]) : "";
    }

    public function filter_data()
    {
        $searchUsers = User::whereNot('id', Auth::user()->id);

        $searchUsers->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search_user_list . '%');
        });

        return $searchUsers->get();
    }

    public function removeChat()
    {
        $this->message_user = "";
        $this->conversation = null;
    }

    public function send_message()
    {
        $this->validate(["message_user" => "required"]);

        $this->conversation->messages()->create([
            "content" => $this->message_user,
            "user_id" => Auth::user()->id,
        ]);

        $this->dispatch("scrolling_chat");
        $this->message_user = "";
    }

    public function loadMessages()
    {
        $messages = $this->conversation ? $this->conversation->messages : [];
        return $messages;
    }


    public function render()
    {
        $messages = $this->loadMessages();
        $users = $this->filter_data();
        $conversations = Conversation::all();
        return view('livewire.chat.chat-component', compact("users", "conversations", "messages"));
    }
}
