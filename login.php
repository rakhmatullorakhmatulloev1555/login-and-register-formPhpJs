<?php

include 'config.php'; //Пут к БД
session_start();

// Рандомный код 
$rand = rand(9999, 1000);

if (isset($_COOKIE['email_id']) ?? isset($_COOKIE['password'])) {
   $email_id = $_COOKIE['email_id'];
   $password = $_COOKIE['password'];
} else {
   $email_id =  $password = "";
}


if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($connect, $_POST['email']);
   $password = mysqli_real_escape_string($connect, hash("md5", $_POST['password'], false));
   $captcha =  $_POST['captcha'];
   $captcharandom = $_POST['captcha-rand'];

   // Eсли значение каптчи не совпадает
   if ($captcha != $captcharandom) {?>
      <script type="text/javascript">
       alert('Не правилный код')
      </script>
    <?php
    } 
    else {

      //Выташитим данные из БД
      $select = mysqli_query($connect, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'") or die('query failed');

      // Если есть данные откриваем окно 'home.php'
      if (mysqli_num_rows($select) > 0) {
         $row = mysqli_fetch_assoc($select);
         $_SESSION['user_id'] = $row['id'];
         //Если включен куки
         if (isset($_REQUEST['rememberMe'])) {
            setcookie('email_id', $_REQUEST['email'], time() + 60 * 60); //1 Час сохраняется данные
            setcookie('password', $_REQUEST['password'], time() + 60 * 60); //20 секунд
         }
         //Иначе
         else {
            setcookie('email_id', $_REQUEST['email'], time() - 10); // 
            setcookie('password', $_REQUEST['password'], time() - 10);
         }
         if (password_verify($password, $storedPassword)) {
            $_SESSION['username'] = $username;
         } else {
            echo $message;
         }

         header('location:home.php');
         //Если неверный адрес электронной почты или пароль
      } else  {?>
            <script type="text/javascript">
             alert('Не правилный логин или парол')
            </script>
          <?php
          } 
      }
   }


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Вход</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <div class="container">
      <div class="box form__box">

         <form id="loginForm" action="" method="post" enctype="multipart/form-data">
            <header>Вход</header>
            <div class="field input">
               <label>Логин:</label>
               <input type="email" name="email" placeholder="Введите email" class="box" required value="<?php echo $email_id; ?>">
            </div>
            <div class="field input">
               <label>Пароль:</label>
               <input type="password" name="password" placeholder="Введите пароль" class="box" required value="<?php echo $password; ?>">
            </div>
            <div class="col-md-6 field input" style="width: 50%; float:right">
               <label>Captcha Код:</label>
               <input type="text" name="captcha" id="captcha" placeholder="Введите код" class="box" required>
               <input type="hidden" name="captcha-rand" value="<?php echo $rand; ?>">
            </div>
            <div class="col-md-6 field input">
               <label>Код:</label>
               <div class="captcha"><?php echo $rand; ?></div>
            </div>
            <div class="field input">
               <input type="submit" name="submit" value="Войти" class="btn" onsubmit="return false;">
            </div>
            <p><input type="checkbox" name="rememberMe" />Запомнит меня</p>
            <p>У вас нет аккаунта? <a href="register.php">Регистрация</a></p>
         </form>
      </div>
   </div>

</body>

</html>