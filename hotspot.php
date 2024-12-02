<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        canvas {
            border: 1px solid #000;
            display: block;
            margin: 10px auto;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
        }
        button[type="submit"] {
            background-color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Page</h1>
        <form action="hot.php" method="post" enctype="multipart/form-data" onsubmit="saveHotspots()">
            <label for="name">Image Name:</label>
            <input type="text" name="name" required>
            <br>
            <label for="image">Image File:</label>
            <input type="file" name="image" accept="image/*" required>
            <br>
            <h3>Hotspots</h3>
            <canvas id="hotspotCanvas" width="500" height="500"></canvas>
            <br>
            <button type="button" id="addHotspot">Add Hotspot</button>
            <br>
            <input type="submit" value="Upload">
            <input type="hidden" name="hotspots" id="hotspotsData">
        </form>
    </div>
    <script>
        // Variables to store hotspot data
        let hotspots = [];

        // Canvas setup
        const canvas = document.getElementById('hotspotCanvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        let hotspotCount = 0;

        // Function to draw the hotspot icons on the canvas
        function drawHotspots() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            hotspots.forEach((hotspot) => {
                ctx.beginPath();
                ctx.arc(hotspot.x, hotspot.y, 5, 0, 2 * Math.PI);
                ctx.fillStyle = 'red';
                ctx.fill();
                ctx.closePath();
            });
        }

        // Function to create hotspot data entry fields dynamically
        function createHotspotField(index) {
            const hotspotContainer = document.createElement('div');
            hotspotContainer.innerHTML = `
                <label>X Coordinate:</label>
                <input type="text" name="x_coord[]" value="${hotspots[index].x}" required>
                <br>
                <label>Y Coordinate:</label>
                <input type="text" name="y_coord[]" value="${hotspots[index].y}" required>
                <br>
                <label>Description:</label>
                <textarea name="description[]" required></textarea>
                <br>
            `;
            return hotspotContainer;
        }

        // Function to load and display the image on the canvas
        function loadImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Attach event listener to the file input to trigger image load
        const fileInput = document.querySelector('input[name="image"]');
        fileInput.addEventListener('change', function () {
            loadImage(this);
        });

        // Add event listener to the image to redraw hotspots on load
        img.addEventListener('load', function () {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            // Initialize hotspot data with a single hotspot in the center
            hotspots = [{ x: canvas.width / 2, y: canvas.height / 2 }];
            drawHotspots();

            // Create and append hotspot data entry fields
            const hotspotFields = createHotspotField(0);
            document.querySelector('form').appendChild(hotspotFields);
            hotspotCount++;
        });

        // Add event listener to the "Add Hotspot" button
        document.getElementById('addHotspot').addEventListener('click', function () {
            // Place the new hotspot in the center
            const x = canvas.width / 2;
            const y = canvas.height / 2;
            hotspots.push({ x, y });
            drawHotspots();

            // Create and append hotspot data entry fields
            const hotspotFields = createHotspotField(hotspotCount);
            document.querySelector('form').appendChild(hotspotFields);

            // Increase the hotspot count
            hotspotCount++;
        });

        // Add event listener to hotspot fields to redraw hotspots when the values are changed
        document.querySelector('form').addEventListener('input', function (event) {
            const target = event.target;
            if (target.name === 'x_coord[]' || target.name === 'y_coord[]') {
                const index = Array.prototype.indexOf.call(target.parentElement.children, target);
                if (index >= 0 && index < hotspots.length) {
                    hotspots[index].x = parseFloat(target.parentElement.querySelector('input[name="x_coord[]"]').value);
                    hotspots[index].y = parseFloat(target.parentElement.querySelector('input[name="y_coord[]"]').value);
                    drawHotspots();
                }
            }
        });

        // Function to save hotspot data to the hidden input field
        function saveHotspots() {
            document.getElementById('hotspotsData').value = JSON.stringify(hotspots);
        }
    </script>
</body>
</html>
