<?php
require_once "config.php";
 
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor coloque um nome de usuário.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    } else{
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "Este nome de usuário já está em uso.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor insira uma senha.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme a senha.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
         
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            
            if($stmt->execute()){
                header("location: login.php");
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
    <title>Cadastro</title>
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
        p {
            font-size: 16px; 
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
                        <div class="wrapper">
                            <h3>CADASTRO</h3>
                            <p>Por favor, preencha este formulário para criar uma conta.</p>
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
                                <div class="valid-feedback">Válido</div>
                                <div class="invalid-feedback">Por favor, preencha o campo com dados válidos.</div>
                            </div>
                            <div class="form-group">
                                <h5>Confirme a senha*:</h5>
                                <input type="password" name="confirm_password" class="form-control custom-height <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" required>
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                                <div class="valid-feedback">Válido</div>
                                <div class="invalid-feedback">Por favor, preencha o campo com dados válidos.</div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-dark btn-lg btn-block w-50" value="Criar Conta">
                            </div>
                            <p>Já tem uma conta? <a href="login.php">Entre aqui</a>.</p>
                        </div>
                    </div>
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
