<?php

namespace App\Http\Controllers;

use App\Models\AirLineCash;
use App\Models\AirLines;
use Illuminate\Http\Request;
class AirLinesController extends Controller
{
    public function index()
    {
        $airlines = AirLines::all();
        return response()->json($airlines, 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'transport_id' => 'required',
            // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // $fileName = time() . '.' . $request->photo->extension();
        // $request->photo->storeAs('images', $fileName , 'public');
        // $airline = new AirLines();
        // $airline->photo= "images/". $fileName;
        // $airline->save();
        $data = AirLines::create($request->all());
        //إنشاء كاش لشركة الطيران
        $cash_airline = new AirLineCash();
        $cash_airline->airline_name = $request->name;
        $cash_airline->save();
        
        return response()->json(["Add Succesfully",$data], 200);
    }
    public function destroy($id)
    {
        $airline = AirLines::find($id);
        $airline->delete();
        return response()->json("Delete Succesfully ^^");
    }
}
