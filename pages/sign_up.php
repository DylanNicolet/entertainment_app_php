<?php
include("../database.php");

$email_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($user_email)) {
        echo "Please enter an email";
    } elseif (empty($password)) {
        echo "Please enter a password";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (user_email, user_password) VALUES ('$user_email', '$hash')";

        try {
            mysqli_query($conn, $sql);
            $email_error = "";

            header("Location: homepage.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            $email_error = "This Email is already registered";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entertainment Web App | Sign Up</title>
    <link rel="icon" type="image/png" href="../assets/logo.svg">
    <link rel="stylesheet" href="../sass/App.css">
</head>

<body class="sign_up">
    <section class="logo-container">
        <img src="../assets/logo.svg" alt="Logo">
    </section>
    <form id="sign_up_form" class="form-login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <h1>Sign Up</h1>

        <fieldset>
            <section class="input_container">
                <input id="input_email" type="email" name="user_email" placeholder="Email address">
                <p id="email_error_msg" class="error_msg"><?php echo $email_error; ?></p>
            </section>
            <section class="input_container">
                <input id="input_password" type="password" name="password" placeholder="Password">
                <p id="password_error_msg" class="error_msg"></p>
            </section>
            <section class="input_container">
                <input id="input_password_confirmation" type="password" name="password_confirmation" placeholder="Repeat Password">
                <p id="password_confirmation_error_msg" class="error_msg"></p>
            </section>

            <button id="btn_submit" type="button">Create an account</button>
        </fieldset>

        <section class="alternative-link-wrapper">
            <p>Already have an account?</p>
            <a href="../index.php">Login</a>
        </section>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Handle form submit and validation
            $("#btn_submit").on("click", function(e) {
                e.preventDefault();

                let userEmail = $("#input_email");
                let password = $("#input_password");
                let passwordConfirmation = $("#input_password_confirmation");
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

                // Password confirmation validation
                if (passwordConfirmation.val().length === 0) {
                    $("#password_confirmation_error_msg").html("Can't be empty");
                    passwordConfirmation.addClass("--error");
                    wholeFormValid = false;
                } else if (passwordConfirmation.val() != password.val()) {
                    $("#password_confirmation_error_msg").html("Does not match");
                    passwordConfirmation.addClass("--error");
                    wholeFormValid = false;
                } else {
                    $("#password_confirmation_error_msg").html("");
                    passwordConfirmation.removeClass("--error");
                }

                if (wholeFormValid) {
                    $("#sign_up_form").submit();
                }
            });
        });
    </script>
</body>

</html>