<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TransportController extends Controller
{
    public function index()
    {
        $transports = Transport::all();
        return response()->json($transports, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->has('photo')) {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName , 'public');
            $data = Transport::create($request->all());
            $data->photo= "storage/images/". $fileName;
            $data->save();
            return response()->json(["Add Succesfully",$data], 200);
            }
        $data = Transport::create($request->all());

        return response()->json(["Add Succesfully",$data], 200);
    }

    public function destroy($id)
    {
        $transport = Transport::find($id);
        $transport->delete();
        return response()->json("Delete Succesfully ^^", 200);
    }
}
