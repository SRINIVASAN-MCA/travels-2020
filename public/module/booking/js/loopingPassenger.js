
document.getElementById("passengersubmit").addEventListener("click", function (event) {
    event.preventDefault(); // Prevent form submission

    let travellersData = [];

    // Loop through each traveller section and collect data
    document.querySelectorAll(".traveller-section").forEach((traveller, index) => {
        let travellerID = index + 1; // 1-based index

        let formData = {
            title: document.querySelector(`input[name="title[${travellerID}]"]:checked`).value,
            first_name: document.querySelector(`[name="travellers[${travellerID}][first_name]"]`).value,
            last_name: document.querySelector(`[name="travellers[${travellerID}][last_name]"]`).value,
            dob: document.querySelector(`[name="travellers[${travellerID}][dob]"]`).value,
            passport_number: document.querySelector(`[name="travellers[${travellerID}][passport_number]"]`).value,
            issue_date: document.querySelector(`[name="travellers[${travellerID}][issue_date]"]`).value,
            expiry_date: document.querySelector(`[name="travellers[${travellerID}][expiry_date]"]`).value,
            country: document.querySelector(`[name="travellers[${travellerID}][country]"]`).value,
            meal_preference: document.querySelector(`[name="travellers[${travellerID}][meal_preference]"]`).value,
            pan_number: document.getElementById("pan_number").value
        };

        // Append file inputs
        let passportFront = document.querySelector(`[name="travellers[${travellerID}][passport_front]"]`).files[0];
        let passportBack = document.querySelector(`[name="travellers[${travellerID}][passport_back]"]`).files[0];

        if (passportFront) {
            formData.append(`travellers[${travellerID}][passport_front]`, passportFront);
        }
        if (passportBack) {
            formData.append(`travellers[${travellerID}][passport_back]`, passportBack);
        }

        travellersData.push(formData);
    });

    // Prepare payload
    let payload = {
        code: document.querySelector('input[name="code"]').value,
        travellers: travellersData,
        term_conditions: document.querySelector('input[name="term_conditions"]').checked ? 1 : 0
    };

    console.log("Payload:", payload); // Debugging - check payload before sending

    // Send AJAX request using Fetch API
    fetch("{{ route('booking.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify(payload)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Booking saved successfully!");
                window.location.href = data.redirect_url; // Redirect if necessary
            } else {
                alert("Error saving booking. Please try again.");
            }
        })
        .catch(error => console.error("Error:", error));
});

