<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
};

if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>
   <div class="nav">
      <div class="logo">
         <p>Fin <span class="logo__span">Kit</span></p>
      </div>
      <div class="right__links">
         <a class="btn" href="home.php?logout=<?php echo $user_id; ?>">Выйти</a>
      </div>
   </div>
   <div class="success_message" id="success_message">
            <p>Вы успешно авторизованы!</p>
   </div>
   <div class="container">

      <div class="profile">
         <?php
         $select = mysqli_query($connect, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
         }
         if ($fetch['image'] == '') {
            echo '<img src="images/default-avatar.png">';
         } else {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
         }
         ?>
         <h3> Добро пожаловать!<br> <?php echo $fetch['name']; ?></h3>
         <p> Ваш email:<?php echo $fetch['email']; ?></p>
         <a href="update_profile.php" class="btn">Обновить профиль</a>
      </div>

   </div>

   <script src="script.js"></script>
</body>

</html>