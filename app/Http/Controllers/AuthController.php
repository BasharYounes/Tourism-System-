<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Profile;
use App\Models\Purse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Stichoza\GoogleTranslate\GoogleTranslate;


class AuthController extends Controller
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

       public function register(Request $request)
       {
    
        $fields=$request->validate([
            'name' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required',
        ]);

        $exist_name = User::where('name',$fields['name'])->first();

        if($exist_name)
        {
          $message = $request->translator->translate("The name is already exists..");
          return response()->json($message);
        }
        
        if (strpos($fields['email'], '.') == false    ||    strpos($fields['email'], 'com') == false) 
        {

          $message = $request->translator->translate("Email IS Not Currect");

          return response()->json(["message"=>$message]);
        } 
        $user=User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'mobile'=>$fields['mobile'],
            'password'=>bcrypt($fields['password']),
            'gender'=>$fields['gender'],
        ]);



        $token=$user->createToken('myapptoken')->plainTextToken;

        $cash_user = new Purse();
        $cash_user->user_id = $user['id'];
        $cash_user->save();

        $user_profile = new Profile();
        $user_profile->name = $fields['name'];
        $user_profile->email = $fields['email'];
        $user_profile->mobile = $fields['mobile'];
        $user_profile->gender = $fields['gender'];
        $user_profile->save();

        $message = $request->translator->translate("Register Succesfully");
    
        $response = [
            'user'=>$user,
            'message' => $message,
            'token'=>$token
        ];
        return response($response,201);
    
       }
    
       public function login(Request $request)
       {
    
        $fields=$request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
       
        //check email
        $user = User::where('email',$fields['email'])->first();
    
        //check password
        if(!$user||!Hash::check($fields['password'],$user->password)) {
          $message = $request->translator->translate("Email Or Password Is Not currect");
           return response([
            'message' => $message,
           ],401);
        }
        if ($user->effective == 0) {
          $message = $request->translator->translate("Your Account Is Not effective");
          return response()->json(["message"=>$message]);
        }

        $token=$user->createToken('myapptoken')->plainTextToken;

        $user->update(['fcm_token' => $request->fcm_token]);

        $message = $request->translator->translate("Log in Succesfully");

        $response = [
           // 'user'=>$user,
            'message' => $message,
            'token'=>$token
        ];

        return response($response,201);
       }
    
       public function logout(Request $request)
       {
         auth()->guard('user')->user()->tokens()->delete();

         $message = $request->translator->translate("Log Out Succesfully");

         return response([
            'message' =>$message
         ]);
       }

       public function destroy_account($id,Request $request)
       {

          $user = auth()->user();
          $cash_user = Purse::where('user_id',$user->id)->first();
          $user->effective = 0;
          $user->save();
    
          if ($cash_user->cash > 0) {
            $message = $request->translator->translate("Delete Succesfully ^^ 
             You Have a Money In Your Cash .. 
             We Will Connect With You Soon ..^^");
             return response()->json(["message" => $message]);
          }
          $message = $request->translator->translate("Delete Succesfully ^^");

          return response()->json(["message" => $message]);
       }
}