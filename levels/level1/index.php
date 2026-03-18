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
    
    if(!$error){
        $name = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        
        // Sanitize filename to prevent directory traversal
        $name = basename($name);
        
        if(move_uploaded_file($tmp, $upload_dir . $name)){
            $msg = "File successfully uploaded to: /uploads/" . htmlspecialchars($name);
        } else {
            $error = "Error uploading file. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level 1 - Basic RCE | Mini RCE Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-bug"></i>
        </div>
        <div class="header-title">
            <div>Level 1: Basic RCE</div>
            <div class="sub">File Upload Vulnerability</div>
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
                <h3>File Upload Challenge</h3>
                <p>Upload any file to the server</p>
            </div>
        </div>
        
        <!-- Challenge Description -->
        <div class="challenge-description">
            <div class="description-item">
                <i class="fa-solid fa-circle-info"></i>
                <p>This challenge demonstrates a basic file upload vulnerability. The server accepts all file types without any restrictions.</p>
            </div>
            <div class="description-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p><strong>Your goal:</strong> Get the flag</p>
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

    <!-- Footer -->
    <div class="footer">
        <p>© 2026 Mini RCE Platform - Educational Purpose Only</p>
    </div>
</div>

<!-- Optional JavaScript for better UX -->
<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if(this.files.length > 0) {
        const file = this.files[0];
        fileName.textContent = file.name;
        
        // Format file size
        let size = file.size;
        let sizeText = '';
        if(size < 1024) {
            sizeText = size + ' B';
        } else if(size < 1024 * 1024) {
            sizeText = (size / 1024).toFixed(1) + ' KB';
        } else {
            sizeText = (size / (1024 * 1024)).toFixed(1) + ' MB';
        }
        fileSize.textContent = sizeText;
        
        fileInfo.style.display = 'flex';
    } else {
        fileInfo.style.display = 'none';
    }
});

// Optional: Show upload progress (simulated)
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const progressBar = document.querySelector('.progress-bar');
    const uploadProgress = document.getElementById('uploadProgress');
    
    uploadProgress.classList.add('active');
    
    let width = 0;
    const interval = setInterval(function() {
        if(width >= 90) {
            clearInterval(interval);
        } else {
            width += 10;
            progressBar.style.width = width + '%';
        }
    }, 100);
});
</script>

</body>
</html>