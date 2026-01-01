<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->for == "select") {
            $users = User::select("id", "name")->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'users' => $users
                ]
            ]);
        }

        $users = User::select("id", "name", "email")->paginate();
        return response()->json([
            'success' => true,
            'data' => ['users' => $users]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = $request->user();
        if ($authUser->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized. Only Admin can create new users.'], Response::HTTP_FORBIDDEN);
        }

        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $createdUser = User::create([
            'name' => $validatedUser['name'],
            'email' => $validatedUser['email'],
            'password' => Hash::make($validatedUser['password']),
            'role_id' => 2
        ]);

        return response()->json([
            'message'   => 'User created successfully.',
            'data'      => [
                'user' => $createdUser
            ],
            'success'    => true
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int|string $id, Request $request)
    {
        // User $user
        if ($id == "me") {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    "user" => $user
                ]
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $authUser = $request->user();
        // Yahan logged in user check hoga, matlab ya toh admin hai ya jo update kar raha hai woh end user hai aur apne hi account ki detail ko update kar raha hai.
        if ($authUser->role_id == 1 || $authUser->id == $user->id) {
            $validatedUser = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'required|email',
                'old_password' => 'nullable|string',
                'new_password' => 'nullable|string|min:8|confirmed',
            ]);

            // Name update karein agar request mein maujood ho
            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            // Password update logic
            if ($request->filled('new_password')) {
                // Check karein ke purana password sahi hai ya nahi
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'message' => 'Old password is wrong.'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                $user->password = Hash::make($request->new_password);
            }

            $user->update($validatedUser);
        } else {
            return response()->json([
                'message' => 'Unauthorized. Either you are not admin or you are trying to update another user\'s profile'
            ]);
        }

        return response()->json([
            'message'   => 'User updated successfully.',
            'data'      => [
                'user' => $user,
            ],
            'success'    => true
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message'   => 'User deleted successfully.',
            'data'      => [
                'user' => $user,
            ],
            'success'    => true
        ], Response::HTTP_OK);
    }
}
