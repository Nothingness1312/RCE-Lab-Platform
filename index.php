<?php
// =============================================
// SECURITY CONFIGURATION
// =============================================
ini_set('display_errors', 0);
error_reporting(E_ALL);

// =============================================
// SESSION & DATABASE INIT
// =============================================
session_start();
include "init.php";

// =============================================
// INCLUDE NOTIFICATION HANDLER
// =============================================
include "notif/notif.php";

// =============================================
// LOAD ENVIRONMENT VARIABLES
// =============================================
$env_file = __DIR__ . '/.env';
if (!file_exists($env_file)) {
    die("Error: .env file not found at " . $env_file);
}
$env = parse_ini_file($env_file);
if ($env === false) {
    die("Error: Failed to parse .env file. Check file format.");
}

// =============================================
// USER REGISTRATION
// =============================================
if (isset($_POST['username'])) {
    $username = trim($_POST['username']);

    if ($username !== "") {
        $stmt = $db->prepare("INSERT OR IGNORE INTO users(username) VALUES(:u)");
        $stmt->bindValue(":u", $username);
        $stmt->execute();

        $_SESSION['user'] = $username;
        
        // Set welcome notification without sound
        notifySuccess("Welcome {$username}! Ready to hack?", 4000, false);
        
        header("Location: /");
        exit;
    }
}

// =============================================
// GET USER ID FROM SESSION
// =============================================
$uid = null;
if (isset($_SESSION['user'])) {
    $stmt = $db->prepare("SELECT id FROM users WHERE username = :u");
    $stmt->bindValue(":u", $_SESSION['user']);
    $result = $stmt->execute();

    if ($result) {
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            $uid = $row['id'];
        }
    }
}

// =============================================
// CHECK USER PROGRESS
// =============================================
$solved_levels = [];
if ($uid) {
    $stmt = $db->prepare("SELECT level FROM progress WHERE user_id = :u");
    $stmt->bindValue(":u", $uid);
    $res = $stmt->execute();

    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $solved_levels[] = $row['level'];
    }
}

// =============================================
// INCLUDE ALL LEVEL LOGIC (HANDLERS + DISPLAY)
// =============================================
include "levels/level1.php";
include "levels/level2.php";

// =============================================
// SESSION SECURITY: REGENERATE ID
// =============================================
if (isset($_SESSION['user']) && !isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini RCE Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- HEADER SECTION -->
<div class="header">
    <div>Mini RCE Platform</div>
    <div class="social-links">
        <a href="https://discord.gg/nH94vYstKA" class="social-link" target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-discord"></i> Discord
        </a>
        <a href="https://github.com/Nothingness1312/RCE-Lab-Platform" class="social-link" target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-github"></i> GitHub
        </a>
    </div>
</div>

<div class="container">

<?php
// Display notification if exists
displayNotification();
?>

<?php if (!isset($_SESSION['user'])): ?>
    <!-- REGISTRATION SECTION -->
    <div class="card">
        <h2>Register to Start</h2>
        <form method="POST">
            <div class="form-row">
                <input type="text" name="username" placeholder="Enter your username" autocomplete="off" required maxlength="50">
                <button type="submit">Start Journey!!</button>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- MAIN DASHBOARD -->
    <div class="welcome-card">
        <div class="welcome-content">
            <i class="fa-solid fa-user-astronaut"></i>
            <div>
                <h3>Welcome Back, <span><?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?></span>!</h3>
                <p>Ready to hack? Complete the challenges below.</p>
            </div>
        </div>
    </div>

    <!-- PROGRESS OVERVIEW -->
    <div class="progress-overview">
        <?php for($i = 1; $i <= 6; $i++): ?>
            <div class="progress-chip <?php echo in_array($i, $solved_levels) ? 'done' : 'pending'; ?>">
                <i class="fa-solid <?php echo in_array($i, $solved_levels) ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                Level <?php echo $i; ?>
            </div>
        <?php endfor; ?>
    </div>

    <!-- CHALLENGES GRID -->
    <div class="challenges-grid">
        <?php
        // Render all level cards (functions defined in level.php)
        renderLevel1Card($solved_levels);
        renderLevel2Card($solved_levels);
        renderLevel3Card($solved_levels);
        renderLevel4Card($solved_levels);
        renderLevel5Card($solved_levels);
        renderLevel6Card($solved_levels);
        ?>
        
        <!-- COMMUNITY CARD -->
        <div class="community-compact-card <?php echo (count($solved_levels) == 6) ? 'highlight' : ''; ?>">
            <div class="community-compact-header">
                <h3><i class="fa-solid fa-users"></i> Community</h3>
                <?php if (count($solved_levels) == 6): ?>
                    <span class="next-badge"><i class="fa-solid fa-star"></i> Master Achieved!</span>
                <?php endif; ?>
            </div>
            <div class="community-compact-content">
                <div class="community-item">
                    <i class="fa-brands fa-discord discord-icon"></i>
                    <div>
                        <strong>Discord</strong>
                        <span class="color-sample" style="background: #5865F2;">#5865F2</span>
                        <p>Join 100+ members</p>
                    </div>
                    <a href="https://discord.gg/nH94vYstKA" class="compact-link discord-link" target="_blank" rel="noopener noreferrer">
                        Join <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="community-item">
                    <i class="fa-brands fa-github github-icon"></i>
                    <div>
                        <strong>GitHub</strong>
                        <p>Star the repo</p>
                    </div>
                    <a href="https://github.com/Nothingness1312/RCE-Lab-Platform" class="compact-link github-link" target="_blank" rel="noopener noreferrer">
                        View <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php if (count($solved_levels) >= 4): ?>
                <div class="preview-teaser">
                    <i class="fa-solid fa-crown"></i>
                    <span>Champion!</span>
                </div>
            <?php endif; ?>
            <div class="community-note-compact">
                <i class="fa-solid fa-bell"></i>
                <span>Next challenge will be announced in Discord!!</span>
            </div>
        </div>
    </div>

<?php endif; ?>

<!-- FOOTER -->
<div class="footer">
    <p>© 2026 Mini RCE Platform</p>
    <p>
        <a href="https://discord.gg/nH94vYstKA" target="_blank" rel="noopener noreferrer">Discord</a> 
        | 
        <a href="https://github.com/Nothingness1312/RCE-Lab-Platform" target="_blank" rel="noopener noreferrer">GitHub</a>
    </p>
</div>

</div>

<!-- Audio Player for Notifications -->
<audio id="successSound" preload="auto" style="display: none;">
    <source src="/notif/951.wav" type="audio/wav">
    Your browser does not support the audio element.
</audio>

<script>
// Function to play success sound
function playSuccessSound() {
    var audio = document.getElementById('successSound');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(function(e) {
            console.log('Audio play failed:', e);
        });
    }
}

// Check if we need to play sound from PHP
<?php if (isset($_SESSION['play_sound']) && $_SESSION['play_sound'] === true): ?>
    playSuccessSound();
    <?php unset($_SESSION['play_sound']); ?>
<?php endif; ?>
</script>

</body>
</html>