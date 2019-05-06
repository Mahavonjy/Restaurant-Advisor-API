<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show() {

        if (Auth::user()) {
            $right = Auth::user()->right;

            if ($right != 0) {
                $users = User::all()->where('right', 1);

                return response()->json([
                    $users
                ]);

            } else {
                return response()->json([
                    "unauthorized",
                ]);
            }

        } else {
            return response()->json([
                "auth" => "unauthorized"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        if (Auth::user()) {
            $right = Auth::user()->right;

            if ($right == 2) {

                $user = User::where('id', $id)->first();

                if ($user) {

                    if ($user->right == 1) {

                        $user->right = 0;
                        $user->update();

                        return response()->json([
                            "finish",
                        ]);
                    } elseif ($user->right == 0) {

                        $user->right = 1;
                        $user->update();

                        return response()->json([
                            "finish",
                        ]);
                    } else {
                        return response()->json([
                            "unauthorized change",
                        ]);
                    }
                } else {
                    return response()->json([
                        "User not found",
                    ]);
                }
            } else {
                return response()->json([
                    "unauthorized",
                ]);
            }
        } else {
            return response()->json([
                "auth" => false,
            ]);
        }
    }

}
