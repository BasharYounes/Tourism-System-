<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Hotel_complete_flight;
use App\Models\Room_Hotel_complete_flight;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HotelCompleteFlightController extends Controller
{

    public function get_all_hotels()
    {
        $hotels = Hotel_complete_flight::all();
        return response()->json($hotels, 200);
    }
    
    public function add_hotel(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'star_rating' => 'required',
            'rating_average' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if($request->photo!= null)
        {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName , 'public');
            
            $data = Hotel_complete_flight::create($request->all());
            $data->photo= "storage/images/". $fileName;
            $data->save();
    
            return response()->json(["Add Succesfully",$data], 200);
        }
        
        $data = Hotel_complete_flight::create($request->all());

        return response()->json(["Add Succesfully",$data], 200);
    }

    public function show($id)
    {
        $hotel = Hotel_complete_flight::find($id);
        $hotel->name = $this->translator->translate($hotel->name);
        $hotel->address = $this->translator->translate($hotel->address);
        $hotel->city = $this->translator->translate($hotel->city);
        $hotel->country = $this->translator->translate($hotel->country);
        $rooms = Room_Hotel_complete_flight::where('hotel_id',$hotel->id)->get();
        return response()->json([$hotel,$rooms ],200);
    }

    public function showAdmin($id)
    {
        $hotel = Hotel_complete_flight::find($id);
        return response()->json($hotel,200);
    }

    public function update_hotel(Request $request, $id)
    {
        $hotel = Hotel_complete_flight::find($id);
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'star_rating' => 'required',
            'rating_average' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->has('photo'))
        {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName , 'public');
            $hotel->photo= "storage/images/". $fileName;
        }
            $hotel->name = $request->input('name');
            $hotel->address = $request->input('address');
            $hotel->city = $request->input('city');
            $hotel->country = $request->input('country');
            $hotel->star_rating = $request->input('star_rating');
            $hotel->rating_average = $request->input('rating_average');
            $hotel->save();

     return response()->json("Updated Succesfully", 200);
    }

    public function destroy_hotel($id)
    {
        $hotel = Hotel_complete_flight::find($id);
        $rooms = Room_Hotel_complete_flight::where('hotel_id',$hotel->id)->get();
        foreach ($rooms as $room) 
        {
           $room->delete();
        }
        $hotel->delete();
        return response()->json("Delete Succesfully ^^", 200);
        
    }
}
