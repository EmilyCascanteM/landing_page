

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
  <title>Registro</title>
  <link rel="stylesheet" href="index.css">
</head>

<body class="container">
  <div >
    <div style="display: flex; justify-content: center; align-items: center ;">
      <h1 style="margin-right: 5%;">Registro</h1>
      <form action="report.php" method="post">
        <input
          style="background-color: transparent; border-color: transparent; color: rgb(240, 239, 238); text-decoration: underline;"
          type="submit" value="Descargar CSV">
      </form>
    </div>

    <form action="insert.php" method="post">
      <div class="inputs-form">
        Nombre: <input type="text" name="first_name" required /><br />
        Apellidos: <input type="text" name="last_name" required /><br />
        Identificación: <input type="number" name="id_number" id="id_number" required /><br />
        Correo: <input type="email" name="email" required /><br />
        País:
        <select style="height: 1.5rem;" name="country" required id="countries" name="countries">
          <?php
          $country_codes = json_decode(
            file_get_contents("http://country.io/names.json")
            ,
            true
          );
          uasort($country_codes, function ($a, $b) {
            if ($a == $b) {
              return 0;
            }
            return ($a < $b) ? -1 : 1;
          });
          foreach ($country_codes as $code => $country) {
            ?>
            <option value="<?= $code ?>"><?= $country ?></option>
          <?php } ?>
        </select>
        Ciudad: <input type="text" name="city" required /><br />
        Dirección: <input type="text" name="address" required /><br />
        Teléfono: <input type="number" name="phone_number" required /><br />
        Palabra clave: <input type="text" name="keyword" required /><br />
      </div>

      <input id="button"
        style="background-color: #838888; border-color: transparent; color: rgb(240, 239, 238); cursor: pointer; width: 50%;"
        type="submit"  name="submit" value="Guardar">
    </form>

    <script>
      "use strict";

      let button = document.querySelector('#button');
      let input = document.querySelector('#id_number');
      let length = 12;

      button.addEventListener('click', validate);

      function validate(event) {
      const value_size = input.value.length;
      console.log(value_size, input, "size")
        if (value_size != length) {
          
          event.preventDefault();
          alert('El número de identificación debe contener ' + length + ' dígitos');
        }
      }
    </script>
  </div>
  <footer></footer>
</body>

</html>