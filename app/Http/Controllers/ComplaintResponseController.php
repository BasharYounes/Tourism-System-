<?php

namespace App\Http\Controllers;

use App\Events\SpecialEventNotification;
use App\Http\Middleware\TranslationMiddleware;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ComplaintResponseController extends Controller
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
        //
    }

    public function store(Request $request,$id)
    {
  
        $replied = $request->input('replied');
        $data = new ComplaintResponse;
        $data->replied = $replied;
        $data->complaint_id = $id;
        $data->save();
        $complaint = Complaint::find($id);
        $message = $request->translator->translate("تم الرد على تعليقٌ لك ..");
        event(new SpecialEventNotification($complaint->user_id,$message));
        
        return response()->json("Replied Successfully");
    }

 
    public function update(Request $request,$id)
    {
        $replied_complaint = ComplaintResponse::find($id);
        $replied_complaint->replied = $request->input('replied');
        $replied_complaint->save();
        return response()->json("Updated Successfully");
    }

    public function destroy($id)
    {
        $replied_complaint = ComplaintResponse::find($id);
        $replied_complaint->delete();
    }
}
