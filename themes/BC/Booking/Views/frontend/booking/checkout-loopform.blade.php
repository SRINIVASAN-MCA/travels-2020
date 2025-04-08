
<div class="form-checkout" id="form-checkout">
    <input type="hidden" name="code" value="{{$booking->code}}">
    <div class="form-section">
        <div class="row">
            @if(is_enable_guest_checkout() && is_enable_registration())
            <div class="col-12">
                <div class="form-group">
                    <label for="confirmRegister">
                        <input type="checkbox" name="confirmRegister" id="confirmRegister" value="1">
                        {{__('Create a new account?')}}
                    </label>
                </div>
            </div>
            @endif
            @if(is_enable_guest_checkout())
            <div class="col-12 d-none" id="confirmRegisterContent">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="lh-1 text-16 text-light-1">{{__("Password")}} <span class="required">*</span></label>
                            <input type="password" class="form-control" name="password" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="lh-1 text-16 text-light-1">{{__('Password confirmation')}} <span class="required">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" autocomplete="off">
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            @endif

            <!-- Main Form -->
            <!-- <form method="POST" action="{{ route('doCheckout') }}" class="form" id="checkoutForm"> -->
             <form id="checkoutForm" >
                 @csrf
                <h4> {{__('Personal Information')}} </h4>
                <hr>
                <div class="row">
                    <!-- Title -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <div class="d-flex align-items-center gap-3 ">
                                <div class="form-check p-3 border rounded-lg bg-light shadow-sm">
                                    <input class="form-check-input" type="radio" name="title" id="title" value="Mr"
                                        {{ ($user->title ?? '') == 'Mr' ? 'checked' : '' }} id="mr" checked>
                                    <label class="form-check-label mt-2" for="mr">{{ __("Mr.") }}</label>
                                </div>
                                <div class="form-check p-3 border rounded-lg bg-light shadow-sm">
                                    <input class="form-check-input" type="radio" name="title" id="title" value="Ms"
                                        {{ ($user->title ?? '') == 'Ms' ? 'checked' : '' }} id="ms">
                                    <label class="form-check-label mt-2" for="ms">{{ __("Ms.") }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Given Name -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Given Name")}} <span class="required">*</span></label>
                            <input type="text" placeholder="{{__("First Name")}}" class="form-control" id="first_name"
                                value="{{$user->first_name ?? ''}}" name="first_name">
                        </div>
                    </div>

                    <!-- Surname -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Surname")}} <span class="required">*</span></label>
                            <input type="text" placeholder="{{__("Last Name")}}" class="form-control"
                                value="{{$user->last_name ?? ''}}" name="last_name" id="last_name">
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("DOB")}} <span class="required">*</span></label>
                            <input type="date" class="form-control" value="{{$user->dob ?? ''}}" name="dob" id="dob"
                                placeholder="{{__('Date of Birth')}} *">
                        </div>
                    </div>

                    <!-- Passport Number -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Passport Number")}} <span class="required">*</span></label>
                            <input type="text" placeholder="{{__("Passport Number")}}" class="form-control" id="passport_number"
                                value="{{$user->passport_number ?? ''}}" name="passport_number">
                        </div>
                    </div>

                    <!-- Issue Date -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Issue Date")}} <span class="required">*</span></label>
                            <input type="date" placeholder="{{__("Issue Date ")}}" class="form-control" id="issue_date"
                                value="{{$user->issue_date ?? ''}}" name="issue_date">
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Expiry Date")}} <span class="required">*</span></label>
                            <input type="date" placeholder="{{__("Expiry Date ")}}" class="form-control"    id="expiry_date"
                                value="{{$user->expiry_date ?? ''}}" name="expiry_date">
                        </div>
                    </div>

                    <!-- Issue City -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Issue City")}} </label>
                            <input type="text" class="form-control" value="{{$user->city ?? ''}}" name="city"  id="city"
                                placeholder="{{__("Your City")}}">
                        </div>
                    </div>

                    <!-- Issue Country -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__("Issue Country")}} <span class="required">*</span> </label>
                            <select name="country" id="country" class="form-control">
                                <option value="">{{__('-- Select --')}}</option>
                                @foreach(get_country_lists() as $id=>$name)
                                <option @if(($user->country ?? '') == $id) selected @endif value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- PAN Number -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__(" PAN Number")}} </label>
                            <input type="text" class="form-control" value="{{$user->pan_number ?? ''}}" name="pan_number" id="pan_number"
                                placeholder="{{__("Pan Number")}}">
                        </div>
                    </div>

                    <hr>

                    <!-- Meal Preference -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __("Meal Preference") }}</label>
                            <div class="d-flex gap-2 mt-2 p-2 border rounded-lg bg-light shadow-sm">
                                <div class="form-check d-flex flex-column align-items-center p-3 border rounded-lg bg-light shadow-sm">
                                    <input class="form-check-input" type="radio" name="meal_preference" value="veg" id="meal_preference"
                                        {{ ($user->meal_preference ?? '') == 'veg' ? 'checked' : '' }} id="veg" checked>
                                    <label class="form-check-label mt-2 fw-bold" for="veg">{{ __("Veg") }}</label>
                                </div>
                                <div class="form-check d-flex flex-column align-items-center p-3 border rounded-lg bg-light shadow-sm">
                                    <input class="form-check-input" type="radio" name="meal_preference" value="nonveg" id="meal_preference"
                                        {{ ($user->meal_preference ?? '') == 'nonveg' ? 'checked' : '' }} id="nonveg">
                                    <label class="form-check-label mt-2 fw-bold" for="nonveg">{{ __("Non-Veg") }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Upload Documents -->
                    <div class="col-md-12">
                        <h2>Upload Documents</h2>

                        <!-- Passport Front Upload -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("Passport Front")}} </label>
                                <input type="file" class="form-control" name="passport_front" id="passport_front" accept="image/*,application/pdf">
                            </div>
                        </div>

                        <!-- Passport Back Upload (Optional) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("Passport Back")}}</label>
                                <input type="file" class="form-control" name="passport_back" id="passport_back" accept="image/*,application/pdf">
                            </div>
                        </div>

                        <!-- PAN Card Upload (Optional) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("PAN Card")}} (Optional)</label>
                                <input type="file" class="form-control" name="pan_card" id="pan_card" accept="image/*,application/pdf">
                            </div>
                        </div>
                    </div>

                </div> <!-- End of row -->
                <hr>

                <!-- Terms and Conditions -->
                @php
                $term_conditions = setting_item('booking_term_conditions');
                @endphp

                <div class="form-group">
                    <label class="term-conditions-checkbox">
                        <input type="checkbox" name="term_conditions"> {{__('I have read and accept the')}} <a target="_blank"
                            href="{{get_page_url($term_conditions)}}">{{__('terms and conditions')}}</a>
                    </label>
                </div>

                <!-- reCAPTCHA -->
                @if(setting_item("booking_enable_recaptcha"))
                <div class="form-group">
                    {{recaptcha_field('booking')}}
                </div>
                @endif
                <div class="html_before_actions"></div>

                <p class="alert-text mt10" v-show=" message.content" v-html="message.content"
                    :class="{'danger':!message.type,'success':message.type}"></p>
            

                <!-- Submit Button -->
                <div class="form-actions">
                    <!-- <button class="btn btn-danger" @click="doCheckout">{{__('Submit')}}
                        <i class="fa fa-spin fa-spinner" v-show="onSubmit"></i>
                    </button> -->
                    <button type="submit" class="btn btn-danger" id="passengersubmit">{{__('Submit')}}</button>
                </div>

            </form>
        </div>
    </div>
</div>





-------------------------------------------

@php
    $total_adults = $booking->getMeta('adults') ?? 1; // Default 1 if not set
@endphp

<div class="form-checkout" id="form-checkout">
    <input type="hidden" name="code" value="{{$booking->code}}">

    @for ($i = 1; $i <= $total_adults; $i++)
    <div class="traveller-section">
        <!-- Traveller Toggle -->
        <button type="button" class="btn btn-light text-center mb-2 traveller-toggle" data-target="#traveller-form-{{$i}}">
            {{ __('Adults') }} {{$i}}
        </button>

        <!-- Traveller Form (Initially Hidden) -->
        <div id="traveller-form-{{$i}}" class="traveller-form p-3 border rounded-lg bg-light shadow-sm" style="display: none;">
            <h4>{{__('Personal Information')}} - {{__('Adults')}} {{$i}}</h4>
            <hr>
            <div class="row">
                 <!-- Title -->
                 <div class="col-md-6 col-12">
                      <div class="form-group">
                         <div class="d-flex align-items-center gap-3">
                               <div class="form-check p-3 border rounded-lg bg-light shadow-sm">
                                <input class="form-check-input" type="text" name="whichPassanger" value=`${{__('Adults')}} {{$i}}`>
                                   <input class="form-check-input" type="radio" name="title[{{$i}}]" value="Mr"
                                                    id="mr{{$i}}" checked>
                                                <label class="form-check-label mt-2" for="mr{{$i}}">{{ __("Mr.") }}</label>
                                            </div>             
                                            <div class="form-check p-3 border rounded-lg bg-light shadow-sm">
                                                <input class="form-check-input" type="radio" name="title[{{$i}}]" value="Ms"
                                                    id="ms{{$i}}">
                                                <label class="form-check-label mt-2" for="ms{{$i}}">{{ __("Ms.") }}</label>
                                   </div>             
                              </div>
                          </div>
                 </div>
                               

                                <!-- Given Name -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Given Name")}} <span class="required">*</span></label>
                        <input type="text" placeholder="{{__("First Name")}}" class="form-control" name="travellers[{{$i}}][first_name]">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Surname")}} <span class="required">*</span></label>
                        <input type="text" placeholder="{{__("Last Name")}}" class="form-control" name="travellers[{{$i}}][last_name]">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("DOB")}}</label>
                        <input type="date" class="form-control" name="travellers[{{$i}}][dob]">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Passport Number")}} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="travellers[{{$i}}][passport_number]">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Issue Date")}}</label>
                        <input type="date" class="form-control" name="travellers[{{$i}}][issue_date]">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Expiry Date")}} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="travellers[{{$i}}][expiry_date]">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Issue Country")}} <span class="required">*</span> </label>
                        <select name="travellers[{{$i}}][country]" class="form-control">
                            <option value="">{{__('-- Select --')}}</option>
                            @foreach(get_country_lists() as $id=>$name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                    <!-- PAN Number -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>{{__(" PAN Number")}} </label>
                            <input type="text" class="form-control" value="{{$user->pan_number ?? ''}}" name="pan_number" id="pan_number"
                                placeholder="{{__("Pan Number")}}">
                        </div>
                    </div>

                  <!-- Meal Preference -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __("Meal Preference") }}</label>
                            <div class="d-flex gap-2 mt-2 p-2 border rounded-lg bg-light shadow-sm">
                                <div class="form-check d-flex flex-column align-items-center p-3 border rounded-lg bg-light shadow-sm">
                                    <input class="form-check-input" type="radio" name="meal_preference" value="veg" id="meal_preference"
                                        {{ ($user->meal_preference ?? '') == 'veg' ? 'checked' : '' }} id="veg" checked>
                                    <label class="form-check-label mt-2 fw-bold" for="veg">{{ __("Veg") }}</label>
                                </div>
                                <div class="form-check d-flex flex-column align-items-center p-3 border rounded-lg bg-light shadow-sm">
                                    <input class="form-check-input" type="radio" name="meal_preference" value="nonveg" id="meal_preference"
                                        {{ ($user->meal_preference ?? '') == 'nonveg' ? 'checked' : '' }} id="nonveg">
                                    <label class="form-check-label mt-2 fw-bold" for="nonveg">{{ __("Non-Veg") }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>{{__("Meal Preference")}}</label>
                        <select name="travellers[{{$i}}][meal_preference]" class="form-control">
                            <option value="veg">{{__("Veg")}}</option>
                            <option value="nonveg">{{__("Non-Veg")}}</option>
                        </select>
                    </div>
                </div>

                <hr>

                <!-- Upload Documents -->
                <div class="col-md-12">
                    <h2>{{__("Upload Documents")}}</h2>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Passport Front")}} </label>
                            <input type="file" class="form-control" name="travellers[{{$i}}][passport_front]" accept="image/*,application/pdf">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Passport Back")}}</label>
                            <input type="file" class="form-control" name="travellers[{{$i}}][passport_back]" accept="image/*,application/pdf">
                        </div>
                    </div>

                     <!-- PAN Card Upload (Optional) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("PAN Card")}} (Optional)</label>
                                <input type="file" class="form-control" name="pan_card" id="pan_card" accept="image/*,application/pdf">
                            </div>
                        </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
    @endfor
  <!-- Terms and Conditions -->
                @php
                $term_conditions = setting_item('booking_term_conditions');
                @endphp
    <!-- Terms & Submit -->
    <div class="form-group mt-3">
        <label>
            <input type="checkbox" name="term_conditions"> {{__('I have read and accept the')}} 
            <a target="_blank" href="{{get_page_url(setting_item('booking_term_conditions'))}}">{{__('terms and conditions')}}</a>
        </label>
    </div>

    <button type="submit" class="btn btn-danger" id="passengersubmit">{{__('Submit')}}</button>
</div>

push@('scripts')
<!-- JavaScript to Open/Close Traveller Forms -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".traveller-toggle").forEach(button => {
        button.addEventListener("click", function() {
            let target = document.querySelector(this.getAttribute("data-target"));

            // Toggle visibility of the selected form
            if (target.style.display === "none" || target.style.display === "") {
                document.querySelectorAll(".traveller-form").forEach(form => form.style.display = "none"); // Hide all forms
                target.style.display = "block"; // Show clicked form
            } else {
                target.style.display = "none"; // Hide if already open
            }
        });
    });
});
</script>
endpush('scripts')