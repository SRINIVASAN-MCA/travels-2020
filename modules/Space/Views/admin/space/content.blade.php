<div class="panel">
    <div class="panel-title"><strong>{{__("Space Content")}}</strong></div>
    <div class="panel-body">
        <div class="form-group magic-field" data-id="title" data-type="title">
            <label class="control-label">{{__("Title")}}</label>
            <input type="text" value="{{$translation->title}}" placeholder="{{__("Title")}}" name="title" class="form-control">
        </div>
        <div class="form-group magic-field" data-id="content" data-type="content">
            <label class="control-label">{{__("Content")}}</label>
            <div class="">
                <textarea name="content" class="d-none has-ckeditor" id="content" cols="30" rows="10">{{$translation->content}}</textarea>
            </div>
        </div>
        @if(is_default_lang())
            <div class="form-group">
                <label class="control-label">{{__("Youtube Video")}}</label>
                <input type="text" name="video" class="form-control" value="{{$row->video}}" placeholder="{{__("Youtube link video")}}">
            </div>
        @endif
        <div class="form-group-item">
            <label class="control-label">{{__('FAQs')}}</label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5">{{__("Title")}}</div>
                    <div class="col-md-5">{{__('Content')}}</div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                @if(!empty($translation->faqs))
                    @php if(!is_array($translation->faqs)) $translation->faqs = json_decode($translation->faqs); @endphp
                    @foreach($translation->faqs as $key=>$faq)
                        <div class="item" data-number="{{$key}}">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="faqs[{{$key}}][title]" class="form-control" value="{{$faq['title']}}" placeholder="{{__('Eg: When and where does the tour end?')}}">
                                </div>
                                <div class="col-md-6">
                                    <textarea name="faqs[{{$key}}][content]" class="form-control" placeholder="...">{{$faq['content']}}</textarea>
                                </div>
                                <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-right">
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
            </div>
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" __name__="faqs[__number__][title]" class="form-control" placeholder="{{__('Eg: Can I bring my pet?')}}">
                        </div>
                        <div class="col-md-6">
                            <textarea __name__="faqs[__number__][content]" class="form-control" placeholder=""></textarea>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group-item">
             <label class="control-label">{{__('More Content')}}</label>
                <div class="g-items-header">
                    <div class="row">
                        <div class="col-md-5">{{__("Content")}}</div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="g-items">
                    @if(!empty($translation->activities))
                        @php 
                            if(!is_array($translation->activities)) {
                                $translation->activities = json_decode($translation->activities, true); // Ensure it is an array
                            }
                        @endphp
                        @foreach($translation->activities as $key => $activity)
                            <div class="item" data-number="{{$key}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <input type="text" name="activities[{{$key}}][title]" class="form-control" value="{{ $activity['title'] ?? '' }}" placeholder="{{__('Eg: Speedboat Transfer')}}">
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="text-right">
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                </div>
                <div class="g-more hide">
                    <div class="item" data-number="__number__">
                        <div class="row">
                            <div class="col-md-11">
                                <input type="text" __name__="activities[__number__][title]" class="form-control" placeholder="{{__('Eg: Activities & Transport')}}">
                            </div>
                            <div class="col-md-1">
                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
         </div>

        <div class="form-group magic-field" data-id="stay" data-type="stay">
            <label class="control-label">{{__("Stay ")}}</label>
            <input type="text" value="{{$translation->stay}}" placeholder="{{__("stay")}}" name="stay" class="form-control">
        </div>

        <div class="form-group magic-field" data-id="hotel" data-type="hotel">
            <label class="control-label">{{__("Hotel Ratting")}}</label>
            <input type="number" value="{{$translation->hotel}}" placeholder="{{__("hotel")}}" name="hotel" class="form-control">
        </div>
        <div class="form-group magic-field" data-id="shortdesc" data-type="shortdesc">
            <label class="control-label">{{__(" Short Inclusion")}}</label>
            <input type="text" value="{{$translation->shortdesc}}" placeholder="{{__("Short Inclusion")}}" name="inclusion" class="form-control">
        </div>

        @if(is_default_lang())
            <div class="form-group">
                <label class="control-label">{{__("Banner Image")}}</label>
                <div class="form-group-image">
                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id',$row->banner_image_id) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{{__("Gallery")}}</label>
                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery',$row->gallery) !!}
            </div>
        @endif
    </div>
</div>
@if(is_default_lang())
<div class="panel">
    <div class="panel-title"><strong>{{__("Extra Info")}}</strong></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{__("No. Bed")}}</label>
                    <input type="number" value="{{$row->bed}}" placeholder="{{__("Example: 3")}}" name="bed" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{__("No. Bathroom")}}</label>
                    <input type="number" value="{{$row->bathroom}}" placeholder="{{__("Example: 5")}}" name="bathroom" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{__("Square")}}</label>
                    <input type="number" value="{{$row->square}}" placeholder="{{__("Example: 100")}}" name="square" class="form-control">
                </div>
            </div>
        </div>
        @if(is_default_lang())
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">{{__("Minimum advance reservations")}}</label>
                        <input type="number" name="min_day_before_booking" class="form-control" value="{{$row->min_day_before_booking}}" placeholder="{{__("Ex: 3")}}">
                        <i>{{ __("Leave blank if you dont need to use the min day option") }}</i>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">{{__("Minimum day stay requirements")}}</label>
                        <input type="number" name="min_day_stays" class="form-control" value="{{$row->min_day_stays}}" placeholder="{{__("Ex: 2")}}">
                        <i>{{ __("Leave blank if you dont need to set minimum day stay option") }}</i>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endif
