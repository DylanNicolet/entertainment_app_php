<?php
include("database.php");
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form-login">
        <h1>Login</h1>

        <fieldset>
            <input type="email" name="user_email" placeholder="Email address" id="input_email">
            <input type="password" name="password" placeholder="Password">

            <button type="submit" name="submit">Login to your account</button>
        </fieldset>

        <section class="alternative-link-wrapper">
            <p>Don't have an account?</p>
            <a href="pages/sign_up.php">Sign Up</a>
        </section>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous">
    </script>

    <script>
        $("#input_email").on("input", function() {
            console.log(this.value);
        });
    </script>

</body>

</html>

<?php
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
            echo "You are now registered!";
        } catch (mysqli_sql_exception $e) {
            echo "There was an error: " . $e->getMessage();
        }
    }
}

mysqli_close($conn);
?>