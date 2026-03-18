<?php
// Security: Hide errors from users
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

// Redirect if not logged in
if(!isset($_SESSION['user'])){
    header("Location: /");
    exit;
}

$msg = "";
$error = "";

// Handle file inclusion/access
if(isset($_GET['file'])){
    $filename = basename($_GET['file']);
    $filepath = "uploads/" . $filename;
    
    if(file_exists($filepath)){
        // Include the uploaded file (vulnerable to RCE)
        include($filepath);
        exit;
    } else {
        $error = "File not found: " . htmlspecialchars($filename);
    }
}

// Handle file upload
if(isset($_FILES['file'])){
    $upload_dir = "uploads/";
    
    // Create uploads directory if it doesn't exist
    if(!is_dir($upload_dir)){
        if(!mkdir($upload_dir, 0777, true)){
            $error = "Error: Cannot create uploads directory. Check permissions.";
        }
    }
    
    // Ensure directory is writable
    if(!is_writable($upload_dir)){
        @chmod($upload_dir, 0777);
    }
    
    if(!$error && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name']){
        $name = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        
        // sanitize filename
        $name = basename($name);
        
        // ambil extension
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
        // cek mime (with fallback)
        $mime = '';
        if(function_exists('mime_content_type')){
            $mime = mime_content_type($tmp);
        } elseif(function_exists('finfo_file')){
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);
        }
        
        // Validation checks
        if($ext !== "jpg" && $ext !== "jpeg"){
            $error = "Only JPG files allowed!";
        }
        elseif($mime && $mime !== "image/jpeg"){
            $error = "Invalid MIME type! Received: " . htmlspecialchars($mime);
        }
        else{
            // random name biar gak gampang ditebak
            $new_name = uniqid() . ".jpg";
            $target_path = $upload_dir . $new_name;
            
            if(move_uploaded_file($tmp, $target_path)){
                $msg = "File successfully uploaded to: /uploads/" . htmlspecialchars($new_name);
            } else {
                $error = "Error uploading file. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level 4 - MIME Type Bypass RCE | Mini RCE Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-shield-virus"></i>
        </div>
        <div class="header-title">
            <div>Level 4: MIME Type Bypass RCE</div>
            <div class="sub">Advanced File Upload Vulnerability</div>
        </div>
    </div>
    <a href="/" class="dashboard-link">
        <i class="fa-solid fa-arrow-left"></i> Dashboard
    </a>
</div>

<div class="container">
    
    <!-- Challenge Card -->
    <div class="challenge-card">
        
        <!-- Challenge Header -->
        <div class="challenge-header">
            <div class="challenge-icon">
                <i class="fa-solid fa-cloud-upload-alt"></i>
            </div>
            <div class="challenge-title">
                <h3>MIME Type Bypass Challenge</h3>
                <p>Bypass MIME type restrictions and upload a file</p>
            </div>
        </div>
        
        <!-- Challenge Description -->
        <div class="challenge-description">
            <div class="description-item">
                <i class="fa-solid fa-circle-info"></i>
                <p>This challenge demonstrates MIME type bypass techniques. The server checks both extension and MIME type, but there may be ways around it...</p>
            </div>

            <div class="description-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p><strong>Your goal:</strong> Get the flag by finding how to bypass the file upload restrictions and execute code</p>
            </div>
        </div>
        
        <!-- Warning Box -->
        <div class="warning-box">
            <i class="fa-solid fa-shield-halved"></i>
            <div class="warning-content">
                <strong>⚠️ Security Warning</strong>
                <p>This is a deliberately vulnerable application for educational purposes. Never use these techniques on real systems.</p>
            </div>
        </div>
        
        <!-- Upload Form -->
        <div class="upload-form">
            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-file"></i> Select File to Upload
                </label>
                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                    <div class="file-input-wrapper">
                        <input type="file" name="file" id="fileInput" required>
                        <div class="file-info" id="fileInfo" style="display: none;">
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="fileName"></span> (<span id="fileSize"></span>)
                        </div>
                    </div>
                    
                    <button type="submit" class="upload-btn" id="uploadBtn">
                        <i class="fa-solid fa-cloud-upload-alt"></i>
                        Upload File
                    </button>
                </form>
            </div>
            
            <!-- Upload Progress (optional) -->
            <div class="upload-progress" id="uploadProgress">
                <div class="progress-bar" style="width: 0%;"></div>
            </div>
            
            <!-- Message Display -->
            <?php if(!empty($msg)): ?>
                <div class="msg-box">
                    <i class="fa-solid fa-circle-check"></i>
                    <div class="msg-content">
                        <div class="msg-title">Upload Result</div>
                        <div class="msg-text"><?php echo $msg; ?></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($error)): ?>
                <div class="error-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div class="error-content">
                        <div class="error-title">Error</div>
                        <div class="error-text"><?php echo $error; ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Back Link -->
        <a href="/" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

</div>

</body>
</html>