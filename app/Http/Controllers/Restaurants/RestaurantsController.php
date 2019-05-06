<?php

namespace App\Http\Controllers\Restaurants;

use App\Http\Controllers\Controller;
use App\Model\Days;
use App\Model\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestaurantsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function IdShow($id, Restaurants $restaurants) {

        $restaurant = $restaurants::all()->where("id", $id);
        $restaurants->Visited = $restaurants->Visited+1;
        $restaurants->update();

        if ($restaurant) {
            return response()->json(
                $restaurant
            );
        } else {
            return response()->json([
                "Not found" => True,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function NameShow($name, Restaurants $restaurants) {

        $restaurant = $restaurants::all()->where("name", $name)->first();
        $restaurants->Visited = $restaurants->Visited+1;
        $restaurants->update();

        if ($restaurant) {
            return response()->json(
                $restaurant
            );
        } else {
            return response()->json([
                "Not found" => True,
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurants $restaurants) {
        $restaurant = $restaurants::all();
        $Rest = [];
        $count = 0;
        foreach ($restaurant as $r) {
            $Rest[$count] = [
                'Restaurant' => $r,
                'Days' => Days::where('Restau_Id', $r->id)->first()
            ];
            $count++;
        }

        return response()->json(
                 $Rest
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Restaurants $restaurants) {
        $auth = Auth::user();

        if ($auth) {
            $name = $restaurants->where("name", $request->name)->first();
            $localisation = $restaurants->where("localisation", $request->localisation)->first();

            if (!$name and $localisation or $name and !$localisation or !$name and !$localisation) {

                $Verification = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'note' => 'integer|min:0|max:5',
                    'web_site' => 'string',
                    'telephone' => 'string',
                    'description' => 'string',
                    'localisation' => 'required|string',
                    'Semaine_start_time' => 'date_format:"H:i"',
                    'Semaine_end_time' => 'date_format:"H:i"',
                    'Week_start_time' => 'date_format:"H:i"',
                    'Week_end_time' => 'date_format:"H:i"'
                ]);

                if ($Verification->fails()) {

                    return response()->json([
                        "Validator" => false,
                    ]);

                } else {

                    $rest = new Restaurants();
                    $rest->name = $request->name;
                    $rest->note = $request->note;
                    $rest->téléphone = $request->telephone;
                    $rest->description = $request->description;
                    $rest->localisation = $request->localisation;
                    $rest->web_site = $request->web_site;
                    $rest->User_id = $request->user()->id;
                    $rest->Semaine_start_time = $request->Semaine_start_time;
                    $rest->Semaine_end_time = $request->Semaine_end_time;
                    $rest->Week_start_time = $request->Week_start_time;
                    $rest->Week_end_time = $request->Week_end_time;
                    $rest->save();

                    $rest = $restaurants->where([["name", $request->name], ["localisation", $request->localisation]])->first();

                    $days = new Days();
                    $days->Restau_Id = $rest->id;
                    $days->save();

                    return response()->json([
                        "restaurant is add"
                    ]);
                }
            } else {
                return response()->json([
                    "restaurant existing"
                ]);
            }
        } else {
            return response()->json([
                "Unauthorized"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function update($id ,Request $request, Restaurants $restaurants) {
        $auth = Auth::user();

        if ($auth) {

            $Verification = Validator::make($request->all(), [
                'name' => 'required|string',
                'note' => 'integer|min:0|max:5',
                'telephone' => 'string',
                'description' => 'string',
                'localisation' => 'required|string',
                'web_site' => 'string',
                'Semaine_start_time' => 'date_format:"H:i"',
                'Semaine_end_time' => 'date_format:"H:i"',
                'Week_start_time' => 'date_format:"H:i"',
                'Week_end_time' => 'date_format:"H:i"'

            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "verification" => false,
                ]);
            } else {
                $rest = $restaurants->where([["User_id", $request->user()->id], ["id", $id]])->first();

                $right = $request->user()->right;

                if ($rest) {

                    $rest->name = $request->name;
                    $rest->téléphone = $request->telephone;
                    $rest->description = $request->description;
                    $rest->localisation = $request->localisation;
                    $rest->web_site = $request->web_site;
                    $rest->User_id = $request->user()->id;
                    $rest->Semaine_start_time = $request->Semaine_start_time;
                    $rest->Semaine_end_time = $request->Semaine_end_time;
                    $rest->Week_start_time = $request->Week_start_time;
                    $restaurants->Visited = $restaurants->Visited+1;
                    $rest->Week_end_time = $request->Week_end_time;
                    $rest->update();

                    return response()->json([
                        "Update" => true,
                    ]);

                } else {
                    if ($right != 0) {

                        $rest = $restaurants->where("id", $id)->first();
                        $rest->name = $request->name;
                        $rest->note = $request->note;
                        $rest->téléphone = $request->telephone;
                        $rest->description = $request->description;
                        $rest->localisation = $request->localisation;
                        $rest->web_site = $request->web_site;
                        $rest->User_id = $request->user()->id;
                        $rest->Semaine_start_time = $request->Semaine_start_time;
                        $rest->Semaine_end_time = $request->Semaine_end_time;
                        $rest->Week_start_time = $request->Week_start_time;
                        $restaurants->Visited = $restaurants->Visited+1;
                        $rest->Week_end_time = $request->Week_end_time;
                        $rest->update();

                        return response()->json([
                            "Update" => true,
                        ]);

                    } else {
                        return response()->json([
                            "Unauthorized"
                        ]);
                    }
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
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request,Restaurants $restaurants) {
        $auth = Auth::user();

        if ($auth) {
            $resto =  $restaurants->where([["User_id", $request->user()->id], ["id", $id]])->first();
            $right = $request->user()->right;

            if ($resto) {

                $resto->delete();

                return response()->json([
                    "destroy" => true,
                ]);
            } else {
                $resto =  $restaurants->where("id", $id)->first();
                if ($right != 0) {

                    $resto->delete();

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
                "auth" => false,
            ]);
        }
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function getmostvisited(Restaurants $restaurants) {

        $rest = $restaurants->orderBy('Visited', 'DESC')->get();

        return response()->json([
            $rest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function getbestnote(Restaurants $restaurants) {

        $rest = $restaurants->all()->whereBetween('note', [4, 5]);

        return response()->json([
            $rest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function getnote(Restaurants $restaurants) {

        $rest = $restaurants->orderBy('note', 'DESC')->get();

        return response()->json([
            $rest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function getprice(Restaurants $restaurants) {

        $rest = $restaurants->orderBy('price', 'DESC')->get();

        return response()->json([
            $rest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Restaurants  $restaurants
     * @return \Illuminate\Http\Response
     */
    public function getrecentadd(Restaurants $restaurants) {

        $rest = $restaurants->orderBy('id', 'DESC')->get();

        return response()->json([
            $rest
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UpdateNote($id, Request $request, Restaurants $restaurants) {
        $auth = Auth::user();

        if ($auth) {

            $Verification = Validator::make($request->all(), [
                'note' => 'required|integer|min:0|max:5'
            ]);

            if ($Verification->fails()) {
                return response()->json([
                    "Note" => "Empty",
                    "Number" => "between 0 and 5"
                ]);
            }

            $rest = $restaurants->where("id", $id)->first();

            if ($rest) {

                $rest->note = ROUND(($rest->note+$request->note)/2, 0);
                $rest->Visited = $rest->Visited+1;
                $rest->update();

                return response()->json([
                    "UpdateNote" => true,
                ]);

            } else {
                return response()->json([
                    "Restaurant" => false,
                ]);
            }

        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }
}
