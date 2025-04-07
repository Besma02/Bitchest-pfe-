<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendPasswordMail;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserService
{


    public function createUser(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'role' => 'required|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $password = Str::random(10);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($password),
                'photo' => $request->file('photo')?->store('photos', 'public')
            ]);

            // Create wallet with initial balance
            $wallet = Wallet::create([
                'idUser' => $user->id,
                'balance' => 500.00,
                'publicAdress' => '0x' . Str::random(64),
                'privateAdress' => '0x' . hash('sha256', Str::random(64)),
            ]);

            // Send email
            Mail::to($user->email)->send(new SendPasswordMail($user->name, $password));

            DB::commit();

            return response()->json([
                'user' => $user,
                'wallet' => $wallet,
                'password' => $password // Only for debug, remove in production
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'User creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser($request, $id)
    {
        // Validate data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,client',
            'photo' => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp|max:2048',
        ]);

        // Find user
        $user = User::findOrFail($id);

        // Prepare data for updating
        $userData = $request->only(['name', 'email', 'role']);

        // Handle photo upload if present
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = $photo->storeAs('photos', 'photo_' . time() . '.' . $photo->getClientOriginalExtension(), 'public');
            $userData['photo'] = $path;
        }

        // Update user
        $user->update($userData);

        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Delete associated wallets if needed
        $user->wallets()->delete();

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
