<?php

namespace App\Http\Controllers\Restaurants;

use App\Http\Controllers\Controller;
use App\Model\Avis;
use App\Model\Menu;
use App\Model\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AvisController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMenu($id ,Request $request) {
        $auth = Auth::user();

        if ($auth) {
            $menu = Menu::where('id', $id)->first();
            $Verification = Validator::make($request->all(), [
                'Avis' => 'required|string'
            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "Validator" => false,
                ]);
            }

            $avis = new Avis();
            $avis->Avis = $request->Avis;
            $avis->Menu_Id = $menu->id;
            $avis->User_id = $request->user()->id;
            $avis->save();

            $menu->Avis_Number = $menu->Avis_Number+1;
            $menu->update();

            return response()->json([
                "Avis" => True,
            ]);
        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRestau($id ,Request $request) {
        $auth = Auth::user();

        if ($auth) {
            $rest = Restaurants::where('id', $id)->first();
            $Verification = Validator::make($request->all(), [
                'Avis' => 'required|string'
            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "Validator" => false,
                ]);
            }

            $avis = new Avis();
            $avis->Avis = $request->Avis;
            $avis->Menu_Id = 0;
            $avis->User_id = $request->user()->id;
            $avis->Restau_id = $rest->id;
            $avis->save();

            $rest->Avis = $rest->Avis+1;
            $rest->update();

            return response()->json([
                "Avis" => True,
            ]);
        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function showMenu($id, Avis $avis) {
        $menu = Menu::where('id', $id)->first();

        if ($menu) {
            $Avis = $avis->all()->where('Menu_Id', $id);

            if ($Avis) {
                return response()->json(
                    $Avis
                );

            } else {
                return response()->json([
                    "Avis" => False,
                ]);
            }
        } else {
            return response()->json([
                "Menu" => False,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function showRestau($id, Avis $avis) {
        $rest = Restaurants::where('id', $id)->first();

        if ($rest) {
            $Avis = $avis->all()->where('Restau_Id', $id);

            if ($Avis) {
                return response()->json(
                    $Avis
                );

            } else {
                return response()->json([
                    "Avis" => False,
                ]);
            }
        } else {
            return response()->json([
                "Menu" => False,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Avis $avis) {
        $auth = Auth::user();

        if ($auth) {
            $Avis = $avis->where('id', $id)->first();
            $right = $request->user()->right;
            $Verification = Validator::make($request->all(), [
                'Avis' => 'required|string'
            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "Validator" => false,
                ]);
            }

            if ($Avis->User_id == $request->user()->id) {
                $Avis->Avis = $request->Avis;
                $Avis->update();
                return response()->json([
                    "Update" => true,
                ]);
            } else {
                if ($right != 0) {
                    $Avis->Avis = $request->Avis;
                    $Avis->update();

                    return response()->json([
                        "Update" => true,
                    ]);
                } else {
                    return response()->json([
                        "Access" => False,
                    ]);
                }
            }
        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request, Avis $avis) {
        $auth = Auth::user();

        if ($auth) {
            $Avis = $avis->where('id', $id)->first();
            $right = $request->user()->right;

            if ($Avis->User_id == $request->user()->id) {
                $Avis->delete();
                return response()->json([
                    "destroy" => true,
                ]);
            } else {
                if ($right != 0) {
                    $Avis->delete();
                    return response()->json([
                        "destroy" => true,
                    ]);
                } else {
                    return response()->json([
                        "Access" => False,
                    ]);
                }
            }
        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }
}
