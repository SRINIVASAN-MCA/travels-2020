<!-- <form id="otp-form" class="bravo-form-login"> -->
<form class="bravo-form-otplogin" method="POST" action="{{ route('otplogin') }}">
    @csrf
    <div id="phone-section">
        <div class="form-group">
            <input type="number" class="form-control" id="phone" name="phone" autocomplete="off" required placeholder="{{__('Enter your Mobile number')}}">
            <i class="input-icon icofont-phone"></i>
            <span class="invalid-feedback error error-phone"></span>
        </div>
        
        <button type="button" id="send-otp-btn" class="btn btn-primary">Send OTP</button>
    </div>

    <!-- OTP Verification Section (Hidden by Default) -->
    <div id="otp-section" style="display: none;">
        <div class="form-group">
            <input type="text" class="form-control" id="otp" name="otp" autocomplete="off" required placeholder="Enter OTP">
            <span class="invalid-feedback error error-otp"></span>
        </div>
        
        <button type="button" id="verify-otp-btn" class="btn btn-success">Verify OTP</button>
    </div>
</form>

<!-- JavaScript for OTP Handling -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const sendOtpBtn = document.getElementById("send-otp-btn");
    const verifyOtpBtn = document.getElementById("verify-otp-btn");
    
    sendOtpBtn.addEventListener("click", function () {
        let phone = document.getElementById("phone").value;

        if (phone.length !== 10) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }

        fetch("{{ route('send.otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("OTP Sent Successfully!");
                document.getElementById("phone-section").style.display = "none";
                document.getElementById("otp-section").style.display = "block";
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.log(error));
    });

    verifyOtpBtn.addEventListener("click", function () {
        let otp = document.getElementById("otp").value;
        let phone = document.getElementById("phone").value;

        fetch("{{ route('verify.otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ phone: phone, otp: otp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Login Successful!");
                // window.location.href = "{{ route('dashboard') }}"; 
            } else {
                alert("Invalid OTP! Please try again.");
            }
        })
        .catch(error => console.log(error));
    });
});
</script>
