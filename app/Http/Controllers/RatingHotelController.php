<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Rating_Hotel;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class RatingHotelController extends Controller
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
        $rate = new Rating_Hotel();
        $rate->rating = $request->input('rating');
        $rate->comment = $request->input('comment');
        $rate->user_id = auth()->user()->id;
        $rate->hotel_id = $id;
        $rate->save();
        $message = $request->translator->translate("Rating Succesfully");
        return response()->json(["message"=>$message], 200);
    }


    public function update(Request $request, $id)
    {
        $rate = Rating_Hotel::find($id);
        $rate->rating = $request->input('rating');
        $rate->comment = $request->input('comment');
        $rate->save();
        $message = $request->translator->translate("Update Succesfully");
        return response()->json(["message"=>$message], 200);
    }

    public function destroy($id,Request $request)
    {
        $rate = Rating_Hotel::find($id);
        $rate->delete();
        $message = $request->translator->translate("Delete Succesfully ^^");
        return response()->json(["message"=>$message], 200);
    }

    public function get_rating($id)
    {
        $i = 0;
        $sum = 0;
        $rating_hotel = Rating_Hotel::where('hotel_id',$id)->get();
        foreach ($rating_hotel as $rate) {
            $sum += $rate->rating;
            $i++;
        }
        return response()->json($sum / $i);
    }
}
