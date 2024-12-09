<?php
include('assets/conn.php');

$features = ['Wi-Fi', 'AC', 'Projector', 'Audio System', 'Smart Board', 'Black Board'];
if (isset($_GET['hall_id'])) {
    $hall_id = intval($_GET['hall_id']);
    $sql = "SELECT h.*, s.school_name, d.department_name
    FROM hall_details h
    LEFT JOIN schools s ON h.school_id = s.school_id
    LEFT JOIN departments d ON h.department_id = d.department_id
    WHERE h.hall_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo '<p>Database error: ' . htmlspecialchars($conn->error) . '</p>';
        exit;
    }
    $stmt->bind_param("i", $hall_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo '<p>Room not found.</p>';
        exit;
    }
} else {
    echo '<p>No room selected.</p>';
    exit;
}

function getDaysInMonth($year, $month) {
    return date('t', mktime(0, 0, 0, $month, 1, $year));
}
function getBookedSlots($conn, $hall_id, $date) {
    $query = "SELECT slot_or_session FROM bookings 
              WHERE hall_id = ? 
              AND ? BETWEEN start_date AND end_date
              AND status IN ('approved', 'booked')";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $hall_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookedSlots = [];
    while ($row = $result->fetch_assoc()) {
        $bookedSlots = array_merge($bookedSlots, explode(',', $row['slot_or_session']));
    }
    return array_map('intval', $bookedSlots);
}
$calendar = [];
$currentDate = new DateTime();
for ($i = 0; $i < 3; $i++) {
    $year = $currentDate->format('Y');
    $month = $currentDate->format('n');
    $daysInMonth = getDaysInMonth($year, $month);

    $monthCalendar = [
        'year' => $year,
        'month' => $month,
        'days' => []
    ];

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
        $bookedSlots = getBookedSlots($conn, $hall_id, $date);
        $monthCalendar['days'][] = [
            'date' => $date,
            'bookedSlots' => $bookedSlots
        ];
    }

    $calendar[] = $monthCalendar;
    $currentDate->modify('+1 month');
}

$calendarJson = json_encode($calendar);
$currentDateTime = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['hall_name']); ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        .navbar {
            background-color: #007bff;
            z-index: 100;
            position: fixed;
            width: 100%;
            top: 0;
        }
        .navbar span {
            color: white;
        }
        .booking-container {
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .hall-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 20px;
        }
        .hall-details {
            flex: 0 0 25%;
            max-width: 25%;
            background-color: #ffffff;
            padding: 15px;
            text-align: left;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .calendar-container {
            padding: 20px;
            flex: 0 0 75%;
            max-width: 75%;
        }
        .back_button {
    position: fixed; /* Or use 'absolute' if you want it to move with the content */
    top: 130px; /* Adjust for vertical positioning */
    right: 20px; /* Adjust for horizontal positioning */
    padding: 10px 20px;
    background-color: #6c757d;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    font-family: Arial, sans-serif;
    font-size: 14px;
    transition: background-color 0.3s ease;
    z-index: 1000; /* Ensure it stays on top of other elements */
}

.back_button:hover {
    background-color: #5a6268;
    cursor: pointer;
}

        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: start;
            gap: 10px;
            margin: 20px 0;
        }
        .legend-item {
            display: flex;
            align-items: center;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border: 1px solid #000;
        }
        .feature-icon {
            font-size: 1.2em;
            color: #007bff;
        }
        .month-calendar {
            margin-bottom: 30px;
            position: relative;
        }
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }
        .calendar-table th, .calendar-table td {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #737373;
            padding: 5px;
            text-align: center;
        }
        .calendar-table th {
            background-color: #f2f2f2;
        }
        .time-slot {
            font-size: 0.8em;
        }
        .available {
            background-color: #d2ffdc;
        }
        .booked {
            background-color: #ffcdd1;
        }
        .past {
            background-color: #d7d7d7; /* Gray color */
            pointer-events: none;     /* Make non-clickable */
        }

        .month-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .prev-month, .next-month {
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: #007bff;
        }
        .prev-month:disabled, .next-month:disabled {
            color: #8e8e8e;
            cursor: not-allowed;
        }
        @media (max-width: 992px) {
            .hall-row {
                flex-direction: column;
            }
            .hall-details, .calendar-container {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        h2{
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light" style="position: fixed;">
        <img src="image/logo/PU_Logo_Full.png" alt="Pondicherry University Logo" style="margin: 10px 50px;" class="logo">
        <h5 style="color: white; margin-right:100px;">UNIVERSITY HALL BOOKING SYSTEM</h5>
    </nav>


    <div class="booking-container">
        <a class="back_button" href="javascript:history.back()" class="btn btn-secondary" style="margin-bottom: 20px;">Back</a>
        <br><br><br>
        <br>

        <div class="hall-row">
            <div class="hall-details">
               <center> <h2>Hall Details</h2></center><br>
                <strong>Hall Name:</strong> <?= htmlspecialchars($room['hall_name']) ?><br><br>
                <strong>In-Charge:</strong> <?= htmlspecialchars($room['incharge_name']) ?><br>
                <strong>In-Charge Phone:</strong> <?= htmlspecialchars($room['phone']) ?><br>
                <strong>In-Charge Email:</strong> 
                <a href="mailto:<?= htmlspecialchars($room['incharge_email']) ?>" style="color: blue; text-align: left;">
                    <?= htmlspecialchars($room['incharge_email']) ?>
                </a><br><br>      
                <span><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></span><br>
                <br><span><strong>Features:</strong></span>
                <div class="feature-grid">
                    <?php foreach ($features as $feature): ?>
                        <div class="feature-item">
                            <i class="feature-icon fas fa-check-circle"></i>
                            <span style="padding-left:10px;" class="feature-name"><?php echo htmlspecialchars(trim($feature)); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <br><center>
    <!-- Book Now Button -->
    <a href="book_room.php?hall_id=<?php echo $room['hall_id']; ?>
    &type_id=<?php echo $room['type_id']; ?>
    &school_name=<?php echo urlencode($room['school_name']); ?>
    &department_name=<?php echo urlencode($room['department_name']); ?>
    &room_name=<?php echo urlencode($room['hall_name']); ?>" 
    class="btn btn-primary">Book</a>
    </center>
            </div>

            <div class="calendar-container">
        <br><br>

        <div class="calendar-legend">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #d2ffdc;"></div>
                <span>Available</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ffcdd1;"></div>
                <span>Booked</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #d7d7d7;"></div>
                <span>Past</span>
            </div>
        </div>

        <div id="calendar-container"></div>
    </div>

    <script>
        const calendarData = <?php echo $calendarJson; ?>;
        const currentDateTime = new Date('<?php echo $currentDateTime; ?>');
        const timeSlots = [
            {slot: 1, time: '09:30 AM'}, {slot: 2, time: '10:30 AM'}, {slot: 3, time: '11:30 AM'}, {slot: 4, time: '12:30 PM'},
            {slot: 5, time: '01:30 PM'}, {slot: 6, time: '02:30 PM'}, {slot: 7, time: '03:30 PM'}, {slot: 8, time: '04:30 PM'}
        ];

        let currentMonthIndex = 0;

        function renderCalendar(calendarData, startIndex = 0) {
            const container = document.getElementById('calendar-container');
            container.innerHTML = '';

            const monthElement = document.createElement('div');
            monthElement.className = 'month-calendar';
            const month = calendarData[startIndex];
            
            const prevButton = startIndex === 0 ? '' : '<button class="prev-month" onclick="showPrevMonth()">←</button>';
            const nextButton = startIndex === calendarData.length - 1 ? '' : '<button class="next-month" onclick="showNextMonth()">→</button>';
            
            monthElement.innerHTML = `
                <div class="month-header">
                    ${prevButton}
                    <h3>${new Date(month.year, month.month - 1).toLocaleString('default', { month: 'long', year: 'numeric' })}</h3>
                    ${nextButton}
                </div>
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            ${month.days.map(day => `<th>${new Date(day.date).getDate()}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        ${renderTimeSlots(month.days)}
                    </tbody>
                </table>
            `;
            container.appendChild(monthElement);
        }

        function renderTimeSlots(days) {
    // Get the current date and time (update dynamically when function is called)
    const currentDateTime = new Date(); 

    return timeSlots.map(({slot, time}) => `
        <tr>
            <td class="time-slot">${time}</td>
            ${days.map(day => {
                // Parse time in AM/PM format and convert to 24-hour format
                const [hours, minutes, period] = time.match(/(\d+):(\d+)\s(AM|PM)/).slice(1);
                const hour = period === 'PM' && hours !== '12' ? parseInt(hours) + 12 : (period === 'AM' && hours === '12' ? 0 : parseInt(hours));
                const formattedTime = `${String(hour).padStart(2, '0')}:${minutes}`;
                
                // Combine date and time for the slot
                const slotDateTime = new Date(`${day.date}T${formattedTime}`);

                // Compare slotDateTime with currentDateTime
                if (slotDateTime <= currentDateTime) {
                    return '<td class="past"></td>'; // Add 'past' styling
                } else if (day.bookedSlots.includes(slot)) {
                    return '<td class="booked"></td>'; // Add 'booked' styling
                } else {
                    return '<td class="available"></td>'; // Add 'available' styling
                }
            }).join('')}
        </tr>
    `).join('');
}




        function showPrevMonth() {
            if (currentMonthIndex > 0) {
                currentMonthIndex--;
                renderCalendar(calendarData, currentMonthIndex);
            }
        }

        function showNextMonth() {
            if (currentMonthIndex < calendarData.length - 1) {
                currentMonthIndex++;
                renderCalendar(calendarData, currentMonthIndex);
            }
        }

        // Initial render
        renderCalendar(calendarData);
    </script>
</body>
</html>

