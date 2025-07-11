<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class ajaxController extends Controller
{
    public function topic_progress_update(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $topic = Topic::findOrFail($request->topic_id);
        $topic->progress = $request->progress;
        $topic->save();

        return response()->json(['success' => true, 'message' => 'Topic progress updated successfully.']);
    }
}
