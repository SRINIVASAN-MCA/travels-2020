@extends('layouts.user')
@section('content')
    <h2 class="title-bar">
        {{__("Settings")}}
        <a href="{{route('user.change_password')}}" class="btn-change-password">{{__("Change Password")}}</a>
    </h2>
            
    @include('admin.message')
    <form action="{{route('user.profile.update')}}" method="post" class="input-has-icon">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-title">
                    <strong>{{__("Personal Information")}}</strong>
                </div>
                <!-- @if($is_vendor_access)
                    <div class="form-group">
                        <label>{{__("Business name")}}</label>
                        <input type="text" value="{{old('business_name',$dataUser->business_name)}}" name="business_name" placeholder="{{__("Business name")}}" class="form-control">
                        <i class="fa fa-user input-icon"></i>
                    </div>
                @endif -->
                <div class="form-group">
                    <label>{{__("User name")}} <span class="text-danger">*</span></label>
                    <input type="text" required minlength="4" name="user_name" value="{{old('user_name',$dataUser->user_name)}}" placeholder="{{__("User name")}}" class="form-control">
                    <i class="fa fa-user input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("E-mail")}}</label>
                    <input type="text" name="email" value="{{old('email',$dataUser->email)}}" placeholder="{{__("E-mail")}}" class="form-control">
                    <i class="fa fa-envelope input-icon"></i>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("First name")}}</label>
                            <input type="text" value="{{old('first_name',$dataUser->first_name)}}" name="first_name" placeholder="{{__("First name")}}" class="form-control">
                            <i class="fa fa-user input-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Last name")}}</label>
                            <input type="text" value="{{old('last_name',$dataUser->last_name)}}" name="last_name" placeholder="{{__("Last name")}}" class="form-control">
                            <i class="fa fa-user input-icon"></i>
                        </div>
                    </div>
                </div>
               <div class="form-group">
                    <label>{{__("Phone Number")}}</label>
                    <input type="tel" value="{{ old('phone', $dataUser->phone) }}" name="phone" placeholder="{{ __('Phone Number') }}" 
                        class="form-control" maxlength="10" pattern="[0-9]{10}" required>
                    <i class="fa fa-phone input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("Birthday")}}</label>
                    <input type="text" value="{{ old('birthday',$dataUser->birthday? display_date($dataUser->birthday) :'') }}" name="birthday" placeholder="{{__("Birthday")}}" class="form-control date-picker">
                    <i class="fa fa-birthday-cake input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("Anniversary Date")}}</label>
                    <input type="date" value="{{ old('anniversary ',$dataUser->anniversary ? display_date($dataUser->anniversary ) :'') }}" name="anniversary " placeholder="{{__("Anniversary ")}}" class="form-control date-picker">
                    <i class="fa fa-birthday-cake input-icon"></i>
                </div>
                <div class="form-title">
                    <strong>{{__("Business Information")}}</strong>
                </div>
                @if($is_vendor_access)
                    <div class="form-group">
                        <label>{{__("Business name")}}</label>
                        <input type="text" value="{{old('business_name',$dataUser->business_name)}}" name="business_name" placeholder="{{__("Business name")}}" class="form-control">
                        <i class="fa fa-user input-icon"></i>
                    </div>
                @endif
                <div class="form-group">
                    <label>{{_("GST Number")}}</label>
                    <input type="text" value="{{old('gst_number',$dataUser->gst_number)}}" name="gst_number" placeholder="{{__("GST Number")}}" class="form-control">
                    <i class="fa fa-user input-icon"></i>
                </div>
            

                <!-- <div class="form-group">
                    <label>{{__("About Yourself")}}</label>
                    <textarea name="bio" rows="5" class="form-control">{{old('bio',$dataUser->bio)}}</textarea>
                </div> -->
                <div class="form-group">
                    <label>{{__("Avatar")}}</label>
                    <div class="upload-btn-wrapper">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-default btn-file">
                                    {{__("Browse")}}… <input type="file">
                                </span>
                            </span>
                            <input type="text" data-error="{{__("Error upload...")}}" data-loading="{{__("Loading...")}}" class="form-control text-view" readonly value="{{ get_file_url( old('avatar_id',$dataUser->avatar_id) ) ?? $dataUser->getAvatarUrl()?? __("No Image")}}">
                        </div>
                        <input type="hidden" class="form-control" name="avatar_id" value="{{ old('avatar_id',$dataUser->avatar_id)?? ""}}">
                        <img class="image-demo" src="{{ get_file_url( old('avatar_id',$dataUser->avatar_id) ) ??  $dataUser->getAvatarUrl() ?? ""}}"/>
                    </div>
                </div>
            </div>



            <div class="col-md-6">
                <div class="form-title">
                    <strong>{{__("Location Information")}}</strong>
                </div>
                <div class="form-group">
                    <label>{{__("Address Line 1")}}</label>
                    <input type="text" value="{{old('address',$dataUser->address)}}" name="address" placeholder="{{__("Address")}}" class="form-control">
                    <i class="fa fa-location-arrow input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("Address Line 2")}}</label>
                    <input type="text" value="{{old('address2',$dataUser->address2)}}" name="address2" placeholder="{{__("Address2")}}" class="form-control">
                    <i class="fa fa-location-arrow input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("City")}}</label>
                    <input type="text" value="{{old('city',$dataUser->city)}}" name="city" placeholder="{{__("City")}}" class="form-control">
                    <i class="fa fa-street-view input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("State")}}</label>
                    <input type="text" value="{{old('state',$dataUser->state)}}" name="state" placeholder="{{__("State")}}" class="form-control">
                    <i class="fa fa-map-signs input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("Country")}}</label>
                    <select name="country" class="form-control">
                        <option value="">{{__('-- Select --')}}</option>
                        @foreach(get_country_lists() as $id=>$name)
                            <option @if((old('country',$dataUser->country ?? '')) == $id) selected @endif value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Zip Code")}}</label>
                    <input type="text" value="{{old('zip_code',$dataUser->zip_code)}}" name="zip_code" placeholder="{{__("Zip Code")}}" class="form-control">
                    <i class="fa fa-map-pin input-icon"></i>
                </div>

            </div>

            
             <div class="accordion" id="familyAccordion">
    
                <!-- Wife Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingWife">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWife" aria-expanded="true" aria-controls="collapseWife">
                            {{__("Spouse Information")}}
                        </button>
                    </h2>
                    <div id="collapseWife" class="accordion-collapse collapse show" aria-labelledby="headingWife" data-bs-parent="#familyAccordion">
                        <div class="accordion-body">
                            <div class="form-group">
                                <label>{{__("Spouse Name")}}</label>
                                <input type="text" value="{{ old('spouse_name', $dataUser->spouse_name) }}" name="spouse_name" placeholder="{{__('Spouse Name')}}" class="form-control">
                                <i class="fa fa-user input-icon"></i>
                            </div>
                            <div class="form-group">
                                <label>{{__("Phone Number")}}</label>
                                <input type="tel" value="{{ old('spouse_phone', $dataUser->spouse_phone) }}" maxlength="10" pattern="[0-9]{10}" name="spouse_phone" placeholder="{{__('Phone Number')}}" class="form-control">
                                <i class="fa fa-phone input-icon"></i>
                            </div>
                            <div class="form-group">
                                <label>{{__("Date of Birth")}}</label>
                                <input type="date" value="{{ old('spouse_birthday', $dataUser->spouse_birthday ? display_date($dataUser->spouse_birthday) : '') }}" name="spouse_birthday" class="form-control date-picker">
                                <i class="fa fa-birthday-cake input-icon"></i>
                            </div>
                            
                                <!-- <div class="col-md-6 col-12"> -->
                                    <div class="form-group">
                                        <label>{{__("Passport Number")}}</label>
                                        <input type="text" placeholder="{{__("Passport Number")}}" class="form-control" id="passport_number"
                                            value="{{$user->passport_number ?? ''}}" name="passport_number">
                                    </div>
                                <!-- </div> -->

                                <!-- Issue Date -->
                                <!-- <div class="col-md-6 col-12"> -->
                                    <div class="form-group">
                                        <label>{{__("Issue Date")}}</label>
                                        <input type="date" placeholder="{{__("Issue Date ")}}" class="form-control" id="issue_date"
                                            value="{{$user->issue_date ?? ''}}" name="issue_date">
                                    </div>
                                <!-- </div> -->

                                <!-- Expiry Date -->
                                <!-- <div class="col-md-6 col-12"> -->
                                    <div class="form-group">
                                        <label>{{__("Expiry Date")}}</label>
                                        <input type="date" placeholder="{{__("Expiry Date ")}}" class="form-control"    id="expiry_date"
                                            value="{{$user->expiry_date ?? ''}}" name="expiry_date">
                                    </div>
                                <!-- </div> -->

                                <!-- Issue City -->
                                    <div class="form-group">
                                        <label>{{__("Issue City")}} </label>
                                        <input type="text" class="form-control" value="{{$user->city ?? ''}}" name="city"  id="city"
                                            placeholder="{{__("Your City")}}">
                                    </div>

                                <!-- Issue Country -->
                                    <div class="form-group">
                                        <label>{{__("Issue Country")}} </label>
                                        <select name="country" id="country" class="form-control">
                                            <option value="">{{__('-- Select --')}}</option>
                                            @foreach(get_country_lists() as $id=>$name)
                                            <option @if(($user->country ?? '') == $id) selected @endif value="{{$id}}">{{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                <!-- PAN Number -->
                                    <div class="form-group">
                                        <label>{{__(" PAN Number")}} </label>
                                        <input type="text" class="form-control" value="{{$user->pan_number ?? ''}}" name="pan_number" id="pan_number"
                                            placeholder="{{__("Pan Number")}}">
                                    </div>

                                <hr>

                                        <!-- Meal Preference -->
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

                                <hr>

                                <!-- Upload Documents -->
                                <div class="col-md-12">
                                    <h2>Upload Documents</h2>

                                    <!-- Passport Front Upload -->
                                        <div class="form-group">
                                            <label>{{__("Passport Front")}} </label>
                                            <input type="file" class="form-control" name="passport_front" id="passport_front" accept="image/*,application/pdf">
                                        </div>

                                    <!-- Passport Back Upload (Optional) -->
                                    <!-- <div class="col-md-6"> -->
                                        <div class="form-group">
                                            <label>{{__("Passport Back")}}</label>
                                            <input type="file" class="form-control" name="passport_back" id="passport_back" accept="image/*,application/pdf">
                                        </div>
                                    <!-- </div> -->

                                    <!-- PAN Card Upload (Optional) -->
                                    <!-- <div class="col-md-6"> -->
                                        <div class="form-group">
                                            <label>{{__("PAN Card")}} (Optional)</label>
                                            <input type="file" class="form-control" name="pan_card" id="pan_card" accept="image/*,application/pdf">
                                        </div>
                                    </div>
                                <!-- </div> -->
                           
                        </div>
                    </div>
                </div>

                <!-- Children Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingChildren">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChildren" aria-expanded="false" aria-controls="collapseChildren">
                            {{__("Children Information")}}
                        </button>
                    </h2>
                    <div id="collapseChildren" class="accordion-collapse collapse" aria-labelledby="headingChildren" data-bs-parent="#familyAccordion">
                        <div class="accordion-body">
                            <div class="form-group">
                                <label>{{__("Child Name")}}</label>
                                <input type="text" value="{{ old('child_name', $dataUser->child_name) }}" name="child_name" placeholder="{{__('Child Name')}}" class="form-control">
                                <i class="fa fa-user input-icon"></i>
                            </div>
                            <div class="form-group">
                                <label>{{__("Phone Number (Optional)")}}</label>
                                <input type="number" value="{{ old('child_phone', $dataUser->child_phone) }}" maxlength="10" name="child_phone" placeholder="{{__('Phone Number')}}" class="form-control">
                                <i class="fa fa-phone input-icon"></i>
                            </div>
                            <div class="form-group">
                                <label>{{__("Date of Birth")}}</label>
                                <input type="date" value="{{ old('child_birthday', $dataUser->child_birthday ? display_date($dataUser->child_birthday) : '') }}" name="child_birthday" class="form-control date-picker">
                                <i class="fa fa-birthday-cake input-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

                <!-- Bootstrap CSS & JS (Make sure Bootstrap is included) -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

            <div class="col-md-12">
                <hr>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Save Changes')}}</button>
            </div>
        </div>
    </form>
    @if(!empty(setting_item('user_enable_permanently_delete')) and !is_admin())
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-danger">
                {{__("Delete account")}}
            </h4>
            <div class="mb-4 mt-2">
                {!! clean(setting_item_with_lang('user_permanently_delete_content','',__('Your account will be permanently deleted. Once you delete your account, there is no going back. Please be certain.'))) !!}
            </div>
            <a data-toggle="modal" data-target="#permanentlyDeleteAccount" class="btn btn-danger" href="">{{__('Delete your account')}}</a>
        </div>

        <!-- Modal -->
        <div class="modal  fade" id="permanentlyDeleteAccount" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Confirm permanently delete account')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="my-3">
                            {!! clean(setting_item_with_lang('user_permanently_delete_content_confirm')) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <a href="{{route('user.permanently.delete')}}" class="btn btn-danger">{{__('Confirm')}}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
