<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 105vh;
        }    
    </style>
</head>
<body>
  <div class="container center-container">
  <div class="col-md-5">
    <form method="POST" action="admin_dashboard.php">
    <h1 class="text-center">Admin Login</h1>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="admin"required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" value="admin" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      </form> 
</div>
</div>
</body>
</html>
