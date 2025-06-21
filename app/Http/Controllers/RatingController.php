<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Rating;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class RatingController extends Controller
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
    public function store(Request $request,$id)
    {
        $rate = new Rating();
        $rate->rating = $request->input('rating');
        $rate->user_id = auth()->user()->id;
        $rate->flight_id = $id;
        $rate->save();
        $message = $request->translator->translate("Rating Succesfully");
        return response()->json($message, 200);
    }


    public function update(Request $request, $id)
    {
        $rate = Rating::find($id);
        $rate->rating = $request->input('rating');
        $rate->save();
        $message = $this->translator->translate("Update Succesfully");
        return response()->json($message, 200);
    }

    public function destroy($id,Request $request)
    {
        $rate = Rating::find($id);
        $rate->delete();
        $message = $request->translator->translate("Delete Succesfully ^^");
        return response()->json($message, 200);
    }
}
