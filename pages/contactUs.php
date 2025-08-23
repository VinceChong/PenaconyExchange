<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }
?>

<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Contact Us </title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/contactUs.css?v=2"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">
        <style>
            .error {
				color:red;
				font-size:14px;
			}
            </style>
    </head>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class="pageWrapper">
  <div class="pageContent">
    <div class="contactCard">
      <h2>Contact us</h2>

      <form id="contactForm" action="/pie/contact/post-message.php" method="POST" class="formGrid">
        <!-- Salutation -->
        <label for="sal">Salutation:</label>
        <div>
          <select id="sal" name="sal">
            <option disabled selected value>-- Select a Salutation --</option>
            <option value="Mr">Mr</option>
            <option value="Ms">Ms</option>
            <option value="Mrs">Mrs</option>
            <option value="Mdm">Mdm</option>
          </select>
          <div id="salutationError" class="error"></div>
        </div>

        <!-- Name -->
        <label for="name">Name</label>
        <div>
          <input id="name" type="text" name="name" required />
          <div id="nameError" class="error"></div>
        </div>

        <!-- Email -->
        <label for="email">Email</label>
        <div>
          <input id="email" type="email" name="email" required />
          <div id="emailError" class="error"></div>
        </div>

        <!-- Phone -->
        <label for="phone">Phone Number</label>
        <div>
          <input id="phone" type="tel" name="phone" required />
          <div id="phoneError" class="error"></div>
        </div>

        <!-- Enquiry -->
        <label>Type of Enquiry</label>
        <div class="enquiry enquiry-chips">
        <label class="chip">
            <input type="checkbox" name="enquiry" value="General" required>
            <span>General</span>
        </label>
        <label class="chip">
            <input type="checkbox" name="enquiry" value="Complaints">
            <span>Complaints</span>
        </label>
        <label class="chip">
            <input type="checkbox" name="enquiry" value="Suggestions">
            <span>Suggestions</span>
        </label>
        <div id="enquiryError" class="error fullRow"></div>
        </div>

        <!-- Message -->
        <label for="subject" class="fullRowLabel">Message</label>
        <div class="fullRow">
          <textarea id="subject" name="subject" rows="8" required></textarea>
          <div id="subjectError" class="error"></div>
        </div>

        <!-- Submit -->
        <div class="fullRow actions">
          <input type="button" value="Send message" class="btnPrimary" onclick="validateForm()" />
        </div>
      </form>
    </div>
  </div>
</div>

        
        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/contactUs.js"></script>
    </body>
</html>