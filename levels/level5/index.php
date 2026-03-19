<?php
// Security: Hide errors from users
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

// Initialize database
include_once("../../init.php");

// Create users table for this level if not exists
$db->exec("CREATE TABLE IF NOT EXISTS level5_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT,
    password TEXT
)");

// Insert default user if not exists
$result = $db->query("SELECT COUNT(*) as count FROM level5_users");
$row = $result->fetchArray(SQLITE3_ASSOC);
if ($row['count'] == 0) {
    $db->exec("INSERT INTO level5_users (username, password) VALUES ('kataketuactfnyagampangjadicobaajadulu', 'aaoijioahaadcnwnacwncawuiwcnca')");
}

$message = "";
$message_type = ""; // 'success' or 'error'
$logged_in = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields.";
        $message_type = "error";
    } else {
        // Vulnerable SQL query - intentionally vulnerable for the challenge
        $query = "SELECT * FROM level5_users WHERE username='$username' AND password='$password'";
        $result = @$db->query($query);
        
        if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
            $logged_in = true;
            $message_type = "success";
            
            // Get the flag
            $flag_file = __DIR__ . "/uploads/flag.txt";
            if (file_exists($flag_file)) {
                $flag = file_get_contents($flag_file);
                $message = "✅ Login successful!<br><br>🏆 <strong>FLAG:</strong> " . htmlspecialchars($flag);
            } else {
                $message = "✅ Login successful!<br><br>⚠️ Flag file not found.";
            }
        } else {
            $message = "❌ Invalid username or password.";
            $message_type = "error";
            
            // Log the attempted SQL injection for educational purposes
            if (preg_match('/[\'"]\s*(OR|AND)\s*[\'"]/i', $username . ' ' . $password)) {
                $message = "❌ SQL Injection detected! But keep trying...<br><br>💡 Hint: Try ' OR '1'='1";
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
    <title>Level 5 - SQL Injection Login | Mini RCE Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-database"></i>
        </div>
        <div class="header-title">
            <div>Level 5: SQL Injection Login</div>
            <div class="sub">Basic SQL Injection Vulnerability</div>
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
                <i class="fa-solid fa-right-to-bracket"></i>
            </div>
            <div class="challenge-title">
                <h3>SQL Injection Login Challenge</h3>
                <p>Bypass the login using SQL injection techniques</p>
            </div>
        </div>
        
        <!-- Challenge Description -->
        <div class="challenge-description">
            <div class="description-item">
                <i class="fa-solid fa-circle-info"></i>
                <p>This login form is vulnerable to SQL injection. The application doesn't use prepared statements, allowing attackers to manipulate the SQL query.</p>
            </div>

            <div class="description-item">
                <i class="fa-solid fa-bolt"></i>
                <p><strong>Your goal:</strong> Bypass the login to get the flag. Try injecting SQL code into the username or password fields.</p>
            </div>
            
            <div class="description-item">
                <i class="fa-solid fa-code"></i>
                <p><strong>Vulnerable code:</strong> <code style="background: #1e293b; padding: 2px 6px; border-radius: 4px;">SELECT * FROM users WHERE username='$username' AND password='$password'</code></p>
            </div>
        </div>
        
        <!-- Login Status (if logged in) -->
        <?php if ($logged_in): ?>
            <div class="success-box">
                <i class="fa-solid fa-circle-check"></i>
                <div class="success-content">
                    <div class="success-title">🎉 Login Successful!</div>
                    <div class="success-text"><?php echo $message; ?></div>
                </div>
            </div>
            
            <a href="?reset" class="reset-link">
                <i class="fa-solid fa-arrow-rotate-left"></i> Try Again
            </a>
        <?php else: ?>
        
        <!-- Login Form -->
        <div class="login-form">
            <form method="POST" action="" id="loginForm">
                <!-- Username Field -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-user"></i> Username
                    </label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               placeholder="Enter username..."
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                               required>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-lock"></i> Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               placeholder="Enter password..."
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="login-btn" id="loginBtn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </button>
            </form>
            
            <!-- Message Display -->
            <?php if(!empty($message) && !$logged_in): ?>
                <div class="message-box <?php echo $message_type; ?>">
                    <i class="fa-solid <?php echo ($message_type == 'success') ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                    <div class="message-content">
                        <div class="message-text"><?php echo $message; ?></div>
                    </div>
                </div>
            <?php endif; ?>
            <?php endif; ?>
        
        <!-- Back Link -->
        <a href="/" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

</div>

<!-- JavaScript -->
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function fillPayload(username, password) {
    document.getElementById('username').value = username;
    document.getElementById('password').value = password;
    
    // Highlight the fields briefly
    document.getElementById('username').style.borderColor = '#22c55e';
    document.getElementById('password').style.borderColor = '#22c55e';
    
    setTimeout(() => {
        document.getElementById('username').style.borderColor = '';
        document.getElementById('password').style.borderColor = '';
    }, 500);
}

// Add loading state on form submit
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Logging in...';
    btn.disabled = true;
});
</script>

</body>
</html>