<div class="form-checkout" id="form-checkout">
    <input type="hidden" name="code" value="{{ $booking->code }}">
    <div class="form-section">
        <div class="row">
            @if (is_enable_guest_checkout() && is_enable_registration())
                <div class="col-12">
                    <div class="form-group">
                        <label for="confirmRegister">
                            <input type="checkbox" name="confirmRegister" id="confirmRegister" value="1">
                            {{ __('Create a new account?') }}
                        </label>
                    </div>
                </div>
            @endif
            @if (is_enable_guest_checkout())
                <div class="col-12 d-none" id="confirmRegisterContent">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="lh-1 text-16 text-light-1">{{ __('Password') }} <span
                                        class="required">*</span></label>
                                <input type="password" class="form-control" name="password" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="lh-1 text-16 text-light-1">{{ __('Password confirmation') }} <span
                                        class="required">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            @endif

            @php
                $person_types = $booking->getJsonMeta('person_types');
                $allPassangers = [];

                if (!empty($person_types)) {
                    foreach ($person_types as $type) {
                        if (stripos($type['name'], 'Adutls') !== false) {
                            // Fixed spelling
                            for ($i = 1; $i <= $type['number']; $i++) {
                                $allPassangers[] = 'Adults - ' . $i;
                            }
                        } elseif (stripos($type['name'], 'Child') !== false) {
                            for ($i = 1; $i <= $type['number']; $i++) {
                                $allPassangers[] = 'Children - ' . $i; // Fixed "Childrens"
                            }
                        }
                    }
                }
            @endphp

            <div class="form-checkout" id="form-checkout">
                <input type="hidden" name="code" value="{{ $booking->code }}">

                @foreach ($allPassangers as $i => $val)
                    <div class="traveller-section">
                        <!-- Traveller Toggle -->
                        <button type="button" class="btn btn-light text-center mb-2 traveller-toggle"
                            data-target="#traveller-form-{{ $i }}">
                            {{ $val }}
                        </button>

                        <!-- Traveller Form (Initially Hidden) -->

                        <form id="traveller-form-{{ $i }}"
                            class="traveller-form checkoutForm p-3 mb-3 border rounded-lg bg-light shadow-sm"
                            style="display: none;">
                            @csrf
                            <h4>{{ __('Personal Information') }} - {{ $val }}</h4>

                            <hr>
                            <div class="row">
                                <!-- <input class="form-check-input" type="hidden" name="whichPassanger" id="whichPassanger" value="{{ $i }}"> -->
                                <input type="hidden" name="whichPassanger" id="whichPassanger" class="whichPassanger"
                                    value="{{ $val }}">
                                <input type="hidden" name="bookingID" id="bookingID" value="{{ $val }}">
                                <!-- Title -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 ">
                                            <div class="form-check p-3 border rounded-lg bg-light shadow-sm">
                                                <input class="form-check-input" type="radio" name="title"
                                                    id="title" value="Mr"
                                                    {{ ($user->title ?? '') == 'Mr' ? 'checked' : '' }} id="mr"
                                                    checked>
                                                <label class="form-check-label mt-2"
                                                    for="mr">{{ __('Mr.') }}</label>
                                            </div>
                                            <div class="form-check p-3 border rounded-lg bg-light shadow-sm">
                                                <input class="form-check-input" type="radio" name="title"
                                                    id="title" value="Ms"
                                                    {{ ($user->title ?? '') == 'Ms' ? 'checked' : '' }} id="ms">
                                                <label class="form-check-label mt-2"
                                                    for="ms">{{ __('Ms.') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Given Name -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Given Name') }} <span class="required">*</span></label>
                                        <input type="text" placeholder="{{ __('First Name') }}" class="form-control"
                                            id="first_name" name="first_name">
                                    </div>
                                </div>

                                <!-- Surname -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Surname') }} <span class="required">*</span></label>
                                        <input type="text" placeholder="{{ __('Last Name') }}" class="form-control"
                                            value="{{ $user->last_name ?? '' }}" name="last_name" id="last_name">
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('DOB') }} <span class="required">*</span></label>
                                        <input type="date" class="form-control" value="{{ $user->dob ?? '' }}"
                                            name="dob" id="dob" placeholder="{{ __('Date of Birth') }} *">
                                    </div>
                                </div>

                                <!-- Passport Number -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Passport Number') }} <span class="required">*</span></label>
                                        <input type="text" placeholder="{{ __('Passport Number') }}"
                                            class="form-control" id="passport_number"
                                            value="{{ $user->passport_number ?? '' }}" name="passport_number">
                                    </div>
                                </div>

                                <!-- Issue Date -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Issue Date') }} <span class="required">*</span></label>
                                        <input type="date" placeholder="{{ __('Issue Date ') }}"
                                            class="form-control" id="issue_date"
                                            value="{{ $user->issue_date ?? '' }}" name="issue_date">
                                    </div>
                                </div>

                                <!-- Expiry Date -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Expiry Date') }} <span class="required">*</span></label>
                                        <input type="date" placeholder="{{ __('Expiry Date ') }}"
                                            class="form-control" id="expiry_date"
                                            value="{{ $user->expiry_date ?? '' }}" name="expiry_date">
                                    </div>
                                </div>

                                <!-- Issue City -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Issue City') }} </label>
                                        <input type="text" class="form-control" value="{{ $user->city ?? '' }}"
                                            name="city" id="city" placeholder="{{ __('Your City') }}">
                                    </div>
                                </div>

                                <!-- Issue Country -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Issue Country') }} <span class="required">*</span> </label>
                                        <select name="country" id="country" class="form-control">
                                            <option value="">{{ __('-- Select --') }}</option>
                                            @foreach (get_country_lists() as $id => $name)
                                                <option @if (($user->country ?? '') == $id) selected @endif
                                                    value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- PAN Number -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __(' PAN Number') }} </label>
                                        <input type="text" class="form-control"
                                            value="{{ $user->pan_number ?? '' }}" name="pan_number" id="pan_number"
                                            placeholder="{{ __('Pan Number') }}">
                                    </div>
                                </div>

                                <hr>

                                <!-- Meal Preference -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('Meal Preference') }}</label>
                                        <div class="d-flex gap-2 mt-2 p-2 border rounded-lg bg-light shadow-sm">
                                            <div
                                                class="form-check d-flex flex-column align-items-center p-3 border rounded-lg bg-light shadow-sm">
                                                <input class="form-check-input" type="radio" name="meal_preference"
                                                    value="veg" id="meal_preference"
                                                    {{ ($user->meal_preference ?? '') == 'veg' ? 'checked' : '' }}
                                                    id="veg" checked>
                                                <label class="form-check-label mt-2 fw-bold"
                                                    for="veg">{{ __('Veg') }}</label>
                                            </div>
                                            <div
                                                class="form-check d-flex flex-column align-items-center p-3 border rounded-lg bg-light shadow-sm">
                                                <input class="form-check-input" type="radio" name="meal_preference"
                                                    value="nonveg" id="meal_preference"
                                                    {{ ($user->meal_preference ?? '') == 'nonveg' ? 'checked' : '' }}
                                                    id="nonveg">
                                                <label class="form-check-label mt-2 fw-bold"
                                                    for="nonveg">{{ __('Non-Veg') }}</label>
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
                                            <label>{{ __('Passport Front') }} </label>
                                            <input type="file" class="form-control" name="passport_front"
                                                id="passport_front" accept="image/*,application/pdf">
                                        </div>
                                    </div>

                                    <!-- Passport Back Upload (Optional) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Passport Back') }}</label>
                                            <input type="file" class="form-control" name="passport_back"
                                                id="passport_back" accept="image/*,application/pdf">
                                        </div>
                                    </div>

                                    <!-- PAN Card Upload (Optional) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('PAN Card') }} (Optional)</label>
                                            <input type="file" class="form-control" name="pan_card"
                                                id="pan_card" accept="image/*,application/pdf">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end w-100 px-4">
                                    <button type="button" class="btn btn-danger passengersubmit"
                                        data-passenger="{{ $i }}">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach


                <!-- Terms and Conditions -->
                {{-- @php
                    $term_conditions = setting_item('booking_term_conditions');
                    @endphp
                <div class="form-group mt-3">
                    <label>
                        <input type="checkbox" name="term_conditions"> {{__('I have read and accept the')}}
                        <a target="_blank" href="{{get_page_url(setting_item('booking_term_conditions'))}}">{{__('terms and conditions')}}</a>
                    </label>
                </div> --}}
            </div>
        </div>
    </div>
</div>
