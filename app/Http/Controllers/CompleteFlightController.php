<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Events\NewEvent;
use App\Http\Middleware\TranslationMiddleware;
use App\Models\Complete_Flight;
use App\Models\Hotel_complete_flight;
use App\Models\Notification;
use App\Models\Transport;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class CompleteFlightController extends Controller
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

    public function get_all_complete_flights()
    {
        $flights = Complete_Flight::all();
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
            'inclusions' => 'required' ,
            'activities' => 'required' ,
            'price' => 'required' ,
            'hotel_id' => 'required',
            'nights' => 'required',
            'famous' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // Encode the arrays to JSON
        $requestData = $request->all();
        $requestData['inclusions'] = json_encode($request->inclusions);
        $requestData['activities'] = json_encode($request->activities);

        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('images', $fileName, 'public');
            $requestData['photo'] = "storage/images/" . $fileName;
        }

        $data = Complete_Flight::create($requestData);


   event(new GenericNotificationEvent(
    id: auth()->user()->id,
    type: 'bookingCompleteFlight',
    data: ['complete_flight_name' => $requestData['name']]
   ));

        $message = $request->translator->translate("Add Succesfully");
        return response()->json(["message"=>$message, $data], 200);
    }

    public function show($id,Request $request)
    {
        try{
            $flight = Complete_Flight::find($id);
            $flight->name = $request->translator->translate($flight->name);
            $flight->destination = $request->translator->translate($flight->destination);
            $flight->transport_company = $request->translator->translate($flight->transport_company);
            $flight->famous = $request->translator->translate($flight->famous);
            $inclusions = json_decode($flight->inclusions);
            if (is_array($inclusions)) {
                $inclusions_string = implode(", ", $inclusions);
                $translated_inclusions = $request->translator->translate($inclusions_string);
                $flight->inclusions = $translated_inclusions;
            }
            $activities = json_decode($flight->activities);
            if (is_array($activities)) {
                $activities_string = implode(", ", $activities);
                $translated_activities = $request->translator->translate($activities_string);
                $flight->activities = $translated_activities;
            }
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
        
        $flight = Complete_Flight::find($id);     
        return response()->json($flight ,200);
    }

    public function update(Request $request, string $id)
    {
        $flight = Complete_Flight::find($id);
        $request->validate([
            'name' => 'required',
            'destination' => 'required',
            'travel_dates_departure' => 'required',
            'travel_dates_return' => 'required' ,
            'reservation_type' => 'required',
            'available_place' => 'required' ,
            'transport_id' => 'required' ,
            'transport_company' => 'required' ,
            'inclusions' => 'required' ,
            'activities' => 'required' ,
            'price' => 'required' ,
            'hotel_id' => 'required',
            'nights' => 'required',
            'famous' => 'required',
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // Encode the arrays to JSON
        $requestData = $request->all();
        $requestData['inclusions'] = json_encode($request->inclusions);
        $requestData['activities'] = json_encode($request->activities);

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
        $flight->inclusions = $requestData['inclusions'];
        $flight->activities = $requestData['activities'];
        $flight->price = $requestData['price'];
        $flight->hotel_id = $requestData['hotel_id'];
        $flight->nights = $requestData['nights'];
        $flight->save();

        // $name = 'Trip Updated!';
        // $details = "A new trip titled '{$flight->name}' has been updated.";

        // $this->sendNotification($name,$details);

        $message = $request->translator->translate("Updated Succesfully");
        return response()->json(["message"=>$message,$flight]);
    }

    public function destroy(string $id ,Request $request)
    {
        $flight = Complete_Flight::find($id);
        $flight->delete();

        // $name = 'Trip Delete!';
        // $details = "A new trip titled '{$flight->name}' has been deleted.";

        // $this->sendNotification($name,$details);

        $message = $request->translator->translate("Delete Succesfully ^^");
        return response()->json(["message"=>$message], 200);
    }
}
