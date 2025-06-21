<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateController extends Controller
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
    public function translate(Request $request)
    {
        $text = $request->input('text');
        return response()->json(
            $request->translator->translate($text)
        );
    }
}
