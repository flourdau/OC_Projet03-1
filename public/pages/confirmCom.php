<?php
  namespace Gen;
  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  $env['conf']['session'] = Session::getInstance();
  if (!$env['conf']['session']->read("username"))
    Lib::redirect("pages/login.php");
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
    $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
    $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
    $env['conf']['pdo']->query("UPDATE `post` SET `date_add` = NOW(), `post` = ? WHERE `id_post` = ?", [$_SESSION['commentaire']['newCom'], $_SESSION['commentaire']['id_post']]);
    Lib::redirect("acteur.php?id_acteur=" . $_SESSION['commentaire']['id_acteur']);
  }
  include('../../app/inc/head.php');
  include('../../app/inc/header.php');
?>
<main>
  <p>Voulez-vous remplacer votre ancien commentaire du <?= $_SESSION['commentaire']['myDate']?>?</p>
  <p><?= $_SESSION['commentaire']['com']?></p>
  <p>par</p>
  <p><?= $_SESSION['commentaire']['newCom']?></p>
    <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
      <input type="hidden" name="id_user" value="<?= $_SESSION['commentaire']['id_user']?>">
      <input type="hidden" name="id_post" value="<?= $_SESSION['commentaire']['id_post']?>">
      <input type="hidden" name="id_acteur" value="<?= $_SESSION['commentaire']['id_acteur']?>">
      <input type="hidden" name="newCom" value="<?= $_SESSION['commentaire']['newCom']?>">
      <div>
        <button type="submit">Oui</button>
        <a class="mybtn2" href="acteur.php?id_acteur=<?= $_SESSION['commentaire']['id_acteur']?>">Non</a>
      </div>
    </form>
</main>
<?php include('../../app/inc/footer.php');?>
