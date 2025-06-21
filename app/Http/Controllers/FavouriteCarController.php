<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Car;
use App\Models\FavouriteCar;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FavouriteCarController extends Controller
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
    public function store(Request $request ,$id)
    {
        $car = new FavouriteCar();
        $car->car_id = $id;
        $car->user_id = auth()->user()->id;
        $car->save();
        $message = $request->translator->translate("تمت الإضافة بنجاح ");
        return response()->json(["message"=>$message]);
    }

    public function destroy($id,Request $request)
    {
        $car = FavouriteCar::find($id);
        $car->delete();
        $message = $request->translator->translate("تمت الحذف بنجاح ");
        return response()->json(["message"=>$message]);
    }
}
