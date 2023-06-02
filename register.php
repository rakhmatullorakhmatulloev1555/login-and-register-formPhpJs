<?php

include 'config.php';

if (isset($_POST['submit'])) {

   $name = mysqli_real_escape_string($connect, $_POST['name']);
   $email = mysqli_real_escape_string($connect, $_POST['email']);
   $pass = mysqli_real_escape_string($connect, hash("md5", $_POST['password'], false)); 
   $cpass = mysqli_real_escape_string($connect, hash("md5", $_POST['cpassword'], false));
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select = mysqli_query($connect, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select) > 0) {
      $message[] = 'Пользователь уже существует';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Пароль подтверждения не совпадает!';
      } elseif ($image_size > 2000000) {
         $message[] = 'image size is too large!';
      } else {
         $insert = mysqli_query($connect, "INSERT INTO `user_form`(name, email, password, image) VALUES('$name', '$email', '$pass', '$image')") or die('query failed');

         if ($insert) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registered successfully!';
            header('location:login.php');
         } else {
            $message[] = 'registeration failed!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Регистрация</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <div class="container">
      <div class="box form__box">

         <form action="" method="post" enctype="multipart/form-data">
            <header>Регистрация</header>
            <?php
            if (isset($message)) {
               foreach ($message as $message) {
                  echo '<div class="message">' . $message . '</div>';
               }
            }
            ?>
            <div class="field input">
               <label>Имя:</label>
               <input type="text" name="name" placeholder="Введите имя" class="box" required>
            </div>
            <div class="field input">
               <label>Email:</label>
               <input type="email" name="email" placeholder="Введите email" class="box" required>
            </div>
            <div class="field input">
               <label>Пароль:</label>
               <input type="password" name="password" placeholder="Введите пароль" class="box" required>
            </div>
            <div class="field input">
               <label>Подтвердите пароль:</label>
               <input type="password" name="cpassword" placeholder="Подтвердите пароль" class="box" required>
            </div>
            <div class="field input">
               <label>Фото профиля:</label>
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
            </div>
            <div class="field">
               <input type="submit" name="submit" value="Регистрация" class="btn">
            </div>
            <div class="links">
               <p>У вас уже есть аккаунт? <a href="login.php">Войты</a></p>
            </div>
         </form>
      </div>

   </div>

</body>

</html>