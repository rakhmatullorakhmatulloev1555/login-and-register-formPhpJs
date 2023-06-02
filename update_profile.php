<?php


session_start();
include 'config.php';//Пут к БД

$user_id = $_SESSION['user_id'];

if(isset($_POST['update_profile'])){

   $update_name = mysqli_real_escape_string($connect, $_POST['update_name']);//Путь к БД для обновления имя
   $update_email = mysqli_real_escape_string($connect, $_POST['update_email']);//Путь к БД для обновления email

   mysqli_query($connect, "UPDATE `user_form` SET name = '$update_name', email = '$update_email' WHERE id = '$user_id'") or die('query failed');

   $old_pass = $_POST['old_pass'];
   $update_pass = mysqli_real_escape_string($connect, md5($_POST['update_pass']));
   $new_pass = mysqli_real_escape_string($connect, md5($_POST['new_pass']));
   $confirm_pass = mysqli_real_escape_string($connect, md5($_POST['confirm_pass']));

   if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
      //Если старый пароль не верный! 
      if($update_pass != $old_pass){
         $message[] = 'Cтарый пароль не верный!';
      }
      //Если пароль подтверждения не совпадает
      elseif($new_pass != $confirm_pass){
         $message[] = 'Пароль подтверждения не совпадает!';
      }
      //Если все данние верни 
      else{
         mysqli_query($connect, "UPDATE `user_form` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
         $message[] = 'Пароль был успешно обновлен!!';
      }
   }

   $update_image = $_FILES['update_image']['name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_folder = 'uploaded_img/'.$update_image;
   if(!empty($update_image)){
      //Если размер изображение больше
      if($update_image_size > 2000000){
         $message[] = 'Изображение слишком большое';
      }else{
         $image_update_query = mysqli_query($connect, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
         if($image_update_query){
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
         }
         $message[] = 'Изображение успешно обновлено!';
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
   <title>Обновить профиль</title>
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="update-profile">

   <?php
      $select = mysqli_query($connect, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <?php
         if($fetch['image'] == ''){
            echo '<img src="images/default-avatar.png">';
         }else{
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
         }
         if(isset($message)){
            foreach($message as $message){
               echo '<div class="message">'.$message.'</div>';
            }
         }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>Имя :</span>
            <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
            <span>Ваш email :</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
            <span>Обновит аватар :</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
            <span>Старый пароль :</span>
            <input type="password" name="update_pass" placeholder="введите прежний пароль" class="box">
            <span>Новый пароль :</span>
            <input type="password" name="new_pass" placeholder="введите новый пароль" class="box">
            <span>Подтвердите новый пароль :</span>
            <input type="password" name="confirm_pass" placeholder="подтвердите новый пароль " class="box">
         </div>
      </div>
      <input type="submit" value="Обновить профиль" name="update_profile" class="btn">
      <a href="home.php" class="delete-btn">Вернуться назад</a>
   </form>

</div>

</body>
</html>