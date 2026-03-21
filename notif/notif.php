<?php
// =============================================
// NOTIFICATION HANDLER
// =============================================

/**
 * Set flash notification message
 * @param string $type Type of notification (success, error, info, warning)
 * @param string $message Notification message
 * @param int $duration Auto-hide duration in milliseconds (default: 5000)
 * @param bool $playSound Whether to play sound notification (default: false)
 */
function setNotification($type, $message, $duration = 5000, $playSound = false) {
    $_SESSION['notification'] = [
        'type' => $type,
        'message' => $message,
        'duration' => $duration,
        'playSound' => $playSound,
        'timestamp' => time()
    ];
}

/**
 * Get and clear notification
 * @return array|null Notification data or null if none exists
 */
function getNotification() {
    if (isset($_SESSION['notification'])) {
        $notification = $_SESSION['notification'];
        unset($_SESSION['notification']);
        return $notification;
    }
    return null;
}

/**
 * Display notification HTML with JavaScript (Center Screen)
 */
function displayNotification() {
    $notification = getNotification();
    if (!$notification) {
        return;
    }
    
    $type = $notification['type'];
    $message = htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8');
    $duration = $notification['duration'];
    $playSound = $notification['playSound'];
    
    // Icons for different notification types
    $icons = [
        'success' => 'fa-circle-check',
        'error' => 'fa-circle-exclamation',
        'warning' => 'fa-triangle-exclamation',
        'info' => 'fa-circle-info'
    ];
    
    $icon = isset($icons[$type]) ? $icons[$type] : 'fa-circle-info';
    $bgColor = getNotificationColor($type);
    
    echo <<<HTML
    <div id="custom-notification" class="notification-center notification-{$type}" style="background: {$bgColor};">
        <div class="notification-center-content">
            <i class="fa-solid {$icon}"></i>
            <div class="notification-center-text">
                <div class="notification-center-title">{$message}</div>
            </div>
        </div>
        <button class="notification-center-close" onclick="closeNotification()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <script>
        function closeNotification() {
            const notif = document.getElementById('custom-notification');
            if (notif) {
                notif.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => {
                    if (notif && notif.remove) notif.remove();
                }, 300);
            }
        }
        
        // Auto close after duration
        setTimeout(() => {
            closeNotification();
        }, {$duration});
        
        // Close when clicking outside
        document.addEventListener('click', function(event) {
            const notif = document.getElementById('custom-notification');
            if (notif && !notif.contains(event.target)) {
                closeNotification();
            }
        });
        
        // Play sound if needed
        {$playSoundScript}
    </script>
HTML;
}

/**
 * Get CSS color for notification type
 */
function getNotificationColor($type) {
    switch ($type) {
        case 'success':
            return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        case 'error':
            return 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        case 'warning':
            return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
        case 'info':
        default:
            return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
    }
}

/**
 * Set success notification helper with sound
 */
function notifySuccess($message, $duration = 5000, $playSound = false) {
    setNotification('success', $message, $duration, $playSound);
}

/**
 * Set error notification helper
 */
function notifyError($message, $duration = 5000, $playSound = false) {
    setNotification('error', $message, $duration, $playSound);
}

/**
 * Set warning notification helper
 */
function notifyWarning($message, $duration = 5000, $playSound = false) {
    setNotification('warning', $message, $duration, $playSound);
}

/**
 * Set info notification helper
 */
function notifyInfo($message, $duration = 5000, $playSound = false) {
    setNotification('info', $message, $duration, $playSound);
}
?>