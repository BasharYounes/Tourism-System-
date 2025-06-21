<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Car;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CarController extends Controller
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
        return response()->json(Car::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_car' => 'required',
            'color' => 'required',
            'monthly_rent' => 'required',
            'class' => 'required',
            'driver_id' => 'required',
            'car_number' => 'required',
            'people_number' => 'required'
        ]);
        $data = Car::create($request->all());


        return response()->json(["Added Successfully",$data]);
    }

    public function show($id ,Request $request)
    {
        $get = Car::find($id);
        try {
            $get->type_car = $request->translator->translate($get->type_car);
            $get->color = $request->translator->translate($get->color);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Translation error: ' . $e->getMessage()], 500);
        }
        
        return response()->json($get);
    }

    public function showAdmin($id)
    {
        $get = Car::find($id);
        return response()->json($get);
    }

    public function update(Request $request,$id)
    {
        $car = Car::find($id);
       $car->update($request->validate([
        'type_car' => 'required',
        'color' => 'required',
        'monthly_rent' => 'required',
        'class' => 'required',
        'driver_id' => 'required',    
        'car_number' => 'required',
        'people_number' => 'required'
    ])) ;

    return response()->json(["Updated Successfully",$car]);
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        $car->delete();
        return response()->json("Delete Successfully");
    }
}