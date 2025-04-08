<?php

namespace Modules\Booking\Controllers;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mockery\Exception;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Events\EnquirySendEvent;
use Modules\Booking\Events\SetPaidAmountEvent;
use Modules\Booking\Models\BookingPassenger;
use Modules\Booking\Models\PassengerBooking;
use Modules\User\Events\SendMailUserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Enquiry;
use App\Helpers\ReCaptchaEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Passenger;

use Illuminate\Support\Facades\DB;


// use Modules\Booking\Entities\Passenger;


class BookingController extends \App\Http\Controllers\Controller
{
    use AuthorizesRequests;

    protected $PassengerBooking;
    protected $booking;
    protected $enquiryClass;
    protected $bookingInst;

    public function __construct(Booking $booking, Enquiry $enquiryClass , PassengerBooking $PassengerBooking)
    {
        $this->booking = $booking;
        $this->enquiryClass = $enquiryClass;
        $this->PassengerBooking = $PassengerBooking;
    }

    protected function validateCheckout($code)
    {

        if (!is_enable_guest_checkout() and !Auth::check()) {
            $error = __("You have to login in to do this");
            if (\request()->isJson()) {
                return $this->sendError($error)->setStatusCode(401);
            }
            return redirect(route('login', ['redirect' => \request()->fullUrl()]))->with('error', $error);
        }

        $booking = $this->booking::where('code', $code)->first();

        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }
        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            abort(404);
        }
        return true;
    }

    public function checkout($code)
    {
        $res = $this->validateCheckout($code);
        if ($res !== true) return $res;

        $booking = $this->bookingInst;

        if (!in_array($booking->status, ['draft', 'unpaid'])) {
            return redirect('/');
        }

        $is_api = request()->segment(1) == 'api';

        $data = [
            'page_title' => __('Checkout'),
            'booking'    => $booking,
            'service'    => $booking->service,
            'gateways' => get_available_gateways(),
            'user'       => auth()->user(),
            'is_api'     => $is_api
        ];
        return view('Booking::frontend/checkout', $data);
    }

    public function checkStatusCheckout($code)
    {
        $booking = $this->booking::where('code', $code)->first();
        $data = [
            'error'    => false,
            'message'  => '',
            'redirect' => ''
        ];
        if (empty($booking)) {
            $data = [
                'error'    => true,
                'redirect' => url('/')
            ];
        }
        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            $data = [
                'error'    => true,
                'redirect' => url('/')
            ];
        }
        if (!in_array($booking->status, ['draft', 'unpaid'])) {
            $data = [
                'error'    => true,
                'redirect' => url('/')
            ];
        }
        return response()->json($data, 200);
    }

    protected function validateDoCheckout()
    {

        $request = \request();
        if (!is_enable_guest_checkout() and !Auth::check()) {
            return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        }

        if (auth()->user() && !auth()->user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user') == 1) {
            return $this->sendError(__("You have to verify email first"), ['url' => url('/email/verify')]);
        }
        /**
         * @param Booking $booking
         */
        // $validator = Validator::make($request->all(), [
        //     'code' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return $this->sendError('', ['errors' => $validator->errors()]);
        // }
        // $bookingID = $request->input('bookingID');
        // $booking = $this->booking::where('id', $bookingID)->first();
        $code = $request->input('code');
        $booking = $this->booking::where('code', $code)->first();

        if($booking && $booking != null) {
            $this->bookingInst = $booking;

            if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
                abort(404);
            }
        }else{
            $this->bookingInst = new Booking();
        }

        // if (empty($booking)) {
        //     abort(404);
        // }

        return true;
    }

    public function bookPassengerDetails (Request $request)
    {
        // var_dump($request->all());
        // die();
        $rules = [
            'whichPassanger' => 'required',
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'passport_number' => 'required',
            'issue_date' => 'required',
            'expiry_date' => 'required',
            'city' => 'required',
            'country' => 'required',
            'meal_preference' => 'required',
            'passportfront' => 'required',
            'passportback' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        // Fetch existing booking if exists
        // $bookingDetails = PassengerBooking::where('code', $request->input('code'))
        $bookingDetails = PassengerBooking::where('update_user', $request->input('update_user'))
            ->where('passenger', $request->input('whichPassanger'))
            ->first();

        // Use existing booking or create a new instance
        $booking = $bookingDetails ?? new PassengerBooking();


        // $booking->passenger = $request->input('whichPassanger');
        $user = auth()->user();
        $booking->passenger = trim($request->input('whichPassanger'));

        $booking->title = $request->input('title');
        $booking->create_user = $user;
        $booking->first_name = $request->input('first_name');
        $booking->last_name = $request->input('last_name');
        $booking->dob = $request->input('dob');
        $booking->passport_number = $request->input('passport_number');
        $booking->issue_date = $request->input('issue_date');
        $booking->expiry_date = $request->input('expiry_date');
        $booking->pan_number = $request->input('pan_number');
        $booking->city = $request->input('city');
        $booking->country = $request->input('country');
        $booking->meal_preference = $request->input('meal_preference');
        $booking->code = $request->input('code');

         // Handle file uploads and store them on Cloudinary
        $uploadedFiles = [];
        // var_dump("-1-----");
        if ($request->hasFile('passportfront')) {
            //  var_dump("--2----");
            try {
                $passportFront = $request->file('passportfront');
                // $uploadedFiles['passport_front'] = cloudinary()->upload($passportFront->getRealPath(), [
                //     'folder' => 'Travels2020',
                // ])->getSecurePath();
                $uploadedFiles['passport_front'] = cloudinary()->upload($passportFront->getRealPath(), [
                    'folder' => 'Travels2020/passport',
                ])->getSecurePath();

        //         var_dump("--3----");
        //         var_dump($uploadedFiles['passport_front']);
        // die();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        if ($request->hasFile('passportback')) {
            try{
            $passportBack = $request->file('passportback');
            $uploadedFiles['passport_back'] = Cloudinary::upload($passportBack->getRealPath(),[
                'folder' => 'travels2020/passport',
            ])->getSecurePath();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        if ($request->hasFile('pancard')) {
            try{
            $panCard = $request->file('pancard');
            $uploadedFiles['pan_card'] = Cloudinary::upload($panCard->getRealPath(), [
                'folder' => 'travels2020/pancard',
            ])->getSecurePath();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

                 // Assign file URLs to booking (stored on Cloudinary)
        $booking->passport_front = $uploadedFiles['passport_front'] ?? null;
        $booking->passport_back = $uploadedFiles['passport_back'] ?? null;
        $booking->pan_card = $uploadedFiles['pan_card'] ?? null;

        $booking->save();

        return $this->sendSuccess([
            'url' => $booking->getDetailUrl()
        ], __("You Passenger details has been processed successfully"));

    }

    public function doCheckout(Request $request)
    {
        /**
         * @var $booking Booking
         * @var $user User
         */

        $res = $this->validateDoCheckout();
        if ($res !== true) return $res;
        $user = auth()->user();

        $booking = $this->bookingInst;


        // if (!in_array($booking->status, ['draft', 'unpaid'])) {
        //     return $this->sendError('', [
        //         'url' => $booking->getDetailUrl()
        //     ]);
        // }
        $service = $booking->service;
        if (empty($service)) {
            return $this->sendError(__("Service not found"));
        }



        $is_api = request()->segment(1) == 'api';

        /**
         * Google ReCapcha
         */
        if (!$is_api and ReCaptchaEngine::isEnable() and setting_item("booking_enable_recaptcha")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                return $this->sendError(__("Please verify the captcha"));
            }
        }

        $messages = [];
        $rules = [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'country'         => 'required',
            // 'term_conditions' => 'required',
        ];


        $confirmRegister = $request->input('confirmRegister');
        if (!empty($confirmRegister)) {
            $rules['password'] = 'required|string|confirmed|min:6|max:255';
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users')];
            $messages['password.confirmed'] = __('The password confirmation does not match');
            $messages['password.min'] = __('The password must be at least 6 characters');
        }

        $how_to_pay = $request->input('how_to_pay', '');
        $credit = $request->input('credit', 0);
        // $payment_gateway = $request->input('payment_gateway');

        // require payment gateway except pay full
        if (empty(floatval($booking->deposit)) || $how_to_pay == 'deposit' || !auth()->check()) {
            // $rules['payment_gateway'] = 'required';
        }

        if (auth()->check()) {
            if ($credit > $user->balance) {
                return $this->sendError(__("Your credit balance is :amount", ['amount' => $user->balance]));
            }
        } else {
            // force credit to 0 if not login
            $credit = 0;
        }



        $rules = $service->filterCheckoutValidate($request, $rules);
        if (!empty($rules)) {

            // $messages['term_conditions.required'] = __('Term conditions is required field');
            // $messages['payment_gateway.required'] = __('Payment gateway is required field');


            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return $this->sendError('', ['errors' => $validator->errors()]);
            }
        }

        $wallet_total_used = credit_to_money($credit);
        if ($wallet_total_used > $booking->total) {
            $credit = money_to_credit($booking->total, true);
            $wallet_total_used = $booking->total;
        }

        // if ($res = $service->beforeCheckout($request, $booking)) {
        //     return $res;
        // }


        if ($how_to_pay == 'full' and !empty($booking->deposit)) {
            $booking->addMeta('old_deposit', $booking->deposit ?? 0);
        }
        $oldDeposit = $booking->getMeta('old_deposit', 0);
        if (empty(floatval($booking->deposit)) and !empty(floatval($oldDeposit))) {
            $booking->deposit = $oldDeposit;
        }

        // Handle file uploads and store them on Cloudinary
        $uploadedFiles = [];
        // var_dump("-1-----");
        if ($request->hasFile('passportfront')) {
            //  var_dump("--2----");
            try {
                $passportFront = $request->file('passportfront');
                // $uploadedFiles['passport_front'] = cloudinary()->upload($passportFront->getRealPath(), [
                //     'folder' => 'Travels2020',
                // ])->getSecurePath();
                $uploadedFiles['passport_front'] = cloudinary()->upload($passportFront->getRealPath(), [
                    'folder' => 'Travels2020/passport',
                ])->getSecurePath();

        //         var_dump("--3----");
        //         var_dump($uploadedFiles['passport_front']);
        // die();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        if ($request->hasFile('passportback')) {
            try{
            $passportBack = $request->file('passportback');
            $uploadedFiles['passport_back'] = Cloudinary::upload($passportBack->getRealPath(),[
                'folder' => 'travels2020/passport',
            ])->getSecurePath();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        if ($request->hasFile('pancard')) {
            try{
            $panCard = $request->file('pancard');
            $uploadedFiles['pan_card'] = Cloudinary::upload($panCard->getRealPath(), [
                'folder' => 'travels2020/pancard',
            ])->getSecurePath();
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        //  var_dump($uploadedFiles,"check a thiss");
        // die();
        // Normal Checkout
           $passenger = new Passenger();
        $booking->passenger = $request->input('whichPassanger');
        $booking->title = $request->input('title');
        $booking->first_name = $request->input('first_name');
        $booking->last_name = $request->input('last_name');
        $booking->dob = $request->input('dob');
        $booking->passport_number = $request->input('passport_number');
        $booking->issue_date = $request->input('issue_date');
        $booking->expiry_date = $request->input('expiry_date');
        $booking->pan_number = $request->input('pan_number');
        $booking->city = $request->input('city');
        $booking->country = $request->input('country');
        $booking->meal_preference = $request->input('meal_preference');

         // Assign file URLs to booking (stored on Cloudinary)
        $booking->passport_front = $uploadedFiles['passport_front'] ?? null;

        $booking->passport_back = $uploadedFiles['passport_back'] ?? null;
        $booking->pan_card = $uploadedFiles['pan_card'] ?? null;

        // $booking->gateway = $payment_gateway;
        $booking->wallet_credit_used = floatval($credit);
        $booking->wallet_total_used = floatval($wallet_total_used);
        $booking->pay_now = floatval((int)$booking->deposit == null ? $booking->total : (int)$booking->deposit);

        // If using credit
        if ($booking->wallet_total_used > 0) {
            if ($how_to_pay == 'full') {
                $booking->deposit = 0;
                $booking->pay_now = $booking->total;
            } elseif ($how_to_pay == 'deposit') {
                // case guest input credit more than "pay deposit" need to pay
                // Ex : pay deposit 10$ but guest input 20$ -> minus credit balance = 10$
                if ($wallet_total_used > $booking->deposit) {
                    $wallet_total_used = $booking->deposit;
                    $booking->wallet_total_used = floatval($wallet_total_used);
                    $booking->wallet_credit_used = money_to_credit($wallet_total_used, true);
                }

            }

            $booking->pay_now = max(0, $booking->pay_now - $wallet_total_used);
            $booking->paid = $booking->wallet_total_used;
        } else {
            if ($how_to_pay == 'full') {
                $booking->deposit = 0;
                $booking->pay_now = $booking->total;
            }
        }

        // $gateways = get_payment_gateways();
        // if ($booking->pay_now > 0) {
        //     $gatewayObj = new $gateways[$payment_gateway]($payment_gateway);
        //     if (!empty($rules['payment_gateway'])) {
        //         if (empty($gateways[$payment_gateway]) or !class_exists($gateways[$payment_gateway])) {
        //             return $this->sendError(__("Payment gateway not found"));
        //         }
        //         if (!$gatewayObj->isAvailable()) {
        //             return $this->sendError(__("Payment gateway is not available"));
        //         }
        //     }
        // }

        if ($booking->wallet_credit_used && auth()->check()) {
            try {
                $transaction = $user->withdraw($booking->wallet_credit_used, [
                    'wallet_total_used' => $booking->wallet_total_used
                ], $booking->id);

            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
            $booking->wallet_transaction_id = $transaction->id;
        }
        $booking->save();

            //  event(new VendorLogPayment($booking));

        if (Auth::check()) {
            $user = auth()->user();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->city = $request->input('city');
            $user->country = $request->input('country');
            $user->save();
        } elseif (!empty($confirmRegister)) {
            $user = new User();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->city = $request->input('city');
            $user->country = $request->input('country');
            $user->password = bcrypt($request->input('password'));
            $user->status = 'publish';
            $user->save();

            event(new Registered($user));
            Auth::loginUsingId($user->id);
            try {
                event(new SendMailUserRegistered($user));
            } catch (\Matrix\Exception $exception) {
                Log::warning("SendMailUserRegistered: " . $exception->getMessage());
            }
            $user->assignRole(setting_item('user_role'));
        }

        $booking->addMeta('locale', app()->getLocale());
        $booking->addMeta('how_to_pay', $how_to_pay);

        // Save Passenger
        $this->savePassengers($booking, $request);
        $booking->status = $booking::PAID;
        $booking->save();
        event(new BookingCreatedEvent($booking));
        return $this->sendSuccess([
            'url' => $booking->getDetailUrl()
        ], __("You payment has been processed successfully"));



        // if ($res = $service->afterCheckout($request, $booking)) {
        //     return $res;
        // }

        // if ($booking->pay_now > 0) {
        //     try {
        //         // $gatewayObj->process($request, $booking, $service);
        //     } catch (Exception $exception) {
        //         return $this->sendError($exception->getMessage());
        //     }
        // } else {
        //     if ($booking->paid < $booking->total) {
        //         $booking->status = $booking::PARTIAL_PAYMENT;
        //     } else {
        //         $booking->status = $booking::PAID;
        //     }

        //     if (!empty($booking->coupon_amount) and $booking->coupon_amount > 0 and $booking->total == 0) {
        //         $booking->status = $booking::PAID;
        //     }

        //     $booking->save();
        //     event(new BookingCreatedEvent($booking));
        //     return $this->sendSuccess([
        //         'url' => $booking->getDetailUrl()
        //     ], __("You payment has been processed successfully"));
        // }
    }

    public function getPassengerDetails($code,$passenger)
    {
         $bookingDetails = DB::table('passenger_booking_details')
                    ->where('code', $code)
                    ->where('passenger', $passenger)
                    ->get();

        if ($bookingDetails) {
            return response()->json(['success' => true, 'data' => $bookingDetails]);
        } else {
            return response()->json(['success' => false, 'message' => 'bookingDetails not found'], 404);
        }
    }

    protected function savePassengers(Booking $booking, Request $request)
    {
        if ($booking->service && method_exists($booking->service, 'savePassengers') ) {
            call_user_func([$booking->service, 'savePassengers'], $booking, $request);
            return;
        }
        if ($totalPassenger = $booking->calTotalPassenger()) {
            $booking->passengers()->delete();
            $input = $request->input('passengers', []);
            for ($i = 1; $i <= $totalPassenger; $i++) {
                $passenger = new BookingPassenger();
                $data = [
                    'booking_id' => $booking->id,
                    'first_name' => $input[$i]['first_name'] ?? '',
                    'last_name'  => $input[$i]['last_name'] ?? '',
                    'dob'  => $input[$i]['dob'] ?? '',
                    'passport_number'  => $input[$i]['passport_number'] ?? '',
                    'pan_number'  => $input[$i]['pan_number'] ?? '',
                    'email'      => $input[$i]['email'] ?? '',
                    'phone'      => $input[$i]['phone'] ?? '',
                ];
                $data = $booking->service->filterPassengerData($data, $booking, $request, $i);
                $passenger->fillByAttr(array_keys($data), $data);
                $passenger->save();
            }
        }
    }

    public function confirmPayment(Request $request, $gateway)
    {

        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        return $gatewayObj->confirmPayment($request);
    }

    public function callbackPayment(Request $request, $gateway)
    {
        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        if (!empty($request->input('is_normal'))) {
            return $gatewayObj->callbackNormalPayment();
        }
        return $gatewayObj->callbackPayment($request);
    }

    public function cancelPayment(Request $request, $gateway)
    {

        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        return $gatewayObj->cancelPayment($request);
    }

    /**
     * @param Request $request
     * @return string json
     * @todo Handle Add To Cart Validate
     *
     */
    public function addToCart(Request $request)
    {
        if (!is_enable_guest_checkout() and !Auth::check()) {
            return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        }
        if (auth()->user() && !auth()->user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user') == 1) {
            return $this->sendError(__("You have to verify email first"), ['url' => url('/email/verify')]);
        }

        $validator = Validator::make($request->all(), [
            'service_id'   => 'required|integer',
            'service_type' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $service_type = $request->input('service_type');
        $service_id = $request->input('service_id');
        $allServices = get_bookable_services();
        if (empty($allServices[$service_type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$service_type];
        $service = $module::find($service_id);
        if (empty($service) or !is_subclass_of($service, '\\Modules\\Booking\\Models\\Bookable')) {
            return $this->sendError(__('Service not found'));
        }
        if (!$service->isBookable()) {
            return $this->sendError(__('Service is not bookable'));
        }

        if (\auth()->user() && Auth::id() == $service->author_id) {
            return $this->sendError(__('You cannot book your own service'));
        }

        return $service->addToCart($request);
    }

    public function detail(Request $request, $code)
    {
        if (!is_enable_guest_checkout() and !Auth::check()) {
            return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        }

        $booking = $this->booking::where('code', $code)->first();
        if (empty($booking)) {
            abort(404);
        }

        if ($booking->status == 'draft') {
            return redirect($booking->getCheckoutUrl());
        }
        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            abort(404);
        }
        $data = [
            'page_title' => __('Booking Details'),
            'booking'    => $booking,
            'service'    => $booking->service,
        ];
        if ($booking->gateway) {
            $data['gateway'] = get_payment_gateway_obj($booking->gateway);
        }
        return view('Booking::frontend/detail', $data);
    }

    public function exportIcal($type, $id = false)
    {
        if (empty($type) or empty($id)) {
            return $this->sendError(__('Service not found'));
        }

        $allServices = get_bookable_services();
        $allServices['room'] = 'Modules\Hotel\Models\HotelRoom';
        if (empty($allServices[$type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$type];

        $path = '/ical/';
        $fileName = 'booking_' . $type . '_' . $id . '.ics';
        $fullPath = $path . $fileName;

        $content = $this->booking::getContentCalendarIcal($type, $id, $module);
        Storage::disk('uploads')->put($fullPath, $content);
        $file = Storage::disk('uploads')->get($fullPath);

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        echo $file;
    }

    public function addEnquiry(Request $request)
    {
        $rules = [
            'service_id'    => 'required|integer',
            'service_type'  => 'required',
            'enquiry_name'  => 'required',
            'enquiry_note'  => 'required',
            'enquiry_email' => [
                'required',
                'email',
                'max:255',
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }

        if (setting_item('booking_enquiry_enable_recaptcha')) {
            $codeCapcha = trim($request->input('g-recaptcha-response'));
            if (empty($codeCapcha) or !ReCaptchaEngine::verify($codeCapcha)) {
                return $this->sendError(__("Please verify the captcha"));
            }
        }

        $service_type = $request->input('service_type');
        $service_id = $request->input('service_id');
        $allServices = get_bookable_services();
        if (empty($allServices[$service_type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$service_type];
        $service = $module::find($service_id);
        if (empty($service) or !is_subclass_of($service, '\\Modules\\Booking\\Models\\Bookable')) {
            return $this->sendError(__('Service not found'));
        }
        $row = new $this->enquiryClass();
        $row->fill([
            'name'  => $request->input('enquiry_name'),
            'email' => $request->input('enquiry_email'),
            'phone' => $request->input('enquiry_phone'),
            'note'  => $request->input('enquiry_note'),
        ]);
        $row->object_id = $request->input("service_id");
        $row->object_model = $request->input("service_type");
        $row->status = "pending";
        $row->vendor_id = $service->author_id;
        $row->save();
        event(new EnquirySendEvent($row));
        return $this->sendSuccess([
            'message' => __("Thank you for contacting us! We will be in contact shortly.")
        ]);
    }

    public function storeNoteBooking(Request $request)
    {
        $rules = [
            'note' => 'required',
            'id'     => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $id = $request->input('id');
        $booking = Booking::where('id', $id)->first();
        if (empty($booking)) {
            return $this->sendError(__('Booking not found'));
        }
        if (!Auth::user()->hasPermission('dashboard_vendor_access')) {
            if ($booking->vendor_id != Auth()->id()) {
                return $this->sendError(__("You don't have access."));
            }
        }
        $booking->addMeta("note_for_vendor",$request->input('note'));
        return $this->sendSuccess([
            'message' => __("Save successfully")
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPaidAmount(Request $request)
    {
        $rules = [
            'remain' => 'required|integer',
            'id'     => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }


        $id = $request->input('id');
        $remain = floatval($request->input('remain'));

        if ($remain < 0) {
            return $this->sendError(__('Remain can not smaller than 0'));
        }

        $booking = Booking::where('id', $id)->first();
        if (empty($booking)) {
            return $this->sendError(__('Booking not found'));
        }

        if (!Auth::user()->hasPermission('dashboard_vendor_access')) {
            if ($booking->vendor_id != Auth()->id()) {
                return $this->sendError(__("You don't have access."));
            }
        }

        $booking->pay_now = $remain;
        $booking->paid = floatval($booking->total) - $remain;
        event(new SetPaidAmountEvent($booking));
        if ($remain == 0) {
            $booking->status = $booking::PAID;
//            $booking->sendStatusUpdatedEmails();
            event(new BookingUpdatedEvent($booking));
        }

        $booking->save();

        return $this->sendSuccess([
            'message' => __("You booking has been changed successfully")
        ]);
    }

    public function modal(Booking $booking)
    {
        if (!is_admin() and $booking->vendor_id != auth()->id() and $booking->customer_id != auth()->id()) abort(404);

        return view('Booking::frontend.detail.modal', ['booking' => $booking, 'service' => $booking->service]);
    }
}
