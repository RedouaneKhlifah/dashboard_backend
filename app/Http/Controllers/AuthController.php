<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests\SignInRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * The authentication service.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle user sign-in.
     *
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        // Generate the throttle key
        $throttleKey = $this->authService->throttleKey($request->email, $request->ip());
    
        // Ensure the request is not rate-limited
        if ($this->authService->isRateLimited($throttleKey)) {
            return $this->authService->handleRateLimit($throttleKey);
        }
    
        // Attempt to authenticate the user
        if ($this->authService->attemptAuthentication($request->only('email', 'password'))) {
            // Clear rate limiting and generate a token
            $this->authService->clearRateLimiting($throttleKey);
            return $this->authService->generateAuthenticationResponse();
        }
    
        // Increment rate limiting and return error response
        $this->authService->incrementRateLimiting($throttleKey);
        return $this->authService->handleFailedAuthentication();
    }
    
}