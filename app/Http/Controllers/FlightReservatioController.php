<?php

namespace App\Http\Controllers;

use App\Models\Complete_Flight;
use App\Models\Complete_Flight_Reservation;
use App\Models\FlightReservatio;
use App\Models\Flight;
use App\Models\Purse;
use App\Models\AirLineCash;
use App\Models\TourismCountry;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlightReservatioController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $flights = FlightReservatio::where('user_id',$user_id)->get();
        return response()->json($flights, 200);
    }

    public function get_all_flights()
    {
        $flights = Flight::all();
        return response()->json($flights, 200);
    }

    public function add_flight(Request $request)
    {
        $request->validate([
            'airline' => 'required',
            'website' => 'required',
            'departure_airport' => 'required',
            'departure_time' => 'required' ,
            'departure_date' => 'required',
            'arrival_airport' => 'required' ,
            'arrival_time' => 'required' ,
            'duration' => 'required' ,
            'price' => 'required' ,
            'reservation_type' => 'required',
            'available_place' => 'required',
            'transport_id' => 'required'
        ]);
        $data = Flight::create($request->all());
        return response()->json(["Add Succesfully",$data], 200);
    }

    public function update(Request $request,$id)
    {
        $flight = Flight::find($id);

        $flight->airline = $request->input('airline');
        $flight->website = $request->input('website');
        $flight->departure_airport = $request->input('departure_airport');
        $flight->departure_time = $request->input('departure_time');
        $flight->departure_date = $request->input('departure_date');
        $flight->arrival_airport = $request->input('arrival_airport');
        $flight->arrival_time = $request->input('arrival_time');
        $flight->duration = $request->input('duration');
        $flight->price = $request->input('price');
        $flight->reservation_type = $request->input('reservation_type');
        $flight->available_place = $request->input('available_place');
        $flight->transport_id = $request->input('transport_id');
        $flight->save();

        return response()->json(["Updated Succesfully",$flight], 200);
    }

    public function search_flight(Request $request){
        return response()->json(
        Flight::where('transport_id', 'like', '%' .  $request->transport_id . '%')
        ->where('departure_airport', 'like', '%' . $request->departure_airport . '%')
        ->where('arrival_airport', 'like', '%' . $request->arrival_airport . '%')
        ->where('departure_date', 'like', '%' . $request->departure_date . '%')
        // ->where('arrival_time', 'like', '%' . $request->return_date . '%')
        ->where('reservation_type', 'like', '%' . $request->reservation_type . '%')
        ->where('available_place','>=',$request->people)
        ->get()
        );
    }

    public function destroy($id)
    {
        $flight = Flight::find($id);
        $flight->delete();
        return response()->json("Delete Succesfully ^^");

    }

    public function select_place(Request $request)
    {
        $active = $request->input('active');
        $places = Complete_Flight::where('famous',$active)->get();
        return response()->json($places, 200);
    }

    public function show($id)
    {
        $flight = Flight::find($id);
        $Flight_['airline'] = $flight->airline;
        $Flight_['website'] = $flight->website;
        $Flight_['departure_airport'] = $flight->departure_airport;
        $Flight_['departure_time'] = $flight->departure_time;
        $Flight_['departure_date'] = $flight->departure_date;
        $Flight_['arrival_airport'] = $flight->arrival_airport;
        $Flight_['arrival_time'] = $flight->arrival_time;
        $Flight_['duration'] = $flight->duration;
        $Flight_['price'] = $flight->price."$";
        $Flight_['reservation_type'] = $flight->reservation_type;
        $Flight_['available_place'] = $flight->available_place;
        $Flight_['transport_id'] = $flight->transport_id;
        return response()->json($Flight_, 200);
    }




    public function post()
    {
        $trips = Complete_Flight_Reservation ::where('user_id',auth()->user()->id)->get();
        $futures = [];
        $i = 0;
        foreach ($trips as $trip)
        {
            $flight = Complete_Flight::find($trip->complete_flight_id);
            $date = Carbon::parse($flight->travel_dates_departure) ;
            $day = $date->subDays(3);

            if ($day < Carbon::today()) {
               $futures['flight_reservation'][$i] = $trip;
               $i++;
            }
        }
        return response()->json($futures, 200);
    }

    public function current()
    {
        $trips = Complete_Flight_Reservation::where('user_id',auth()->user()->id)->get();
        $futures = [];
        $i = 0;
        foreach ($trips as $trip) {
            $flight = Complete_Flight::find($trip->complete_flight_id);
            $date = Carbon::parse($flight->travel_dates_departure) ;
            $previousday = $date->subDays(3);
            if ($flight->travel_dates_departure >= Carbon::today() && $previousday <= Carbon::today()) {
               $futures['flight_reservation'][$i] = $trip;
               $i++;
            }
        }
        return response()->json($futures, 200);
    }

    public function future()
    {
        $trips = Complete_Flight_Reservation::where('user_id',auth()->user()->id)->get();
        $futures = [];
        $i = 0;
        foreach ($trips as $trip) {
            $flight = Complete_Flight::find($trip->complete_flight_id);
            if ($flight->travel_dates_departure > Carbon::today()) {
               $futures['flight_reservation'][$i] = $trip;
               $i++;
            }
        }
        return response()->json($futures, 200);
    }
}