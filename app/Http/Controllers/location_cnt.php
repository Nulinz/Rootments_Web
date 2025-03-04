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

            if(!is_null(Auth::user()->store_id)){

            $st_time = DB::table('stores')->where('id',Auth::user()->store_id)->select('stores.store_start_time','stores.store_end_time')->first();

            // dd($st_time);

            }

            // dd($st_end_time,now()->format('H:i:s'));
           // Get the current time and the end time


            $alreadyLoggedIn = DB::table('attendance')
            ->where('user_id', $user)
            ->whereDate('c_on', now()->format('Y-m-d'))
            ->exists();

            if (!$alreadyLoggedIn) {
                   $last_id  = DB::table('attendance')->insertGetId([
                        'user_id' => $user,
                        'attend_status' => 'Present',
                        'in_location' => $district,
                        'in_add' => $formattedAddress,
                        'in_time' => now()->format('H:i:s'),
                        'c_on' => now()->format('Y-m-d'),
                        'status' => 'Active'
                    ]);

                    $c_time = Carbon::now(); // Get the current time using Carbon

                    if(!is_null(Auth::user()->store_id)){
                    // Assuming store_start_time is stored in $st_time->store_start_time as a Carbon instance
                    $start_time = Carbon::parse($st_time->store_start_time); // Convert store start time to a Carbon instance

                    }
                    else{
                        $start_time = Carbon::parse('10:00:00'); // Co
                    }

                    // Calculate the 5-minute range (+5 and -5 minutes)
                    $start_time_plus_5 = $start_time->copy()->addMinutes(5);
                    $start_time_minus_5 = $start_time->copy()->subMinutes(5);




                    if (!($c_time >= $start_time_minus_5 && $c_time <= $start_time_plus_5)) {



                       // Calculate the difference in hours, minutes, and seconds
                        $diff = $start_time->diff($c_time);

                        // Format the difference in hours, minutes, and seconds
                        $late = $diff->format('%H:%I');  // Example output: 02:30:00

                        // Output the result
                        // dd($formattedDiff);


                        DB::table('attd_ot')->insert([
                            'attd_id' => $last_id,
                            'cat' => 'late',
                            'time' => $late,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                }
            else{

                $get_last = DB::table('attendance')
                ->where('user_id', $user)
                ->whereDate('c_on', now()->format('Y-m-d'))
                ->orderBy('id', 'desc')->first();  // Get the first record

                $c_time = now()->format('H:i:s');

                if(!is_null(Auth::user()->store_id)){

                    $end_time = $st_time->store_end_time;

                }else{
                    $end_time = '17:00:00';
                }

                    if ($c_time > $end_time) {
                        // Define the two times
                        $time1 = Carbon::createFromFormat('H:i:s', $end_time);
                        $time2 = Carbon::createFromFormat('H:i:s', $c_time);

                        // Calculate the difference
                        $diff = $time1->diff($time2);

                        $ot = $diff->format('%H:%I');

                        DB::table('attd_ot')->insert([
                            'attd_id' => $get_last->id,
                            'cat' => 'ot',
                            'time' => $ot,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                    DB::table('attendance')
                    ->where('user_id', $user)
                    ->whereDate('c_on', now()->format('Y-m-d'))
                    ->whereNull('out_add')
                    ->update([
                        'out_time' => now()->format('H:i:s'),
                        'out_location' => $district,
                        'out_add' => $formattedAddress,
                        'u_by'=>now()->format('Y-m-d')
                    ]);





            }

            // Return the district (or locality) as the response
            return response()->json([
                'status'=>'Success',
                'attd_status'=>'CheckIn Update Success',
                'district' => $district ?? 'District not found'
            ]);
            }


            // Handle errors (e.g., location not found)
             return response()->json(['status'=>'Failed','attd_status'=>'CheckOut Update Failed','error' => 'Location not found'], 404);
        }
    }
}
