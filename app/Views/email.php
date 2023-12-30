<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Email Example</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
      href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&family=Poppins&display=swap"
      rel="stylesheet" />
<style>
      /* Reset some default styles */
      body,
      table,
      td,
      p,
      a {
        font-family: Poppins, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        margin: 0;
      }

      /* Styles for the container */
      .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
      }

      /* Styles for the header */
      .header {
        background-color: #20b286 ;
        padding: 10px;
        text-align: center;
      }

      /* Styles for the content */
      .content {
        padding: 20px;
      }

      /* Styles for the footer */
      .footer {
        background-color: #1b5e20;
        padding: 10px;
        text-align: center;
        color: white;
      }
</style>
</head>
<body>
<div class="container">
<div class="header">
<h2 style="margin: 0">Celestech Enterprise</h2>
</div>
<div class="content">
<div style="text-align: center">
          Your One-Time Password is: <?= $otp ?>

        </div>
<div
</div>
</div>
</div>
</body>
</html>
