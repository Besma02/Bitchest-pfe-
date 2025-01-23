<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\RegistrationRequestService;
use Illuminate\Http\Request;

class RegistrationRequestController extends Controller
{
    protected $service;

    public function __construct(RegistrationRequestService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
        ]);

        $result = $this->service->checkExistingRequests($request->email);

        if ($result['status'] === 'new_request') {
            $this->service->createRequest($request->email);
        }

        return response()->json($result, 200);
    }
}
