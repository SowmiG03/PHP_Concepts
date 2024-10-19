<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/design.css" />
    <title>admin Home</title>

    <style>
        /* Initially hide both forms */
        #seminarHallForm, #auditoriumForm, #complexForm {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-light " style="background-color: #5161ce; z-index: 1;">
            <div class="container-fluid">
                <a class="navbar-brand">Pondicherry University</a>
                <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </div>

    <div class="side-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="add_view_roomtype.php">Room Type</a>
        <a href="add_room.php">Add Room</a>
        <a href="#contact">Contact</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-2">

            </div>
            <div class="col-10 pt-5 mt-5">

                <h1 class="mb-4">Add Room Details</h1>

                

                <!-- <label> Sel</label>
                <input type="text"  name="name" placeholder="Your name.." required> -->
                <form id="roomSelectionForm">
                    <label for="room_type">Room Type</label>
                    <select id="room_type" name="room_type" onchange="showForm()">
                        <option value="#">Select room</option>
                        <option value="auditorium">Auditorium</option>
                        <option value="complex" >Lecture Hall Complex</option>
                        <option value="seminarHall">Seminar Hall</option>
                    </select>
                </form>


                

                <!-- Seminar Hall Form -->
                <form id="seminarHallForm" action="insert.php" method="post">
                    <h3>Seminar Hall Details</h3>

                    <label for="room_type">Room Type</label>
                    <select id="room_type" name="room_type" >
                        <option value="#">Select room</option>
                        <option value="auditorium">Auditorium</option>
                        <option value="complex" >Lecture Hall Complex</option>
                        <option value="seminarHall">Seminar Hall</option>
                    </select>

                    <?php 
                    include 'assets/conn.php';
                    $i =1;
                    $stmt=$conn->query("select * from hall_type where type_name = 'Seminar Hall' ");
                    while($res=$stmt->fetch_assoc()){
                    ?>
                    
                    <!-- <label for="seminar_name">Type ID:</label> -->
                    <input type="hidden" id="type_id" name="type_id" value="<?php echo $res['type_id']; ?>" > 
                    <input type="hidden" id="type_name" name="type_name"  value="<?php echo $res['type_name']; ?>" >

                    <?php 
                      }
                    ?>
                    <label class="mt-4" for="seminar_name">Seminar Name:</label>
                    <input type="text" id="seminar_name" name="seminar_name" placeholder="SeminarHall Name..">

                    <label for="seminar_capacity">Capacity:</label>
                    <input type="number" id="seminar_capacity" name="seminar_capacity" placeholder="capacity..">

                    <label>Location </label>
                    <input type="text" name="location" placeholder="Location.." required>

                    <label>Cost</label>
                    <input type="number" name="cost" placeholder=" Cost.." required>

                    <label>Incharge Name</label>
                    <input type="text" name="incharge_name" placeholder=" Incharge name.." required>

                    <label>Incharge Email</label>
                    <input type="email" name="incharge_email" placeholder="Incharge email.." required>

                    <label>Incharge Phone Number</label>
                    <input type="number" name="phoneNumber" placeholder="Incharge Phone Number.." required>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Upload Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                    </div>

                    

                    <label>Room Availability:</label><br>
                    <div class="mt-2">
                        <label class="avail " for="available">
                            <input type="radio" id="available" name="availability" value="Available">
                            Available
                        </label>

                        <label class="avail" for="not_available">
                            <input type="radio" id="not_available" name="availability" value="Not Available">
                            Not Available
                        </label>

                        <label class="avail" for="in_maintenance">
                            <input type="radio" id="in_maintenance" name="availability" value="In Maintenance">
                            In Maintenance
                        </label><br><br>
                    </div>

                    <label class="form-label">Features:</label>3                   
                    <div class="row mt-2">
                        <!-- WiFi -->
                        <div class="col-md-3 ">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wifi" id="wifi">
                                <label class="form-check-label" for="wifi">
                                    WiFi
                                </label>
                            </div>
                        </div>

                        <!-- Projector -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="projector" id="projector">
                                <label class="form-check-label" for="projector">
                                    Projector
                                </label>
                            </div>
                        </div>

                        <!-- AC -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ac" id="ac">
                                <label class="form-check-label" for="ac">
                                    AC
                                </label>
                            </div>
                        </div>

                        <!-- Audio System -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="audio" id="audio">
                                <label class="form-check-label" for="audio">
                                    Audio System
                                </label>
                            </div>
                        </div>

                        <!-- Smart Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="smartboard" id="smartboard">
                                <label class="form-check-label" for="smartboard">
                                    Smart Board
                                </label>
                            </div>
                        </div>

                        <!-- Power Backup -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="powerbackup" id="powerbackup">
                                <label class="form-check-label" for="powerbackup">
                                    Power Backup
                                </label>
                            </div>
                        </div>

                        <!-- Sound Proof -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="soundproof" id="soundproof">
                                <label class="form-check-label" for="soundproof">
                                    Sound Proof
                                </label>
                            </div>
                        </div>

                        <!-- Ordinary White Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="whiteboard" id="whiteboard">
                                <label class="form-check-label" for="whiteboard">
                                    Ordinary White Board
                                </label>
                            </div>
                        </div>

                        <!-- Black Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="blackboard" id="blackboard">
                                <label class="form-check-label" for="blackboard">
                                    Black Board
                                </label>
                            </div>
                        </div>

                        <!-- Fans -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fan" id="fan">
                                <label class="form-check-label" for="fan">
                                    Fans
                                </label>
                            </div>
                        </div>

                        <!-- Electrical Sockets -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sockets" id="sockets">
                                <label class="form-check-label" for="sockets">
                                    Electrical Sockets
                                </label>
                            </div>
                        </div>

                        <!-- Lift -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lift" id="lift">
                                <label class="form-check-label" for="lift">
                                    Lift
                                </label>
                            </div>
                        </div>
                    </div>

                    <label class="mt-3">Floor name</label> </br>
                    <input type="text" name="floor" placeholder="Enter Floor.."  required>

                    <label for="seminarhall_stage_size">Stage Size:</label>
                    <input type="text" id="auditorium_stage_size" name="auditorium_stage_size" placeholder="Stage size..">


                    <input type="submit" name="submitroom" value="Submit Room Dtails">
                </form>


                <!-- Auditorium Form -->
                <form id="auditoriumForm" action="insert.php" method="post">
                    <h3>Auditorium Details</h3>

                    <?php 
                    include 'assets/conn.php';
                    $i =1;
                    $stmt=$conn->query("select * from hall_type where type_name = 'Auditorium' ");
                    while($res=$stmt->fetch_assoc()){
                    ?>
                    
                    <!-- <label for="seminar_name">Type ID:</label> -->
                    <input type="hidden" id="type_id" name="type_id" value="<?php echo $res['type_id']; ?>" > 
                    <input type="hidden" id="type_name" name="type_name"  value="<?php echo $res['type_name']; ?>" >

                    <?php 
                      }
                    ?>
                    <label for="auditorium_name">Auditorium Name:</label>
                    <input type="text" id="auditorium_name" name="auditorium_name" placeholder="Auditorium Name..">

                    <label for="auditorium_capacity">Capacity:</label>
                    <input type="number" id="auditorium_capacity" name="auditorium_capacity" placeholder="Capacity..">

                    <label>Location </label>
                    <input type="text" name="location" placeholder="Location" required>

                    <label>Cost</label>
                    <input type="number" name="cost" placeholder=" Cost.." required>

                    <label>Incharge Name</label>
                    <input type="text" name="incharge_name" placeholder=" Incharge name.." required>

                    <label>Incharge Email</label>
                    <input type="email" name="incharge_email" placeholder="Incharge email.." required>

                    <label>Incharge Phone Number</label>
                    <input type="number" name="phoneNumber" placeholder="Incharge Phone Number.." required>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Upload Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                    </div>
                    
                    <label>Room Availability:</label><br>
                    <div class="mt-2">
                        <label class="avail " for="available">
                            <input type="radio" id="available" name="availability" value="Available">
                            Available
                        </label>

                        <label class="avail" for="not_available">
                            <input type="radio" id="not_available" name="availability" value="Not Available">
                            Not Available
                        </label>

                        <label class="avail" for="in_maintenance">
                            <input type="radio" id="in_maintenance" name="availability" value="In Maintenance">
                            In Maintenance
                        </label><br><br>
                    </div>

                    <label class="form-label">Features:</label>
                    <div class="row mt-2">
                        <!-- WiFi -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wifi" id="wifi">
                                <label class="form-check-label" for="wifi">
                                    WiFi
                                </label>
                            </div>
                        </div>

                        <!-- Projector -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="projector" id="projector">
                                <label class="form-check-label" for="projector">
                                    Projector
                                </label>
                            </div>
                        </div>

                        <!-- AC -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ac" id="ac">
                                <label class="form-check-label" for="ac">
                                    AC
                                </label>
                            </div>
                        </div>

                        <!-- Audio System -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="audio" id="audio">
                                <label class="form-check-label" for="audio">
                                    Audio System
                                </label>
                            </div>
                        </div>

                        <!-- Smart Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="smartboard" id="smartboard">
                                <label class="form-check-label" for="smartboard">
                                    Smart Board
                                </label>
                            </div>
                        </div>

                        <!-- Power Backup -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="powerbackup" id="powerbackup">
                                <label class="form-check-label" for="powerbackup">
                                    Power Backup
                                </label>
                            </div>
                        </div>

                        <!-- Sound Proof -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="soundproof" id="soundproof">
                                <label class="form-check-label" for="soundproof">
                                    Sound Proof
                                </label>
                            </div>
                        </div>

                        <!-- Ordinary White Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="whiteboard" id="whiteboard">
                                <label class="form-check-label" for="whiteboard">
                                    Ordinary White Board
                                </label>
                            </div>
                        </div>

                        <!-- Black Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="blackboard" id="blackboard">
                                <label class="form-check-label" for="blackboard">
                                    Black Board
                                </label>
                            </div>
                        </div>

                        <!-- Fans -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fan" id="fan">
                                <label class="form-check-label" for="fan">
                                    Fans
                                </label>
                            </div>
                        </div>

                        <!-- Electrical Sockets -->
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sockets" id="sockets">
                                <label class="form-check-label" for="sockets">
                                    Electrical Sockets
                                </label>
                            </div>
                        </div>

                        <!-- Lift -->
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lift" id="lift">
                                <label class="form-check-label" for="lift">
                                    Lift
                                </label>
                            </div>
                        </div>
                    </div>

                    <label class="mt-3">Floor name</label> </br>
                    <input type="text" name="floor" placeholder="Floor .." required>

                    <label for="auditorium_stage_size">Stage Size:</label>
                    <input type="text" id="auditorium_stage_size" name="auditorium_stage_size" placeholder="Stage size..">

                    <input type="submit" name="submitAuditorium" value="Submit Auditorium">
                </form>


                <!-- Complex Form -->
                <form id="complexForm" action="insert.php" method="post">
                    <h3>Lecture Hall Complex Details</h3>

                    <?php 
                    include 'assets/conn.php';
                    $i =1;
                    $stmt=$conn->query("select * from hall_type where type_name = 'Lecture Hall Complex' ");
                    while($res=$stmt->fetch_assoc()){
                    ?>
                    
                    <!-- <label for="seminar_name">Type ID:</label> -->
                    <input type="hidden" id="type_id" name="type_id" value="<?php echo $res['type_id']; ?>" > 
                    <input type="hidden" id="type_name" name="type_name"  value="<?php echo $res['type_name']; ?>" >

                    <?php 
                      }
                    ?> 

                    <label class="mt-4" for="auditorium_name">Lecture Hall Complex Name:</label>
                    <input type="text" id="complex_name" name="complex_name" placeholder="Lecture Hall Complex Name..">

                    <label for="auditorium_capacity">Capacity:</label>
                    <input type="number" id="complex_capacity" name="complex_capacity" placeholder="Capacity..">

                    <label>Location </label>
                    <input type="text" name="location" placeholder="Location" required>

                    <label>Cost</label>
                    <input type="number" name="cost" placeholder=" Cost.." required>

                    <label>Incharge Name</label>
                    <input type="text" name="incharge_name" placeholder=" Incharge name.." required>

                    <label>Incharge Email</label>
                    <input type="email" name="incharge_email" placeholder="Incharge email.." required>

                    <label>Incharge Phone Number</label>
                    <input type="number" name="phoneNumber" placeholder="Incharge Phone Number.." required>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Upload Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                    </div>

                    <label>Room Availability:</label><br>
                    <div class="mt-2">
                        <label class="avail " for="available">
                            <input type="radio" id="available" name="availability" value="Available">
                            Available
                        </label>

                        <label class="avail" for="not_available">
                            <input type="radio" id="not_available" name="availability" value="Not Available">
                            Not Available
                        </label>

                        <label class="avail" for="in_maintenance">
                            <input type="radio" id="in_maintenance" name="availability" value="In Maintenance">
                            In Maintenance
                        </label><br><br>
                    </div>

                    <label class="form-label">Features:</label>
                    <div class="row mt-2">
                        <!-- WiFi -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wifi" id="wifi">
                                <label class="form-check-label" for="wifi">
                                    WiFi
                                </label>
                            </div>
                        </div>

                        <!-- Projector -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="projector" id="projector">
                                <label class="form-check-label" for="projector">
                                    Projector
                                </label>
                            </div>
                        </div>

                        <!-- AC -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ac" id="ac">
                                <label class="form-check-label" for="ac">
                                    AC
                                </label>
                            </div>
                        </div>

                        <!-- Audio System -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="audio" id="audio">
                                <label class="form-check-label" for="audio">
                                    Audio System
                                </label>
                            </div>
                        </div>

                        <!-- Smart Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="smartboard" id="smartboard">
                                <label class="form-check-label" for="smartboard">
                                    Smart Board
                                </label>
                            </div>
                        </div>

                        <!-- Power Backup -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="powerbackup" id="powerbackup">
                                <label class="form-check-label" for="powerbackup">
                                    Power Backup
                                </label>
                            </div>
                        </div>

                        <!-- Sound Proof -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="soundproof" id="soundproof">
                                <label class="form-check-label" for="soundproof">
                                    Sound Proof
                                </label>
                            </div>
                        </div>

                        <!-- Ordinary White Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="whiteboard" id="whiteboard">
                                <label class="form-check-label" for="whiteboard">
                                    Ordinary White Board
                                </label>
                            </div>
                        </div>

                        <!-- Black Board -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="blackboard" id="blackboard">
                                <label class="form-check-label" for="blackboard">
                                    Black Board
                                </label>
                            </div>
                        </div>

                        <!-- Fans -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fan" id="fan">
                                <label class="form-check-label" for="fan">
                                    Fans
                                </label>
                            </div>
                        </div>

                        <!-- Electrical Sockets -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sockets" id="sockets">
                                <label class="form-check-label" for="sockets">
                                    Electrical Sockets
                                </label>
                            </div>
                        </div>

                        <!-- Lift -->
                        <div class="col-md-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lift" id="lift">
                                <label class="form-check-label" for="lift">
                                    Lift
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <label class="mt-3">Floor name</label> </br>
                    <input type="text" name="floor" placeholder="Floor name.." required>

                    <label for="complex_stage_size">Stage Size:</label>
                    <input type="text" id="complex_stage_size" name="complex_stage_size" placeholder="Stage Size..">

                    <input type="submit" name="submitComplex" value="Submit Lecture Hall Complex">
                </form>
            </div>
        </div>
    </div>
    
                
                <!-- <form action="insert.php" method="POST">
                    <label>Type ID</label> </br>
                    <input type="number" name="type_id" required>
            
                    <label>Room Name</label>
                    <input type="text" name="name" placeholder="Room name.." required>

                    <label>Seat Capacity</label>
                    <input type="number" name="capacity" placeholder="Seat capacity.." required>

                    <label>Department </label>
                    <input type="text" name="dept" placeholder="Department.." required>

                    <label>Location </label>
                    <input type="text" name="location" placeholder="Location" required>

                    <label>Cost</label>
                    <input type="number" name="cost" placeholder=" Cost.." required>

                    <label>Incharge Name</label>
                    <input type="text" name="incharge_name" placeholder=" Incharge name.." required>

                    <label>Incharge Email</label>
                    <input type="email" name="incharge_email" placeholder="Incharge email.." required>

                    <label>Incharge Phone Number</label>
                    <input type="number" name="phoneNumber" placeholder="Incharge Phone Number.." required>

                    <label>Room Availability:</label><br>
                    <div class="mt-2">
                        <label class="avail " for="available">
                            <input type="radio" id="available" name="availability" value="Available">
                            Available
                        </label>

                        <label class="avail" for="not_available">
                            <input type="radio" id="not_available" name="availability" value="Not Available">
                            Not Available
                        </label>

                        <label class="avail" for="in_maintenance">
                            <input type="radio" id="in_maintenance" name="availability" value="In Maintenance">
                            In Maintenance
                        </label><br><br>
                    </div>

                    <label>Floor name</label> </br>
                    <input type="text" name="floor" placeholder="Enter Floor.." required>

                    

                    <input type="submit" name="submit_rooms" value="Submit">

                </form> -->
            </div>
        </div>
    </div>
    <script>
       function showForm() {
        var selectedRoomType = document.getElementById("room_type").value;

        // Hide both forms initially
        document.getElementById('seminarHallForm').style.display = 'none';
        document.getElementById('auditoriumForm').style.display = 'none';
        document.getElementById('complexForm').style.display = 'none';


        // Show the relevant form based on the selection
        if (selectedRoomType === "seminarHall") {
            document.getElementById('seminarHallForm').style.display = 'block';
        } else if (selectedRoomType === "auditorium") {
            document.getElementById('auditoriumForm').style.display = 'block';
        }
        else if (selectedRoomType === "complex") {
            document.getElementById('complexForm').style.display = 'block';
        }
    }
    </script>
</body>
</html>