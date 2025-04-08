@extends('layouts.app')
@push('css')
    <link href="{{ asset('module/booking/css/checkout.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">
    <style>
        /* Stepper Wrapper - Ensures Single Row */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 70px;
            overflow-x: auto; /* Enables horizontal scrolling */
            white-space: nowrap;
            padding-bottom: 5px;
        }

        /* Buttons - Reduced Size */
        .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-weight: bold;
            font-size: 14px; /* Reduced font size */
            padding: 8px 14px; /* Smaller padding */
            border-radius: 20px;
            background: none;
            border: 2px solid #ccc; /* Lighter border color */
            transition: all 0.3s ease;
            flex: 1; /* Ensures even spacing */
            min-width: 160px; /* Prevents buttons from becoming too small */
        }

        .nav-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Step Number */
        .step-number {
            background-color: #007bff;
            color: white;
            width: 24px; /* Smaller step number */
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }

        .nav-link.active .step-number {
            background-color: white;
            color: #007bff;
            border: 2px solid #007bff;
        }

        /* Responsive Handling - Ensures Row Layout on All Screens */
        @media (max-width: 768px) { /* Tablets & Mobile */
            .stepper-wrapper {
                overflow-x: auto; /* Enables horizontal scrolling */
                flex-wrap: nowrap; /* Prevents wrapping to new rows */
                gap: 10px;
            }
        }
        .error {
            color: red;
            font-size: 12px;
            display: block;
        }
    </style>
@endpush
@section('content')
    <div class="bravo-booking-page mt-3">
        <div class="container">
            <div id="bravo-checkout-page">
                <div class="container mt-1">
                    <!-- Tabs navigation -->
                    <div class="container">
                        <div class="stepper-wrapper">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" 
                                type="button" role="tab" aria-controls="home" aria-selected="true">
                                <span class="step-number">1</span> Trip Summary
                            </button>
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" 
                                type="button" role="tab" aria-controls="profile" aria-selected="false">
                                <span class="step-number">2</span> Passenger
                            </button>
                            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" 
                                type="button" role="tab" aria-controls="review" aria-selected="false">
                                <span class="step-number">3</span> Review Cost
                            </button>
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" 
                                type="button" role="tab" aria-controls="contact" aria-selected="false">
                                <span class="step-number">4</span> Make Payment
                            </button>
                        </div>
                    </div> 

                    <!-- Tab content -->
                    <div class="tab-content mt-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="booking-detail booking-form">
                                @include ($service->checkout_booking_detail_file ?? '')

                                  @php
                                    $term_conditions = setting_item('booking_term_conditions');
                                    @endphp

                                    <div class="form-group">
                                        <label class="term-conditions-checkbox">
                                            <input type="checkbox" name="term_conditions"> {{__('I acknowledge that I have fully read and agree upon the alerts, cancellation and payment terms mentioned above.')}} <a
                                                target="_blank" href="{{get_page_url($term_conditions)}}">{{__('terms and conditions')}}</a>
                                        </label>
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="booking-form px-3">
                                @include ($service->checkout_form_file ?? 'Booking::frontend/booking/checkout-form')
                                @if(!empty($token = request()->input('token')))
                                    <input type="hidden" name="token" value="{{$token}}">
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                            <h5>Review Total Cost</h5>
                              <h2 style="display: flex; justify-content: center; align-item: center; ">Total Cost <br/> {{format_money($booking->total)}}   </h2><br /> <br />
                                <!-- Apply Coupon section -->
                                <div class="apply-coupon" style="display: flex; justify-content: center;">
                                    <button type="button" class="btn btn-link" id="applyCouponBtn">Apply Coupon</button><br/>
                                    <!-- Coupon Form (initially hidden) -->
                                    <div id="couponForm" style="display: none;">
                                        @includeIf('Coupon::frontend/booking/checkout-coupon')
                                    </div>
                                </div>
                         </div>

                        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            <h4>You are one step away from your dream destination.</h4>
                           @include ('Booking::frontend/booking/checkout-passengers')
                            @include ('Booking::frontend/booking/checkout-deposit')
                            @include ($service->checkout_form_payment_file ?? 'Booking::frontend/booking/checkout-payment')

                            <p>In case of any doubt, Call us at <a href="tel:+919445552020">+91 9445552020</a></p> 

                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="my-4 d-flex justify-content-between">
                        <button class="btn btn-secondary" id="backButton">Back</button>
                        <button class="btn btn-primary" id="nextButton">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('module/booking/js/checkout.js') }}"></script>
    <script src="{{ asset('module/booking/js/passengerDetails.js') }}"></script>
    <!-- <script src="{{ asset('module/booking/js/loopingPassenger.js') }}"></script> -->
    <script type="text/javascript">
        jQuery(function () {
            $.ajax({
                'url': bookingCore.url + '{{$is_api ? '/api' : ''}}/booking/{{$booking->code}}/check-status',
                'cache': false,
                'type': 'GET',
                success: function (data) {
                    if (data.redirect !== undefined && data.redirect) {
                        window.location.href = data.redirect
                    }
                }
            });
        })

        $('.deposit_amount').on('change', function () {
            checkPaynow();
        });

        $('input[type=radio][name=how_to_pay]').on('change', function () {
            checkPaynow();
        });

        function checkPaynow() {
            var credit_input = $('.deposit_amount').val();
            var how_to_pay = $("input[name=how_to_pay]:checked").val();
            var convert_to_money = credit_input * {{ setting_item('wallet_credit_exchange_rate', 1)}};

            if (how_to_pay == 'full') {
                var pay_now_need_pay = parseFloat({{floatval($booking->total)}}) - convert_to_money;
            } else {
                var pay_now_need_pay = parseFloat({{floatval($booking->deposit == null ? $booking->total : $booking->deposit)}}) - convert_to_money;
            }

            if (pay_now_need_pay < 0) {
                pay_now_need_pay = 0;
            }
            $('.convert_pay_now').html(bravo_format_money(pay_now_need_pay));
            $('.convert_deposit_amount').html(bravo_format_money(convert_to_money));
        }


        jQuery(function () {
            $(".bravo_apply_coupon").click(function () {
                var parent = $(this).closest('.section-coupon-form');
                parent.find(".group-form .fa-spin").removeClass("d-none");
                parent.find(".message").html('');
                $.ajax({
                    'url': bookingCore.url + '/booking/{{$booking->code}}/apply-coupon',
                    'data': parent.find('input,textarea,select').serialize(),
                    'cache': false,
                    'method': "post",
                    success: function (res) {
                        parent.find(".group-form .fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            window.location.reload();
                        }
                        if (res.message && res.status === 1) {
                            parent.find('.message').html('<div class="alert alert-success">' + res.message + '</div>');
                        }
                        if (res.message && res.status === 0) {
                            parent.find('.message').html('<div class="alert alert-danger">' + res.message + '</div>');
                        }
                    }
                });
            });
            $(".bravo_remove_coupon").click(function (e) {
                e.preventDefault();
                var parent = $(this).closest('.section-coupon-form');
                var parentItem = $(this).closest('.item');
                parentItem.find(".fa-spin").removeClass("d-none");
                $.ajax({
                    'url': bookingCore.url + '/booking/{{$booking->code}}/remove-coupon',
                    'data': {
                        coupon_code: $(this).attr('data-code')
                    },
                    'cache': false,
                    'method': "post",
                    success: function (res) {
                        parentItem.find(".fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            window.location.reload();
                        }
                        if (res.message && res.status === 0) {
                            parent.find('.message').html('<div class="alert alert-danger">' + res.message + '</div>');
                        }
                    }
                });
            });
        })
    </script>

@endpush