<?php
  include('inc/pdo.php');
  include('inc/fonction.php');
  include('inc/request.php');
  include('newsletter.php');
?>
<?php
  $listeVaccins = getVaccin() ;
  if(isLogged()) {

      if (!empty($_POST['submitted'])) {
        //sécurité XSS
        $numeroLot = trim(strip_tags($_POST['numlot']));
        $datevaccin = trim(strip_tags($_POST['date']));

        $errors = array();
       if (!empty($datevaccin)) {
        } else {
          $errors['date'] = 'Veulliez remplir ce champ.';
        }

        if (!empty($numeroLot)) {
          if (strlen($numeroLot) > 20) {
            $errors['numlot'] = 'Ce que vous avez saisi est trop long, un n° de lot ne peut avoir plus de 20 caractères.';
          }
        }

        if (count($errors) == 0) {
          addVaccin();
          header('Location: carnet.php');
        }
      }

      include('inc/header.php');
?>

<section id="contenu" class="wrap">
  <form class="wrap table" action="" method="post">

      <label for="nom">Nom du vaccin</label>

    <select  name="nom" class="numlot">
      <?php foreach ($listeVaccins as $listeVaccin) { ?>
        <option value="<?= $listeVaccin['nomvaccin']; ?>"><?= $listeVaccin['nomvaccin']; ?></option>
      <?php } ?>
    </select>
    <div>
       <label for="numlot">Numéro du lot</label>
       <input class="numlot ligne" type="text" name="numlot" value="" placeholder="ex: H25994"><?php if(!empty($errors['numlot'])) { echo '<p class="error">' . $errors['numlot'] . '</p>'; } ?>
    </div>
    <div>
       <label for="date">Date du vaccin</label>
       <input class="numlot" type="date" name="date" value="" placeholder="ex: 2018-11-13"><?php if(!empty($errors['date'])) { echo '<p class="error">' . $errors['date'] . '</p>'; } ?>
    </div>
    <div>
       <label for="rappel">Rappel dans</label>
       <select class= name="rappel">
           <option value="3 mois">3 mois</option>
           <option value="6 mois">6 mois</option>
           <option value="9 mois">9 mois</option>
           <option value="1 an">1 an</option>
           <option value="2 ans">2 ans</option>
           <option value="3 ans">3 ans</option>
           <option value="plusieurs années">plusieurs années</option>
      </select>
   </div>
    <input class="btnConfirm"  type="submit" name="submitted" value="Ajouter">
  </form>
</section>

<?php
  } else {
  header('Location: 403.php');
  }

  include('inc/footer.php');
