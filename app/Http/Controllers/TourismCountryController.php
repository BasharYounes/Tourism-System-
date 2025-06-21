<?php

namespace App\Http\Controllers;

use App\Models\TourismCountry;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TourismCountryController extends Controller
{

    public function index()
    {
        $countries = TourismCountry::all();
        return response()->json($countries, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'country' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->has('photo')) {
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->storeAs('images', $fileName , 'public');
        $data = TourismCountry::create($request->all());
        $data->photo= "storage/images/". $fileName;
        $data->save();
        return response()->json(["Add Succesfully",$data], 200);
        }
        $data = TourismCountry::create($request->all());

        return response()->json(["Add Succesfully",$data], 200);;
    }

    public function show($id)
    {
        $country = TourismCountry::find($id);
        $country->name = $this->translator->translate($country->name);
        $country->city = $this->translator->translate($country->city);
        $country->country = $this->translator->translate($country->country);
        $country->famous = $this->translator->translate($country->famous);
        return response()->json($country, 200);
    }

    public function showAdmin($id)
    {
        $country = TourismCountry::find($id);
        return response()->json($country, 200);
    }

    public function update(Request $request,  $id)
    {
        $country = TourismCountry::find($id);
        $country->update(
            $request->validate([
                'name' => 'required',
                'city' => 'required',
                'country' => 'required',
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ])
        );
        if($request->has('photo')){
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->storeAs('images', $fileName , 'public');
        $country->photo= "storage/images/". $fileName;
        $country->save();
        }
        
        return response()->json("Update Succesfully", 200);
    }

    public function destroy($id)
    {
        $country = TourismCountry::find($id);
        $country->delete();
        return response()->json("Delete Succesfully ^^", 200);
    }
}