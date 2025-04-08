<!-- 
<div class="form-section">
    <h4 class="form-section-title">{{__(' Payment Options')}}</h4>
    <div class="gateways-table accordion" id="accordionExample">
        @foreach($gateways as $k=>$gateway)
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <label class="" data-toggle="collapse" data-target="#gateway_{{$k}}" >
                            <input type="radio" name="payment_gateway" value="{{$k}}">
                            @if($logo = $gateway->getDisplayLogo())
                                <img src="{{$logo}}" alt="{{$gateway->getDisplayName()}}">
                            @endif
                            {{$gateway->getDisplayName()}}
                        </label>
                    </h4>
                </div>
                <div id="gateway_{{$k}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="gateway_name">
                            <button>Pay Now</button>
                            {!! $gateway->getDisplayName() !!}
                        </div>
                        {!! $gateway->getDisplayHtml() !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>-->


<div class="form-section">
    <h4 class="form-section-title">{{ __('Payment Options') }}</h4>
    <div class="gateways-table accordion" id="accordionExample">
        
        <!-- Pay Now -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <label>
                        <input type="radio" name="payment_gateway" class="payment-option" data-target="#pay_now_section">
                        Pay Now 50000
                    </label>
                </h4>
            </div>
            <div id="pay_now_section" class="collapse" data-parent="#accordionExample">
                <div class="card-body">
                    <form id="razorpay-form">
                        <!-- Razorpay button script will be injected here -->
                    </form>
                </div>
            </div>
        </div>

        <!-- Full Payment -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <label>
                        <input type="radio" name="payment_gateway" class="payment-option" data-target="#full_payment_section">
                        Full Payment {{ format_money($booking->total) }}
                    </label>
                </h4>
            </div>
            <div id="full_payment_section" class="collapse" data-parent="#accordionExample">
                <div class="card-body">
                    <button class="pay-full-btn">Make Payment</button>
                </div>
            </div>
        </div>

        <!-- Custom Payment -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <label>
                        <input type="radio" name="payment_gateway" class="payment-option" data-target="#custom_payment_section">
                        Custom Payment
                    </label>
                </h4>
            </div>
            <div id="custom_payment_section" class="collapse" data-parent="#accordionExample">
                <div class="card-body">
                    <div class="gateway_name">
                        <input type="number" id="paymentAmount" placeholder="Enter Amount" min="1">
                        <button class="pay-now-btn">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- <form><script src="https://checkout.razorpay.com/v1/payment-button.js" data-payment_button_id="pl_Q34lXHTvhP9Dxj" async> </script> </form> -->

    </div>
</div>
 @push('js')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle radio button click to open the corresponding accordion section
        document.querySelectorAll(".payment-option").forEach(function (radio) {
            radio.addEventListener("click", function () {
                let targetId = this.getAttribute("data-target");
                let targetCollapse = document.querySelector(targetId);

                // Collapse all sections first
                document.querySelectorAll(".collapse").forEach(function (collapse) {
                    collapse.classList.remove("show");
                });

                // Show the selected section
                if (targetCollapse) {
                    targetCollapse.classList.add("show");

                    // If it's the "Pay Now" section, load the Razorpay button dynamically
                    if (targetId === "#pay_now_section") {
                        loadRazorpayButton();
                    }
                }
            });
        });

        // Function to dynamically inject Razorpay button inside the form
        function loadRazorpayButton() {
            let form = document.getElementById("razorpay-form");
            form.innerHTML = ''; // Clear any previous script

            let script = document.createElement("script");
            script.src = "https://checkout.razorpay.com/v1/payment-button.js";
            script.setAttribute("data-payment_button_id", "pl_Q31xDPSoNXmWNm");
            script.async = true;

            form.appendChild(script);
        }

        // Handle Custom Payment "Pay Now" button click
        document.querySelector(".pay-now-btn").addEventListener("click", function () {
            let amount = document.getElementById("paymentAmount").value;

            if (!amount || amount <= 0) {
                alert("Please enter a valid amount!");
                return;
            }

            // Redirect to Razorpay payment page with the entered amount
            let razorpayUrl = `https://razorpay.me/@travels2020?amount=${amount}`;
            window.location.href = razorpayUrl;
        });

        // Handle Full Payment button click
        document.querySelector(".pay-full-btn").addEventListener("click", function () {
            // Parse the total amount safely
            let fullPaymentAmount = @json((int) $booking->total);

            let razorpayUrl = `https://razorpay.me/@travels2020?amount=${fullPaymentAmount}`;
            window.location.href = razorpayUrl;
        });
    });
</script>
@endpush