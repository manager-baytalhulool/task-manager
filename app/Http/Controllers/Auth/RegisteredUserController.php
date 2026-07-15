<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed'],
            'subdomain' => 'required|string|max:255|alpha_dash|unique:tenants,id',
        ]);

        $subdomain = $request->subdomain;

        // 1. Create Tenant
        $tenant = Tenant::create([
            'id' => $subdomain,
        ]);

        // 2. Create Domain (e.g., tenant-one.localhost)
        $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
        $tenant->domains()->create([
            'domain' => $subdomain . '.' . $centralDomain,
        ]);

        // 3. Create Tenant Admin User inside the tenant database
        $tenant->run(function () use ($request) {
            // Tenant database me Roles seed karein taake Foreign Key constraint fail na ho
            if (\App\Models\Role::count() === 0) {
                $roles = ["super-admin", "admin", "manager", "developer"];
                foreach ($roles as $index => $role) {
                    \App\Models\Role::create([
                        "id" => $index + 1,
                        "name" => $role
                    ]);
                }
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 1, // 1 = super-admin
            ]);
        });

        return response()->json([
            'message' => 'Tenant and User registered successfully',
            'tenant_id' => $tenant->id,
            'domain' => $subdomain . '.' . $centralDomain,
            'status' => 'success'
        ], 201);
    }
}
