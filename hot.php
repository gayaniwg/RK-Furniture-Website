
<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an empty message variable
$message = '';

// Handle image upload and hotspot data insertion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];

    // Process image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create the target directory if it doesn't exist
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert image record into the database
        $stmt_image = $conn->prepare("INSERT INTO images (name, image_path) VALUES (?, ?)");
        $stmt_image->bind_param("ss", $name, $target_file);
        $stmt_image->execute();

        // Get the inserted image ID
        $image_id = $conn->insert_id;

        // Process hotspots
        if (!empty($_POST['x_coord']) && !empty($_POST['y_coord']) && !empty($_POST['description'])) {
            $x_coords = $_POST['x_coord'];
            $y_coords = $_POST['y_coord'];
            $descriptions = $_POST['description'];

            // Insert hotspots into the database
            $stmt_hotspot = $conn->prepare("INSERT INTO hotspots (image_id, x_coord, y_coord, description) VALUES (?, ?, ?, ?)");
            $stmt_hotspot->bind_param("iiis", $image_id, $x_coord, $y_coord, $description);

            for ($i = 0; $i < count($x_coords); $i++) {
                $x_coord = $x_coords[$i];
                $y_coord = $y_coords[$i];
                $description = $descriptions[$i];

                $stmt_hotspot->execute();
            }
            $stmt_hotspot->close();
        }

        $stmt_image->close();
        $message = "Upload successful!";
    } else {
        $message = "Upload failed. Check the target directory permissions.";
    }
}

// Close the connection
$conn->close();
?>