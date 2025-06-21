<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\AirPort;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AirPortController extends Controller
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
        $airports = AirPort::all();
        return response()->json($airports, 200);
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
            $data = AirPort::create($request->all());
            $data->photo= "images/". $fileName;
            $data->save();
            $message = $request->translator->translate("Add Succesfully");
            return response()->json([$message,$data], 200);
        }

        $data = AirPort::create($request->all());
        $message = $request->translator->translate("Add Succesfully");
        return response()->json([$message,$data], 200);
    }

    public function destroy($id,Request $request)
    {
        $airport = AirPort::find($id);
        $airport->delete();
        $message = $request->translator->translate("Delete Succesfully");
        return response()->json($message);
    }
}