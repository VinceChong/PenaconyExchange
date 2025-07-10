<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Penacony </title>
        <link rel = "stylesheet" href = "./styles/login.css"/>
        <link rel = "icon" href = "./assets/image/harmony.png">
    </head>

    <body>
        
        <?php 
            $message = "";
            $type = "";

            if (isset($_GET["error"])) {
                $message = htmlspecialchars($_GET["error"]);
                $type = "error";

            } elseif (isset($_GET["success"])) {
                $message = htmlspecialchars($_GET["success"]);
                $type = "success";
            }
        ?>

        <?php if ($message): ?>
            <div class = "alert <?= $type ?>" id = "alertBox">
                <span class = "closeButton" onclick = "document.getElementById('alertBox').style.display='none';">&times;</span>
                    <p> <?= $message ?> </p>
            </div>
        <?php endif; ?>

        <div class = "container">
            <div class = "tabs">
                <button id = "loginTab" class = "active"> Login </button>
                <button id = "signUpTab"> Sign Up </button>
            </div>

            <form id = "loginForm" class = "form active" action = "./db/login.php" method = "POST">
                <div class="form-group">
                    <label for = "email"> Email </label>
                    <input type = "email" name = "email" placeholder = "Songshu@gmail.com" required/>
                </div>

                <div class="form-group">
                    <label for = "password"> Password </label>
                    <input type = "password" name = "password" placeholder = "Password" required/>
                </div>

                <button type = "submit" class = "button"></i> Login </button>
            </form>

            <form id = "signUpForm" class = "form"  action = "./db/signUp.php" method = "POST">
                <div class="form-group">    
                    <label for = "username"> Username </label>
                    <input type = "text" name = "username" placeholder = "Songshu" required/>
                </div>

                <div class="form-group">
                    <label for = "email"> Email </label>
                    <input type = "email" name = "email" placeholder = "Songshu@gmail.com" required/>
                </div>

                <div class="form-group">
                    <label for = "password"> Password </label>
                    <input type = "password" name = "password" placeholder = "Password" required/>
                </div>
                
                <div class="form-group">
                    <label for = "confirmPassword"> Confirm Password </label>
                    <input type = "password" name = "confirmPassword" placeholder = "Confirm Password" required/>
                </div>
                
                <button type = "submit" class = "button"> Sign Up </button>
            </form>
        </div>

        <script src = "./scripts/login.js"></script>
    </body>
</html>