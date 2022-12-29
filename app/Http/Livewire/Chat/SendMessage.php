<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class SendMessage extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $body;
    public $listeners = [
        'updateSendMessage',
        'refresh' => '$refresh',
    ];

    public function sendMessage(){
        if($this->body == null){
            return null;
        }

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $this->receiverInstance->id,
            'body' => $this->body,
        ]);

        $this->selectedConversation->last_time_message = $createdMessage->created_at;
        $this->selectedConversation->save();


        $this->emitTo('chat.chat-box','pushMessage', $createdMessage->id);
        $this->emitTo('chat.chat-list','updateChatLists');

        $this->reset('body');
    }

    public function updateSendMessage(Conversation $conversation, User $receiver){
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}