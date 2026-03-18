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
        header("Location: /");
        exit;
    }
}

// =============================================
// INITIALIZE VARIABLES
// =============================================
$uid = null;
$msg_level1 = '';
$msg_level2 = '';
$msg_level3 = '';
$msg_level4 = '';

// =============================================
// GET USER ID FROM SESSION
// =============================================
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
// CHECK USER PROGRESS (SINGLE QUERY)
// =============================================
$solved_level1 = false;
$solved_level2 = false;
$solved_level3 = false;
$solved_level4 = false;

if ($uid) {
    $stmt = $db->prepare("SELECT level FROM progress WHERE user_id = :u");
    $stmt->bindValue(":u", $uid);
    $res = $stmt->execute();

    $solved_levels = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $solved_levels[] = $row['level'];
    }

    $solved_level1 = in_array(1, $solved_levels);
    $solved_level2 = in_array(2, $solved_levels);
    $solved_level3 = in_array(3, $solved_levels);
    $solved_level4 = in_array(4, $solved_levels);
}

// =============================================
// SUBMIT FLAG FOR LEVEL 1
// =============================================
if (isset($_POST['submit_level1']) && $uid && !$solved_level1) {
    $flag = trim($_POST['flag_level1']);

    if (isset($env['LEVEL1_FLAG']) && $flag === $env['LEVEL1_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 1, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();

        $solved_level1 = true;
    } else {
        $msg_level1 = "Wrong flag for Level 1.";
    }
}

// =============================================
// SUBMIT FLAG FOR LEVEL 2
// =============================================
if (isset($_POST['submit_level2']) && $uid) {
    $flag2 = trim($_POST['flag_level2']);

    if (isset($env['LEVEL2_FLAG']) && $flag2 === $env['LEVEL2_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 2, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();

        $solved_level2 = true;
    } else {
        $msg_level2 = "Wrong flag for Level 2.";
    }
}

// =============================================
// SUBMIT FLAG FOR LEVEL 3
// =============================================
if (isset($_POST['submit_level3']) && $uid) {
    $flag3 = trim($_POST['flag_level3']);

    if (isset($env['LEVEL3_FLAG']) && $flag3 === $env['LEVEL3_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 3, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();

        $solved_level3 = true;
    } else {
        $msg_level3 = "Wrong flag for Level 3.";
    }
}

// =============================================
// SUBMIT FLAG FOR LEVEL 4
// =============================================
if (isset($_POST['submit_level4']) && $uid && !$solved_level4) {
    $flag4 = trim($_POST['flag_level4']);

    if (isset($env['LEVEL4_FLAG']) && $flag4 === $env['LEVEL4_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 4, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();

        $solved_level4 = true;
    } else {
        $msg_level4 = "Wrong flag for Level 4.";
    }
}

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

<!-- ============================================= -->
<!-- HEADER SECTION -->
<!-- ============================================= -->
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

<!-- ============================================= -->
<!-- REGISTRATION SECTION (NOT LOGGED IN) -->
<!-- ============================================= -->
<?php if (!isset($_SESSION['user'])): ?>
    <div class="card">
        <h2>Register to Start</h2>
        <form method="POST">
            <div class="form-row">
                <input type="text" name="username" placeholder="Enter your username" autocomplete="off" required maxlength="50">
                <button type="submit">Start Journey!!</button>
            </div>
        </form>
    </div>

<!-- ============================================= -->
<!-- MAIN DASHBOARD (LOGGED IN) -->
<!-- ============================================= -->
<?php else: ?>

    <!-- ---------- WELCOME CARD ---------- -->
    <div class="welcome-card">
        <div class="welcome-content">
            <i class="fa-solid fa-user-astronaut"></i>
            <div>
                <h3>Welcome Back, <span><?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?></span>!</h3>
                <p>Ready to hack? Complete the challenges below.</p>
            </div>
        </div>
    </div>

    <!-- ---------- PROGRESS OVERVIEW ---------- -->
    <div class="progress-overview">
        <!-- Level 1 Progress Chip -->
        <div class="progress-chip <?php echo $solved_level1 ? 'done' : 'pending'; ?>">
            <i class="fa-solid <?php echo $solved_level1 ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
            Level 1
        </div>

        <!-- Level 2 Progress Chip -->
        <div class="progress-chip <?php echo $solved_level2 ? 'done' : 'pending'; ?>">
            <i class="fa-solid <?php echo $solved_level2 ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
            Level 2
        </div>

        <!-- Level 3 Progress Chip -->
        <div class="progress-chip <?php echo $solved_level3 ? 'done' : 'pending'; ?>">
            <i class="fa-solid <?php echo $solved_level3 ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
            Level 3
        </div>

        <!-- Level 4 Progress Chip -->
        <div class="progress-chip <?php echo $solved_level4 ? 'done' : 'pending'; ?>">
            <i class="fa-solid <?php echo $solved_level4 ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
            Level 4
        </div>
    </div>

    <!-- ---------- CHALLENGES GRID (2 COLUMNS) ---------- -->
    <div class="challenges-grid">

        <!-- ===== LEVEL 1 CARD ===== -->
        <div class="challenge-card <?php echo $solved_level1 ? 'solved' : ''; ?>">
            <!-- Card Header -->
            <div class="challenge-card-header">
                <div>
                    <span class="challenge-level">Challenge 1</span>
                    <h3>Basic RCE</h3>
                </div>
                <?php if ($solved_level1): ?>
                    <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
                <?php else: ?>
                    <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
                <?php endif; ?>
            </div>

            <!-- Access Link (Always Visible) -->
            <a href="/levels/level1/" class="challenge-access">
                <i class="fa-solid fa-terminal"></i> Access Challenge
            </a>

            <!-- Conditional Content -->
            <?php if (!$solved_level1): ?>
                <!-- Active State: Show Flag Form -->
                <div class="compact-flag-form">
                    <form method="POST">
                        <input type="text" name="flag_level1" placeholder="flag{...}" required>
                        <button type="submit" name="submit_level1" class="small-btn">Submit</button>
                    </form>
                    <?php if ($msg_level1): ?>
                        <div class="mini-msg <?php echo strpos($msg_level1, 'Correct') !== false ? 'success' : 'error'; ?>">
                            <?php echo htmlspecialchars($msg_level1, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Completed State: Show Trophy -->
                <div class="compact-completed">
                    <i class="fa-solid fa-trophy"></i> Completed
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== LEVEL 2 CARD ===== -->
        <div class="challenge-card 
    <?php 
    if ($solved_level2) {
        echo 'solved';
    }
    ?>">
            
            <!-- Card Header -->
            <div class="challenge-card-header">
                <div>
                    <span class="challenge-level">Challenge 2</span>
                    <h3>Filtered RCE</h3>
                </div>
                <?php if ($solved_level2): ?>
                    <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
                <?php else: ?>
                    <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
                <?php endif; ?>
            </div>

            <!-- Access Link (Always Visible) -->
            <a href="/levels/level2/" class="challenge-access">
                <i class="fa-solid fa-terminal"></i> Access Challenge
            </a>

            <!-- Conditional Content based on Progress -->
            <?php
            // CASE 1: Level 2 Completed
            if ($solved_level2):
            ?>
                <div class="compact-completed">
                    <i class="fa-solid fa-trophy"></i> Completed
                </div>

            <?php
            // CASE 2: Level 2 Not Completed (Active)
            else:
            ?>
                <div class="compact-flag-form">
                    <form method="POST">
                        <input type="text" name="flag_level2" placeholder="flag{...}" required>
                        <button type="submit" name="submit_level2" class="small-btn">Submit</button>
                    </form>
                    <?php if ($msg_level2): ?>
                        <div class="mini-msg <?php echo strpos($msg_level2, 'Correct') !== false ? 'success' : 'error'; ?>">
                            <?php echo htmlspecialchars($msg_level2, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== LEVEL 3 CARD ===== -->
        <div class="challenge-card 
    <?php 
    if ($solved_level3) {
        echo 'solved';
    }
    ?>">
            
            <!-- Card Header -->
            <div class="challenge-card-header">
                <div>
                    <span class="challenge-level">Challenge 3</span>
                    <h3>Hostile RCE</h3>
                </div>
                <?php if ($solved_level3): ?>
                    <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
                <?php else: ?>
                    <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
                <?php endif; ?>
            </div>

            <!-- Access Link (Always Visible) -->
            <a href="/levels/level3/" class="challenge-access">
                <i class="fa-solid fa-terminal"></i> Access Challenge
            </a>

            <!-- Conditional Content based on Progress -->
            <?php
            // CASE 1: Level 3 Completed
            if ($solved_level3):
            ?>
                <div class="compact-completed">
                    <i class="fa-solid fa-trophy"></i> Completed
                </div>

            <?php
            // CASE 2: Level 3 Not Completed (Active)
            else:
            ?>
                <div class="compact-flag-form">
                    <form method="POST">
                        <input type="text" name="flag_level3" placeholder="flag{...}" required>
                        <button type="submit" name="submit_level3" class="small-btn">Submit</button>
                    </form>
                    <?php if ($msg_level3): ?>
                        <div class="mini-msg <?php echo strpos($msg_level3, 'Correct') !== false ? 'success' : 'error'; ?>">
                            <?php echo htmlspecialchars($msg_level3, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== LEVEL 4 CARD ===== -->
        <div class="challenge-card 
    <?php 
    if ($solved_level4) {
        echo 'solved';
    }
    ?>">
            
            <!-- Card Header -->
            <div class="challenge-card-header">
                <div>
                    <span class="challenge-level">Challenge 4</span>
                    <h3>MIME Type Bypass</h3>
                </div>
                <?php if ($solved_level4): ?>
                    <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
                <?php else: ?>
                    <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
                <?php endif; ?>
            </div>

            <!-- Access Link (Always Visible) -->
            <a href="/levels/level4/" class="challenge-access">
                <i class="fa-solid fa-terminal"></i> Access Challenge
            </a>

            <!-- Conditional Content based on Progress -->
            <?php
            // CASE 1: Level 4 Completed
            if ($solved_level4):
            ?>
                <div class="compact-completed">
                    <i class="fa-solid fa-trophy"></i> Completed
                </div>

            <?php
            // CASE 2: Level 4 Not Completed (Active)
            else:
            ?>
                <div class="compact-flag-form">
                    <form method="POST">
                        <input type="text" name="flag_level4" placeholder="flag{...}" required>
                        <button type="submit" name="submit_level4" class="small-btn">Submit</button>
                    </form>
                    <?php if ($msg_level4): ?>
                        <div class="mini-msg <?php echo strpos($msg_level4, 'Correct') !== false ? 'success' : 'error'; ?>">
                            <?php echo htmlspecialchars($msg_level4, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== COMMUNITY CARD (FULL WIDTH) ===== -->
        <div class="community-compact-card <?php echo ($solved_level1 && $solved_level2 && $solved_level3 && $solved_level4) ? 'highlight' : ''; ?>">
            <!-- Community Header -->
            <div class="community-compact-header">
                <h3><i class="fa-solid fa-users"></i> Community</h3>
                <?php if ($solved_level1 && $solved_level2 && $solved_level3 && $solved_level4): ?>
                    <span class="next-badge"><i class="fa-solid fa-star"></i> Master Achieved!</span>
                <?php endif; ?>
            </div>

            <!-- Community Links (2 Columns) -->
            <div class="community-compact-content">
                <!-- Discord Card -->
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

                <!-- GitHub Card -->
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

            <!-- Preview Teaser (Only if All Levels Completed) -->
            <?php if ($solved_level1 && $solved_level2 && $solved_level3 && $solved_level4): ?>
                <div class="preview-teaser">
                    <i class="fa-solid fa-crown"></i>
                    <span>Champion!</span>
                </div>
            <?php endif; ?>

            <!-- Community Note -->
            <div class="community-note-compact">
                <i class="fa-solid fa-bell"></i>
                <span>Next challange will be announced in Discord!!</span>
            </div>
        </div>
    </div> <!-- End Challenges Grid -->

<?php endif; ?> <!-- End Logged In Section -->

<!-- ============================================= -->
<!-- FOOTER SECTION -->
<!-- ============================================= -->
<div class="footer">
    <p>© 2026 Mini RCE Platform</p>
    <p>
        <a href="https://discord.gg/nH94vYstKA" target="_blank" rel="noopener noreferrer">Discord</a> 
        | 
        <a href="https://github.com/Nothingness1312/RCE-Lab-Platform" target="_blank" rel="noopener noreferrer">GitHub</a>
    </p>
</div>

</div> <!-- End Container -->

</body>
</html>