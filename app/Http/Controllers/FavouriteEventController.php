<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\FavouriteEvent;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FavouriteEventController extends Controller
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
    public function store($id,Request $request)
    {
        $flight = new FavouriteEvent();
        $flight->event_flight_id = $id;
        $flight->user_id = auth()->user()->id;
        $flight->save();
        $message = $request->translator->translate("تمت الإضافة بنجاح ");
        return response()->json(["message"=>$message]);
    }

    public function destroy($id,Request $request)
    {
        $flight = FavouriteEvent::find($id);
        $flight->delete();
        $message = $request->translator->translate("تمت الحذف بنجاح ");
        return response()->json(["message"=>$message]);
    }
}
