<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $lastName = $_POST["lastname"];
  $phone = $_POST["phone"];
  $email = $_POST["email"];
  $message = $_POST["comments"];

  $to = "carpirok@gmail.com";
  $subject = "Nuevo mensaje de contacto";
  
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
 

  $body = '
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
      /* Estilos CSS adicionales aquí */
    </style>
  </head>
  <body>
    <div
      id="email"
      style="
        width: 600px;
        border: 1px solid rgb(78, 78, 78);
        display: flex;
        justify-content: center;
        margin-inline: auto;
        margin-top: 2rem;
      "
    >
      <!-- Header -->
      <table
        style="
          width: 600px;
          font-family: system-ui, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;
        "
      >
        <thead>
          <tr>
            <th
              colspan="2"
              style="
                font-weight: 700;
                font-size: 1.2rem;
                border-bottom: 1px solid rgb(78, 78, 78);
                background-color: rgb(44, 44, 44);
                color: rgb(247, 247, 247);
              "
            >
              Nuevo correo de contacto
            </th>
          </tr>
        </thead>
        <tbody style="border: 1px solid rgb(78, 78, 78)">
          <tr>
            <td
              style="
                padding-block: 1rem;
                border-bottom: 1px solid rgb(78, 78, 78);
              "
            >
              <span
                style="
                  color: rgb(46, 46, 46);
                  font-weight: 700;
                  padding: 0.3rem;
                "
                >Nombre:</span
              >' . $name . '
            </td>
            <td
              style="
                padding-block: 1rem;
                border-bottom: 1px solid rgb(78, 78, 78);
              "
            >
              <span
                style="
                  color: rgb(46, 46, 46);
                  font-weight: 700;
                  padding: 0.3rem;
                "
                >Apellido:</span
              >' . $lastName . '
            </td>
          </tr>
          <tr>
            <td
              style="
                padding-bottom: 1rem;
                border-bottom: 1px solid rgb(78, 78, 78);
              "
            >
              <span
                style="
                  color: rgb(46, 46, 46);
                  font-weight: 700;
                  padding: 0.3rem;
                "
                >Mail:</span
              >' . $email . '
            </td>
            <td
              style="
                padding-bottom: 1rem;
                border-bottom: 1px solid rgb(78, 78, 78);
              "
            >
              <span
                style="
                  color: rgb(46, 46, 46);
                  font-weight: 700;
                  padding: 0.3rem;
                "
                >Telefono:</span
              >' . $phone . '
            </td>
          </tr>
          <tr>
            <td class="mail-mensaje" colspan="2" style="padding-bottom: 1rem">
              <span
                style="
                  color: rgb(46, 46, 46);
                  font-weight: 700;
                  padding: 0.3rem;
                "
                >Mensaje:</span
              >' . $message . '
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>';

  if (mail($to, $subject, $body, $headers)) {
    header('Location: /');
    exit;
  } else {
    echo "Hubo un error al enviar el mensaje";
  }
}
?>
