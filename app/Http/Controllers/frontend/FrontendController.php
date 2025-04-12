<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Profession;
use App\Models\RequestedService;
use App\Models\User;
use App\Models\UserTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_options = Profession::where('status', 1)->get();

        $professions = Profession::where('status', 1)->paginate(6);
        $bestProviders = RequestedService::with(["provider.profession"])->get()
            ->groupBy('provider_id')
            ->map(function ($row) {
                $rating = $row->sum('rating') / $row->count();
                $row->first()->rating = $rating;
                return $row->first();
            })->sortByDesc('rating')->take(6);

        return view('frontend.index', compact('professions', 'search_options', 'bestProviders'));
    }
    public function homeSearch(Request $request)
    {
        $search_options = Profession::where('status', 1)->get();

        if(isset(Session::get('session_user')->id)){
            $current_user_id = Session::get('session_user')->id;
            $current_user_location = UserTracker::where('user_id', $current_user_id)->first();
            $current_user_lat = $current_user_location->current_latitude??0;
            $current_user_lng = $current_user_location->current_longitude??0;
            $professions = User::join('professions', 'users.profession_id', '=', 'professions.id')
            ->join('provider_trackers', 'users.id', '=', 'provider_trackers.provider_id')
            ->where('users.status', 1)
            ->where('professions.status', 1)
            ->orWhere('professions.name', 'like', '%' . $request->search . '%')
            ->orderBy('provider_trackers.is_active', 'desc')
            ->paginate(10,[
                'professions.name as profession_name',
                'professions.avatar as profession_avatar',
                'users.name as user_name',
                'users.avatar as user_avatar',
                'users.id as this_provider_id',
                'professions.*',
                'users.*',
                'provider_trackers.*'
            ]);
            foreach ($professions as $key => $value) {
                $current_provider_rating_sum = RequestedService::where('provider_id', $value->this_provider_id)->sum('rating');
                $divideBy = RequestedService::where('provider_id', $value->this_provider_id)->count();
                if($divideBy == 0){
                    $divideBy = 1;
                }
                $current_provider_rating_count = round($current_provider_rating_sum / $divideBy);
                $professions[$key]->current_provider_rating = $current_provider_rating_count;
            }

        for($i = 0; $i < count($professions); $i++) {
            $professions[$i]['current_user_lattitude'] = $current_user_lat;
            $professions[$i]['current_user_longitude'] = $current_user_lng; 
            if($current_user_lat != null && $current_user_lng != null && $professions[$i]->current_latitude != null && $professions[$i]->current_longitude != null){
                    $distance = $this->calc_distance_in_mile($current_user_lat, $current_user_lng, $professions[$i]->current_latitude, $professions[$i]->current_longitude);

                                $professions[$i]['distance'] = $distance+$i;
                            }
               
         }
        //  dd($professions);
        return view('frontend.pages.homeSearch', compact('professions', 'search_options'));
        }else{
            $professions = User::join('professions', 'users.profession_id', '=', 'professions.id')
            ->join('provider_trackers', 'users.id', '=', 'provider_trackers.provider_id')
            ->where('users.status', 1)
            ->where('professions.status', 1)
            ->orWhere('professions.name', 'like', '%' . $request->search . '%')
            ->orderBy('provider_trackers.is_active', 'desc')
            ->paginate(10,[
                'professions.name as profession_name',
                'professions.avatar as profession_avatar',
                'users.name as user_name',
                'users.avatar as user_avatar',
                'users.id as this_provider_id',
                'professions.*',
                'users.*',
                'provider_trackers.*'
            ]);

        //    dd($professions);
            return view('frontend.pages.homeSearch', compact('professions', 'search_options'));


        }
       
        
    }
    function calc_distance_in_mile($latitudeFrom, $longitudeFrom,$latitudeTo,  $longitudeTo)
        {
            $long1 = deg2rad($longitudeFrom);
            $long2 = deg2rad($longitudeTo);
            $lat1 = deg2rad($latitudeFrom);
            $lat2 = deg2rad($latitudeTo);

            //Haversine Formula
            $dlong = $long2 - $long1;
            $dlati = $lat2 - $lat1;

            $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2);

            $res = 2 * asin(sqrt($val));

            $radius = 3958.756; //earth radius in miles
            $distance_in_mile = $res*$radius;
            $distance_in_km = round($distance_in_mile * 1.609344,2);


            return ($distance_in_km); //to convert into KM mul by 1.609344.
        }

        public function requestService(Request $request)
        {
            RequestedService::create($request->all());
            return redirect('user-panel/request-history/'.$request->user_id)->with('success', 'Requested Successfully');
        }
  

}
