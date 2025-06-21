<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Profile;
use App\Models\Purse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProfileController extends Controller
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
        // $profiles = Profile::all()->with('cash');
        // $cash = Purse::all();
        return response()->json(User::with('cash')->get(), 200);
    }

    public function show(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $details = Profile::where('email',$user->email)->first();
        $details->name = $request->translator->translate($details->name);
        $details->gender = $request->translator->translate($details->gender);
        return response()->json($details, 200);
    }


    public function update(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $details = Profile::where('email',$user->email)->first();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
        ]);
        $exist_name = User::where('name',$request->name)->first();

        if($exist_name)
        {
          $message = $request->translator->translate("The name is already exists..");
          return response()->json($message);
        }

        $exist_email = User::where('email',$request->email)->first();

        if($exist_email)
        {
          $message = $request->translator->translate("The email is already exists..");
          return response()->json($message);
        }

        $details->update($request->all());

        $message = $request->translator->translate("Update Succesfully");
        return response()->json(["message"=>$message], 200);
    }

    public function changePassword(Request $request)
{
    // التحقق من صحة المدخلات
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    // الحصول على المستخدم الحالي
    $user = auth()->user();

    // التحقق من أن كلمة المرور الحالية صحيحة
    if (!Hash::check($request->current_password, $user->password)) {
        $message = $request->translator->translate("كلمة المرور الحالية غير صحيحة.");
        return response()->json(['current_password' => $message]);
    }

    // تحديث كلمة المرور
    $user->password = Hash::make($request->new_password);
    $user->save();

    $message = $request->translator->translate("تم تغيير كلمة المرور بنجاح.");
    return response()->json(['status', "message"=>$message]);
}
}
