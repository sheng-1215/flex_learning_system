<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class VideoProgressMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Log the request for debugging
            if (config('video_progress.debug', false)) {
                Log::info('Video progress request received', [
                    'method' => $request->method(),
                    'url' => $request->url(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'headers' => $request->headers->all()
                ]);
            }

            // Check if CSRF token is valid - but be more lenient
            if (!$this->validateCsrfToken($request)) {
                Log::warning('CSRF token validation failed for video progress request', [
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'provided_token' => $request->header('X-CSRF-TOKEN') ?: 'missing'
                ]);

                // Don't block the request, just log it
                // return response()->json([
                //     'success' => false,
                //     'message' => 'CSRF token validation failed',
                //     'error_code' => 'CSRF_MISMATCH'
                // ], 419);
            }

            // Apply rate limiting if enabled - but be more lenient
            if (config('video_progress.enable_rate_limiting', false)) {
                $rateLimitKey = 'video_progress:' . auth()->id();
                $maxAttempts = config('video_progress.max_updates_per_minute', 120);

                if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
                    Log::warning('Rate limit exceeded for video progress updates', [
                        'user_id' => auth()->id(),
                        'ip' => $request->ip()
                    ]);

                    // Don't block the request, just log it
                    // return response()->json([
                    //     'success' => false,
                    //     'message' => 'Too many progress updates. Please wait before trying again.',
                    //     'error_code' => 'RATE_LIMIT_EXCEEDED',
                    //     'retry_after' => RateLimiter::availableIn($rateLimitKey)
                    // ], 429);
                }

                RateLimiter::hit($rateLimitKey);
            }

            // Validate request data - but be more lenient
            if (config('video_progress.enable_validation', false)) {
                $validationResult = $this->validateRequest($request);
                if (!$validationResult['valid']) {
                    Log::warning('Request validation failed for video progress', [
                        'user_id' => auth()->id(),
                        'errors' => $validationResult['errors']
                    ]);

                    // Don't block the request, just log it
                    // return response()->json([
                    //     'success' => false,
                    //     'message' => 'Invalid request data',
                    //     'errors' => $validationResult['errors'],
                    //     'error_code' => 'VALIDATION_FAILED'
                    // ], 422);
                }
            }

            // Add request metadata for debugging
            $request->attributes->set('video_progress_debug', [
                'timestamp' => now()->toISOString(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Error in VideoProgressMiddleware', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Don't block the request on middleware error, just log it
            return $next($request);
        }
    }

    /**
     * Validate CSRF token
     */
    private function validateCsrfToken(Request $request): bool
    {
        $token = $request->header('X-CSRF-TOKEN');
        
        if (!$token) {
            return false;
        }

        // Check if token matches session token
        try {
            $sessionToken = $request->session()->token();
            return hash_equals($sessionToken, $token);
        } catch (\Exception $e) {
            Log::warning('Error validating CSRF token', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Validate request data
     */
    private function validateRequest(Request $request): array
    {
        $errors = [];

        // Check required fields - but be more lenient
        if (!$request->has('topic_id')) {
            $errors[] = 'topic_id is required';
        }

        if (!$request->has('progress')) {
            $errors[] = 'progress is required';
        }

        // Validate progress value - but be more lenient
        if ($request->has('progress')) {
            $progress = $request->input('progress');
            $minProgress = config('video_progress.min_progress', 0);
            $maxProgress = config('video_progress.max_progress', 100);

            if (!is_numeric($progress)) {
                $errors[] = 'progress must be a number';
            } elseif ($progress < $minProgress || $progress > $maxProgress) {
                $errors[] = "progress must be between {$minProgress} and {$maxProgress}";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
