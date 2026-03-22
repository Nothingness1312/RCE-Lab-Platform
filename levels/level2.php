<?php
// =============================================
// LEVEL 6 - SQLi Search
// =============================================
if (isset($_POST['submit_level6']) && $uid && !in_array(6, $solved_levels)) {
    $flag = trim($_POST['flag_level6']);

    if (isset($env['LEVEL6_FLAG']) && $flag === $env['LEVEL6_FLAG']) {
        $stmt = $db->prepare("INSERT INTO progress(user_id, level, solved) VALUES(:u, 6, 1)");
        $stmt->bindValue(":u", $uid, SQLITE3_INTEGER);
        $stmt->execute();
        
        $solved_levels[] = 6;
        
        // Set flag to play sound
        $_SESSION['play_sound'] = true;
        
        // Notifikasi dengan audio
        notifySuccess("Congratulations! You solved Level 6: SQLi Search.", 5000, true);
        
        header("Location: /");
        exit;
    } else {
        notifyError("Wrong flag for Level 6. Please try again.");
        header("Location: /");
        exit;
    }
}

function renderLevel6Card($solved_levels) {
    $solved = in_array(6, $solved_levels);
    ?>
    <div class="challenge-card <?php echo $solved ? 'solved' : ''; ?>">
        <div class="challenge-card-header">
            <div>
                <span class="challenge-level">Challenge 6</span>
                <h3>SQLi Search</h3>
            </div>
            <?php if ($solved): ?>
                <span class="status-badge solved"><i class="fa-solid fa-check"></i> Done</span>
            <?php else: ?>
                <span class="status-badge unsolved"><i class="fa-solid fa-circle"></i> Active</span>
            <?php endif; ?>
        </div>
        <a href="/levels/level6/" class="challenge-access">
            <i class="fa-solid fa-terminal"></i> Access Challenge
        </a>
        <?php if (!$solved): ?>
            <div class="compact-flag-form">
                <form method="POST">
                    <input type="text" name="flag_level6" placeholder="flag{...}" required>
                    <button type="submit" name="submit_level6" class="small-btn">Submit</button>
                </form>
            </div>
        <?php else: ?>
            <div class="compact-completed">
                <i class="fa-solid fa-trophy"></i> Completed
            </div>
        <?php endif; ?>
    </div>
    <?php
}