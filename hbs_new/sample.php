<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Filters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Room Booking Filters</h1>
    <form id="hallForm" method="GET" action="">
    <div class="col-md-6">
                <label for="capacity" class="form-label">Capacity:</label>
                <div class="form-check">
                    <input type="radio" name="capacity" value="50" id="capacity50" class="form-check-input"
                        <?php echo (isset($_GET['capacity']) && $_GET['capacity'] == '50') ? 'checked' : ''; ?>>
                    <label for="capacity50" class="form-check-label">Up to 50</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="capacity" value="100" id="capacity100" class="form-check-input"
                        <?php echo (isset($_GET['capacity']) && $_GET['capacity'] == '100') ? 'checked' : ''; ?>>
                    <label for="capacity100" class="form-check-label">Up to 100</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="capacity" value="150" id="capacity150" class="form-check-input"
                        <?php echo (isset($_GET['capacity']) && $_GET['capacity'] == '150') ? 'checked' : ''; ?>>
                    <label for="capacity150" class="form-check-label">Up to 150</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="capacity" value="200" id="capacity200" class="form-check-input"
                        <?php echo (isset($_GET['capacity']) && $_GET['capacity'] == '200') ? 'checked' : ''; ?>>
                    <label for="capacity200" class="form-check-label">200 or More</label>
                </div>
            </div>

            <!-- Features Filter -->
            <div class="col-md-6">
                <label class="form-label">Features:</label>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="projector" id="projector" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('projector', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="projector" class="form-check-label">Projector</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="smart_board" id="smartboard" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('smart_board', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="smartboard" class="form-check-label">Smartboard</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="blackboard" id="blackboard" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('blackboard', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="blackboard" class="form-check-label">Blackboard</label>
                </div>
                <!-- Add other features as checkboxes -->
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="ac" id="ac" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('ac', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="ac" class="form-check-label">AC</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="computer" id="computer" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('computer', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="computer" class="form-check-label">Computer</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="audio_system" id="audio_system" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('audio_system', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="audio_system" class="form-check-label">Audio System</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="podium" id="podium" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('podium', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="podium" class="form-check-label">Podium</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="ramp" id="ramp" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('ramp', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="ramp" class="form-check-label">Ramp</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="white_board" id="white_board" class="form-check-input"
                        <?php echo (isset($_GET['features']) && in_array('white_board', $_GET['features'])) ? 'checked' : ''; ?>>
                    <label for="white_board" class="form-check-label">White Board</label>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Filter</button>
                <button type="button" class="clear btn btn-secondary" onclick="clearForm()">Clear</button>
            </div>
        </div>
    </form>

    <!-- Results -->
<div class="mt-5">
    <h2>Available Rooms</h2>
    <div class="row" id="roomsContainer">
        <?php
        // Include backend logic to fetch rooms
        include 'sam.php'; // Make sure the logic for fetching rooms is here
        ?>
    </div>
</div>

<script>
    function clearForm() {
        // Reset all fields in the form
        document.getElementById('hallForm').reset();
    }
</script>
</body>
</html>
