<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicProgressController extends Controller
{
    /**
     * Store or update topic progress
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|integer|exists:topics,id',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        try {
            $userId = Auth::id();
            $topicId = $request->topic_id;
            $progress = $request->progress;

            // Find existing progress or create new one
            $topicProgress = TopicProgress::updateOrCreate(
                [
                    'user_id' => $userId,
                    'topic_id' => $topicId,
                ],
                [
                    'progress' => $progress,
                    'last_watched_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Progress saved successfully',
                'data' => $topicProgress
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get topic progress for a specific topic
     */
    public function show($topicId)
    {
        try {
            $userId = Auth::id();
            
            $topicProgress = TopicProgress::where('user_id', $userId)
                ->where('topic_id', $topicId)
                ->first();

            if ($topicProgress) {
                return response()->json([
                    'success' => true,
                    'progress' => $topicProgress->progress,
                    'last_watched_at' => $topicProgress->last_watched_at
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'progress' => 0,
                    'last_watched_at' => null
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving progress: ' . $e->getMessage()
            ], 500);
        }
    }
}
