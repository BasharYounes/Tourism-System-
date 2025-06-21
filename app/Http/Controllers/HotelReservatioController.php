<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\HotelCash;
use App\Models\HotelReservatio;
use App\Models\Hotel;
use App\Models\Purse;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;


class HotelReservatioController extends Controller
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
        $user_id = auth()->user()->id;
        $hotels = HotelReservatio::where('user_id',$user_id)->get();
        return response()->json($hotels, 200);
    }

    public function get_all_reservation_hotels()
    {
        $hotels = HotelReservatio::all();
        return response()->json($hotels, 200);
    }

    public function show_reservation_hotel($id)
    {
        $hotel_reservation = HotelReservatio::find($id);
        $user = User::find($hotel_reservation->user_id);
        $room = User::find($hotel_reservation->room_id);
        $hotel = Hotel::find($room->hotel_id);

        return response()->json([$hotel,$room,$user,$hotel_reservation], 200);
    }
    public function get_all_hotels()
    {
        $hotels = Hotel::all();
        return response()->json($hotels, 200);
    }

    public function search_hotel(Request $request){
        $hotel = new Hotel();
        $hotel->name = $request->input('name');
        $hotel->tourism_country_id = $request->input('city');

        // $roomDate = new Room();
        // $roomDate->reservation_date = $request->input('reservation_date');


        $hotels = Hotel::where('name', 'like', '%' . $hotel->name . '%')
        ->where('city', 'like', '%' . $hotel->city . '%')
        ->first();

        // $reservation = [];
        // $i = 0;
        // $rooms = Room::where('hotel_id',$hotels->id)->get();
        // foreach ($rooms as $room) {
        //     $reservation_rooms = HotelReservatio::where('room_id', 'like', '%' . $room->id . '%')
        //     ->where('from', '<=', $roomDate->reservation_date)
        //     ->Where('to', '>=', $roomDate->reservation_date)
        //     ->get();
        //     if ($reservation_rooms->count() == 0) 
        //     {
        //         $reservation['room'][$i] = $room;
        //         $i++;
        //     }
        // }
        return response()->json($hotels,200);
    }

    public function search_room(Request $request,$id){


        $roomDate = new Room();
        $roomDate->reservation_date = $request->input('reservation_date');


        $hotels = Hotel::find($id);

        $reservation = [];
        $i = 0;
        $rooms = Room::where('hotel_id',$hotels->id)->get();
        foreach ($rooms as $room) {
            $reservation_rooms = HotelReservatio::where('room_id', 'like', '%' . $room->id . '%')
            ->where('from', '<=', $roomDate->reservation_date)
            ->Where('to', '>=', $roomDate->reservation_date)
            ->get();
            if ($reservation_rooms->count() == 0) 
            {
                $reservation['room'][$i] = $room;
                $i++;
            }
        }
        return response()->json($reservation,200);
    }
    public function add_hotel(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'url' => 'required',
            'star_rating' => 'required',
            'rating_average' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = Hotel::create($request->all());
        if($request->has('photo'))
        {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName , 'public');
            $data->photo= "storage/images/". $fileName;
            $data->save();
        }


        $cash_hotel = new HotelCash();
        $cash_hotel->hotel_id = $data['id'];
        $cash_hotel->save();

        // $hotel->save();
        $message = $request->translator->translate("Add Succesfully");
        return response()->json(["message"=>$message,$data,$cash_hotel], 200);
    }
 

    public function store(Request $request,$id)
    {
        $reservation = new HotelReservatio();
        $reservation->from = $request->input('from');
        $reservation->to = $request->input('to');
        $room = Room::find($id);
        $user_id = auth()->user()->id;
        $true = HotelReservatio::where('user_id',$user_id)
        ->where('room_id',$id)->first();
        if($true!= null)
        {
            $message = $this->translator->translate("You do not reservate a room more once");
            return response()->json(["message"=>$message]);
        }
        $cash_user = Purse::where('user_id',$user_id)->first();

        $startDate = Carbon::parse( $reservation->from);
        $endDate = Carbon::parse($reservation->to);
        $x = $startDate->diffInDays($endDate) + 1;

        if($x * $room->price <= $cash_user->cash)
        {
            $hotel_id = $room->hotel_id;
            $cash_hotel = HotelCash::where('hotel_id',$hotel_id)->first();
            $cash_hotel->cash = $x * $room->price + $cash_hotel->cash;
            $cash_user->cash = $cash_user->cash - $x * $room->price;
            $cash_hotel->save();
            $cash_user->save();
          
          $reservation->user_id = $user_id;
          $reservation->room_id = $id;
          $reservation->reservation_date = Carbon::now();
          $reservation->reservation_cost = $x * $room->price;
          $reservation->save();

          return response()->json([$reservation,$cash_hotel,$cash_user], 200);
        }
        else{
        $message = $request->translator->translate("Sorry,you do not have enough balance to complete the transaction");
        return response()->json(["message"=>$message]);
    }
    }


    public function cancel_reservation(Request $request,$id)
    {
        $reservation = HotelReservatio::find($id);

        $user_id = auth()->user()->id;
        $cash_user = Purse::where('user_id',$user_id)->first();

        $room = Room::find($reservation->room_id);
        $hotel_id = $room->hotel_id;
        $cash_hotel = HotelCash::where('hotel_id',$hotel_id)->first();
        $cash_hotel->cash = $reservation->reservation_cost - $cash_hotel->cash;
        $cash_user->cash = $cash_user->cash + $reservation->reservation_cost;
        $cash_hotel->save();
        $cash_user->save();
          
        $reservation->delete();

        $message = $request->translator->translate("Cancel Flight Successfully");
        return response()->json(["message"=>$message], 200);
    }

    public function show($id)
    {
        $hotel = Hotel::find($id);
        $hotel->name = $this->translator->translate($hotel->name);
        $hotel->address = $this->translator->translate($hotel->address);
        $hotel->city = $this->translator->translate($hotel->city);
        $hotel->country = $this->translator->translate($hotel->country);
        $rooms = Room::where('hotel_id',$hotel->id)->get();
        return response()->json([$hotel,$rooms ],200);
    }

    public function update_hotel(Request $request, $id)
    {
        $hotel = Hotel::find($id);
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'url' => 'required',
            'star_rating' => 'required',
            'rating_average' => 'required',
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // if($request->has('photo')){
        //     $fileName = time() . '.' . $request->photo->extension();
        //     $request->photo->storeAs('images', $fileName , 'public');
        //     $hotel->photo= "storage/images/". $fileName;
        //     // $hotel->save();
        //     }
        $hotel->name = $request->input('name');
        $hotel->address = $request->input('address');
        $hotel->city = $request->input('city');
        $hotel->country = $request->input('country');
        $hotel->url = $request->input('url');
        $hotel->star_rating = $request->input('star_rating');
        $hotel->rating_average = $request->input('rating_average');
        $hotel->save();

        $message = $request->translator->translate("Updated Succesfully");
        return response()->json([$message,$hotel], 200);
    }

    public function destroy_hotel($id,Request $request)
    {
        $hotel = Hotel::find($id);
        $rooms = Room::where('hotel_id',$hotel->id)->get();
        foreach ($rooms as $room) 
        {
           $room->delete();
        }
        $hotel->delete();
        $message = $request->translator->translate("Delete Succesfully ^^");
        return response()->json($message);
    }
}