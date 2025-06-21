<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Admin;
use App\Models\EventFlight;
use App\Models\EventFlightReservation;
use App\Models\Purse;
use App\Models\Room_Hotel_complete_flight;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class EventFlightReservationController extends Controller
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
        return response()->json(EventFlightReservation::where('user_id',auth()->user()->id));
    }

    public function store(Request $request, string $id)
    {
        $reservation = new EventFlightReservation();
        $people_number = $request->input('people');
        // $reservation->room_id = $request->input('room_id');
        $reserv_room = Room_Hotel_complete_flight::where('capacety',$people_number)->where('active',0)->first();

        if (!$reserv_room) {
            $message = $request->translator->translate("It Have Not Room For The Number Of People ... ");
            return response()->json(["message"=>$message]);
        }

        $room_id = $reserv_room->id;
        $room = Room_Hotel_complete_flight::find($room_id);

        if($people_number > $room->capacety)
        {
            $message = $request->translator->translate("you have not reservation flight where the number of people less than room capacety");
            return response()->json(["message"=>$message]);
        }

        $complete_flight = EventFlight::find($id);
        $user_id = auth()->user()->id;
        $true = EventFlightReservation::where('user_id',$user_id)
        ->where('event_flight_id',$id)
        ->first();
        if($true!= null)
        {
            $message = $request->translator->translate("You do not reservate a flight more once");
            return response()->json(["message"=>$message]);
        }

        $cash_user = Purse::where('user_id',$user_id)->first();

        if( $complete_flight->price <= $cash_user->cash)
        {
        

            $admin_cash = Admin::find(1);
            $admin_cash->cash +=$complete_flight->price * $people_number;
            $admin_cash->save();
            
            $cash_user->cash -= $complete_flight->price * $people_number;
            $cash_user->save();

            $room->active = true;
            $room->save();
          
          $reservation->user_id = $user_id;
          $reservation->event_flight_id = $id;
          $reservation->room_id = $room_id;
          $reservation->people =  $people_number;
          $reservation->reservation_date = Carbon::now();
          $reservation->reservation_cost = $complete_flight->price * $people_number;
          $reservation->save();

          $complete_flight->available_place -= $people_number;
          $complete_flight->save();
          return response()->json([$reservation , $complete_flight], 200);
        }
        else{
            $message = $request->translator->translate("Sorry,you do not have enough money to complete the transaction");
            return response()->json(["message"=>$message]);
        }
        
    }


    public function update(Request $request, string $id)
    {
        $reservation_flight = EventFlightReservation::find($id);

        $complete_flight_updated_id = $request->input('event_flight_id');
        $complete_flight_updated = EventFlight::find($complete_flight_updated_id);

        $user_id = auth()->user()->id;

        $people_num = $reservation_flight->people;

        $people = $request->input('people');

        $user_cash = Purse::where('user_id',$user_id)->first();

        $oldRoom = Room_Hotel_complete_flight::find($reservation_flight->room_id);

        $reserv_room = Room_Hotel_complete_flight::where('capacety',$people)->first();

        if (!$reserv_room) {
            $message = $request->translator->translate("It Have Not Room For The Number Of People ... ");
            return response()->json(["message"=>$message]);
        }

        $room_id = $reserv_room->id;
        $reservation_flight->room_id = $room_id;
        $room = Room_Hotel_complete_flight::find($reservation_flight->room_id);

        if($people > $room->capacety)
        {
            $message = $request->translator->translate("you have not reservation flight where the number of people less than room capacety");
            return response()->json(["message"=>$message]);
        }

        $flight = EventFlight::find($reservation_flight->event_flight_id);

        if( $complete_flight_updated->price <= $user_cash->cash + $reservation_flight->reservation_cost)
        {
        /////////////////

        $departureDate = Carbon::parse($flight->travel_dates_departure);
        $previous = $departureDate->subDays(3);

        if(Carbon::today() < $previous)
       {
 

            $admin = Admin::find(1);
            $admin->cash -= $reservation_flight->reservation_cost;
            $admin->save();

            $user_cash->cash += $reservation_flight->reservation_cost;
            $user_cash->save();

            $oldRoom->active = false;
            $oldRoom->save();
            $room->active = true;     
            $room->save();

            ///////////////////

            $admin->cash  += $complete_flight_updated->price * $people ;
            $admin->save();

            $user_cash->cash -= $complete_flight_updated->price * $people ;
            $user_cash->save();

            if ($complete_flight_updated_id==$reservation_flight->complete_flight_id) {
                $complete_flight_updated->available_place +=$people_num;
                $complete_flight_updated->available_place -=$people;
                $complete_flight_updated->save();
            }
            else 
            {
            $flight->available_place = $flight->available_place + $people_num;
            $flight->save();
            $complete_flight_updated->available_place -= $people;
            $complete_flight_updated->save();
            }
      
            $reservation_flight->user_id = $user_id;
            $reservation_flight->event_flight_id = $complete_flight_updated_id;
            $reservation_flight->people =  $people;
            $reservation_flight->reservation_date = Carbon::now();
            $reservation_flight->reservation_cost = $complete_flight_updated->price * $people;
            $reservation_flight->save();

            return response()->json([
            $reservation_flight ,
            "event_flight_updated" => $complete_flight_updated,
            "flight"=>$flight
            ],
             200);
        }
        else {
            $message = $request->translator->translate("the time allowed for flight cancellation has been exceeded");
            return response()->json(["message"=>$message]);
        }

    }
    $message = $request->translator->translate("Sorry,you do not have enough money to complete the transaction");
    return response()->json(["message"=>$message]);
    }

    public function destroy(string $id,Request $request)
    {
        $complete_flight = EventFlightReservation::find($id);

        $people_num = $complete_flight->people;

        $user_id = auth()->user()->id;
        $user_cash = Purse::where('user_id',$user_id)->first();

        $flight = EventFlight::find($complete_flight->event_flight_id);

        $departureDate = Carbon::parse($flight->travel_dates_departure);
        $previous = $departureDate->subDays(3);

        if (Carbon::today() < $previous) 
        {            

        $room = Room_Hotel_complete_flight::find($complete_flight->room_id);
        $room->active = false;
        $room->save();

        $flight->available_place = $flight->available_place + $people_num;
        $flight->save();

        $admin = Admin::find(1);
        $admin->cash -=$complete_flight->reservation_cost;
        $admin->save();
        
        $user_cash->cash += $complete_flight->reservation_cost;
        $user_cash->save();

        $complete_flight->delete();
        }
        else
        {
        $message = $request->translator->translate("the time allowed for flight cancellation has been exceeded");
        return response()->json(["message"=>$message]);
        }
        $message = $request->translator->translate("Canceling Flight Succesfully ^^");
        return response()->json(["message"=>$message]);
    }

    public function search(Request $request)
    {
       return EventFlight::where('name','like','%'.$request->name.'%')->get();
    }

    public function get_all_resrvation_events()
    {
        return response()->json(EventFlightReservation::all());
    }

    public function show_resrvation_event($id)
    {
        $event = EventFlightReservation::find($id);
        $user = User::find($event->user_id);
        $event_flight = User::find($event->event_flight_id);
        return response()->json([$event,$user,$event_flight]);
    }
}
