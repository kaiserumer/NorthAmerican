<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentName = $_POST['student_name'];
    $universityName = $_POST['university_name'];
    $date = $_POST['date'];

    $uploadDir = 'uploads/' . $studentName . '_' . $universityName . '_' . $date . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_POST['upload_file1']) && !empty($_FILES['file1']['name'])) {
        $file1Name = $_POST['file1_name'];
        $file1 = $uploadDir . basename($_FILES['file1']['name']);
        if (move_uploaded_file($_FILES['file1']['tmp_name'], $file1)) {
            echo '<script>
                    document.getElementById("file1_message").innerText = "File 1 uploaded successfully.";
                  </script>';
        }
    }

    if (isset($_POST['upload_file2']) && !empty($_FILES['file2']['name'])) {
        $file2Name = $_POST['file2_name'];
        $file2 = $uploadDir . basename($_FILES['file2']['name']);
        if (move_uploaded_file($_FILES['file2']['tmp_name'], $file2)) {
            echo '<script>
                    document.getElementById("file2_message").innerText = "File 2 uploaded successfully.";
                  </script>';
        }
    }
}
?>
