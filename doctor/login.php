<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /master.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Email et mot de passe sont requis.";
        exit;
    }

    $url = "http://localhost/api/doctor/login.php";
    $data = json_encode(["email" => $email, "password" => $password]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "Erreur de connexion.";
        exit;
    }

    $response = json_decode($result, true);

    if ($response['success']) {
        $_SESSION['user_id'] = $response['user']['id'];
        $_SESSION['user_name'] = $response['user']['name'];
        header("Location: /master.php");
        exit;
    } else {
        echo $response['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Hôpital</title>
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="index.php" class="logo">
      <span class="logo-mini"><b>M</b>BD</span>
      <span class="logo-lg"><b>Medi</b>CENTER</span>
    </a>
  </header>

  <section class="content-wrapper">
    <section class="content-header">
      <h1>Connexion</h1>
    </section>
    <section class="content container-fluid">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Se connecter</h3>
        </div>
        <form action="login.php" method="POST">
          <div class="box-body">
            <div class="form-group">
              <label for="email">Email :</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="password">Mot de passe :</label>
              <input type="password" name="password" class="form-control" required>
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Se connecter</button>
          </div>
        </form>
      </div>
    </section>
  </section>

  <footer class="main-footer">
    <strong>Copyright &copy; 2018 .</strong>
  </footer>

  <div class="control-sidebar-bg"></div>
</div>

<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
