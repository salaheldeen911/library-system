<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\UserResource;
use App\Models\Book;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthorController extends Controller
{
    public function store(StoreAuthorRequest $request)
    {
        try {
            $validated = $request->validated();

            $author = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ])->assignRole('Author');

            $author->getRoleNames()->toArray();

            return $this->success('Author created successfully', new UserResource($author), 201);
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to create author');
            return $this->failed('Failed to create author');
        }
    }
}
