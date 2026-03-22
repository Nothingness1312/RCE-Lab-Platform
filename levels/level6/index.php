<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
include_once("../../init.php");

// ======================
// SETUP TABLE ITEMS
// ======================
$db->exec("CREATE TABLE IF NOT EXISTS level6_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    image TEXT
)");

$result = $db->query("SELECT COUNT(*) as count FROM level6_items");
$row = $result->fetchArray(SQLITE3_ASSOC);

if ($row['count'] == 0) {
    $db->exec("
        INSERT INTO level6_items (title, image) VALUES
        ('Waguri', 'https://i.pinimg.com/736x/3b/24/d8/3b24d82e86c26bb29e032b1936136cfd.jpg'),
        ('Reze', 'https://i.pinimg.com/736x/64/bf/df/64bfdfad3c51e2e7664dba3399ccbbea.jpg'),
        ('Bocchi', 'https://i.pinimg.com/736x/57/ad/a1/57ada16ce666c54bbe7198f8d3f937e2.jpg'),
        ('Furina', 'https://i.pinimg.com/736x/67/10/ac/6710ac66b058fe0c5ba0ea90d1542c25.jpg'),
        ('My...', 'https://i.pinimg.com/736x/0a/16/6b/0a166bc499e6ee94cf163a01d39b83ba.jpg')
    ");
}

// ======================
// SETUP FLAG TABLE
// ======================
$db->exec("CREATE TABLE IF NOT EXISTS level6_flags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    flag TEXT
)");

$db->exec("INSERT OR IGNORE INTO level6_flags (id, flag) VALUES (1, 'flag{Un10n_S3arch_3xtr@ct_L3v3L6}')");

// ======================
// SEARCH LOGIC (VULNERABLE)
// ======================
$search = isset($_GET['q']) ? $_GET['q'] : '';
$results = [];
$error_message = '';

if (!empty($search)) {
    // VULNERABLE QUERY - SQL Injection
    $query = "SELECT title, image FROM level6_items WHERE title = '$search'";
    
    $res = @$db->query($query);
    
    if ($res === false) {
        $error_message = "SQL Error: Invalid query syntax";
    } else {
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $results[] = $row;
        }
        
        if (count($results) == 0) {
            $error_message = "No results found for: " . htmlspecialchars($search);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level 6 - SQL Injection Search | Mini RCE Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <div class="header-title">
            <div>Level 6: SQL Injection Search</div>
            <div class="sub">Union Based SQL Injection Vulnerability</div>
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
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div class="challenge-title">
                <h3>SQL Injection Search Challenge</h3>
                <p>Union Based SQL Injection Vulnerability</p>
            </div>
        </div>
        
        <!-- Challenge Description -->
        <div class="challenge-description">
            <div class="description-item">
                <i class="fa-solid fa-circle-info"></i>
                <p>This search functionality is vulnerable to SQL injection. The application doesn't use prepared statements.</p>
            </div>
            
            <div class="description-item">
                <i class="fa-solid fa-bolt"></i>
                <p><strong>Your goal:</strong> Extract data from other tables using UNION-based SQL injection to get the flag (%) (sqlite_master).</p>
            </div>

            <div class="description-item">
                <i class="fa-solid fa-code"></i>
                <p><strong>Vulnerable code:</strong> <code>$query = "SELECT title, image FROM level6_items WHERE title = '$search'";</code></p>
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="search-form">
            <form method="GET" action="" id="searchForm">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-search"></i> Search Images
                    </label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-magnifying-glass input-icon"></i>
                        <input type="text" 
                               name="q" 
                               id="searchInput"
                               placeholder="Enter search term..."
                               value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                               autocomplete="off">
                        <button type="submit" class="search-btn" id="searchBtn">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Error Message -->
            <?php if ($search && !empty($error_message)): ?>
                <div class="message-box error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div class="message-content">
                        <div class="message-text"><?php echo $error_message; ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Results Section -->
        <div class="results-section">
            <?php if (!empty($search) && count($results) > 0): ?>
                <div class="results-header">
                    <i class="fa-solid fa-images"></i>
                    <span>Found <?php echo count($results); ?> result(s)</span>
                </div>
                <div class="results-grid">
                    <?php foreach ($results as $item): ?>
                        <div class="result-card">
                            <div class="result-image">
                                <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="result-info">
                                <h4><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif (!empty($search) && count($results) == 0 && !$error_message): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-face-frown"></i>
                    <h3>No Results Found</h3>
                    <p>Try different keywords</p>
                </div>
            <?php elseif (empty($search)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-images"></i>
                    <h3>Start Searching</h3>
                    <p>Enter a search term to find images</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Back Link -->
        <a href="/" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

</div>

<!-- JavaScript -->
<script>
// Add loading state on form submit
document.getElementById('searchForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('searchBtn');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Searching...';
    btn.disabled = true;
});
</script>

</body>
</html>