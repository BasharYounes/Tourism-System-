<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Room_Hotel_complete_flight;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class RoomHotelCompleteFlightController extends Controller
{
    public function add_room(Request $request)
    {
        $request->validate([
            'capacety' => 'required',
            'hotel_id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if($request->photo!= null)
        {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName , 'public');
            
            $data = Room_Hotel_complete_flight::create($request->all());
            $data->photo= "storage/images/". $fileName;
            $data->save();

            return response()->json(["Add Succesfully",$data], 200);
        }
        
        $data = Room_Hotel_complete_flight::create($request->all());

        return response()->json(["Add Succesfully",$data], 200);
    }

    public function update_room(Request $request,$id)
    {
        $room = Room_Hotel_complete_flight::find($id);
        $request->validate([
            'capacety' => 'required',
            'hotel_id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if($request->has('photo')){
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->storeAs('images', $fileName , 'public');
        $room->photo= "storage/images/". $fileName;
        }
        $room->capacety = $request->input('capacety');
        $room->hotel_id = $request->input('hotel_id');
        $room->save();

        return response()->json(["Updated Succesfully",$room], 200);
    }

    public function destroy_room($id)
    {
        $room = Room_Hotel_complete_flight::find($id);
        $room->delete();
        return response()->json("Delete Succesfully ^^", 200);
    }
}
