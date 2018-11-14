<?php include('inc/pdo.php'); ?>
<?php include('inc/fonction.php'); ?>
<?php
if(isLogged()){
  $id = $_SESSION['user']['id'];

  //requette pour récupérer les données du carnet
  $sql = "SELECT * FROM v2_vaccins
          LEFT JOIN v2_carnets ON v2_carnets.id_vaccins = v2_vaccins.id
          WHERE v2_carnets.id_user = :id ORDER BY datevaccin ASC";
  $query = $pdo -> prepare($sql);
  $query->bindValue(':id',$id,PDO::PARAM_INT);
  $query -> execute();
  $carnets = $query -> fetchAll();

  if (!empty($_POST['submitted'])) {
    header('Location: carnet_addvaccin.php');
  }

}else{
  header('Location: 404.php');
}

?>

<?php include('inc/header.php'); ?>
<!--  affichage du carnet-->
<div class="wrap">
  <form action="" method="post">
    <div class="container">
      <input type="submit" name="submitted" value="Ajouter un vaccin">
      <table class="table">
        <tr>
          <th>Nom du vaccin</th>
          <th>Date du vaccin</th>
          <th>Rappel du vaccin</th>
          <th>N°de lot</th>
          <th>Effectué ?</th>
        </tr>

          <?php foreach ($carnets as $carnet){ ?>
        <tr>
            <td><?=  $carnet['nomvaccin']; ?></td>
            <td><?=  $carnet['datevaccin'] ?></td>
            <td><?=  $carnet['rappelvaccin'] ?></td>
            <td><?=  $carnet['num_lot'] ?></td>
            <?php if ($carnet['etat'] == 'fait'){?>
              <td><p class="vaccinfait">X</p></td>
            <?php }
            else { ?>
              <td></td>
            <?php } ?>
            <td><a href="carnet_modifvaccin.php?id=<?= $carnet['id']; ?>">Modifier</a></td>
            <td><a href="carnet_supprvaccin.php?id=<?= $carnet['id']; ?>">Supprimer</a></td>

       </tr>
       <?php } ?>
     </table>

    </div>
  </form>
</div>
 <a href="profil.php">Mon profil</a>
<?php include('inc/footer.php'); ?>
