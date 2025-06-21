<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;
use Stichoza\GoogleTranslate\GoogleTranslate;


class ResetPasswordController extends Controller
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
            'password' => 'required|string|min:6|confirmed',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            $message = $request->translator->translate("passwords.code_is_expire");

            return response()->json(['message' => $message], 422);
        }

        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update($request->only('password'));

        // delete current code 
        $passwordReset->delete();

        $message = $request->translator->translate("password has been successfully reset");
        return response(['message' =>$message], 200);
    }
}
