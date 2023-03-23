<!DOCTYPE html>
<html>

<head>
    <title>Insertar</title>
</head>

<body>
    <?php

    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'landing_page';
    $db_port = 8889;

    $mysqli = new mysqli(
        $db_host,
        $db_user,
        $db_password,
        $db_db,
        $db_port
    );
    
    if (isset($_POST['submit'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $id_number = $_POST['id_number'];
        $email = $_POST['email'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        $keyword = $_POST['keyword'];

        function get_name($cc)
        {
            $countries = $GLOBALS['country_codes'];
            return (string) $countries[$cc];
        }
        function encrypt($string, $key)
        {
            $result = '';
            for ($i = 0; $i < strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                $char = chr(ord($char) + ord($keychar));
                $result .= $char;
            }
            return base64_encode($result);
        }

        function redirect_error() {
            header("Location: error.php");
            exit();
            
        }
        function redirect_success() {
            header("Location: success.php");
            exit();
            
        }

        $country_codes = json_decode(
            file_get_contents("http://country.io/names.json")
            ,
            true
        );

        $keyword_encrypted = encrypt($keyword, "key_123456");
        
        $sql_get = "SELECT * FROM users WHERE users.id_number = '$id_number' OR users.email = '$email'";
        if ($result_get = mysqli_query($mysqli, $sql_get)) {

            if ($result_get->num_rows > 0) {
                redirect_error();
            } else {
                $sql_insert = "INSERT INTO users (`id`, `first_name`, `last_name`, `id_number`, `email`, `country`, `city`, `address`, `phone_number`, `keyword`) 
                VALUES (null, '$first_name','$last_name','$id_number','$email','$country','$city','$address','$phone_number','$keyword_encrypted')";

                if (mysqli_query($mysqli, $sql_insert)) {
                   redirect_success();
                } else {
                    redirect_error();
                }
            }
        }
    }
    $mysqli->close();
    ?>
</body>

</html>