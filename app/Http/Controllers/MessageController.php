<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(User $user)
    {
        $messages = $user->messages;
        if ($messages->isNotEmpty()) {
            return response()->json($messages, 200);
        }
        return response()->json(['error' => true, 'message' => 'No messages found'], 404);
    }

    public function detachMessage(User $user, Message $message)
    {
        if (Auth::id() == $user['id']) {
            if (DB::table('message_user')->where('user_id', $user['id'])->where('message_id', $message['id'])->exists()) {
                $user->messages()->find($message)->pivot->delete();
                return response()->json(['error' => false, 'message' => 'Message detached successfully'], 200);
            }
            return response()->json(['error' => true, 'message' => 'You are not authorized to perform this action'], 403);
        }
        return response()->json(['error' => true, 'message' => 'You are not authorized to perform this action'], 403);
    }
}
