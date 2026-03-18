<?php
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
            $error = "❌ Error: Cannot create uploads directory. Check permissions.";
        }
    }
    
    // Ensure directory is writable
    if(!is_writable($upload_dir)){
        @chmod($upload_dir, 0777);
    }
    
    if(!$error){
        $name = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];
        
        // Sanitize filename
        $name = basename($name);
        
        if(move_uploaded_file($tmp, $upload_dir . $name)){
            $msg = "✅ File uploaded successfully: " . htmlspecialchars($name);
        } else {
            $error = "❌ Error uploading file. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level 2 - Filtered RCE | Mini RCE Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div class="header-title">
            <div class="main">Level 2: Filtered RCE</div>
            <div class="sub">Find the other way</div>
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
                <i class="fa-solid fa-filter"></i>
            </div>
            <div class="challenge-title">
                <h3>Filter Challenge</h3>
                <p>Execute commands despite the restrictions</p>
            </div>
        </div>
        
        <!-- Challenge Description -->
        <div class="challenge-description">
            <div class="description-item">
                <i class="fa-solid fa-circle-info"></i>
                <p>This level implements a <strong>command filter</strong> that blocks certain keywords. Your goal is to find another way and execute commands to read the flag.</p>
            </div>
            <div class="description-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p><strong>Your goal:</strong> Read the flag at <code>/flag.txt</code></p>
            </div>
        </div>
        
        <!-- Warning Box -->
        <div class="warning-box">
            <i class="fa-solid fa-shield-halved"></i>
            <div class="warning-content">
                <strong>Security Warning</strong>
                <p>This is a deliberately vulnerable application for educational purposes. Never use these techniques on real systems.</p>
            </div>
        </div>
        
        <!-- Upload Form -->
        <div class="upload-form">
            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-file"></i> Upload Your Payload
                </label>
                <div class="file-input-wrapper">
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <input type="file" name="file" id="fileInput" required>
                        <div class="file-info" id="fileInfo" style="display: none;">
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="fileName"></span> (<span id="fileSize"></span>)
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="upload-btn" id="uploadBtn">
                    <i class="fa-solid fa-cloud-upload-alt"></i>
                    Upload Payload
                </button>
                </form>
            
            <!-- Message Display -->
            <?php if(!empty($msg)): ?>
                <div class="msg-box">
                    <i class="fa-solid fa-circle-check"></i>
                    <div class="msg-content">
                        <div class="msg-title">Success</div>
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
        <p style="font-size: 12px; margin-top: 5px;">Level 2: Filter Bypass Challenge</p>
    </div>
</div>

<!-- JavaScript for file info -->
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
</script>

</body>
</html>