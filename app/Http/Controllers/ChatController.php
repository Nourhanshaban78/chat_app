<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function startConversation(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
        ]);

        $user1_id = Auth::id();
        $user2_id = $validatedData['user_id'];

        $conversation = Conversation::where(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user1_id)->where('user2_id', $user2_id);})->orWhere(function ($query) use ($user1_id, $user2_id) 
            {$query->where('user1_id', $user2_id)->where('user2_id', $user1_id);})->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $user1_id,
                'user2_id' => $user2_id,
            ]);
        }

        return response()->json([
            'conversation' => $conversation,
        ]);
    }

    public function getConversations()
    {
        $user_id = Auth::id();

        $conversations = Conversation::where('user1_id', $user_id)
            ->orWhere('user2_id', $user_id)
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $user_id = Auth::id();

        if ($conversation->user1_id != $user_id && $conversation->user2_id != $user_id) {
            return response()->json([
                'error' => 'You are not authorized to view this conversation.',
            ], 403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user_id = Auth::id();

        if ($conversation->user1_id != $user_id && $conversation->user2_id != $user_id) {
            return response()->json([
                'error' => 'You are not authorized to send messages in this conversation.',
            ], 403);
        }

        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user_id,
            'content' => $validatedData['content'],
        ]);

        return response()->json([
            'message' => $message,
        ]);
    }






}
