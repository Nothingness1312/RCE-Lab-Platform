<?php
// =============================================
// LEVEL 1 - Basic RCE
// =============================================
$msg_level1 = '';

if (isset($_POST['submit_level1']) && $uid && !in_array(1, $solved_levels)) {
    $flag = trim($_POST['flag_level1']);

    if (isset($env['LEVEL1_FLAG']) && $flag === $env['LEVEL1_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 1, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();
        
        $solved_levels[] = 1;
        $msg_level1 = "Correct! Level 1 completed!";
    } else {
        $msg_level1 = "Wrong flag for Level 1.";
    }
}

function renderLevel1Card($solved_levels, $msg_level1) {
    $solved = in_array(1, $solved_levels);
    ?>
    <div class="challenge-card <?php echo $solved ? 'solved' : ''; ?>">
        <div class="challenge-card-header">
            <div>
                <span class="challenge-level">Challenge 1</span>
                <h3>Basic RCE</h3>
            </div>
            <?php if ($solved): ?>
                <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
            <?php else: ?>
                <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
            <?php endif; ?>
        </div>
        <a href="/levels/level1/" class="challenge-access">
            <i class="fa-solid fa-terminal"></i> Access Challenge
        </a>
        <?php if (!$solved): ?>
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
            <div class="compact-completed">
                <i class="fa-solid fa-trophy"></i> Completed
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// =============================================
// LEVEL 2 - Filtered RCE
// =============================================
$msg_level2 = '';

if (isset($_POST['submit_level2']) && $uid && !in_array(2, $solved_levels)) {
    $flag = trim($_POST['flag_level2']);

    if (isset($env['LEVEL2_FLAG']) && $flag === $env['LEVEL2_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 2, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();
        
        $solved_levels[] = 2;
        $msg_level2 = "Correct! Level 2 completed!";
    } else {
        $msg_level2 = "Wrong flag for Level 2.";
    }
}

function renderLevel2Card($solved_levels, $msg_level2) {
    $solved = in_array(2, $solved_levels);
    ?>
    <div class="challenge-card <?php echo $solved ? 'solved' : ''; ?>">
        <div class="challenge-card-header">
            <div>
                <span class="challenge-level">Challenge 2</span>
                <h3>Filtered RCE</h3>
            </div>
            <?php if ($solved): ?>
                <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
            <?php else: ?>
                <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
            <?php endif; ?>
        </div>
        <a href="/levels/level2/" class="challenge-access">
            <i class="fa-solid fa-terminal"></i> Access Challenge
        </a>
        <?php if (!$solved): ?>
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
        <?php else: ?>
            <div class="compact-completed">
                <i class="fa-solid fa-trophy"></i> Completed
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// =============================================
// LEVEL 3 - Hostile RCE
// =============================================
$msg_level3 = '';

if (isset($_POST['submit_level3']) && $uid && !in_array(3, $solved_levels)) {
    $flag = trim($_POST['flag_level3']);

    if (isset($env['LEVEL3_FLAG']) && $flag === $env['LEVEL3_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 3, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();
        
        $solved_levels[] = 3;
        $msg_level3 = "Correct! Level 3 completed!";
    } else {
        $msg_level3 = "Wrong flag for Level 3.";
    }
}

function renderLevel3Card($solved_levels, $msg_level3) {
    $solved = in_array(3, $solved_levels);
    ?>
    <div class="challenge-card <?php echo $solved ? 'solved' : ''; ?>">
        <div class="challenge-card-header">
            <div>
                <span class="challenge-level">Challenge 3</span>
                <h3>Hostile RCE</h3>
            </div>
            <?php if ($solved): ?>
                <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
            <?php else: ?>
                <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
            <?php endif; ?>
        </div>
        <a href="/levels/level3/" class="challenge-access">
            <i class="fa-solid fa-terminal"></i> Access Challenge
        </a>
        <?php if (!$solved): ?>
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
        <?php else: ?>
            <div class="compact-completed">
                <i class="fa-solid fa-trophy"></i> Completed
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// =============================================
// LEVEL 4 - MIME Type Bypass
// =============================================
$msg_level4 = '';

if (isset($_POST['submit_level4']) && $uid && !in_array(4, $solved_levels)) {
    $flag = trim($_POST['flag_level4']);

    if (isset($env['LEVEL4_FLAG']) && $flag === $env['LEVEL4_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 4, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();
        
        $solved_levels[] = 4;
        $msg_level4 = "Correct! Level 4 completed!";
    } else {
        $msg_level4 = "Wrong flag for Level 4.";
    }
}

function renderLevel4Card($solved_levels, $msg_level4) {
    $solved = in_array(4, $solved_levels);
    ?>
    <div class="challenge-card <?php echo $solved ? 'solved' : ''; ?>">
        <div class="challenge-card-header">
            <div>
                <span class="challenge-level">Challenge 4</span>
                <h3>MIME Type Bypass</h3>
            </div>
            <?php if ($solved): ?>
                <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
            <?php else: ?>
                <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
            <?php endif; ?>
        </div>
        <a href="/levels/level4/" class="challenge-access">
            <i class="fa-solid fa-terminal"></i> Access Challenge
        </a>
        <?php if (!$solved): ?>
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
        <?php else: ?>
            <div class="compact-completed">
                <i class="fa-solid fa-trophy"></i> Completed
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// =============================================
// LEVEL 5 - Basic Login
// =============================================
$msg_level5 = '';

if (isset($_POST['submit_level5']) && $uid && !in_array(5, $solved_levels)) {
    $flag = trim($_POST['flag_level5']);

    if (isset($env['LEVEL5_FLAG']) && $flag === $env['LEVEL5_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 5, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();
        
        $solved_levels[] = 5;
        $msg_level5 = "Correct! Level 5 completed!";
    } else {
        $msg_level5 = "Wrong flag for Level 5.";
    }
}

function renderLevel5Card($solved_levels, $msg_level5) {
    $solved = in_array(5, $solved_levels);
    ?>
    <div class="challenge-card <?php echo $solved ? 'solved' : ''; ?>">
        <div class="challenge-card-header">
            <div>
                <span class="challenge-level">Challenge 5</span>
                <h3>Basic Login</h3>
            </div>
            <?php if ($solved): ?>
                <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
            <?php else: ?>
                <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
            <?php endif; ?>
        </div>
        <a href="/levels/level5/" class="challenge-access">
            <i class="fa-solid fa-terminal"></i> Access Challenge
        </a>
        <?php if (!$solved): ?>
            <div class="compact-flag-form">
                <form method="POST">
                    <input type="text" name="flag_level5" placeholder="flag{...}" required>
                    <button type="submit" name="submit_level5" class="small-btn">Submit</button>
                </form>
                <?php if ($msg_level5): ?>
                    <div class="mini-msg <?php echo strpos($msg_level5, 'Correct') !== false ? 'success' : 'error'; ?>">
                        <?php echo htmlspecialchars($msg_level5, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="compact-completed">
                <i class="fa-solid fa-trophy"></i> Completed
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
