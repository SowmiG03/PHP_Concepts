<?php
include 'assets/conn.php'; // Database connection

if (isset($_GET['type_id'])) {
    $type_id = intval($_GET['type_id']); // Ensure type_id is an integer
    $stmt = $conn->prepare("SELECT * FROM hall_type WHERE type_id = ?");
    $stmt->bind_param("i", $type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch and display the details
        $row = $result->fetch_assoc();
        echo "<h3>Type Details</h3>";
        echo "<p><strong>Type Name:</strong> " . htmlspecialchars($row['type_name']) . "</p>";
        echo "<p><strong>ID:</strong> " . htmlspecialchars($row['type_id']) . "</p>"; // Assuming there's a description field
        // Add more fields as needed
    } else {
        echo "No details found for this room type.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
