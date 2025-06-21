<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Events\SpecialEventNotification;
use App\Http\Middleware\TranslationMiddleware;
use App\Models\Admin;
use App\Models\Hotel;
use App\Models\HotelCash;
use App\Models\Purse;
use App\Models\User;
use App\Models\UsersNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class RechargeController extends Controller
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

 
  public function recharge(Request $request,$id)
  {
    $amount = $request->input('amount');
    $cash_user = Purse::where('user_id',$id)->first();
    $user = User::find($id);
    if($user->active ==1)
    {
      return response()->json("The Account Is Not Active To Filling Her Cash");
    }
    $admin = Admin::find(1);
    if($admin->cash >= $amount)
    {
      $cash_user->cash +=$amount;
      $cash_user->save();
      $admin->cash -=$amount;
      $admin->save();

      event(new GenericNotificationEvent(
        id: auth()->user(),
        type: 'Recharge',
        data: ['amount' => $amount]
      ));

      return response()->json(["user"=>$cash_user,"message"=>"Successfull"]);
    }
    else{
      $message = $request->translator->translate("ليس لديك رصيد كاف لإتمام العملية");
      return response()->json(["message"=>$message]);
    }
    
  }

  public function get_cash(Request $request)
  {
    $user_id = auth()->user()->id;
    $cash_user = Purse::where('user_id',$user_id)->first();
    return response()->json($cash_user->cash, 200);
  }

  // public function test()
  // {
  //   $date2 =Carbon::now();
  //   $date1 =Carbon::now();
  //   $date =Carbon::now();
  //   $nextDate = $date->addDays(3);
  //   $previousDate = $date2->subDays(3);
  //   if($date1 > $nextDate){
  //     return response()->json(["date1 is larger" => $date1]);
  //   }

  //   return response()->json([
  //     "date1"=>$date1,
  //     "nextDate"=>$nextDate,
  //     "previousDate"=>$previousDate]);
  // }

  public function test(Request $request)
  {
    $hotels = Hotel::all();
    foreach ($hotels as $hotel) {
      $cash = new HotelCash();
      $cash->hotel_id = $hotel->id;
      $cash->cash = 50000;
      $cash->save();
    }

        return response()->json();
    
  }
}
