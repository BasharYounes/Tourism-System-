<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\TaxiCar;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TaxiCarController extends Controller
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
        $get = TaxiCar::all();
        return response()->json($get);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_car' => 'required',
            'color' => 'required',
            'car_number' => 'required',
        ]);
        return response()->json([
            "Add Succesfully",
            TaxiCar::create($request->all())
        ]
        );
    }

    public function show($id,Request $request)
    {
        $car = TaxiCar::find($id);
        $car->color = $request->translator->translate($car->color);
        return response()->json($car);
    }

    public function showAdmin($id)
    {
        $car = TaxiCar::find($id);
        return response()->json($car);
    }

    public function update(Request $request,$id)
    {
        $car = TaxiCar::find($id);
        $car->update(
            $request->validate([
                'type_car' => 'required',
                'color' => 'required',
                'car_number' => 'required',
            ])
        );
        return response()->json(["Update Succesfully",$car]);
    }

    public function destroy($id)
    {
        $car = TaxiCar::find($id);
        $car->delete();
        return response()->json("Delete Succesfully ^^", 200);
    }
}