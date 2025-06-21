<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Hash;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;
use Auth;

class SocialiteController extends Controller
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
    public function redirect($provider)
    {
       return Socialite::driver($provider)->redirect();
    }

    public function callback($provider,Request $request)   
    {
       try {
        $provider_user = Socialite::driver($provider)->user();
         // dd($provider_user);
         

         $user = User::where(['provider_id'=>$provider_user->id,
         'provider' => $provider])->first();
          if (!$user) {
            $user = User::create([
               'name' => $provider_user->name,
               'email' => $provider_user->email,
               'password' => Hash::make(str::random(8)),
               'provider' => $provider,
               'provider_id' => $provider_user->id,
               'provider_token' =>  $provider_user->token,
            ]);
            Auth::login($user);
            //$token=$user->createToken('myapptoken')->plainTextToken;
          }
       }

       catch (Exception $e) 
       {
         $message = $request->translator->translate($e->getMessage());
        return response()->json(['message' => $message]);
       }

      finally{}
      $token=$user->createToken('myapptoken')->plainTextToken;
      
      $message = $request->translator->translate("Add Succesfully");
      return response()->json([
         'message' => $message,
         //'user'=>$user,
         'token' =>  $token,
      ],200);
   }

   public function index($provider)
   {
      $user =  auth()->user();
      dd($user);
      $provider_user = Socialite::driver($provider)->userFromToken($user->provider_token); 
      dd($provider_user);

      //return response()->json(['provider_user'=> $provider_user],200);
   }


 }