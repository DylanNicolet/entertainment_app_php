<?php
include("database.php");

$email_error = "";
$password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($user_email)) {
        $email_error = "Please enter an email";
    } elseif (empty($password)) {
        $email_error = "Please enter a password";
    } else {
        // Using prepared statements to avoid SQL injection
        $sql = "SELECT user_password FROM users WHERE user_email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $retrievedPassword = $row['user_password'];
                $email_error = "";

                if (password_verify($password, $retrievedPassword)) {
                    // Passwords matched
                    $password_error = "";
                    header("Location: pages/homepage.php");
                    exit();
                } else {
                    $password_error = "Invalid credentials";
                }
            } else {
                $email_error = "Unknown Email";
            }
        } else {
            $email_error = "Query failed: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entertainment Web App | Login</title>
    <link rel="stylesheet" href="sass/App.css">
</head>

<body class="login">
    <section class="logo-container">
        <img src="assets/logo.svg" alt="Logo">
    </section>
    <form id="login_form" class="form-login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <h1>Login</h1>

        <fieldset>
            <section class="input_container">
                <input id="input_email" type="email" name="user_email" placeholder="Email address">
                <p id="email_error_msg" class="error_msg"><?php echo $email_error; ?></p>
            </section>
            <section class="input_container">
                <input id="input_password" type="password" name="password" placeholder="Password">
                <p id="password_error_msg" class="error_msg"><?php echo $password_error; ?></p>
            </section>

            <button id="btn_submit" type="button">Login to your account</button>
        </fieldset>

        <section class="alternative-link-wrapper">
            <p>Don't have an account</p>
            <a href="pages/sign_up.php">Sign Up</a>
        </section>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            // Handle form submit and validation
            $("#btn_submit").on("click", function(e) {
                e.preventDefault();

                let userEmail = $("#input_email");
                let password = $("#input_password");
                let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                let wholeFormValid = true;

                // User Email validation
                if (userEmail.val().length === 0) {
                    $("#email_error_msg").html("Can't be empty");
                    userEmail.addClass("--error");
                    wholeFormValid = false;
                } else if (!userEmail.val().match(emailRegex)) {
                    $("#email_error_msg").html("Invalid Email");
                    userEmail.addClass("--error");
                    wholeFormValid = false;
                } else {
                    $("#email_error_msg").html("");
                    userEmail.removeClass("--error");
                }

                // Password validation
                if (password.val().length === 0) {
                    $("#password_error_msg").html("Can't be empty");
                    password.addClass("--error");
                    wholeFormValid = false;
                } else {
                    $("#password_error_msg").html("");
                    password.removeClass("--error");
                }

                if (wholeFormValid) {
                    $("#login_form").submit();
                }
            });
        });
    </script>
</body>

</html>