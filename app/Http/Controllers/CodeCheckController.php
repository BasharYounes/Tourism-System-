<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CodeCheckController extends Controller
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
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            $message = $request->translator->translate("passwords.code_is_expire");
            return response(['message' => $message], 422);
        }
        $message = $request->translator->translate("passwords.code_is_valid");
        return response([
            'code' => $passwordReset->code,
            'message' => $message
        ], 200);
    }
}
