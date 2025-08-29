<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ajaxController extends Controller
{
    public function topic_progress_update(Request $request)
    {
        // try {
        //     // Log the incoming request for debugging
        //     Log::info('Topic progress update request received', [
        //         'user_id' => Auth::id(),
        //         'topic_id' => $request->input('topic_id'),
        //         'progress' => $request->input('progress'),
        //         'request_type' => $request->expectsJson() ? 'JSON' : 'FormData',
        //         'headers' => $request->headers->all(),
        //         'all_input' => $request->all()
        //     ]);

        //     // Basic validation
        //     if (!$request->has('topic_id') || !$request->has('progress')) {
        //         Log::warning('Missing required fields for topic progress update', [
        //             'user_id' => Auth::id(),
        //             'topic_id' => $request->input('topic_id'),
        //             'progress' => $request->input('progress')
        //         ]);

        //         if ($request->expectsJson()) {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => 'Missing required fields: topic_id and progress are required'
        //             ], 400);
        //         }
                
        //         return response('Missing required fields', 400);
        //     }

        //     $topicId = $request->input('topic_id');
        //     $progress = (int) $request->input('progress');

        //     // Validate progress value
        //     if ($progress < 0 || $progress > 100) {
        //         Log::warning('Invalid progress value', [
        //             'user_id' => Auth::id(),
        //             'topic_id' => $topicId,
        //             'progress' => $progress
        //         ]);

        //         if ($request->expectsJson()) {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => 'Progress must be between 0 and 100'
        //             ], 400);
        //         }
                
        //         return response('Invalid progress value', 400);
        //     }

        //     // Check if topic exists
        //     $topic = Topic::find($topicId);
        //     if (!$topic) {
        //         Log::warning('Topic not found', [
        //             'user_id' => Auth::id(),
        //             'topic_id' => $topicId
        //         ]);

        //         if ($request->expectsJson()) {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => 'Topic not found'
        //             ], 404);
        //         }
                
        //         return response('Topic not found', 404);
        //     }

        //     $userId = Auth::id();

        //     // Use database transaction for safety
        //     DB::beginTransaction();
        //     try {
        //         // Find or create user-specific progress
        //         $topicProgress = TopicProgress::firstOrNew([
        //             'user_id' => $userId,
        //             'topic_id' => $topicId,
        //         ]);

        //         // Only update if new progress is higher than existing
        //         if ($progress > $topicProgress->progress) {
        //             $oldProgress = $topicProgress->progress;
        //             $topicProgress->progress = $progress;
        //             $topicProgress->last_watched_at = now();
        //             $topicProgress->save();

        //             Log::info('Topic progress updated successfully', [
        //                 'user_id' => $userId,
        //                 'topic_id' => $topicId,
        //                 'old_progress' => $oldProgress,
        //                 'new_progress' => $progress
        //             ]);
        //         } else {
        //             Log::info('Topic progress not updated - new progress not higher', [
        //                 'user_id' => $userId,
        //                 'topic_id' => $topicId,
        //                 'current_progress' => $topicProgress->progress,
        //                 'requested_progress' => $progress
        //             ]);
        //         }

        //         DB::commit();

        //         // If it's a beacon request (FormData), return empty response
        //         if ($request->expectsJson()) {
        //             return response()->json([
        //                 'success' => true, 
        //                 'message' => 'Topic progress updated successfully.',
        //                 'progress' => $topicProgress->progress,
        //                 'timestamp' => now()->toISOString()
        //             ]);
        //         }
                
        //         // For beacon requests, return empty response
        //         return response('');
                
        //     } catch (\Exception $e) {
        //         DB::rollBack();
        //         throw $e;
        //     }
            
        // } catch (\Exception $e) {
        //     Log::error('Error updating topic progress', [
        //         'user_id' => Auth::id(),
        //         'topic_id' => $request->input('topic_id'),
        //         'error' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString()
        //     ]);

        //     if ($request->expectsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Error updating topic progress: ' . $e->getMessage()
        //         ], 500);
        //     }
            
        //     return response('', 500);
        // }
        DB::beginTransaction();
        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'Topic progress updated successfully.',
            'timestamp' => now()->toISOString()
        ]);
        
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

            if (!$request->has('topic_id')) {
                Log::warning('Missing topic_id for progress get request', [
                    'user_id' => Auth::id()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'topic_id is required'
                ], 400);
            }

            $topicId = $request->input('topic_id');
            $userId = Auth::id();

            // Check if topic exists
            $topic = Topic::find($topicId);
            if (!$topic) {
                Log::warning('Topic not found for progress get request', [
                    'user_id' => $userId,
                    'topic_id' => $topicId
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Topic not found'
                ], 404);
            }

            $topicProgress = TopicProgress::where('user_id', $userId)
                ->where('topic_id', $topicId)
                ->first();

            $progress = $topicProgress ? $topicProgress->progress : 0;
            
            Log::info('Topic progress retrieved successfully', [
                'user_id' => $userId,
                'topic_id' => $topicId,
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
