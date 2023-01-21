<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email||unique:users,email',
            'password' => 'required|string',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        $user = User::create([
            'name' => $request->name,
            'position' => $request->position,
            'city' => $request->city,
            'state' => $request->state,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'country_id' => $request->country_id,
            'company_id' => $request->company_id,
            'phone' => $request->phone,
            'website' => $request->website,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $company = Company::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'supplier' => $request->supplier,
            'buyer' => $request->buyer,
            'business_id' => $request->business_id,
            'year' => $request->year,
            'annual_sale' => $request->annual_sale,
            'certificate' => $request->certificate,
        ]);
        $token = $user->createToken('maApp')->plainTextToken;
        event(new Registered($user));

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
        Mail::send(new TestMail());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json('user not found', 401);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json('password is incorrect', 401);
        }
        $token = $user->createToken('maApp')->plainTextToken;
        event(new Registered($user));
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
