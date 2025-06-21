<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\FavouriteHotel;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FavouriteHotelController extends Controller
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
        $flight = new FavouriteHotel();
        $flight->hotel_id = $id;
        $flight->user_id = auth()->user()->id;
        $flight->save();
        $message = $request->translator->translate("تمت الإضافة بنجاح ");
        return response()->json(["message"=>$message]);
    }

    public function destroy($id,Request $request)
    {
        $flight = FavouriteHotel::find($id);
        $flight->delete();
        $message = $request->translator->translate("تمت الحذف بنجاح ");
        return response()->json(["message"=>$message]);
    }
}
