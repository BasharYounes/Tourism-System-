<?php

namespace App\Http\Controllers;

use App\Events\SpecialEventNotification;
use App\Http\Middleware\TranslationMiddleware;
use App\Models\Admin;
use App\Models\AirPort;
use App\Models\Driver;
use App\Models\Purse;
use App\Models\TaxiAirport;
use App\Models\TaxiCar;
use App\Models\TaxiReservation;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;

class TaxiReservationController extends Controller
{
     // protected $language,$translator;

     public function __construct(Request $request)
     {
         //   // يمكنك استخدام معلمات الاستعلام أو الترويسات للحصول على اللغة
         //   $this->language = $request->header('X-Language', 'en');
           
         //   $this->translator = new GoogleTranslate();
   
         //   $this->translator->setTarget($this->language);
         $this->middleware(TranslationMiddleware::class);
     }
    public function index()
    {
       $taxi_reservation = TaxiReservation::all();
       return response()->json($taxi_reservation);
    }

    public function get_user_reservation()
    {
       $taxi_user_reservation = TaxiReservation::where('user_id',auth()->user()->id);
       return response()->json($taxi_user_reservation);
    }

    public function show_reservation_taxi($id)
    {
       $taxi_reservation = TaxiReservation::find($id);
       $driver = Driver::find($taxi_reservation->driver_id);
       $user = User::find($taxi_reservation->user_id);
       return response()->json([$taxi_reservation,$user,$driver]);
    }

    public function store(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $taxi_airport_id = $request->input('taxi_airport_id');
        $cost = rand(0, 25) * rand(0, 10);

        $cash_user = Purse::where('user_id',auth()->user()->id)->first();

        if ($cash_user->cash >= $cost) {
        $admin = Admin::find(1);
        $admin->cash += $cost;
        $admin->save();
        
        $cash_user->cash -= $cost;
        $cash_user->save();
            
        $reservation = new TaxiReservation;
        $reservation->from = $from;
        $reservation->to = $to;
        $reservation->taxi_airport_id = $taxi_airport_id;
        $reservation->user_id = auth()->user()->id;
        $reservation->date = date('Y-m-d H:i:s');
        $reservation->cost = $cost;
        $reservation->save();
        $message = $request->translator->translate("تم الحجز بنجاح ..سيتواصل معك السائق بأقرب وقت ");
        event(new SpecialEventNotification(auth()->user()->id,$message));
        return response()->json([$reservation,]);
        }
        $message = $request->translator->translate("Yoe Have Not Money Enough To Complete this Proccess");
    return response()->json(["message"=>$message]);

    }

    public function show($id,Request $request)
    {
        $get = TaxiReservation::find($id);
        $get->from = $request->translator->translate($get->from);
        $get->from = $request->translator->translate($get->from);

        $taxi_airport= Driver::find($get->taxi_airport_id);

        $car = TaxiCar::find($taxi_airport->car_id);
        $car->type_car = $request->translator->translate($car->type_car);
        $car->color = $request->translator->translate($car->color);

        $airport = AirPort::find($taxi_airport->airport_id);
        $airport->name = $request->translator->translate($airport->name);
        $airport->city = $request->translator->translate($airport->city);
        $airport->country = $request->translator->translate($airport->country);

        $driver = Driver::find($taxi_airport->taxi_airport_id);
        $driver->name = $request->translator->translate($driver->name);
        $driver->nationality = $request->translator->translate($driver->nationality);
        $driver = Driver::find($get->driver_id);
        $driver->name = $request->translator->translate($driver->name);
        $driver->nationality = $request->translator->translate($driver->nationality);

        $user = User::find($get->user_id);
        $user->name = $request->translator->translate($user->name);
        $user->gender = $request->translator->translate($user->gender);

        return response()->json([$get,$user,$driver,$car,$airport]);
    }

    public function update(Request $request,$id)
    {
        $reservation = TaxiReservation::find($id);

        $from = $request->input('from');
        $to = $request->input('to');
        $taxi_airport_id = $request->input('taxi_airport_id');

        $cost = rand(0, 25) * rand(0, 10);

        $cash_user = Purse::where('user_id',auth()->user()->id)->first();

        if ($cash_user->cash >= $cost) {

            $admin = Admin::find(1);
            $admin->cash -= $reservation->cost;
            $admin->save();
            
            $cash_user->cash += $reservation->cost;
            $cash_user->save();

            $admin->cash += $cost;
            $admin->save();
            
            $cash_user->cash -= $cost;
            $cash_user->save();

            // $reservation_update = new TaxiReservation;
            $reservation->from = $from;
            $reservation->to = $to;
            $reservation->taxi_airport_id = $taxi_airport_id;
            $reservation->user_id = auth()->user()->id;
            $reservation->date = date('Y-m-d H:i:s');
            $reservation->cost = $cost;
            $reservation->save();
            return response()->json([$reservation,]);
        }
        $message = $request->translator->translate("Yoe Have Not Money Enough To Complete this Proccess");
    return response()->json(["message"=>$message]);
    }

    public function destroy($id,Request $request)
    {
        $reservation = TaxiReservation::find($id);

        $cash_user = Purse::where('user_id',auth()->user()->id)->first();

        $admin = Admin::find(1);
        $admin->cash -= $reservation->cost;
        $admin->save();
        
        $cash_user->cash += $reservation->cost;
        $cash_user->save();

        $reservation->delete();
        $message = $request->translator->translate("Delete Succesfully ^^");
        return response()->json(["message"=>$message], 200);
    }
    
    public function search_taxi(Request $request)
    {
        $airport = AirPort::where('name','like','%'.$request->name.'%')->first();
        $i = 0;
        $cars = [];
        $people = $request->input('people');
        $taxi_cars = TaxiAirport::where('airport_id',$airport->id)->get();
        foreach ($taxi_cars as $taxi_car) {
            $car = TaxiCar::find($taxi_car->car_id);
            // if($car->capacety >= $people)
            // {
                $cars['car'][$i] = $car;
                $i++;
            // }
        }
        return response()->json($cars);
    }

    public function search_airport(Request $request)
    {
        return AirPort::where('name','like','%'.$request->name.'%')->get();
    }
}
