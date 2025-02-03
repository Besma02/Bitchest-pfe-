<?php

namespace App\Http\Services;

use App\Models\RegistrationRequest;
use App\Models\User;

class RegistrationRequestService
{

    public function checkExistingRequests(string $email)
    {
        if (RegistrationRequest::where('email', $email)->exists()) {
            return [
                'message' => 'You have already submitted a registration request. Please wait for approval.',
                'status' => 'existing_request'
            ];
        }

        if (User::where('email', $email)->exists()) {
            return [
                'message' => 'Email already registered. Redirecting to Sign In.',
                'redirect' => '/login',
                'status' => 'user_exists'
            ];
        }

        return [
            'message' => 'Your request has been received. You will be notified soon.',
            'status' => 'new_request'
        ];
    }


    public function createRequest(string $email)
    {
        RegistrationRequest::create(['email' => $email]);
    }
}
