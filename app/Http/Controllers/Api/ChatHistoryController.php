<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ChatsTitle;

class ChatHistoryController extends Controller
{
    public function storeChats(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|integer',
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'title' => $validated['title'],
            'user_id' => $validated['user_id']
        ]);

      
        $message = $validated['message'];
        ChatsTitle::dispatch($chat->id, $message);

        return response()->json($chat, 201);
    }

    public function storeMessage(Request $request, $chatId)
    {
        $validated = $request->validate([
            'sender_type' => 'required|string|max:50',
            'content'     => 'required|string',
        ]);

        $validated['chat_id'] = $chatId;
        $message = Message::create($validated);

        return response()->json($message, 201);
    }

    public function getMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages, 200);
    }

    public function setFeedback(Request $request, $messageId)
    {
        $validated = $request->validate([
            'feedback' => 'required|in:like,dislike',
        ]);

        $message = Message::findOrFail($messageId);

        $message->feedback = $validated['feedback'];
        $message->save();

        return response()->json(['success' => true, 'feedback' => $message->feedback]);
    }
}
