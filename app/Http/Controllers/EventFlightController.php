<?php

namespace App\Http\Controllers;

use App\Events\NewEvent;
use App\Http\Middleware\TranslationMiddleware;
use App\Models\EventFlight;
use App\Models\Hotel_complete_flight;
use App\Models\Notification;
use App\Models\Transport;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class EventFlightController extends Controller
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

    public function get_all_event_flights()
    {
        $flights = EventFlight::all();
        return response()->json($flights, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'destination' => 'required',
            'travel_dates_departure' => 'required',
            'travel_dates_return' => 'required' ,
            'reservation_type' => 'required',
            'available_place' => 'required' ,
            'transport_id' => 'required' ,
            'transport_company' => 'required' ,
            'price' => 'required' ,
            'hotel_id' => 'required',
            'nights' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName, 'public');
            $requestData['photo'] = "storage/images/" . $fileName;
        }

        $data = EventFlight::create($request->all());

        // $name = 'New Trip Added!';
        // $details = "A new trip titled '{$data->name}' has been added.";

        // $this->sendNotification($name,$details);

        // $post = new Notification();
        // $post->name = $request->name;
        // $post->details = $request->details;
        // $post->save();

        return response()->json([
            "message"=>"Add Succesfully",
            "data"=>$data], 200);
    }

    public function show($id, Request $request)
    {
        try{
            $flight = EventFlight::find($id);
            $flight->name = $request->translator->translate($flight->name);
            $flight->destination = $request->translator->translate($flight->destination);
            $flight->transport_company = $request->translator->translate($flight->transport_company);
            $hotel = Hotel_complete_flight::find($flight->hotel_id);
            $hotel->name = $request->translator->translate($hotel->name);
            $hotel->address = $request->translator->translate($hotel->address);
            $hotel->city = $request->translator->translate($hotel->city);
            $hotel->country = $request->translator->translate($hotel->country);
    
            $transport = Transport::find($flight->transport_id);
            $transport->name = $request->translator->translate($transport->name);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Translation error: ' . $e->getMessage()], 500);
        }
 
        
        return response()->json([$flight,$hotel,$transport], 200);
    }

    public function showAdmin($id)
    {
        $flight = EventFlight::find($id);
        return response()->json($flight, 200);
    }

    public function update(Request $request, string $id)
    {
        $flight = EventFlight::find($id);
        $request->validate([
            'name' => 'required',
            'destination' => 'required',
            'travel_dates_departure' => 'required',
            'travel_dates_return' => 'required' ,
            'reservation_type' => 'required',
            'available_place' => 'required' ,
            'transport_id' => 'required' ,
            'transport_company' => 'required' ,
            'price' => 'required' ,
            'hotel_id' => 'required',
            'nights' => 'required',
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // Encode the arrays to JSON
        $requestData = $request->all();


        // if ($request->hasFile('photo')) {
        //     $fileName = time() . '.' . $request->photo->extension();
        //     $request->photo->storeAs('images', $fileName, 'public');
        //     $requestData['photo'] = "storage/images/" . $fileName;
        // }

        $flight->name = $requestData['name'];
        $flight->destination = $requestData['destination'];
        $flight->travel_dates_departure = $requestData['travel_dates_departure'];
        $flight->travel_dates_return = $requestData['travel_dates_return'];
        $flight->reservation_type = $requestData['reservation_type'];
        $flight->available_place = $requestData['available_place'];
        $flight->transport_id = $requestData['transport_id'];
        $flight->transport_company = $requestData['transport_company'];
        $flight->price = $requestData['price'];
        $flight->hotel_id = $requestData['hotel_id'];
        $flight->nights = $requestData['nights'];
        $flight->save();

        // $name = 'Trip Updated!';
        // $details = "A new event titled '{$flight->name}' has been updated.";

        // $this->sendNotification($name,$details);

        return response()->json([
            "message"=>"Updated Succesfully",
            "flight"=>$flight]);
    }

    public function destroy(string $id)
    {
        $flight = EventFlight::find($id);
        // $name = 'Trip Delete!';
        // $details = "A new event titled '{$flight->name}' has been deleted.";
        $flight->delete();

        // $this->sendNotification($name,$details);
        return response()->json(["message"=>"Delete Succesfully ^^"], 200);
    }
}
