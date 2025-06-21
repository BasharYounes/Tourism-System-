<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarRentalController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintResponseController;
use App\Http\Controllers\CompleteFlightController;
use App\Http\Controllers\CompleteFlightReservationController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EventFlightController;
use App\Http\Controllers\EventFlightReservationController;
use App\Http\Controllers\FavouriteCarController;
use App\Http\Controllers\FavouriteCompleteFlightController;
use App\Http\Controllers\FavouriteEventController;
use App\Http\Controllers\FavouriteHotelController;
use App\Http\Controllers\FavouriteRoomController;
use App\Http\Controllers\HotelCompleteFlightController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingCompleteFlightController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingHotelController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\RoomHotelCompleteFlightController;
use App\Http\Controllers\TaxiAirportController;
use App\Http\Controllers\TaxiCarController;
use App\Http\Controllers\TaxiReservationController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\UsersNotificationController;
use App\Models\EventFlightReservation;
use App\Models\FavouriteCar;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\FlightReservatioController;
use App\Http\Controllers\HotelReservatioController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AirLinesController;
use App\Http\Controllers\AirPortController;
use App\Http\Controllers\TourismCountryController;
use App\Http\Controllers\TransportController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    Route::post('/register-admin',[AdminController::class,'register']);//إنشاء حساب أدمن
    Route::post('/login-admin',[AdminController::class,'login']);//إنشاء حساب أدمن


    Route::post('/register',[AuthController::class,'register']);//إنشاء حساب
    Route::post('/login',[AuthController::class,'login']);//تسجيل دخول 
    Route::get('/auth/{provider}/redirect',[SocialiteController::class,'redirect']);//يأخذ المستخدم إلى صفحة غوغل
    Route::get('/auth/{provider}/callback',[SocialiteController::class,'callback']);//تسجيل دخول بحساب غوغل
    Route::post('password/email',  ForgotPasswordController::class);// كتابة الأيميل الذي سيرسل له كود التحقق
    Route::post('password/code/check', CodeCheckController::class);//التحقق من الكود 
    Route::post('password/reset', ResetPasswordController::class);//إعادة تعيين كلمة السر

    Route::middleware(['auth:admin','checkUserType:admin'])->group(function(){
        Route::delete('/logout-admin',[AdminController::class,'logout']);//إنشاء حساب أدمن
        ////////////////////////////////////////Flight             الرحلات 
    Route::post('/flight',[FlightReservatioController::class,'add_flight']);//إضافة رحلة
    Route::delete('/delete-flight/{id}',[FlightReservatioController::class,'destroy']);//حذف رحلة
    Route::post('/update-flight/{id}',[FlightReservatioController::class,'update']);//تعديل رحلة
    Route::get('/show-flight/{id}',[FlightReservatioController::class,'show']);//عرض تفاصيل رحلة
    Route::get('/all-reservation-flight',[FlightReservatioController::class,'get_all_reservation_flights']);//عرض كل الرحلات المحجوزة
    Route::get('/all-flight',[FlightReservatioController::class,'get_all_flights']);//عرض كل الرحلات المحجوزة

////////////////////////////////////////Hotel               فندق
    Route::post('/hotel',[HotelReservatioController::class,'add_hotel']);//إضافة فندق
    Route::delete('/delete-hotel/{id}',[HotelReservatioController::class,'destroy_hotel']);//حذف فندق
    Route::post('/update-hotel/{id}',[HotelReservatioController::class,'update_hotel']);//تعديل فندق
    Route::get('/show-hotel/{id}',[HotelReservatioController::class,'show']);//عرض تفاصيل فندق
    Route::get('/all-reservation-hotel',[HotelReservatioController::class,'get_all_reservation_hotels']);// عرض كل الفنادق المحجوزة
    Route::get('/all-hotel',[HotelReservatioController::class,'get_all_hotels']);// عرض كل الفنادق 
    Route::get('/show-reservation-hotel/{id}',[HotelReservatioController::class,'show_reservation_hotel']);// عرض تفاصيل حجز فندق 


////////////////////////////////////////Room                 غرفة
    Route::get('/all-rooms',[RoomController::class,'get_rooms']);// جلب كل الغرف
    Route::post('/room',[RoomController::class,'add_room']);//إضافة غرفة
    Route::delete('/delete-room/{id}',[RoomController::class,'destroy_room']);//حذف غرفة
    Route::post('/update-room/{id}',[RoomController::class,'update_room']);//تعديل غرفة

////////////////////////////////////////Airline             شركة الطيران
    Route::post('/airline',[AirLinesController::class,'store']);// إضافة شركة طيران 
    Route::delete('/delete-airline/{id}',[AirLinesController::class,'destroy']);//حذف شركة طيران
    Route::get('/airlines',[AirLinesController::class,'index']);//عرض كل شركات طيران

////////////////////////////////////////Airport             مطار
    Route::post('/airport',[AirPortController::class,'store']);// إضافة مطار  
    Route::delete('/delete-airport/{id}',[AirPortController::class,'destroy']);//حذف مطار 
    Route::get('/airports',[AirPortController::class,'index']);//عرض كل المطارات 

////////////////////////////////////////TourismCountry       منطقة سياحية
    Route::post('/tourismcountry',[TourismCountryController::class,'store']);//إضافة منطقة سياحية
    Route::delete('/delete-tourismcountry/{id}',[TourismCountryController::class,'destroy']);//حذف منطقة سياحية
    Route::post('/update-tourismcountry/{id}',[TourismCountryController::class,'update']);//تعديل منطقة سياحية
    Route::get('/show-tourismcountry/{id}',[TourismCountryController::class,'showAdmin']);//عرض تفاصيل منطقة سياحية
    Route::get('/all-tourismcountries',[TourismCountryController::class,'index']);// عرض كل المناطق السياحية  

///////////////////////////////////////Transport                وسيلة نقل
    Route::post('/transport',[TransportController::class,'store']);// إضافة وسيلة نقل  
    Route::delete('/delete-transport/{id}',[TransportController::class,'destroy']);//حذف وسيلة نقل
    Route::get('/transports',[TransportController::class,'index']);//عرض كل وسائل النقل

///////////////////////////////////////
    Route::get('/all-profiles',[ProfileController::class,'index']);// عرض كل الملفات الشخصية

///////////////////////////////////////complete flight         رحلة كاملة
    Route::get('/all-complete-flights',[CompleteFlightController::class,'get_all_complete_flights']);//جلب كل الرحلات الكاملة
    Route::post('/complete-flight',[CompleteFlightController::class,'store']);//إضافة رحلة كاملة
    Route::get('/show-complete-flight/{id}',[CompleteFlightController::class,'showAdmin']);//عرض تفاصيل رحلة كاملة
    Route::post('/update-complete-flight/{id}',[CompleteFlightController::class,'update']);//تعديل رحلة كاملة
    Route::delete('/delete-complete-flight/{id}',[CompleteFlightController::class,'destroy']);//حذف رحلة كاملة
    ///////////////////////////////////////event flight         رحلة حدث
    Route::get('/all-event-flights',[EventFlightController::class,'get_all_event_flights']);//جلب كل رحلات الحدث
    Route::post('/event-flight',[EventFlightController::class,'store']);//إضافة رحلة حدث
    Route::get('/show-event-flight/{id}',[EventFlightController::class,'showAdmin']);//عرض تفاصيل رحلة حدث
    Route::post('/update-event-flight/{id}',[EventFlightController::class,'update']);//تعديل رحلة حدث
    Route::delete('/delete-event-flight/{id}',[EventFlightController::class,'destroy']);//حذف رحلة حدث
    Route::get('/all-rservation-event-flights',[EventFlightReservationController::class,'get_all_resrvation_events']);//عرض تفاصيل رحلة حدث
    Route::get('/show-reservation-event-flight/{id}',[EventFlightReservationController::class,'show_resrvation_event']);//عرض تفاصيل حجز حدث


    ////////////////////////////////////////Hotel Complete light               فندق رحلة كاملة
    Route::post('/hotel-complete-flight',[HotelCompleteFlightController::class,'add_hotel']);// إضافة فندق رحلة كاملة
    Route::delete('/delete-hotel-complete-flight/{id}',[HotelCompleteFlightController::class,'destroy_hotel']);// حذف فندقرحلة كاملة
    Route::post('/update-hotel-complete-flight/{id}',[HotelCompleteFlightController::class,'update_hotel']);//تعديل فندق رحلة كاملة
    Route::get('/show-hotel-complete-flight/{id}',[HotelCompleteFlightController::class,'showAdmin']);//عرض تفاصيل فندق رحلة كاملة
    Route::get('/all-hotel-complete-flight',[HotelCompleteFlightController::class,'get_all_hotels']);// عرض كل فنادق الرحلة الكاملة

    ////////////////////////////////////////Room Hotel Complete Flight                  غرفة فندق لرحلة كاملة
    Route::post('/room-hotel-complete-flight',[RoomHotelCompleteFlightController::class,'add_room']);//إضافة غرفة
    Route::delete('/delete-room-hotel-complete-flight/{id}',[RoomHotelCompleteFlightController::class,'destroy_room']);//حذف غرفة
    Route::post('/update-room-hotel-complete-flight/{id}',[RoomHotelCompleteFlightController::class,'update_room']);//تعديل غرفة

///////////////////////////////////////////
    Route::post('/import-flights',[DataController::class,'importFlight']);
    Route::post('/import-hotels',[DataController::class,'importHotel']);
    Route::post('/import-complete-flight',[DataController::class,'importCompleteFlight']);
    Route::post('/import-driver',[DataController::class,'importDriver']);
    Route::post('/import-cars',[DataController::class,'importCars']);
    Route::post('/import-taxi-cars',[DataController::class,'importTaxiCars']);
    Route::post('/import-countries',[DataController::class,'importCountry']);

///////////////////////////////////////////
    Route::get('/all-drivers',[DriverController::class,'index']);// جلب كل السائقين
    Route::post('/driver',[DriverController::class,'store']);//إضافة سائق
    Route::delete('/delete-driver/{id}',[DriverController::class,'destroy']);//حذف سائق
    Route::post('/update-driver/{id}',[DriverController::class,'update']);//تعديل سائق
    Route::get('/show-driver/{id}',[DriverController::class,'showAdmin']);//تفاصيل سائق
///////////////////////////////////////////
    Route::get('/all-cars',[CarController::class,'index']);// جلب كل السارات
    Route::post('/car',[CarController::class,'store']);//إضافة سيارة
    Route::delete('/delete-car/{id}',[CarController::class,'destroy']);//حذف سيارة
    Route::post('/update-car/{id}',[CarController::class,'update']);//تعديل سيارة
    Route::get('/show-car/{id}',[CarController::class,'showAdmin']);//تفاصيل سيارة
    Route::get('/show--rental-car/{id}',[CarRentalController::class,'showAdmin']);//تفاصيل حجز سيارة
    Route::get('/all-rental-cars',[CarRentalController::class,'index']);//كل حجوزات السيارات

//////////////////////////////////////////إضافة تكسي
    Route::get('/all-taxi-cars',[TaxiCarController::class,'index']);//جلب كل سيارات التكسي
    Route::post('/taxi-car',[TaxiCarController::class,'store']);//إضافة سيارة تكسي
    Route::get('/show-taxi-car/{id}',[TaxiCarController::class,'showAdmin']);//عرض تفاصيل سيارة تكسي
    Route::post('/update-taxi-car/{id}',[TaxiCarController::class,'update']);//تعديل سيارة تكسي
    Route::delete('/delete-taxi-car/{id}',[TaxiCarController::class,'destroy']);//حذف سيارة تكسي
//////////////////////////////////////////تكسي المطار
    Route::get('/all-taxi-airport',[TaxiAirportController::class,'index']);// جلب كل سيارت تكسي المطار
    Route::post('/taxi-airport',[TaxiAirportController::class,'store']);//إضافة سيارة تكسي المطار
    Route::get('/show-taxi-airport/{id}',[TaxiAirportController::class,'showAdmin']);//عرض سيارة تكسي المطار
    Route::post('/update-taxi-airport/{id}',[TaxiAirportController::class,'update']);//تعديل سيارة تكسي المطار
    Route::delete('/delete-taxi-airport/{id}',[TaxiAirportController::class,'destroy']);//حذف سيارة تكسي المطار
    Route::get('/show-taxi-reservation/{id}',[TaxiReservationController::class,'show_reservation_taxi']);//عرض تفاصيل حجز تكسي مطار
    Route::get('/all-taxis-reservation/{id}',[TaxiReservationController::class,'index']);//عرض تفاصيل حجز تكسي مطار


///////////////////////////////////////// ردود على الشكاوي
    Route::post('/replied/{id}',[ComplaintResponseController::class,'store']);// إضافة رد على شكوى 
    Route::post('/update-replied/{id}',[ComplaintResponseController::class,'update']);// تعديل الرد      
    Route::delete('/delete-replied/{id}',[ComplaintResponseController::class,'destroy']);//حذف الرد 
    Route::get('/all-comments-admin',[ComplaintController::class,'index']);//جلب كل الشكاوي وردودها
/////////////////////////////////////////

    Route::post('/recharge/{id}',[RechargeController::class,'recharge']);//شحن رصيد من الأدمن
    Route::post('/test',[RechargeController::class,'test']);

    Route::get('/cash-admin',[AdminController::class,'check']);//استعلام الأدمن عن رصيده

    });















Route::group(['middleware'=>['auth:user','checkUserType:user']], function () {
    Route::get('/all-flights',[FlightReservatioController::class,'index']);
    Route::delete ('/auth/logoutuser',[AuthController::class,'logout']);//تسجيل خروج
    Route::delete ('/auth/delete-account/{id}',[AuthController::class,'destroy_account']);// حذف حساب
    Route::post('/search-flight',[FlightReservatioController::class,'search_flight']);//البحث عن رحلة
    Route::post('/search-hotel',[HotelReservatioController::class,'search_hotel']);//البحث عن فندق
    Route::post('/search-room/{id}',[HotelReservatioController::class,'search_room']);//البحث عن غرفة
    Route::get('/all-user-reservation-hotel',[HotelReservatioController::class,'index']);//فنادق المحجوزة الخاصة بالمستخدم
    // Route::post('/reservation-flight/{id}',[FlightReservatioController::class,'store']);//حجز رحلة
    // Route::post('/update-reservation-flight/{id}',[FlightReservatioController::class,'update_reservation_flight']);// تعديل حجز رحلة
    Route::post('/reservation-hotel/{id}',[HotelReservatioController::class,'store']);//حجز فندق
    Route::delete('/cancel-hotel/{id}',[HotelReservatioController::class,'cancel_reservation']);// إلغاء حجز فندق 
    // Route::post('/cancel-flight/{id}',[FlightReservatioController::class,'cancel_flight']);// إلغاء حجز رحلة
    Route::get('/showFlight/{id}',[FlightReservatioController::class,'show']);//عرض تفاصيل رحلة
    Route::get('/showHotel/{id}',[HotelReservatioController::class,'show']);//عرض تفاصيل فندق
    Route::get('/showTourismcountry/{id}',[TourismCountryController::class,'show']);//عرض تفاصيل منطقة سياحية
    Route::post('/rating-flight/{id}',[RatingController::class,'store']);//تقييم رحلة 
    Route::post('/update-rating-flight/{id}',[RatingController::class,'update']);//تعديل تقييم رحلة 
    Route::delete('/delete-rating-flight/{id}',[RatingController::class,'destroy']);//حذف تقييم رحلة 
    Route::post('/rating-complete-flight/{id}',[RatingCompleteFlightController::class,'store']);//تقييم رحلة كاملة
    Route::post('/update-rating-complete-flight/{id}',[RatingCompleteFlightController::class,'update']);//تعديل تقييم رحلة كاملة 
    Route::delete('/delete-rating-complete-flight/{id}',[RatingCompleteFlightController::class,'destroy']);//حذف تقييم رحلة كاملة 
    Route::get('/get-rating-complete-flight/{id}',[RatingCompleteFlightController::class,'get_rating']);//جلب تقييم رحلة كاملة 
    Route::post('/rating-hotel/{id}',[RatingHotelController::class,'store']);//تقييم فندق 
    Route::post('/update-rating-hotel/{id}',[RatingHotelController::class,'update']);//تعديل تقييم فندق  
    Route::delete('/delete-rating-hotel/{id}',[RatingHotelController::class,'destroy']);//حذف تقييم فندق 
    Route::get('/get-rating-hotel/{id}',[RatingHotelController::class,'get_rating']);//جلب تقييم فندق    
    Route::get('/postFlight',[FlightReservatioController::class,'post']);//جلب الرحلات السابقة
    Route::get('/currentFlight',[FlightReservatioController::class,'current']);//جلب الرحلات الحالية
    Route::get('/futureFlight',[FlightReservatioController::class,'future']);//جلب الرحلات الحالية   
    Route::post('/select-place',[FlightReservatioController::class,'select_place']);//جلب أماكن سياحية حسب نشاط معين
    Route::get('/show-profile',[ProfileController::class,'show']);// عرض تفاصيل الملف الشخصي
    Route::post('/update-profile',[ProfileController::class,'update']);//  تعديل الملف الشخصي
    Route::post('/change-password',[ProfileController::class,'changePassword']);//تغيير كلمة السر 
    Route::get('/show-CompleteFlight/{id}',[CompleteFlightController::class,'show']);//عرض تفاصيل رحلة كاملة
    Route::post('/reservation-complete-flight/{id}',[CompleteFlightReservationController::class,'store']);//حجز رحلة كاملة
    Route::post('/update-reservation-complete-flight/{id}',[CompleteFlightReservationController::class,'update']);// تعديل حجز رحلة كاملة
    Route::delete('/delete-reservation-complete-flight/{id}',[CompleteFlightReservationController::class,'destroy']);// حذف رحلة كاملة
    Route::post('/search-CompleteFlight',[CompleteFlightReservationController::class,'search']);//بحث عن رحلة كاملة
    Route::get('/get-cash',[RechargeController::class,'get_cash']);//استعلام المستخدم عن رصيده
    Route::post('/reservation-taxiAirport',[TaxiReservationController::class,'store']);// حجز تكسي المطار 
    Route::post('/update-reservation-taxiAirport/{id}',[TaxiReservationController::class,'update']);// تعديل حجز تكسي المطار  
    Route::delete('/delete-reservation-taxiAirport/{id}',[TaxiReservationController::class,'destroy']);//حذف حجز تكسي المطار   
    Route::post('/search-taxiAirport',[TaxiReservationController::class,'search_taxi']);// البحث عن تكسي المطار 
    Route::get('/show-reservation-taxiAirport/{id}',[TaxiReservationController::class,'show']);//عرض تفاصيل حجز تكسي مطار
    Route::get('/search-Airport',[TaxiReservationController::class,'search_airport']);// البحث عن مطار 
    Route::post('/rentals-car',[CarRentalController::class,'store']);// أستئجار سيارة
    Route::post('/search-car',[CarRentalController::class,'search_car']);// البحث عن سيارة
    Route::post('/update-rentals-car/{id}',[CarRentalController::class,'update']);// تعديل أستئجار سيارة    
    Route::delete('/delete-rentals-car/{id}',[CarRentalController::class,'destroy']);//حذف أستئجار سيارة 
    Route::delete('/show-rentals-car/{id}',[CarRentalController::class,'show']);//حذف أستئجار سيارة 
    Route::get('/all-comments',[ComplaintController::class,'index']);// كل الشكاوي مع ردودها    
    Route::get('/all-user-comments',[ComplaintController::class,'get_user_complaints']);// كل الشكاوي المستخدم  
    Route::post('/comment',[ComplaintController::class,'store']);// إضافة شكوى 
    Route::put('/update-comment/{id}',[ComplaintController::class,'update']);// تعديل شكوى      
    Route::delete('/delete-comment/{id}',[ComplaintController::class,'destroy']);//حذف شكوى 
    Route::post('/text-translate',[TranslateController::class,'translate']);// ترجمة نص  
    Route::get('/show-EventFlight/{id}',[EventFlightController::class,'show']);//عرض تفاصيل رحلة حدث
    Route::post('/reservation-event-flight/{id}',[EventFlightReservationController::class,'store']);//حجز رحلة حدث
    Route::post('/update-reservation-event-flight/{id}',[EventFlightReservationController::class,'update']);// تعديل حجز رحلة حدث
    Route::delete('/delete-reservation-event-flight/{id}',[EventFlightReservationController::class,'destroy']);// حذف رحلة حدث
    Route::post('/search-EventFlight',[EventFlightReservationController::class,'search']);//بحث عن رحلة حدث
    Route::delete('/delete-favourite-flight/{id}',[FavouriteCompleteFlightController::class,'destroy']);// حذف رحلة كاملة من المفضلة 
    Route::post('/favourite-flight/{id}',[FavouriteCompleteFlightController::class,'store']);//إضافة رحلة كاملة للمفضلة   
    Route::delete('/delete-favourite-car/{id}',[FavouriteCarController::class,'destroy']);// حذف  سيارة من المفضلة 
    Route::post('/favourite-car/{id}',[FavouriteCarController::class,'store']);//إضافة سيارة للمفضلة      
    Route::post('/favourite-hotel/{id}',[FavouriteHotelController::class,'store']);//إضافة فندق للمفضلة  
    Route::delete('/delete-favourite-hotel/{id}',[FavouriteHotelController::class,'destroy']);// حذف  فندق من المفضلة 
    Route::post('/favourite-room/{id}',[FavouriteRoomController::class,'store']);//إضافة غرفة للمفضلة  
    Route::delete('/delete-favourite-room/{id}',[FavouriteRoomController::class,'destroy']);// حذف  غرفة من المفضلة 
    Route::post('/favourite-event/{id}',[FavouriteEventController::class,'store']);//إضافة حدث للمفضلة  
    Route::delete('/delete-favourite-event/{id}',[FavouriteEventController::class,'destroy']);// حذف  حدث من المفضلة 
    Route::get('/all-user-reservation-taxi',[TaxiReservationController::class,'get_user_reservation']);//جلب حجوزات المطار الخاصة بالمستخدم
    Route::get('/all-user-reservation-event',[EventFlightReservationController::class,'index']);//جلب حجوزات الأحداث الخاصة بالمستخدم
    Route::get('/all-user-reservation-car',[CarRentalController::class,'get_user_reservation']);//جلب استئجارات السيارات الخاصة بالمستخدم
    Route::get('/notifications', [NotificationController::class, 'index']);
    
});



