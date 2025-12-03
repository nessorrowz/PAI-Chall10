<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>File Upload</title>
  <style>
    body {
      background: #f7f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 400px;
      box-sizing: border-box;
    }

    h1 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }

    input[type="file"] {
      display: block;
      margin: 1rem auto;
    }

    input[type="submit"] {
      background-color: #4a90e2;
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      display: block;
      margin: 0 auto;
      font-size: 1rem;
    }

    input[type="submit"]:hover {
      background-color: #357ab8;
    }

    .footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: #888;
    }

    @media (max-width: 480px) {
      .container {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Upload a File</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="file" required>
      <input type="submit" value="Upload">
    </form>
  </div>
</body>
</html>
