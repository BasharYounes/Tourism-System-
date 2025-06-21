<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Car;
use App\Models\CarRental;
use App\Models\Purse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CarRentalController extends Controller
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
        return response()->json(CarRental::all());
    }
    public function get_user_reservation()
    {
        return response()->json(CarRental::where('user_id',auth()->user()->id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $reservation_date = Carbon::parse($request->reservation_date);
        $receirved_date = Carbon::parse($request->receirved_date);
        // الحصول على الفرق بالأيام
        $daysDifference = $receirved_date->diffInDays($reservation_date);
        // إيجاد باقي القسمة على 7
        $x = ($daysDifference % 7) + 1;
        
        $car = Car::find($request->car_id);
        // $data = CarRental::create($request->all());
        $data = new CarRental();
        $data->car_id = $request->car_id;
        $data->from = $request->from;
        $data->to = $request->to;
        $data->user_id = auth()->user()->id;
        $data->reservation_date = date('Y-m-d H:i:s');
        $data->cost = $car->monthly_rent * $x;
        $cash_user = Purse::find(auth()->user()->id);
        $admin = Admin::find(1);
        $cash_user->cash -= $car->monthly_rent * $x;
        $admin->cash += $car->monthly_rent * $x;
        $admin->save();
        $cash_user->save();
        $data->save();


        $message = $request->translator->translate("Added Successfully");
        return response()->json([
            "message"=>$message,
            "data"=>$data
        ]);
    }

    public function show($id,Request $request)
    {
        $get = carRental::find($id);
        $car = Car::find($get->car_id);
        $user = User::find($get->user_id);
        $car->color = $request->translator->translate($car->color);
        $user->name = $request->translator->translate($user->name);
        $user->gender = $request->translator->translate($user->gender);
        return response()->json([$get,$car,$user]);
    }

    public function showAdmin($id)
    {
        $get = carRental::find($id);
        return response()->json($get);
    }

    public function update(Request $request,$id)
    {
        $reservation = CarRental::find($id);

        $from = $reservation->from;
        $to = $reservation->to;

        $date_from = Carbon::parse($from);
        $date_to = Carbon::parse($to);

        $reservation->from = $request->input('from');
        $reservation->to = $request->input('to');

        $reservation_from = Carbon::parse($reservation->from);
        $reservation_to = Carbon::parse($reservation->to);

        if($date_from > $reservation_from)
        {
            $gets = CarRental::all();
            $daysDifference = $date_from->diffInDays($reservation_from);
            for ($i=0; $i < $daysDifference; $i++) { 
                 $date_from->subDays(1);
                foreach ($gets as $get) {
                    if ($date_from->eq(Carbon::parse($get->to))) {
                        return response()->json("The date " . $date_from->toDateString() . " Had Reservation By Another User");
                    }
                }
            }
        }

        if($date_to < $reservation_to)
        {
            $gets = CarRental::all();
            $daysDifference = $reservation_to->diffInDays($date_to);
            for ($i=0; $i < $daysDifference; $i++) { 
                $date_to->addDays(1);
                foreach ($gets as $get) {
                    if ($date_to->eq(Carbon::parse($get->from))) {
                        return response()->json("The date " . $date_from->toDateString() . " Had Reservation By Another User");
                    }
                }
            }
        }



        $cash_user = Purse::find(auth()->user()->id);
        $admin = Admin::find(1);
        $cash_user->cash += $reservation->cost;
        $admin->cash -= $reservation->cost;
        $admin->save();
        $cash_user->save();

        $reservation->car_id = $request->input('car_id');


        $reservation_date = Carbon::parse($request->from);
        $receirved_date = Carbon::parse($request->to);
        // الحصول على الفرق بالأيام
        $daysDifference = $receirved_date->diffInDays($reservation_date);
        // إيجاد باقي القسمة على 7
        $x = ($daysDifference % 7) + 1;

        $car = Car::find($request->car_id);
        $reservation->cost = $car->monthly_rent * $x;
        $reservation->reservation_date = date('Y-m-d H:i:s');
        $cash_user->cash -= $car->monthly_rent * $x;
        $admin->cash += $car->monthly_rent * $x;
        $admin->save();
        $cash_user->save();
        $reservation->save();

        $message = $request->translator->translate("Updated Successfully");
        return response()->json(["mesaage"=>$message,$reservation]);
    }

    public function destroy($id, Request $request)
    {
        $reservation = CarRental::find($id);
        $cash_user = Purse::find(auth()->user()->id);
        $admin = Admin::find(1);
        $cash_user->cash += $reservation->cost;
        $admin->cash -= $reservation->cost;
        $admin->save();
        $cash_user->save();
        $reservation->delete();
        $message = $request->translator->translate("Delete Successfully");
        return response()->json(["message"=>$message]);
    }

    public function search_car(Request $request)
    {
        $i = 0;
        $Cars = [];
        $people = $request->input('people');
        $cars = Car::where('people_number',$people)->get();
        // foreach ($cars as $car) {
        //     $car_ = Car::find($car->car_id);
        //     // if($car->capacety >= $people)
        //     // {
        //         $Cars['car'][$i] = $car_;
        //         $i++;
        //     // }
        // }
        return response()->json(["cars"=>$cars]);
    }
}