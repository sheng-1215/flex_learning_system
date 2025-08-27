<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ajaxController extends Controller
{
    public function topic_progress_update(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Topic progress update request received', [
                'user_id' => Auth::id(),
                'topic_id' => $request->input('topic_id'),
                'progress' => $request->input('progress'),
                'request_type' => $request->expectsJson() ? 'JSON' : 'FormData',
                'headers' => $request->headers->all()
            ]);

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

                Log::info('Topic progress updated successfully', [
                    'user_id' => $userId,
                    'topic_id' => $request->topic_id,
                    'old_progress' => $topicProgress->getOriginal('progress'),
                    'new_progress' => $request->progress
                ]);
            } else {
                Log::info('Topic progress not updated - new progress not higher', [
                    'user_id' => $userId,
                    'topic_id' => $request->topic_id,
                    'current_progress' => $topicProgress->progress,
                    'requested_progress' => $request->progress
                ]);
            }

            // If it's a beacon request (FormData), return empty response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Topic progress updated successfully.',
                    'progress' => $topicProgress->progress,
                    'timestamp' => now()->toISOString()
                ]);
            }
            
            // For beacon requests, return empty response
            return response('');
            
        } catch (\Exception $e) {
            Log::error('Error updating topic progress', [
                'user_id' => Auth::id(),
                'topic_id' => $request->input('topic_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating topic progress: ' . $e->getMessage()
                ], 500);
            }
            
            return response('', 500);
        }
    }

    public function get_topic_progress(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Topic progress get request received', [
                'user_id' => Auth::id(),
                'topic_id' => $request->input('topic_id'),
                'headers' => $request->headers->all()
            ]);

            $request->validate([
                'topic_id' => 'required|exists:topics,id',
            ]);

            $userId = Auth::id();
            $topicProgress = TopicProgress::where('user_id', $userId)
                ->where('topic_id', $request->topic_id)
                ->first();

            $progress = $topicProgress ? $topicProgress->progress : 0;
            
            Log::info('Topic progress retrieved successfully', [
                'user_id' => $userId,
                'topic_id' => $request->topic_id,
                'progress' => $progress
            ]);

            return response()->json([
                'success' => true,
                'progress' => $progress,
                'last_watched_at' => $topicProgress ? $topicProgress->last_watched_at : null,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting topic progress', [
                'user_id' => Auth::id(),
                'topic_id' => $request->input('topic_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting topic progress: ' . $e->getMessage()
            ], 500);
        }
    }
}
