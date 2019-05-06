<?php

namespace App\Http\Controllers\Restaurants;

use App\Http\Controllers\Controller;
use App\Model\Menu;
use App\Model\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request, Restaurants $restaurants, Menu $menus) {
        $auth = Auth::user();

        if ($auth) {
            $resto =  $restaurants->where([["User_id", $request->user()->id], ["id", $id]])->first();
            $right = $request->user()->right;
            $Verification = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'string',
                'price' => 'required'

            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "Validator" => false,
                ]);
            }

            $name = $menus->where("name", $request->name)->first();

            if (!$name) {
                if ($resto) {
                    $menu = new Menu();
                    $menu->name = $request->name;
                    $menu->description = $request->description;
                    $menu->price = $request->price;
                    $menu->Restau_Id = $resto->id;
                    $menu->save();

                    $resto->Menu_Number = $resto->Menu_Number+1;
                    $resto->update();

                    return response()->json([
                        "Add menus" => true,
                    ]);

                } else {
                    if ($right != 0) {
                        $resto = $restaurants->where("id", $id)->first();
                        $menu = new Menu();
                        $menu->name = $request->name;
                        $menu->description = $request->description;
                        $menu->price = $request->price;
                        $menu->Restau_Id = $resto->id;
                        $menu->save();

                        $resto->Menu_Number = $resto->Menu_Number+1;
                        $resto->update();

                        return response()->json([
                            "Add menus" => true,
                        ]);

                    } else {
                        return response()->json([
                            "Access" => False,
                        ]);
                    }
                }
            } else {
                return response()->json([
                    "Menu Name" => "Exist",
                ]);
            }
        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($id ,Menu $menu) {
        $menus = $menu->all()->where("Restau_Id", $id);
        return response()->json(
            $menus
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Menu $menus) {
        $auth = Auth::user();

        if ($auth) {
            $menu = $menus->where("id", $id)->first();
            $restau_ID = $menu->Restau_Id;
            $restaurant = Restaurants::where("id", $restau_ID)->first();
            $User_id = $restaurant->User_id ;
            $right = $request->user()->right;
            $Verification = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'string',
                'price' => 'required'

            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "Validator" => false,
                ]);
            }

            if ($User_id == $request->user()->id) {
                $menu->name = $request->name;
                $menu->description = $request->description;
                $menu->price = $request->price;
                $menu->update();

                return response()->json([
                    "Update" => true,
                ]);

            } else {
                if ($right != 0) {
                    $menu->name = $request->name;
                    $menu->description = $request->description;
                    $menu->price = $request->price;
                    $menu->update();

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
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Menu $menus, Request $request) {
        $auth = Auth::user();

        if ($auth) {
            $menu = $menus->where("id", $id)->first();
            $restau_ID = $menu->Restau_Id;
            $restaurant = Restaurants::where("id", $restau_ID)->first();
            $User_id = $restaurant->User_id ;
            $right = $request->user()->right;

            if ($User_id == $request->user()->id) {
                $menu->delete();

                return response()->json([
                    "destroy" => true,
                ]);
            } else {
                if ($right != 0) {
                    $menu->delete();

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
