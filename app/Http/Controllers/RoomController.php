<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Stichoza\GoogleTranslate\GoogleTranslate;


class RoomController extends Controller
{
    public function get_rooms()
    {
        return response()->json(Room::all());
    }
    public function add_room(Request $request)
    {
        $request->validate([
            'capacety' => 'required',
            'price' => 'required',
            'reservation_type' => 'required',
            'hotel_id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->storeAs('images', $fileName , 'public');
        
        $data = Room::create($request->all());
        $data->photo= "storage/images/".$fileName;
        $data->save();

        return response()->json(["Add Succesfully",$data], 200);
    }

    public function update_room(Request $request,  $id)
    {
        $room = Room::find($id);
        $request->validate([
            'capacety' => 'required',
            'reservation_type' => 'required',
            'price' => 'required',
            'hotel_id' => 'required',
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // if($request->has('photo')){
        // $fileName = time() . '.' . $request->photo->extension();
        // $request->photo->storeAs('images', $fileName , 'public');
        // $room->photo= "storage/images/". $fileName;
        // }
        $room->capacety = $request->input('capacety');
        $room->reservation_type = $request->input('reservation_type');
        $room->price = $request->input('price');
        $room->hotel_id = $request->input('hotel_id');
        $room->save();
        return response()->json(["Update Succesfully",$room], 200);
    }

    public function destroy_room($id)
    {
        $room = Room::find($id);
        $room->delete();
        return response()->json("Delete Succesfully ^^", 200);
    }
}
