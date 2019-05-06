<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Controllers\Profil\ProfilController;
use App\Model\Profil;
use App\Notifications\RegisterSuccess;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Network.
     *
     * @return Response
     */
    public function handleProviderCallback($provider) {
        $user = Socialite::driver($provider)->stateless()->user();

        if ($provider == "google") {
            $auth = User::where('email', $user->email)->first();
            if ($auth) {
                return response()->json([
                    $user
                ], 201);
            }
        }

        $authUser = $this->findOrCreateUser($user, $provider);

        Auth::login($authUser, true);

        return response()->json([
            $authUser,
            $user
        ], 201);
    }

    /**
     * Obtain the user information from Network.
     *
     * @return Response
     */
    public function findOrCreateUser($user, $provider) {

        $authUser = User::where('provider_id', $user->id)->first();

        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => strtoupper($provider),
            'provider_id' => $user->id
        ]);
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $Verification = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'telephone' => 'nullable|integer',
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'pays' => 'nullable|string',
            'langues' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        if ($Verification->fails()) {
            return response()->json([
                "Validator " => false,
            ]);

        } else {

            $profil = new Profil();
            $profil->email = $request->email;
            $profil->telephone = $request->telephone;
            $profil->name = $request->name;
            $profil->firstname = $request->firstname;
            $profil->address = $request->address;

            if ($request->sexe == null) {
                $profil->sexe = 0;
            } else {
                $profil->sexe = $request->sexe;
            }
            $profil->birthdate = $request->birthdate;
            $profil->pays = $request->pays;
            $profil->langues = $request->langues;
            $profil->description = $request->description;
            $profil->avatar = $request->avatar;
            $profil->save();

            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $user->save();


            $user->notify(new RegisterSuccess());

            return response()->json([
                "User" => True
            ], 201);
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request) {
        if (Auth::user()) {
            $request->user()->token()->revoke();

            return response()->json([
                "Deconnected"
            ],200);
        } else {
            return response()->json([
                "unauthorized"
            ],401);
        }
    }
}
