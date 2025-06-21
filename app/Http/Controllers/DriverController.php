<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TranslationMiddleware;
use App\Models\Driver;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class DriverController extends Controller
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
        return response()->json(Driver::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'birth_date' => 'required',
            'nationality' => 'required',
        ]);
        $data = Driver::create($request->all());
        return response()->json(["Added Successfully",$data]);
    }

    public function show($id,Request $request)
    {
        $get = Driver::find($id);
        $get->name = $request->translator->translate($get->name);
        $get->nationality = $request->translator->translate($get->nationality);
        return response()->json($get);
    }

    public function showAdmin($id)
    {
        $get = Driver::find($id);
        return response()->json($get);
    }

    public function update(Request $request,$id)
    {
        $driver = Driver::find($id);
        $driver->update(
            $request->validate([
                'name' => 'required',
                'mobile' => 'required',
                'birth_date' => 'required',
                'nationality' => 'required',
            ])
            );
        return response()->json(["Update Successfully",$driver]);
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        $driver->delete();
        return response()->json("Delete Successfully");
    }
}
