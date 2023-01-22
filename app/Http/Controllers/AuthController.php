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
            'country_id' => $request->country_id,
            'cellphone' => $request->cellphone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $company = Company::create([
            'name' => $request->companyName,
            'user_id' => $user->id,
            'country_id' => $request->country_id,
            'supplier' => $request->supplierOf,
            'buyer' => $request->buyerOf,
            'services' => $request->services,
            'business_id' => $request->businessId,
            'year' => $request->yearEstablished,
            'annual_sale' => $request->annualSaleId,
            'certificate' => $request->certifications,
            'website' => $request->website,
            'fax' => $request->fax,
            'landline' => $request->landline,
            'designation' => $request->designation,
            'contact_person' => $request->contactPerson,
            'city' => $request->city,
            'state' => $request->state,
            'address' => $request->postalAddress,
            'postal_code' => $request->postalCode,
            'landline' => $request->landline,
        ]);
        $user->company_id = $company->id;
        $user->save();
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
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->messages(), 422);
        // }

        // $user = User::where('email', $request->email)->first();
        // if (!$user) {
        //     return response()->json('user not found', 401);
        // }
        // if (!Hash::check($request->password, $user->password)) {
        //     return response()->json('password is incorrect', 401);
        // }
        // $token = $user->createToken('maApp')->plainTextToken;
        // event(new Registered($user));
        // return response()->json([
        //     'user' => $user,
        //     'token' => $token
        // ], 201);
    }
}
