<!DOCTYPE html>
<html>
<head>
    <title>Upload Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 60%;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        .form-group input[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.querySelector('input[type="date"]');
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
        });
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Upload Your Forms</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="student_name">Student Name:</label>
                <input type="text" name="student_name" id="student_name" required>
            </div>
            <div class="form-group">
                <label for="university_name">University Name:</label>
                <input type="text" name="university_name" id="university_name" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>
            </div>
            <div class="form-group">
                <label for="file1_name">File 1 Name:</label>
                <input type="text" name="file1_name" id="file1_name" required>
                <input type="file" name="file1" id="file1" required>
                <input type="submit" name="upload_file1" value="Upload File 1">
                <div id="file1_message" class="message"></div>
            </div>
            <div class="form-group">
                <label for="file2_name">File 2 Name:</label>
                <input type="text" name="file2_name" id="file2_name">
                <input type="file" name="file2" id="file2">
                <input type="submit" name="upload_file2" value="Upload File 2">
                <div id="file2_message" class="message"></div>
            </div>
        </form>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var fileInput = event.target.querySelector('input[type="file"]');
            if (!fileInput.files.length) {
                alert('Please choose a file to upload.');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
