<?php

namespace App\Http\Controllers;

use App\Models\AirLineCash;
use App\Models\AirLines;
use App\Models\AirPort;
use App\Models\Car;
use App\Models\Complete_Flight;
use App\Models\Driver;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\Hotel_complete_flight;
use App\Models\HotelCash;
use App\Models\Room;
use App\Models\TaxiCar;
use App\Models\TourismCountry;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function importFlight()
    {
        $file =  file_get_contents(public_path('flights (2).json'));
        $data = json_decode($file,true);

        foreach ($data as  $dataFlight) {
          $flight = Flight::create([
            "flight_number" => $dataFlight['flight_number'],
            "airline" => $dataFlight['airline'],
            "website" => $dataFlight['website'],
            "departure_airport" => $dataFlight['departure_airport'],
            "departure_time" => $dataFlight['departure_time'],
            "departure_date" => $dataFlight['departure_date'],
            "arrival_airport" => $dataFlight['arrival_airport'],
            "arrival_time" => $dataFlight['arrival_time'],
            "duration" => $dataFlight['duration'],
            "price" => $dataFlight['price'],
            "reservation_type" => $dataFlight['class'],
            "available_place" => $dataFlight['available_seats'],
            "transport_id" => '1'
          ]);
          $airline = AirLines::where('name',$flight->airline)->first();
          $airport = AirPort::where('name',$flight->departure_airport)->first();
          $airport1 = AirPort::where('name',$flight->arrival_airport)->first();
          if(!$airline)
          {
            $url = $dataFlight['airline_image'];

            $client = new Client(['verify' => false]);
            $response = $client->get($url);
            $imageContent = $response->getBody()->getContents();
  
            if ($imageContent===false) {
              return response()->json(['err' => 'false']);
            }
  
           // Extract the file extension from the URL
          $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
  
          // Generate a unique file name with extension
          $filename = time() . '.' . "jpg";
  
          $save = file_put_contents(storage_path("app/public/images/$filename"),$imageContent);

          if(!$save)
          {
            return response()->json(['mess'=>'err not save']);
          }
            AirLines::create([
              "name" => $flight->airline,
              "photo" => "storage/images/".$filename,
              "transport_id" => "1"
            ]);
            AirLineCash::create([
              "airline_name" => $flight->airline
            ]);
          }
          if (!$airport) {
            $url1 = $dataFlight['airport_image1'];

            $client1 = new Client(['verify' => false]);
            $response1 = $client1->get($url1);
            $imageContent1 = $response1->getBody()->getContents();
  
            if ($imageContent1===false) {
              return response()->json(['err' => 'false']);
            }
  
           // Extract the file extension from the URL
          $extension = pathinfo(parse_url($url1, PHP_URL_PATH), PATHINFO_EXTENSION);
  
          // Generate a unique file name with extension
          $filename1 = time() . '.' . "jpg";
  
          $save1 = file_put_contents(storage_path("app/public/images/$filename1"),$imageContent1);

          if(!$save1)
          {
            return response()->json(['mess'=>'err not save']);
          }
            AirPort::create([
              "name" => $flight->departure_airport,
              "country" =>$flight->departure_airport,
              "city" => $flight->departure_airport,
              "photo" => "storage/images/".$filename1,
            ]);
          }
          if (!$airport1) {
            $url2 = $dataFlight['airport_image2'];

            $client2 = new Client(['verify' => false]);
            $response2 = $client2->get($url1);
            $imageContent2 = $response2->getBody()->getContents();
  
            if ($imageContent2===false) {
              return response()->json(['err' => 'false']);
            }
  
           // Extract the file extension from the URL
          $extension = pathinfo(parse_url($url2, PHP_URL_PATH), PATHINFO_EXTENSION);
  
          // Generate a unique file name with extension
          $filename2 = time() . '.' . "jpg";
  
          $save2 = file_put_contents(storage_path("app/public/images/$filename2"),$imageContent2);

          if(!$save2)
          {
            return response()->json(['mess'=>'err not save']);
          }
            AirPort::create([
              "name" => $flight->arrival_airport,
              "country" =>$flight->arrival_airport,
              "city" => $flight->arrival_airport,
              "photo" => "storage/images/".$filename2,
            ]);
          }
        }
        return response()->json('suc');
    }

    public function importHotel()
    {
      set_time_limit(2100);

      $file = file_get_contents(public_path('hotels.json'));
      $data = json_decode($file,true);
      $i = 0;
      foreach ($data as $dataHotel) {
        
          $url = $dataHotel['photo'];

          $client = new Client(['verify' => false]);
          $response = $client->get($url);
          $imageContent = $response->getBody()->getContents();

          if ($imageContent===false) {
            return response()->json(['err' => 'false']);
          }

         // Extract the file extension from the URL
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

        // Generate a unique file name with extension
        $filename = time() . '.' . "jpg";

        $save = file_put_contents(storage_path("app/public/images/$filename"),$imageContent);

        if(!$save)
        {
          return response()->json(['mess'=>'err not save']);
        }
            $hotel= Hotel::create([
              "name" => $dataHotel['name'],
              "address" => $dataHotel['address'],
              "city" => $dataHotel['city'],
              "country" => $dataHotel['country'],
              "star_rating" => $dataHotel['star_rating'],
              "photo" =>  "storage/images/".$filename ,
              "url" => $dataHotel['url'],
              "rating_average" => $dataHotel['rating_average'],
             ]);
             $i++;

             foreach ($dataHotel['rooms'] as $room) {
              $url1 = $room['photo'];

              $client1 = new Client(['verify' => false]);
              $response1= $client1->get($url1);
              $imageContent1 = $response1->getBody()->getContents();
    
              if ($imageContent1===false) {
                return response()->json(['err' => 'false']);
              }
    
             // Extract the file extension from the URL
            $extension = pathinfo(parse_url($url1, PHP_URL_PATH), PATHINFO_EXTENSION);
    
            // Generate a unique file name with extension
            $filename1 = time() . '.' . "jpg";
    
            $save1 = file_put_contents(storage_path("app/public/images/$filename1"),$imageContent1);

            if(!$save1)
            {
              return response()->json(['mess'=>'err not save']);
            }
              Room::create([
                "capacety" => $room['capacity'],
                "price" => $room['price'],
                "reservation_type" => $room['reservation_type'],
                "photo" => "storage/images/".$filename1,
                "hotel_id" => $hotel['id'],
              ]);
             }
             
        }
      // Return a success response with the file path
      return response()->json([
                'success' => true, 
                'message' => 'Successfully'
              ]);
    }

    public function importCompleteFlight()
    {
      $file = file_get_contents(public_path('complete_travel_package.json'));
      $data = json_decode($file,true);

      foreach ($data as $data_com_fli) {

        $is_hotel = Hotel_complete_flight::where('name',$data_com_fli['hotel'])->first();
        if(!$is_hotel)
        {
          $hotel = Hotel_complete_flight::create([
            "name" =>$data_com_fli['hotel'],
            "address" =>"456 Ocean Avenue",
            "city" =>"Los Angeles",
            "country" =>"USA",
            "star_rating" =>4,
            "rating_average" =>2.5,
          ]);
          
        $activitiesJson = json_encode($data_com_fli['activities']);
        $inclusionsJson = json_encode($data_com_fli['inclusions']);

        Complete_Flight::create([
          "name" => $data_com_fli['name'],
          "destination" => $data_com_fli['destination'],
          "travel_dates_departure" => $data_com_fli['travel_dates_departure'],
          "travel_dates_return" => $data_com_fli['travel_dates_return'],
          "reservation_type" => $data_com_fli['type'],
          "hotel_id" =>$hotel['id'],
          "nights" => $data_com_fli['nights'],
          "activities" => $activitiesJson,
          "inclusions" => $inclusionsJson,
          "price" => $data_com_fli['price'],
          "available_place" => $data_com_fli['available_spots'],
          "transport_company" => $data_com_fli['transport_company'],
          "transport_id" =>"1"
        ]);
        }

      }
      return response()->json("suc");
    }


    public function importDriver()
    {
      $file = file_get_contents(public_path('Drivers.json'));
      $data = json_decode($file,true);
      foreach ($data as $driver) {
        Driver::create([
          "name" => $driver['name'],
          "mobile" => $driver['phone_number'],
          "birth_date" => $driver['date_of_birth'],
          "nationality" => $driver['nationality'],
        ]);
      }
      return response()->json("suc");
    }

    public function importCars()
    {
      $file = file_get_contents(public_path('cars.json'));
      $data = json_decode($file,true);
      foreach ($data as $car) {
        $url = $car['car_image'];

        $true = Car::where('car_number',$car['car_number'])->first();

        if(!$true)
        {
          $client = new Client(['verify' => false]);
        $response= $client->get($url);
        $imageContent = $response->getBody()->getContents();

        if ($imageContent===false) {
          return response()->json(['err' => 'false']);
        }

       // Extract the file extension from the URL
      $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

      // Generate a unique file name with extension
      $filename = time() . '.' . "jpg";

      $save = file_put_contents(storage_path("app/public/images/$filename"),$imageContent);

      if(!$save)
      {
        return response()->json(['mess'=>'err not save']);
      }

        Car::create([
          "type_car" => $car['car_type'],
          "color" => $car['car_color'],
          "monthly_rent" => $car['car_cost'],
          "class" => $car['car_classification'],
          "car_number" => $car['car_number'],
          "photo" => "storage/images/".$filename,
          "people_number" => $car['car_capacity'],
        ]);
        }
        
      }
      return response()->json("suc");
    }
    
    public function importTaxiCars()
    {
      $file = file_get_contents(public_path('taxis.json'));
      $data = json_decode($file,true);
      foreach ($data as $car) {
        $url = $car['image_url'];

        $true = Car::where('car_number',$car['car_number'])->first();

        if(!$true)
        {
          $client = new Client(['verify' => false]);
        $response= $client->get($url);
        $imageContent = $response->getBody()->getContents();

        if ($imageContent===false) {
          return response()->json(['err' => 'false']);
        }

       // Extract the file extension from the URL
      $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

      // Generate a unique file name with extension
      $filename = time() . '.' . "jpg";

      $save = file_put_contents(storage_path("app/public/images/$filename"),$imageContent);

      if(!$save)
      {
        return response()->json(['mess'=>'err not save']);
      }

        TaxiCar::create([
          "type_car" => $car['car_type'],
          "color" => $car['car_color'],
          "car_number" => $car['car_number'],
          "photo" => "storage/images/".$filename,
        ]);
        }
        
      }
      return response()->json("suc");
    }

    public function importCountry()
    {
      $file = file_get_contents(public_path('countries.json'));
      $data = json_decode($file,true);

      foreach ($data as $dataCountry) {
        $url = $dataCountry['country_image'];
        $client = new Client(['verify' => false]);
        $response = $client->get($url);
        $imageContent = $response->getBody()->getContents();

        if ($imageContent===false) {
          return response()->json(['err' => 'false']);
        }
        $filename = time() . '.' . "jpg";

        $save = file_put_contents(storage_path("app/public/images/$filename"),$imageContent);

        if(!$save)
        {
          return response()->json(['mess'=>'err not save']);
        }

        $url1 = $dataCountry['dish_image'];
        $client1 = new Client(['verify' => false]);
        $response1 = $client1->get($url1);
        $imageContent1 = $response1->getBody()->getContents();

        if ($imageContent1===false) {
          return response()->json(['err' => 'false']);
        }
        $filename1 = time() . '.' . "jpg";

        $save1 = file_put_contents(storage_path("app/public/images/$filename1"),$imageContent1);

        if(!$save1)
        {
          return response()->json(['mess'=>'err not save']);
        }

        TourismCountry::create([
          "name" => $dataCountry['country'],
          "country" => $dataCountry['country'],
          "city" => $dataCountry['country'],
          "photo" => "storage/images/".$filename,
          "photo_dish" => "storage/images/".$filename1,
          "famous" => $dataCountry['famous_dish'],
        ]);
      }
      return response()->json("suc");
    }
}