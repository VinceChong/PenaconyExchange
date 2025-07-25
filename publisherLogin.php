<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Penacony </title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/index.css"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">
    </head>

    <body style = "display: flex; flex-direction: column; justify-content: center; align-items: center;">
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

            <form id = "loginForm" class = "form active" action = "/PenaconyExchange/db/backend/publisher/publisherLogin.php" method = "POST">
                <div class="form-group">
                    <label for = "email"> Publisher Email </label>
                    <input type = "email" name = "email" id = "email" placeholder = "Songshu@gmail.com" required/>
                </div>

                <div class="form-group">
                    <label for = "password"> Publisher Password </label>
                    <input type = "password" name = "password" id = "password" placeholder = "Password" required/>
                </div>

                <div class = "changeUser">
                    <p> <a href = "/PenaconyExchange/">Login/Sign up As User</a> </p>
                </div>

                <button type = "submit" class = "button"> Login </button>
            </form>

            <form id = "signUpForm" class = "form"  action = "/PenaconyExchange/db/backend/publisher/publisherSignup.php" method = "POST">
                <div class="form-group">    
                    <label for = "username"> Publisher Username </label>
                    <input type = "text" name = "username" id = "username" placeholder = "Songshu" required/>
                </div>

                <div class="form-group">
                    <label for = "email"> Publisher Email </label>
                    <input type = "email" name = "email" id = "email" placeholder = "Songshu@gmail.com" required/>
                </div>

                <div class="form-group">
                    <label for = "password"> Publisher Password </label>
                    <input type = "password" name = "password" id = "password" placeholder = "Password" required/>
                </div>
                
                <div class="form-group">
                    <label for = "confirmPassword"> Confirm Password </label>
                    <input type = "password" name = "confirmPassword" id = "confirmPassword" placeholder = "Confirm Password" required/>
                </div>
                
                <div class = "changeUser">
                    <p> <a href = "/PenaconyExchange/">Login/Sign up As User</a> </p>
                </div>

                <button type = "submit" class = "button"> Sign Up </button>
            </form>
        </div>
        

        <script src = "/PenaconyExchange/scripts/publisher/publisherLogin.js"></script>
    </body>
</html>