<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AdminController extends Controller
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
    public function check()
    {
        $admin = Admin::find(1);
        return response()->json([$admin]);
    }

    public function register(Request $request)
    {
 
     $fields=$request->validate([
         'name' => 'required|string',
         'mobile' => 'required|numeric|digits:10',
         'email' => 'required|email|unique:admins',
         'password' => 'required|string|min:8|confirmed',
     ]);

     $admin=Admin::create([
         'name'=>$fields['name'],
         'email'=>$fields['email'],
         'mobile'=>$fields['mobile'],
         'password'=>bcrypt($fields['password']),
        ]);
     $token=$admin->createToken('myapptoken')->plainTextToken;


 
     $response = [
         'admin'=>$admin,
         'message' => " Register Succesfully",
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
        $user = Admin::where('email',$fields['email'])->first();
    
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
         auth()->guard('admin')->user()->tokens()->delete();

         $message = $request->translator->translate("Log Out Succesfully");

         return response([
            'message' =>$message
         ]);
       }
}
