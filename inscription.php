<?php include('pdo.php'); ?>
<?php include('inc/fonction.php'); ?>
<?php
$error = array();
$success = false;
 // soumission du formulaire
if (!empty($_POST['submitted'])) {
  // Faille XSS
  $login    = trim(strip_tags($_POST['login']));
  $email    = trim(strip_tags($_POST['email']));
  $mdp    = trim(strip_tags($_POST['mdp']));
  $mdp2    = trim(strip_tags($_POST['mdp2']));

  // validation des champs
  //login
  if(!empty($login)){
    if(strlen($login)<3){
      $error['login']='votre identifiant est court';

    }elseif (strlen($login)>30) {
      $error['login']='votre identifiant est long';
    }else {
      //requete sql
      $sql = "SELECT login FROM v2_user WHERE login = :login";
      $query = $pdo->prepare($sql);
      $query->bindValue(':login',$login,PDO::PARAM_STR);
      $query->execute();
      $userLogin = $query->fetch();
      if(!empty($userLogin)) {
        $error['login'] = 'Cet identifient existe déjà';
      }

    }
  }else {
    $error['login']='Veuillez renseigner ce champ';
  }

  //Email
  if (!empty($email)) {
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {

    } else {
      // la requete sql
      $sql = "SELECT email FROM v2_user WHERE email = :email";
      $query = $pdo->prepare($sql);
      $query->bindValue(':email',$email,PDO::PARAM_STR);
      $query->execute();
      $userEmail = $query->fetch();
      if(!empty($userEmail)) {
          $error['email'] = 'email déjà utilisé';
      }
    }
} else {
  $error['email'] = 'Veuillez renseigner un email';
}

// mot de passe
if(!empty($mdp) && !empty($mdp2)) {
      if($mdp != $mdp2) {
        $error['mdp'] = 'Vos mot de passe sont différents';
      } elseif(strlen($mdp) < 5 ) {
        $error['mdp'] = 'Votre mot de passe est trop court';
      }
} else {
  $error['mdp'] = 'Veuillez renseigner un password';
}

// Si pas d'erreur j'insert dans la base de donnée
if(count($error) == 0) {
  $success = true;
  $hash = password_hash($mdp,PASSWORD_DEFAULT);
  $token = generateRandomString(120);
  // insert into

  $sql = "INSERT INTO v2_user (login,email,mdp,status,token,created_at)
          VALUES (:login,:email,:mdp,'user','$token', NOW())";
  $query = $pdo->prepare($sql);
  $query->bindValue(':login',$login,PDO::PARAM_STR);
  $query->bindValue(':email',$email,PDO::PARAM_STR);
  $query->bindValue(':mdp',$hash,PDO::PARAM_STR);
  $query->execute();
  die('good');

}
}

debug($error);

?>

<?php include('inc/header.php'); ?>

<!-- formulaire d'inscription -->
<div class="wrap">
<form action="" method="post">
  <div class="container">
    <h2>Inscription</h2>

    <label for="login">Identifiant</label>
    <span class="error"><?php if(!empty($error['login'])) {echo $error['login']; }  ?></span>
    <input type="text" placeholder="Entrer votre identifiant" name="login" value="<?php if(!empty($_POST['login'])) { echo $_POST['login']; } ?>" >

    <label for="email">Email</label>
    <span class="error"><?php if(!empty($error['email'])) {echo $error['email']; }  ?></span>
    <input type="text" placeholder="exemple@gmail.com" name="email" value="<?php if(!empty($_POST['email'])) { echo $_POST['email']; } ?>">

    <label for="mdp">Mot de passe</label>
    <input type="password" placeholder="Entrer votre mot de passe" name="mdp" value="" >

    <label for="mdp2">Confirmer le mot de passe</label>
    <input type="password" placeholder="Confirmer votre mot de passe" name="mdp2" value="" >
    <p>J'ai lu et j'accepte les <a href="#">Terms & Conditions</a>.</p>

    <div class="container">
     <input type="submit" name="submitted" class="signup" value="S'inscrire"></input>

   </div>
  </div>
</form>
     <a href="connexion.php"><input type="submit" name="submit" class="signup" value="Se connecter"></input></a>
</div>

<?php include('inc/footer.php'); ?>
