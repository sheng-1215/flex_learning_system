<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ajaxController extends Controller
{
    public function topic_progress_update(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $topic = Topic::findOrFail($request->topic_id);
        $userId = Auth::id();

        // Find or create user-specific progress
        $topicProgress = TopicProgress::firstOrNew([
            'user_id' => $userId,
            'topic_id' => $request->topic_id,
        ]);

        // Only update if new progress is higher than existing
        if ($request->progress > $topicProgress->progress) {
            $topicProgress->progress = $request->progress;
            $topicProgress->last_watched_at = now();
            $topicProgress->save();
        }

        // If it's a beacon request (FormData), return empty response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Topic progress updated successfully.',
                'progress' => $topicProgress->progress
            ]);
        }
        
        // For beacon requests, return empty response
        return response('');
    }

    public function get_topic_progress(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
        ]);

        $userId = Auth::id();
        $topicProgress = TopicProgress::where('user_id', $userId)
            ->where('topic_id', $request->topic_id)
            ->first();

        return response()->json([
            'success' => true,
            'progress' => $topicProgress ? $topicProgress->progress : 0,
            'last_watched_at' => $topicProgress ? $topicProgress->last_watched_at : null,
        ]);
    }
}
