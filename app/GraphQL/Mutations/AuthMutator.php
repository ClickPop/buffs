<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthMutator {
    public function login ($root, array $args) {
        $credentials = Arr::only($args, ['email', 'password']);

        if (Auth::once($credentials)) {
            $token = Str::random(60);
            auth()->user()->api_token = hash('sha256', $token);
            auth()->user()->save();
            return $token;
        }
        return null;
    }

    public function removeApiToken ($root, array $args) {
        $id = Arr::only($args, ['id']);
        $user = User::findById($id);

        if ($user) {
            $user->api_token = null;
            $user->save();
        }
    }
}