<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ComplaintController extends Controller
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
        return response()->json(Complaint::with('replies')->get());
    }

    public function get_user_complaints()
    {
        return response()->json(Complaint::where('user_id',auth()->user()->id)->get());
    }

    public function store(Request $request)
    {
        $comment = $request->input('comment');
        $data = new Complaint();
        $data->comment = $comment;
        $data->user_id = auth()->user()->id;
        $data->save();

        $message = $request->translator->translate('تم إضافة التعليق بنجاح');
        return response()->json(["message"=>$message]);

    }

    public function update(Request $request,$id)
    {
        $complaint = Complaint::find($id);
        $complaint->comment = $request->input('comment');
        $complaint->save();
        $message = $request->translator->translate('تم تعديل التعليق بنجاح');
        return response()->json(["mesasage"=>$message]);
    }

    public function destroy($id,Request $request)
    {
        $complaint = Complaint::find($id);
        $replied = ComplaintResponse::where('complaint_id',$complaint->complaint_id)->get();
        if($replied)
        {
            foreach ($replied as $reply) {
                $reply->delete();
            }
        }
        $complaint->delete();
        $message = $request->translator->translate('تم حذف التعليق بنجاح');
        return response()->json(["message"=>$message]);
    }
}
