<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
require_once "config.php";
 
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor, insira o nome de usuário.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, insira sua senha.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            session_start();
                        
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: welcome.php");
                        } else{
                            $login_err = "Nome de usuário ou senha inválidos.";
                        }
                    }
                } else{
                    $login_err = "Nome de usuário ou senha inválidos.";
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }
    
    unset($pdo);
}
?>
 
 <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #bg {
            background-image: url("imgi/hotel.webp");
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .card {
            border-radius: 1rem;
        }
        .form-control {
            margin-bottom: 1rem;
        }
        .form-control.custom-height {
            height: calc(1.5em + 1rem + 2px); 
            width: 100%; 
        }
        .form-group label {
            font-weight: bold;
        }
        .alert {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
        <section id="bg" class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-light text-body">
                            <div class="card-body p-5 text-center">
                                <div class="container">
                                    <div class="row">
                                        <div class="col text-center">
                                            <img src="imgi/logo.3.png" class="w-75 h-100 mx-auto d-block" alt="Logo"> 
                                        </div>
                                    </div>
                                </div>

                                <h3>ENTRE EM SUA CONTA</h3>
                                <p>Por favor, preencha os campos para fazer o login.</p>
                                <?php 
                                if(!empty($login_err)){
                                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                                }        
                                ?>
                                <div class="form-group">
                                    <h5>Nome do usuário*:</h5>
                                    <input type="text" name="username" class="form-control custom-height <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                                    <div class="valid-feedback">Válido</div>
                                    <div class="invalid-feedback">Por favor, preencha o campo com dados válidos.</div>
                                    
                                </div>    
                                <div class="form-group">
                                    <h5>Senha*:</h5>
                                    <input type="password" name="password" class="form-control custom-height <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-dark btn-lg btn-block w-25" value="Entrar">
                                </div>
                                <p>Não tem uma conta? <a href="register.php">Cadastre-se agora</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <script>
  (function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
  })();
</script>
    </form>
</body>
</html>