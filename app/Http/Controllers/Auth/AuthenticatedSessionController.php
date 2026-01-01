<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (!Auth::attempt($data)) {
            return response()->json([
                "message" => "Credentials not match"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $request->user()->createToken('API Token')->plainTextToken;

        // $user = auth("api")->user();
        // $user->load(['role'=>function($q){
        //     $q->select('id','name')->with(['permissions'=>function($q){
        //         $q->select('description');
        //     }]);
        // }]);
        // $user->permissions = $user->role->permissions->pluck('description');

        return response()->json([
            'data' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->tokens()->delete();

        return response()->json([
            "message" => "Logged out successfully"
        ], Response::HTTP_OK);
    }
}
