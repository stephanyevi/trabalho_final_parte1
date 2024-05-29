<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
require_once "config.php";
 
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Por favor insira a nova senha.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme a senha.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
        
    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            if($stmt->execute()){
                session_destroy();
                header("location: login.php");
                exit();
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
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #bg {
            background-image: url("imgi/hotelr2.webp");
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
                                <h3>REDEFINIR SENHA</h3>
                                <p>Por favor, preencha este formulário para redefinir sua senha.</p>
                                <div class="form-group">
                                    <h5>Nova senha*:</h5>
                                    <input type="password" name="new_password" class="form-control custom-height <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>" required>
                                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
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
                                <div class="form-group text-center">
                                    <input type="submit" class="btn btn-dark btn-lg w-25 d-inline-block" value="Redefinir">
                                    <a class="btn btn-danger btn-lg w-40 d-inline-block ml-2" href="welcome.php">Cancelar</a>
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
