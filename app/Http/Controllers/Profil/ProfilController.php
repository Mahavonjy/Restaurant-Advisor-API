<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use App\Model\Profil;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{

     /**
     * Display the specified resource.
     *
     * @param  \App\Model\Profil $profil
     * @return \Illuminate\Http\Response
     */
    public function showid($name) {
        $auth = Auth::user();

        if ($auth) {

            $user_profil = Profil::where("name", $name)->first();

            if ($user_profil == null) {
                return response()->json([
                    "profil" => false,
                ]);
            } else {
                return response()->json([
                    $user_profil,
                ]);
            }
        } else {
            return response()->json([
                "auth" => false,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Profil $profil
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $auth = Auth::user();

        if ($auth) {

            $user_profil = Profil::where("email", $request->user()->email)->first();

            if ($user_profil == null) {
                return response()->json([
                    "profil" => false,
                ]);
            } else {
                return response()->json([
                    $user_profil,
                ]);
            }
        } else {
            return response()->json([
                "auth" => false,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Model\Profil $profil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profil $profils) {

        $auth = Auth::user();

        if ($auth) {

            $Verification = Validator::make($request->all(), [

                'email' => 'required|string|email',
                'telephone' => 'nullable|integer',
                'lastname' => 'nullable|string',
                'firstname' => 'required|string',
                'address' => 'nullable|string',
                'birthdate' => 'nullable|date',
                'pays' => 'nullable|string',
                'langues' => 'nullable|string',
                'description' => 'nullable|string',
                'avatar' => 'nullable|string'
            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "verification" => false,
                ]);

            } else {
                $profil = $profils->where("email", $request->user()->email)->first();

                if ($profil) {
                    if ($profil->email != $request->email) {
                        $profil->email = $request->email;
                        $user = User::where('id', $request->user()->id)->first();
                        $user->email = $request->email;
                        $user->update();
                    }
                    $profil->telephone = $request->telephone;
                    if ($profil->name != $request->name) {
                        $profil->name = $request->name;
                        $user = User::where('id', $request->user()->id)->first();
                        $user->name = $request->name;
                        $user->update();
                    }
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
                    $profil->update();

                    return response()->json(
                        true
                    );
                } else {
                    return response()->json([
                        "profil" => false,
                    ]);
                }
            }
        } else {
            return response()->json([
                "auth" => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Profil $profils
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Profil $profils) {

        $auth = Auth::user();

        if ($auth) {

            $profil = $profils->where("email", $request->user()->email)->first();

            if ($profil) {

                $profil->delete();

                return response()->json([
                    "destroy" => true,
                ]);

            } else {

                return response()->json([
                    "profil" => false,
                ]);
            }

        } else {
            return response()->json([
                "auth" => false,
            ]);
        }
    }

}
