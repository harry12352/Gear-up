<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function storeComment(Request $request, Product $product)
    {
        if ($product['status'] === 'drafted') {
            return response()->json(['error' => true, 'message' => 'Comment creation failed',], 403);
        }
        $request->validate([
                'content' => 'required',
            ]
        );
        $comment = Comment::create(
            [
                'user_id' => Auth::id(),
                'product_id' => $product['id'],
                'content' => $request['content'],
            ]
        );
        $productOwner = $product->user;
        if ($productOwner && Auth::id() != $productOwner['id']) {
            NotificationController::newCommentNotification($productOwner, $comment);
        }
        return response()->json(['error' => false, 'message' => 'Comment creation successfull'], 200);
    }

    public function delete(Comment $comment)
    {
        if (Auth::id() == $comment['user_id']) {
            $comment->delete();
            return response()->json(['error' => false, 'message' => 'Comment deletion successfull'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You can not perform this action'], 403);
    }
}
