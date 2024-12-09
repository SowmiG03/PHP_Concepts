<?php
include('assets/conn.php');

$features = ['Wi-Fi', 'AC', 'Projector', 'Audio System', 'Smart Board', 'Black Board'];
if (isset($_GET['hall_id'])) {
    $hall_id = intval($_GET['hall_id']);
    $sql = "SELECT * FROM hall_details WHERE hall_id = ?";

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

// Function to get the number of days in a month
function getDaysInMonth($year, $month) {
    return date('t', mktime(0, 0, 0, $month, 1, $year));
}

// Function to check if a date is booked
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

// Generate calendar for 3 months
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

// Encode calendar data as JSON for use in JavaScript
$calendarJson = json_encode($calendar);
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
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 14px;
            transition: background-color 0.3s ease;
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
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        .calendar-day {
            border: 1px solid #eee;
            padding: 5px;
            text-align: center;
        }
        .calendar-day-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .time-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2px;
            font-size: 0.7em;
        }
        .time-slot {
            padding: 2px;
            border-radius: 2px;
        }
        .time-slot.available {
            background-color: #d4edda;
        }
        .time-slot.booked {
            background-color: #f8d7da;
        }
        .booked-fn { background-color: #ffc107; }
        .booked-an { background-color: #17a2b8; }
        .booked-both { background-color: #dc3545; }
        @media (max-width: 992px) {
            .hall-row {
                flex-direction: column;
            }
            .hall-details, .calendar-container {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light" style="position: fixed;">
        <img src="image/logo/PU_Logo_Full.png" alt="Pondicherry University Logo" style="margin: 10px 50px;" class="logo">
        <h5 style="color: white; margin-right:100px;">UNIVERSITY HALL BOOKING SYSTEM</h5>
    </nav>

    <br><br><br>

    <div class="booking-container">
        <a class="back_button" href="javascript:history.back()" class="btn btn-secondary" style="margin-bottom: 20px;">Back</a>

        <div class="hall-row">
            <div class="hall-details">
                <h2>Hall Details</h2><br>
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
            </div>

            <div class="calendar-container">
                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #d4edda;"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #f8d7da;"></div>
                        <span>Booked</span>
                    </div>
                </div>

                <div id="calendar-container"></div>
            </div>
        </div>
    </div>

    <script>
        const calendarData = <?php echo $calendarJson; ?>;

        function renderCalendar(calendarData) {
            const container = document.getElementById('calendar-container');
            container.innerHTML = '';

            calendarData.forEach(month => {
                const monthElement = document.createElement('div');
                monthElement.className = 'month-calendar';
                monthElement.innerHTML = `
                    <h3>${new Date(month.year, month.month - 1).toLocaleString('default', { month: 'long', year: 'numeric' })}</h3>
                    <div class="calendar-grid">
                        ${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => `<div class="calendar-day font-weight-bold">${day}</div>`).join('')}
                        ${getEmptyCells(month.year, month.month)}
                        ${month.days.map(day => renderDay(day)).join('')}
                    </div>
                `;
                container.appendChild(monthElement);
            });
        }

        function getEmptyCells(year, month) {
            const firstDay = new Date(year, month - 1, 1).getDay();
            return Array(firstDay).fill('<div class="calendar-day"></div>').join('');
        }

        function renderDay(day) {
            const date = new Date(day.date);
            let content = `<div class="calendar-day-number">${date.getDate()}</div>`;
            
            const timeSlots = [
                {slot: 1, time: '9:30'}, {slot: 2, time: '10:30'}, {slot: 3, time: '11:30'}, {slot: 4, time: '12:30'},
                {slot: 5, time: '1:30'}, {slot: 6, time: '2:30'}, {slot: 7, time: '3:30'}, {slot: 8, time: '4:30'}
            ];

            content += '<div class="time-slots">';
            timeSlots.forEach(({slot, time}) => {
                const isBooked = day.bookedSlots.includes(slot);
                content += `<div class="time-slot ${isBooked ? 'booked' : 'available'}" title="${time}">${time}</div>`;
            });
            content += '</div>';

            return `<div class="calendar-day">${content}</div>`;
        }

        // Render the calendar
        renderCalendar(calendarData);
    </script>
</body>
</html>

