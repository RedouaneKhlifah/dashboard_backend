<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;

class AuthService
{
    /**
     * Attempt to authenticate the user using JWT.
     *
     * @param array $credentials
     * @return bool
     */
    public function attemptAuthentication(array $credentials): bool
    {
        try {
            // Attempt to authenticate the user with the given credentials
            return Auth::attempt($credentials);
        } catch (JWTException $e) {
            // Handle JWT exceptions
            return false;
        }
    }

    /**
     * Check if the request is rate-limited.
     *
     * @param string $throttleKey
     * @return bool
     */
    public function isRateLimited(string $throttleKey): bool
    {
        return RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts());
    }

    /**
     * Handle rate-limited requests.
     *
     * @param string $throttleKey
     * @return JsonResponse
     */
    public function handleRateLimit(string $throttleKey): JsonResponse
    {
        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($throttleKey);

        return response()->json([
            'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
        ], 429);
    }

    /**
     * Clear rate limiting for the request.
     *
     * @param string $throttleKey
     */
    public function clearRateLimiting(string $throttleKey): void
    {
        RateLimiter::clear($throttleKey);
    }

    /**
     * Generate a JWT token for the authenticated user.
     *
     * @return JsonResponse
     */
    public function generateAuthenticationResponse(): JsonResponse
    {
        try {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user); // Create the JWT token

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token, // Include JWT token in the response
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not create token',
            ], 500);
        }
    }

    /**
     * Increment rate limiting for the request.
     *
     * @param string $throttleKey
     */
    public function incrementRateLimiting(string $throttleKey): void
    {
        RateLimiter::hit($throttleKey);
    }

    /**
     * Handle failed authentication attempts.
     *
     * @return JsonResponse
     */
    public function handleFailedAuthentication(): JsonResponse
    {
        return response()->json([
            'message' => trans('messages.invalid_credentials'),
        ], 401);
    }

    /**
     * Get the throttle key for the request.
     *
     * @param string $email
     * @param string $ip
     * @return string
     */
    public function throttleKey(string $email, string $ip): string
    {
        return mb_strtolower($email) . '|' . $ip;
    }

    /**
     * Get the maximum number of login attempts allowed.
     *
     * @return int
     */
    protected function maxAttempts(): int
    {
        return 10; // Adjust as needed
    }
}
