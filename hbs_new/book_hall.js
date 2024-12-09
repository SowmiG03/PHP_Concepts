    // Utility to get the current date in YYYY-MM-DD format
    function getCurrentDate() {
        const today = new Date();
        return today.toISOString().split('T')[0];
    }

    // Utility to get a date 3 months from now in YYYY-MM-DD format
    function getThreeMonthsLaterDate() {
        const today = new Date();
        today.setMonth(today.getMonth() + 3);
        return today.toISOString().split('T')[0];
    }

    function toggleWeekdays(value) {
        const weekdayField = document.getElementById('weekdayField');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        if (value === 'yes') {
            // Show the weekday field and set default semester booking dates
            weekdayField.classList.remove('hidden');
            startDate.value = getCurrentDate();
            startDate.readOnly = true; // Make "From Date" read-only
            endDate.value = getThreeMonthsLaterDate();
            endDate.readOnly = true; // Make "To Date" read-only
        } else {
            // Hide the weekday field and reset date inputs to editable state
            weekdayField.classList.add('hidden');
            startDate.value = "";
            startDate.readOnly = false;
            endDate.value = "";
            endDate.readOnly = false;
        }
    }
    function showSessionOptions() {
            document.getElementById('session_options').style.display = 'block';
            document.getElementById('slot_options').style.display = 'none';
        }

        function showSlotOptions() {
            document.getElementById('session_options').style.display = 'none';
            document.getElementById('slot_options').style.display = 'block';
        }
    document.addEventListener("DOMContentLoaded", function () {
        const startDateInput = document.getElementById("start_date");
        const endDateInput = document.getElementById("end_date");

        // Disable past dates
        const today = getCurrentDate();
        startDateInput.setAttribute("min", today);
        endDateInput.setAttribute("min", today);

        // Ensure "To Date" can't be earlier than "From Date"
        startDateInput.addEventListener("input", function () {
            const startDate = new Date(startDateInput.value);
            if (!endDateInput.value || new Date(endDateInput.value) < startDate) {
                endDateInput.value = startDateInput.value;
            }
            endDateInput.setAttribute("min", startDateInput.value);
        });

        endDateInput.addEventListener("input", function () {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                endDateInput.value = startDateInput.value;
            }
        });
    });

     // Include all JavaScript functions here (showSessionOptions, showSlotOptions, checkAvailability, etc.)
     function showSessionOptions() {
        document.getElementById('session_options').style.display = 'block';
        document.getElementById('slot_options').style.display = 'none';
    }

    function showSlotOptions() {
        document.getElementById('session_options').style.display = 'none';
        document.getElementById('slot_options').style.display = 'block';
    }

    function checkAvailability() {
console.log("Button clicked");

// Check if all required fields are filled
const hallId = document.getElementById('room').value;
const startDate = document.getElementById('start_date').value;
const endDate = document.getElementById('end_date').value;
const bookingType = document.querySelector('input[name="booking_type"]:checked');

if (!hallId || !startDate || !endDate || !bookingType) {
    alert("Please fill in all required fields before checking availability.");
    return;
}

const bookingTypeValue = bookingType.value;

if (bookingTypeValue === 'session') {
    const selectedSession = document.querySelector('input[name="session_choice"]:checked');
    if (!selectedSession) {
        alert("Please select a session before checking availability.");
        return;
    }
} else if (bookingTypeValue === 'slot') {
    const selectedSlots = document.querySelectorAll('input[name="slots[]"]:checked');
    if (selectedSlots.length === 0) {
        alert("Please select at least one time slot before checking availability.");
        return;
    }
}

const formData = new FormData(document.getElementById('checkAvailabilityForm'));

// Determine slot_or_session value
let slotOrSession = '';
if (bookingTypeValue === 'session') {
    slotOrSession = document.querySelector('input[name="session_choice"]:checked').value;
} else {
    slotOrSession = Array.from(document.querySelectorAll('input[name="slots[]"]:checked'))
                         .map(input => input.value)
                         .join(',');
}

// Set the slot_or_session hidden input
document.getElementById('slot_or_session').value = slotOrSession;

fetch('check_availability.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    const availabilityStatus = document.getElementById('availabilityStatus');
    if (data.availability === 'available') {
        availabilityStatus.innerHTML = "<p class='text-success'>The room is available for booking!</p>";
        document.getElementById('bookNowContainer').style.display = 'block';  // Show button
    } else {
        availabilityStatus.innerHTML = "<p class='text-danger'>The room is already booked for your selected time.</p>";
        document.getElementById('bookNowContainer').style.display = 'none';  // Hide button
    }
})
.catch(error => {
    console.error('Error:', error);
    document.getElementById('availabilityStatus').innerHTML = "<p class='text-danger'>An error occurred while checking availability. Please try again.</p>";
});
}

    function proceedToBooking() {
        const form = document.getElementById('checkAvailabilityForm');
        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();
        window.location.href = 'complete_booking.php?' + queryString;
    }

// Load departments based on selected school and hall type
$('#school').change(function() {
var school_id = $(this).val();
var type_id = $('input[name="type_id"]:checked').val();

// Clear the department and room selections
$('#department').html('<option value="">Select Department</option>');
$('#room').html('<option value="">Select Room</option>');

if (school_id && type_id) {
    $.ajax({
        type: 'POST',
        url: 'fetch_departments.php',
        data: {school_id: school_id, type_id: type_id},
        success: function(response) {
            console.log(response);  // Log the response for debugging
            $('#department').html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching departments:", error);
        }
    });
}
});

// Load rooms based on selected department and hall type
$('#department').change(function() {
var department_id = $(this).val();
var type_id = $('input[name="type_id"]:checked').val();

// Clear the room selection
$('#room').html('<option value="">Select Room</option>');

if (department_id && type_id) {
    $.ajax({
        type: 'POST',
        url: 'fetch_rooms.php',
        data: {department_id: department_id, type_id: type_id},
        success: function(response) {
            console.log("Department ID:", department_id);
            console.log("Hall Type:", type_id);
            $('#room').html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching rooms:", error);
        }
    });
}
});

$('input[name="hall_type"]').change(function() {
// Clear all dropdowns
$('#school').val('').change(); // Reset school dropdown
$('#department').html('<option value="">Select Department</option>'); // Reset department dropdown
$('#room').html('<option value="">Select Room</option>'); // Reset room dropdown
});
document.getElementById('bookNowButton').addEventListener('click', function() {
    // Redirect to complete_booking.php with all form details as a query string
    const hallId = document.querySelector('[name="hall_id"]').value; // Get hall_id from the form
const startDate = document.getElementById('start_date').value; // Start date input
const endDate = document.getElementById('end_date').value; // End date input
const slotOrSession = document.querySelector('[name="slot_or_session"]').value; // Slot or session input

// Create the query string from the collected data
const queryString = new URLSearchParams({
    hall_id: hallId,
    start_date: startDate,
    end_date: endDate,
    slot_or_session: slotOrSession
}).toString();

// Redirect to complete_booking.php with the query string
window.location.href = 'complete_booking.php?' + queryString;
});

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const checkAvailabilityForm = document.getElementById('checkAvailabilityForm');
        if (checkAvailabilityForm) {
    checkAvailabilityForm.addEventListener('submit', function(event) {
        event.preventDefault();
        checkAvailability();
    });
}
document.addEventListener('click', function(event) {
    const suggestionsDiv = document.getElementById("suggestions");
    if (suggestionsDiv && !suggestionsDiv.contains(event.target) && event.target.id !== 'organiser_department') {
        suggestionsDiv.style.display = 'none';
    }
});

});
// Utility to get the current date in YYYY-MM-DD format
function getCurrentDate() {
    const today = new Date();
    return today.toISOString().split('T')[0];
}

// Utility to get a date 3 months from now in YYYY-MM-DD format
function getThreeMonthsLaterDate() {
    const today = new Date();
    today.setMonth(today.getMonth() + 3);
    return today.toISOString().split('T')[0];
}

function toggleWeekdays(value) {
    const weekdayField = document.getElementById('weekdayField');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    if (value === 'yes') {
        // Show the weekday field and set default semester booking dates
        weekdayField.classList.remove('hidden');
        startDate.value = getCurrentDate();
        startDate.readOnly = true; // Make "From Date" read-only
        endDate.value = getThreeMonthsLaterDate();
        endDate.readOnly = true; // Make "To Date" read-only
    } else {
        // Hide the weekday field and reset date inputs to editable state
        weekdayField.classList.add('hidden');
        startDate.value = "";
        startDate.readOnly = false;
        endDate.value = "";
        endDate.readOnly = false;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    // Disable past dates
    const today = getCurrentDate();
    startDateInput.setAttribute("min", today);
    endDateInput.setAttribute("min", today);

    // Ensure "To Date" can't be earlier than "From Date"
    startDateInput.addEventListener("input", function () {
        const startDate = new Date(startDateInput.value);
        if (!endDateInput.value || new Date(endDateInput.value) < startDate) {
            endDateInput.value = startDateInput.value;
        }
        endDateInput.setAttribute("min", startDateInput.value);
    });

    endDateInput.addEventListener("input", function () {
        if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
            endDateInput.value = startDateInput.value;
        }
    });
});