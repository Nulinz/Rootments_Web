<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;




class location_cnt extends Controller
{
    public function index(Request $req){

        {
            // Get latitude and longitude from the request
            $latitude = $req->input('latitude');
            $longitude = $req->input('longitude');

            // Google API Key
            $googleApiKey = env('GOOGLE_MAPS_API_KEY');

            // Make the request to the Google Geocoding API
            $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
                'latlng' => "{$latitude},{$longitude}",
                'key' => $googleApiKey
            ]);

            // // Decode the response
             $location = $response->json();

            // Check if the response was successful
            // if ($location['status'] === 'OK') {
            //     // Get the first result
            //     $result = $location['results'][0];
            //     $formattedAddress = $result['formatted_address'];

            //     // Return the address and coordinates
            //     return response()->json([
            //         'status'=>'success',
            //         'address' => $formattedAddress,
            //         'latitude' => $latitude,
            //         'longitude' => $longitude
            //     ]);
            // }
            // Check if the response was successful
            if ($location['status'] === 'OK') {
                $district = null;

                $result = $location['results'][0];
                $formattedAddress = $result['formatted_address'];

            foreach ($location['results'][0]['address_components'] as $component) {
                // Look for the district (usually administrative_area_level_2) or locality (city)
                if (in_array('administrative_area_level_2', $component['types'])) {
                    $district = $component['long_name'];  // District
                    break;
                }

                // If no district is found, you can try to use locality as a fallback
                if (in_array('locality', $component['types'])) {
                    $district = $component['long_name'];  // Locality (City or Town)
                    break;
                }
            }

            $user = Auth::user()->id;

            $alreadyLoggedIn = DB::table('attendance')
            ->where('user_id', $user)
            ->whereDate('c_on', now()->format('Y-m-d'))
            ->exists();

            if (!$alreadyLoggedIn) {
                    DB::table('attendance')->insert([
                        'user_id' => $user,
                        'attend_status' => 'Present',
                        'in_location' => $district,
                        'in_add' => $formattedAddress,
                        'in_time' => now()->format('H:i:s'),
                        'c_on' => now()->format('Y-m-d')
                    ]);
                }
            else{

                $str = Auth::user();

                $st_end_time = DB::table('stores')->where('id',$str->store_id)->select('stores.store_end_time')->first();

                // dd($st_end_time,now()->format('H:i:s'));
               // Get the current time and the end time
                    $c_time = now()->format('H:i:s');

                    if ($c_time > $st_end_time->store_end_time) {
                        // Define the two times
                        $time1 = Carbon::createFromFormat('H:i:s', $st_end_time->store_end_time);
                        $time2 = Carbon::createFromFormat('H:i:s', $c_time);

                        // Calculate the difference
                        $diff = $time1->diff($time2);

                        $ot = $diff->format('%H:%I');
                    }


                    DB::table('attendance')
                    ->where('user_id', $user)
                    ->whereDate('c_on', now()->format('Y-m-d'))
                    ->whereNull('out_add')
                    ->update([
                        'out_time' => now()->format('H:i:s'),
                        'out_location' => $district,
                        'out_add' => $formattedAddress,
                        'ot' => $ot ?? 0,
                        'u_by'=>now()->format('Y-m-d')
                    ]);



            }

            // Return the district (or locality) as the response
            return response()->json([
                'attd_status'=>'CheckIn Update Success',
                'district' => $district ?? 'District not found'
            ]);
            }


            // Handle errors (e.g., location not found)
             return response()->json(['attd_status'=>'CheckOut Update Failed','error' => 'Location not found'], 404);
        }
    }
}
