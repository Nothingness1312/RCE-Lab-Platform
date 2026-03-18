<?php
// =============================================
// SECURITY CONFIGURATION
// =============================================
ini_set('display_errors', 0);
error_reporting(E_ALL);

// =============================================
// SESSION
// =============================================
session_start();

// wajib login
if(!isset($_SESSION['user'])){
    header("Location: /");
    exit;
}

// =============================================
// LOAD FILTER
// =============================================
require_once "filter.php";

// =============================================
// INIT
// =============================================
$output = "";
$error = "";
$cmd = "";

// =============================================
// HANDLE COMMAND
// =============================================
if(isset($_GET['cmd'])){
    $cmd = $_GET['cmd'];

    $blocked = filter_cmd($cmd);

    if($blocked){
        $error = $blocked;
    } else {
        $output = substr(shell_exec($cmd), 0, 300);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level 3 - Hostile RCE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-skull"></i>
        </div>
        <div class="header-title">
            <div class="main">Level 3: Hostile Shell</div>
            <div class="sub">Spaces are overrated</div>
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
                <i class="fa-solid fa-terminal"></i>
            </div>
            <div class="challenge-title">
                <h3>Hostile Shell Challenge</h3>
                <p>Execute commands despite the hostile filter</p>
            </div>
        </div>
        
        <!-- Challenge Description -->
        <div class="challenge-description">
            <div class="description-item">
                <i class="fa-solid fa-circle-info"></i>
                <p>This level implements a <strong>hostile filter</strong> that blocks many commands and special characters. Your goal is to bypass the filter and read the flag.</p>
            </div>
            <div class="description-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p><strong>Your goal:</strong> Read the flag at <code>/flag.txt</code></p>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="info-box">
            <i class="fa-solid fa-shield-halved"></i>
            <div class="info-content">
                <strong>Restricted:</strong>
                <ul>
                    <li>Commands: cat, ls, bash, sh, nc, curl, wget, python, perl</li>
                    <li>Characters: space, /, ;, &, |, `</li>
                </ul>
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
        
        <!-- Command Form -->
        <div class="upload-form">
            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-terminal"></i> Enter Command
                </label>
                <form method="GET" id="commandForm">
                    <div class="file-input-wrapper">
                        <div class="command-input-wrapper">
                            <i class="fa-solid fa-chevron-right"></i>
                            <input type="text" name="cmd" placeholder="Enter command..." value="<?= htmlspecialchars($cmd) ?>" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="upload-btn" id="runBtn">
                        <i class="fa-solid fa-play"></i>
                        Run Command
                    </button>
                </form>
            </div>
            
            <!-- Command Display -->
            <?php if(!empty($cmd)): ?>
                <div class="cmd-box">
                    <i class="fa-solid fa-terminal"></i>
                    <div class="cmd-content">
                        <div class="cmd-title">Command</div>
                        <div class="cmd-text">$ <?= htmlspecialchars($cmd, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Error Display -->
            <?php if($error): ?>
                <div class="error-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div class="error-content">
                        <div class="error-title">Error</div>
                        <div class="error-text"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Output Display -->
            <?php if($output): ?>
                <div class="output-box">
                    <div class="output-header">
                        <i class="fa-solid fa-code"></i>
                        <div class="output-title">You got it!!</div>
                    </div>
                    <pre class="output-content"><code><?= htmlspecialchars($output, ENT_QUOTES, 'UTF-8'); ?></code></pre>
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
        <p style="font-size: 12px; margin-top: 5px;">Level 3: Hostile Filter Challenge</p>
    </div>
</div>

</body>
</html>