<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\AirPort;
use App\Models\TaxiAirport;
use App\Models\TaxiCar;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TaxiAirportController extends Controller
{
    public function index()
    {
        $get = TaxiAirport::all();
        return response()->json($get);
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required',
            'airport_id' => 'required',
            'driver_id' =>'required'
        ]);
        return response()->json([
            "message"=>"Add Succesfully",
            TaxiAirport::create($request->all())
        ]);
    }

    public function show($id)
    {
        $get = TaxiAirport::find($id);
        $car = TaxiCar::find($get->car_id);
        $car->color = $this->translator->translate($car->color);
        $airport = AirPort::find($get->airport_id);
        $airport->name = $this->translator->translate($airport->name);
        $airport->city = $this->translator->translate($airport->city);
        $airport->country = $this->translator->translate($airport->country);
        return response()->json([$get,$car,$airport]);
    }

    public function showAdmin($id)
    {
        $get = TaxiAirport::find($id);
        $car = TaxiCar::find($get->car_id);
        $airport = AirPort::find($get->airport_id);
        return response()->json([$get,$car,$airport]);
    }

    public function update(Request $request,$id)
    {
        $taxi = TaxiAirport::find($id);
        $taxi->update(
            $request->validate([
                'car_id' => 'required',
                'airport_id' => 'required',
                'driver_id' =>'required'
            ])    
        );
        
        $message = $this->translator->translate("Update Succesfully");
        return response()->json(["Update Succesfully",$taxi]);
    }

    public function destroy($id)
    {
        $taxi = TaxiAirport::find($id);
        $taxi->delete();
        $message = $this->translator->translate("Delete Succesfully ^^");
        return response()->json("Delete Succesfully ^^", 200);
    }
}
