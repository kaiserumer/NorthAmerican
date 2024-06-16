<!DOCTYPE html>
<html>
<head>
    <title>View Uploads</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
        }
        .uploads-list {
            width: 50%;
            margin-right: 10px;
        }
        .uploads-list ul {
            list-style-type: none;
            padding: 0;
        }
        .uploads-list li {
            margin-bottom: 10px;
        }
        .preview-area {
            width: 50%;
            border-left: 1px solid #ccc;
            padding-left: 10px;
        }
        .preview-area iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
    </style>
    <script>
        function previewDocument(url) {
            document.getElementById('preview-frame').src = url;
        }
    </script>
</head>
<body>
    <div class="uploads-list">
        <h2>Uploaded Documents</h2>
        <?php
        $dir = 'uploads';
        $students = array_diff(scandir($dir), array('.', '..'));
        if ($students) {
            echo '<ul>';
            foreach ($students as $student) {
                $studentDir = $dir . '/' . $student;
                $files = array_diff(scandir($studentDir), array('.', '..'));
                echo '<li><strong>Student:</strong> ' . $student . '</li>';
                echo '<ul>';
                foreach ($files as $file) {
                    $filePath = $studentDir . '/' . $file;
                    echo '<li>' . $file . ' 
                          <button onclick="previewDocument(\'' . $filePath . '\')">Preview</button> 
                          <a href="' . $filePath . '" download><button>Download</button></a></li>';
                }
                echo '</ul>';
            }
            echo '</ul>';
        } else {
            echo 'No uploads found.';
        }
        ?>
    </div>
    <div class="preview-area">
        <h2>Document Preview</h2>
        <iframe id="preview-frame" src=""></iframe>
    </div>
</body>
</html>
