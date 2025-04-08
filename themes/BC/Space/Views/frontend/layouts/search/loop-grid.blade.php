@php
    $translation = $row->translate();
@endphp
<div class="item-loop item-loop-wrap {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image ">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <img src="{{$row->image_url}}" class="img-responsive" alt="">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!}
                @endif
            @endif
        </a>
        <div class="price-wrapper">
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">
                    {{ $row->display_price }}
                    @if($row->getBookingType()=="by_day")
                        <!-- <span class="unit">{{__("/day")}}</span> -->
                        <span class="unit">{{__("/couple")}}</span>
                    @else
                        <!-- <span class="unit">{{__("/night")}}</span> -->
                        <span class="unit">{{__("/couple")}}</span>
                    @endif
                </span>
            </div>
        </div>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->is_instant)
                <i class="fa fa-bolt d-none"></i>
            @endif
                {{$translation->title}}
        </a>
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
    </div>
    <div class="location">
    @if(!empty($row->location->name))
        @php $location = $row->location->translate(); @endphp
        <!-- <div class="mb-2"> -->
            <strong>{{$location->name ?? ''}}</strong>
        <!-- </div> -->
    @endif

    @if(!empty($row->hotel))
        <!-- <div class="mb-2"> -->
            <span class="fw-bold">{{$row->hotel}}</span> 
            <i class="fa fa-star text-warning"></i> Hotel
        <!-- </div> -->
    @endif

    @if(!empty($row->stay))
        <!-- <div class="mb-2"> -->
            <i class="fa fa-check text-success"></i> {{$row->stay}}
        <!-- </div> -->
    @endif
</div>

   
    <div class="item-title">
        @if(!empty($row->shortdesc))
            <div class="item-title">{{$row->shortdesc}}</div>
        @endif
    </div>
    <!-- @if(setting_item('space_enable_review'))
    <?php
    $reviewData = $row->getScoreReview();
    $score_total = $reviewData['score_total'];
    ?>
    <div class="service-review">
        <span class="rate">
            @if($reviewData['total_review'] > 0) {{$score_total}}/5 @endif <span class="rate-text">{{$reviewData['review_text']}}</span>
        </span>
        <span class="review">
             @if($reviewData['total_review'] > 1)
                {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
            @else
                {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
            @endif
        </span>
    </div>
    @endif -->
   
    <!-- <div class="amenities">
        @if($row->max_guests)
            <span class="amenity total" data-toggle="tooltip"  title="{{ __("No. People") }}">
                <i class="input-icon field-icon icofont-people  "></i> {{$row->max_guests}}
            </span>
        @endif
        @if($row->bed)
            <span class="amenity bed" data-toggle="tooltip" title="{{__("No. Bed")}}">
                <i class="input-icon field-icon icofont-hotel"></i> {{$row->bed}}
            </span>
        @endif
        @if($row->bathroom)
            <span class="amenity bath" data-toggle="tooltip" title="{{__("No. Bathroom")}}" >
                <i class="input-icon field-icon icofont-bathtub"></i> {{$row->bathroom}}
            </span>
        @endif
        @if($row->square)
            <span class="amenity size" data-toggle="tooltip" title="{{__("Square")}}" >
                <i class="input-icon field-icon icofont-ruler-compass-alt"></i> {!! size_unit_format($row->square) !!}
            </span>
        @endif
    </div> -->

    <div class="location">
        <p>INCLUDES: Accomodation, Speed boat Transfer, Green Tax, 24x7 online support</p>
    </div>
    @if(!empty($row->activities))
        @php
            $activities = json_decode($row->activities, true);
        @endphp
        <div class="amenities">
            @foreach($activities as $activity)
                <div class="unit">
                    <i class="fa fa-check text-success"></i> {{$activity['title']}}
                </div>
            @endforeach
        </div>
    @endif

    <div class="amenities">
      <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
          <button>View Details </button>
        </a>
    </div>
</div>
