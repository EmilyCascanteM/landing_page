<!DOCTYPE html>
<html>

<head>
<title>Reporte CSV</title>
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

    function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }
    $country_list = json_decode(
        file_get_contents("http://country.io/names.json")
        ,
        true
    );
    function get_name($cc)
    {
        $countries = $GLOBALS['country_list'];
        return (string) $countries[$cc];
    }

    $sql = "SELECT * FROM users";

    $fields = array('Nombre', 'Apellido', 'Numero de identidad', 'Correo', 'Telefono', 'Direccion', 'Palabra clave');
    $delimiter = ",";
    $filename = "customer_data.csv";
    $f = fopen("php://output", "w");

    if ($result = mysqli_query($mysqli, $sql)) {
        if ($result->num_rows > 0) {
            fputcsv($f, $fields, $delimiter);
            while ($row = $result->fetch_assoc()) {
                $address = $row["address"] . ", " . $row["city"] . ", " . get_name($row["country"]);
                $row_data = array($row["first_name"], $row["last_name"], $row["id_number"], $row["email"], $row["phone_number"], $address, decrypt($row["keyword"], "key_123456"));
                fputcsv($f, $row_data, $delimiter);
            }
            fclose($f);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            exit();
        } else {
             echo '<script language="javascript">alert("No hay datos");</script>';  
        }
    } else {
        echo "Error: " . $sql . ":-" . mysqli_error($mysqli);
    }
    $mysqli->close();

    ?>
</body>

</html>