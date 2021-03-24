<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'email' => 'email|required'
            ]
        );
        if (!NewsLetter::where('email', $request['email'])->exists()) {
            NewsLetter::create([
                    'email' => $request['email']
                ]
            );
            return response()->json(['error' => false, 'message' => 'You have been subscribed to our NewsLetter Successfully, Thanks']);
        }
        return response()->json(['error' => true, 'message' => 'Sorry!! Kindly enter other email address']);
    }
}
