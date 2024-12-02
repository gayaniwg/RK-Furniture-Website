<!DOCTYPE html>
<html>
<head>
    <title>Customer Page</title>
    <style>
        /* Container for the images */
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin: 0 20px;
        }

        /* Image box */
        .image-box {
            width: calc(33.33% - 20px);
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Added shadow */
            border-radius: 10px;
            overflow: hidden;
        }

        /* Hotspot overlay */
        .hotspot-overlay {
            position: absolute;
            background-color: rgba(255, 0, 0, 0.7);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            pointer-events: auto;
            cursor: pointer;
        }

        /* Hotspot description box */
        .hotspot-description {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 8px;
            border-radius: 5px;
            pointer-events: none;
            display: none;
            white-space: nowrap;
            max-width: 200px;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .hotspot-overlay:hover + .hotspot-description {
            display: block;
        }

        /* Navigation icons */
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin: 20px;
        }

        .navigation-icon {
            cursor: pointer;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center;">Customer Page</h1>
        <div class="image-container">
            <?php
            // Connect to the database (adjust database credentials accordingly)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "projdb";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch images and their hotspots from the database
            $sql = "SELECT * FROM images";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $images = $result->fetch_all(MYSQLI_ASSOC);
                $imageCount = count($images);

                $itemsPerPage = 6;
                $totalPages = ceil($imageCount / $itemsPerPage);

                // Determine the current page number from the URL parameter 'page'
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($currentPage - 1) * $itemsPerPage;
                $end = $start + $itemsPerPage;

                // Display images for the current page
                for ($i = $start; $i < $end && $i < $imageCount; $i++) {
                    $image = $images[$i];
                    $image_id = $image['id'];
                    $image_name = $image['name'];
                    $image_path = $image['image_path'];

                    // Get the actual width and height of the image
                    list($image_width, $image_height) = getimagesize($image_path);
            ?>
                    <div class="image-box">
                        <h3 style="text-align: center; margin-bottom: 10px;"><?php echo $image_name; ?></h3>
                        <div class="hotspot-image">
                            <img src="<?php echo $image_path; ?>" alt="<?php echo $image_name; ?>" width="100%">
                            <?php
                            // Fetch hotspots for the current image
                            $sql_hotspots = "SELECT * FROM hotspots WHERE image_id = $image_id";
                            $result_hotspots = $conn->query($sql_hotspots);

                            // Display hotspot overlays as clickable links with tooltips
                            while ($row_hotspot = $result_hotspots->fetch_assoc()) {
                                $hotspot_id = $row_hotspot['id'];
                                $x_coord_percentage = $row_hotspot['x_coord'];
                                $y_coord_percentage = $row_hotspot['y_coord'];
                                $description = $row_hotspot['description'];

                                // Calculate the position of the hotspot in pixels
                                $x_coord_pixels = ($x_coord_percentage / 100) * $image_width;
                                $y_coord_pixels = ($y_coord_percentage / 100) * $image_height;
                            ?>
                                <a class="hotspot-overlay" href="#" title="<?php echo $description; ?>" style="left: <?php echo $x_coord_pixels; ?>px; top: <?php echo $y_coord_pixels; ?>px;"></a>
                            <?php
                            }
                            ?>
                            <div class="hotspot-description"><?php echo $description; ?></div>
                        </div>
                    </div>
            <?php
                }

                // Close the database connection
                $conn->close();
            ?>
        </div>
        <div class="navigation">
            <?php if ($currentPage > 1) { ?>
                <a class="navigation-icon" href="?page=<?php echo $currentPage - 1; ?>">&#8249;</a>
            <?php } ?>
            <span>Page <?php echo $currentPage; ?></span>
            <?php if ($currentPage < $totalPages) { ?>
                <a class="navigation-icon" href="?page=<?php echo $currentPage + 1; ?>">&#8250;</a>
            <?php } ?>
        </div>
        <?php } else {
                echo "<p>No images found in the database.</p>";
            }
            ?>
    </div>
</body>
</html>
