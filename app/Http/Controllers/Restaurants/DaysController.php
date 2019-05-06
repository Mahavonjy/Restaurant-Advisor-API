<?php

namespace App\Http\Controllers\Restaurants;

use App\Http\Controllers\Controller;
use App\Model\Days;
use App\Model\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DaysController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Days  $days
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Days $days) {
        $auth = Auth::user();

        if ($auth) {
            $rest = Restaurants::where([['id', $id],['User_id', Auth::user()->id]])->first();

            if ($rest) {

                $day = $days->where('Restau_Id', $id)->first();

                $Verification = Validator::make($request->all(), [
                    'Monday' => 'boolean',
                    'Tuesday' => 'boolean',
                    'Wednesday' => 'boolean',
                    'Thursday' => 'boolean',
                    'Friday' => 'boolean',
                    'Saturday' => 'boolean',
                    'Sunday' => 'boolean'
                ]);

                if ($Verification->fails()) {
                    return response()->json([
                        "verification" => false,
                    ]);
                } else {

                    $day->Monday = $request->Monday;
                    $day->Tuesday = $request->Tuesday;
                    $day->Wednesday = $request->Wednesday;
                    $day->Thursday = $request->Thursday;
                    $day->Friday = $request->Friday;
                    $day->Saturday = $request->Saturday;
                    $day->Sunday = $request->Sunday;

                    $day->update();

                    return response()->json([
                        "Update" => True,
                    ]);
                }
            } else {
                return response()->json([
                    "NotFound" => True,
                ]);
            }

        } else {
            return response()->json([
                "Auth" => false,
            ]);
        }
    }

}
