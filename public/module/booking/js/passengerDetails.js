$(document).ready(function () {
    let storedPassengers = {}; // To track passengers' submitted data

    function clearNewForm(form) {
        form.find('input[type="text"], input[type="date"], input[type="number"]').val('');
        form.find('input[type="file"]').val('');
        form.find('select').prop('selectedIndex', 0);
    }


    $(document).on("click", ".passengersubmit", function (event) {
        event.preventDefault();

        let passengerNumber = $(this).attr("data-passenger");
        if (!passengerNumber) {
            console.error("Passenger number not found!");
            return;
        }

        let form = $("#traveller-form-" + passengerNumber);
        if (!form.length) {
            console.error("Form not found for Passenger", passengerNumber);
            return;
        }

        let formData = new FormData();
        let passengerData = {};
        let isValid = true;

        let fields = ['whichPassanger', 'title', 'first_name', 'last_name', 'dob', 'passport_number', 'issue_date', 'expiry_date', 'country', 'city', 'pan_number', 'meal_preference'];

        fields.forEach(field => {
            let inputField = form.find("#" + field);
            let value = form.find("#" + field).val() || '';
            if (!value) {
                isValid = false;
                inputField.addClass("is-invalid"); // Bootstrap class to highlight errors
            } else {
                inputField.removeClass("is-invalid");
            }
            formData.append(field, value);
            passengerData[field] = value;
        });

        if (!isValid) {
            alert("Please fill in all required fields before submitting.");
            return;
        }

        // formData.append("whichPassanger", passengerNumber);

        // Get the last segment of the URL path
        let pathSegments = window.location.href.split('/');
        let lastSegment = pathSegments[pathSegments.length - 2];
        formData.append("code", lastSegment);

        let fileInputs = {
            "passport_front": "passportfront",
            "passport_back": "passportback",
            "pan_card": "pancard"
        };

        Object.keys(fileInputs).forEach(id => {
            let file = form.find("#" + id)[0]?.files[0];
            if (file) {
                formData.append(fileInputs[id], file);
            }
        });

        console.log(`Submitting Passenger ${passengerNumber} FormData:`);
        for (let pair of formData.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        $.ajax({
            // url: '/save/passangerDetails',
            url: '/save/bookingpassanger',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(`Success: Passenger ${passengerNumber} saved`, response);
                alert(`Passenger ${passengerNumber} details saved successfully!`);
                storedPassengers[passengerNumber] = true;
                clearNewForm(form);
                form.find(".is-invalid").removeClass("is-invalid");
            },
            error: function (xhr, status, error) {
                console.error(`Submission failed for Passenger ${passengerNumber}:`, error);
                alert('There was an error with the submission.');
            }
        });
    });

    // Fetch and populate data for passengers on page load
    // $(".passenger-form").each(function () {
    //     let passengerNumber = $(this).attr("data-passenger");
    //     if (passengerNumber) {
    //         fetchPassengerData(passengerNumber);
    //     }
    // });

    // Toggle Traveller Form Visibility
    $(".traveller-toggle").on("click", function () {
        let targetForm = $(this).data("target");
        $(targetForm).toggle();

        let form = $(targetForm);

        if (!form.length) {
            console.error("Form not found for Passenger");
            return;
        }

        // Get the last segment of the URL path
        let pathSegments = window.location.href.split('/');
        let lastSegment = pathSegments[pathSegments.length - 2];
        let value = form.find("#whichPassanger").val() || '';

        let passengerNumber = targetForm.split('-');

        if (lastSegment && value) {
            // alert(lastSegment);
            $.ajax({
                url: `/get/passengerDetails/${lastSegment}/${value}`, // Ensure the correct URL
                type: 'GET',  // Use GET method, not POST
                success: function (response) {
                    // console.log(response, "whatttttt");

                    if (response.success) {
                        response.data.forEach(passenger => {
                            if (passengerNumber[2]) {
                                populateForm(passengerNumber[2], passenger);
                            }
                        });
                    } else {
                        console.warn(`No data found for Passenger`);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(`Error fetching Passenger data:`, error);
                }
            });

        }
    });

    // Extract passenger number from "Adults - 3" format
    function extractPassengerNumber(passengerString) {
        let match = passengerString.match(/\d+/);
        return match ? match[0] : null;
    }

    function populateForm(passengerNumber, passengerData) {
        // console.log("----passengerNumber---", passengerNumber);
        // console.log("----passengerData---", passengerData);

        let form = $("#traveller-form-" + passengerNumber);
        if (!form.length) {
            console.error("Form not found for Passenger", passengerNumber);
            return;
        }

        let fields = ['title', 'first_name', 'last_name', 'dob', 'passport_number', 'issue_date', 'expiry_date', 'country', 'city', 'pan_number', 'meal_preference'];

        fields.forEach(field => {
            if (passengerData[field]) {

                // form.find("#" + field).val(passengerData[field]);
                let inputField = form.find("#" + field);
                console.log(`Setting field: #${field}, Found: ${inputField.length}, Value: ${passengerData[field]}`);

                if (inputField.length) {
                    inputField.val(passengerData[field]);
                } else {
                    console.warn(`Field #${field} not found in form`);
                }
            }
        });
    }



    // Tab Navigation
    const backButton = $('#backButton');
    const nextButton = $('#nextButton');
    const tabs = $('.nav-link');
    const tabPanes = $('.tab-pane');
    const termsCheckbox = $('input[name="term_conditions"]');
    let activeIndex = 0;

    // Disable Next button initially
    nextButton.prop('disabled', true);

    // Enable Next button when "Terms & Conditions" checkbox is checked
    termsCheckbox.on('change', function () {
        if ($(this).is(':checked')) {
            nextButton.prop('disabled', false);
        } else {
            nextButton.prop('disabled', true);
        }
    });

    // Function to switch tabs
    function updateTab() {
        tabs.removeClass('active');
        tabPanes.removeClass('show active');

        tabs.eq(activeIndex).addClass('active');
        tabPanes.eq(activeIndex).addClass('show active');

        // Hide Next button on the last tab (Payment)
        if (activeIndex === tabs.length - 1) {
            nextButton.hide();
        } else {
            nextButton.show();
        }
    }

    // Handle back button
    backButton.on('click', function () {
        if (activeIndex > 0) {
            activeIndex--;
        }
        updateTab();
    });

    // Handle next button
    nextButton.on('click', function () {
        if (activeIndex < tabs.length - 1) {
            activeIndex++;
            if (activeIndex === 1) {
                console.log('Moving to Passenger Details...');
            } else if (activeIndex === 2) {
                savePassengerDetails();
            }
        }
        updateTab();
    });

    // Function to save passenger details
    function savePassengerDetails() {
        try {
            alert('Saving passenger details...');
        } catch (error) {
            console.error('Error saving details:', error);
        }
    }

    // Coupon Code Toggle
    $('#applyCouponBtn').on('click', function () {
        $('#couponForm').toggle();
    });


    document.addEventListener("DOMContentLoaded", function () {
        // Handle radio button click to open collapse
        document.getElementById("paymentRadio").addEventListener("click", function () {
            let collapseDiv = document.getElementById("gateway_payment");
            if (!collapseDiv.classList.contains("show")) {
                collapseDiv.classList.add("show");
            }
        });

        // Handle Pay Now click event
        document.querySelector(".pay-now-btn").addEventListener("click", function () {
            let amount = document.getElementById("paymentAmount").value;

            if (!amount || amount <= 0) {
                alert("Please enter a valid amount!");
                return;
            }

            // Redirect to Razorpay payment page with amount (assuming dynamic URL)
            let razorpayUrl = `https://razorpay.me/@travels2020?amount=${amount}`;
            window.location.href = razorpayUrl;
        });
    });

});
