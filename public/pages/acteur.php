<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  if (!file_exists('../../app/db.json'))
    Errors::Error(0, "db file");
  $env = NULL;
  $env['conf']['session'] = Session::getInstance();
  if (empty($env['conf']['session']->read('username')) || empty($_GET) || !is_numeric($_GET['id_acteur']) || $env['conf']['session']->read('errorEmpty'))
    Lib::redirect("..");
  $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
  $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
  $usr = $env['conf']['pdo']->query("SELECT `id_user`, `question`, `reponse`, `nom`, `prenom` FROM `users` WHERE `username` = ?", [$env['conf']['session']->read('username')])->fetch();
  if (empty($usr->nom) || empty($usr->prenom) || empty($usr->question) || empty($usr->reponse)) {
    $env['conf']['session']->write('errorEmpty', "empty");
    Lib::redirect("modif.php");
  }
  $partenaire = $env['conf']['pdo']->query("SELECT `logo`, `acteur`, `description`, `id_acteur` FROM `acteur` WHERE `id_acteur` = ?", [$_GET['id_acteur']])->fetch();
  if (empty($partenaire))
    Lib::redirect("..");
  $usr = $env['conf']['pdo']->query("SELECT `id_user` FROM `users` WHERE `username` = ?", [$_SESSION['username']])->fetch();
  $tmp_id_acteur = $_GET['id_acteur'];
  $tmp_id_user = $usr->id_user;
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST))  {
    $tabErrors = [];
    $_POST['COMMENT'] = Post::verifCleanQuestion($tabErrors, $_POST['COMMENT']);
    if (!$tabErrors) {
      $myCom = $env['conf']['pdo']->query("SELECT `post`, `id_user`, `id_post`, `id_acteur`, `date_add` FROM `post` WHERE `id_acteur` = ? AND `id_user` = ?", [$tmp_id_acteur, $tmp_id_user])->fetch();
      $tmpCom = $_POST['COMMENT'];
      if (isset($myCom->id_post)) {
        $env['conf']['session']->write('commentaire', [
                                                        'id_user' => $myCom->id_user,
                                                        'id_acteur' => $myCom->id_acteur,
                                                        'id_post' => $myCom->id_post,
                                                        'com' => $myCom->post,
                                                        'myDate' => $myCom->date_add,
                                                        'newCom' => $tmpCom
                                                      ]);
        Lib::redirect("confirmCom.php");
      }
      else
        $env['conf']['pdo']->query("INSERT INTO `post` SET `post` = ?, `id_user` = ?, `id_acteur` = ?, `date_add` = NOW()", [$tmpCom, $tmp_id_user, $tmp_id_acteur]);
    }
  }
  if (isset($_GET['vote']) && (($_GET['vote'] == 1) || ($_GET['vote'] == 0))) {
    $tmpVote = $_GET['vote'];
    if (is_numeric($tmpVote)) {
      $tmp_id_acteur = $_GET['id_acteur'];
      $tmp_id_usr = $usr->id_user;
      $vote = $env['conf']['pdo']->query("SELECT `vote` FROM `vote` WHERE `id_user` = ? AND `id_acteur` = ?", [$tmp_id_usr, $_GET['id_acteur']])->fetch();
      if (isset($vote->vote) && $vote->vote == $tmpVote)
        $env['conf']['pdo']->query("DELETE FROM `vote` WHERE `id_user` = $tmp_id_usr AND `id_acteur` = $tmp_id_acteur" );
      else if (isset($vote->vote) && $vote->vote != $tmpVote)
        $env['conf']['pdo']->query("UPDATE `vote` SET `vote` = $tmpVote WHERE `id_user` = $tmp_id_usr AND `id_acteur` = $tmp_id_acteur");
      else {
        $env['conf']['pdo']->query("INSERT INTO `vote` SET `vote` = $tmpVote , `id_user` = $tmp_id_usr , `id_acteur` = $tmp_id_acteur");
      }
    }
  }
  $votePositif = $env['conf']['pdo']->query("SELECT COUNT(`vote`) AS `nb_voteP` FROM `vote` WHERE `vote` = 1 AND `id_acteur` = ?",[$_GET['id_acteur']])->fetchAll();
  $voteNegatif = $env['conf']['pdo']->query("SELECT COUNT(`vote`) AS `nb_voteN` FROM `vote` WHERE `vote` = 0 AND `id_acteur` = ?",[$_GET['id_acteur']])->fetchAll();
  $com = $env['conf']['pdo']->query("SELECT `post`, `id_user`, `id_post`, `id_acteur`, `date_add` FROM `post` WHERE `id_acteur` = ?",[$_GET['id_acteur']])->fetchAll();
  $cntCom = $env['conf']['pdo']->query("SELECT COUNT(`id_post`) AS nb_post FROM `post` WHERE `id_acteur` = ?",[$_GET['id_acteur']])->fetchAll();
  include('../../app/inc/head.php');
  include('../../app/inc/header.php');

  $sComm = NULL;
  if ($cntCom[0]->nb_post > 1)
    $sComm = "s";
?>
    <main id="myblock">
      <?php if (isset($tabErrors['question'])): ?>
        <div>
          <p class="myRED">Erreur le commentaire doit contenir entre 4 et 128 caractères!..</p>
        </div>
      <?php endif;?>
      <section id="myinfo">
        <img alt="Logo de l'entreprise" src="../img/<?= $partenaire->logo;?>"/>
        <div>
          <h3><?= $partenaire->acteur;?></h3>
          <p><?= $partenaire->description;?></p>
        </div>
        <hr>
      </section>
      <section id="myinfo2">
        <div class="myblockLike">
          <a class="myLike mybtn" href="./acteur.php?id_acteur=<?=$_GET['id_acteur']?>&vote=1" title="Like"><?= $votePositif[0]->nb_voteP;?> <i class="material-icons">thumb_up</i></a>
          <a class="myLike mybtn" href="./acteur.php?id_acteur=<?=$_GET['id_acteur']?>&vote=0" title="DisLike"><?= $voteNegatif[0]->nb_voteN;?> <i class="material-icons">thumb_down</i></a>
        </div>
        <h3><?= $cntCom[0]->nb_post;?> Commentaire<?= $sComm;?>: </h3>
        <div id="mycom">
<?php foreach ($com as $value) :?>
          <div>
            <hr>
<?php $usrCom = $env['conf']['pdo']->query("SELECT `prenom` FROM `users` WHERE `id_user` = ?", [$value->id_user])->fetch();?>
            <strong><?=$usrCom->prenom;?></strong>
            <p><?=$value->date_add;?></p>
            <p><?=stripslashes($value->post);?></p>
          </div>
<?php endforeach;?>
        </div>
        <form method="post" action="<?=$_SERVER['PHP_SELF'];?>?id_acteur=<?=$_GET['id_acteur'];?>">
          <textarea minlength="4" maxlength="128" title="Complètez le champs avec 8 caractères minimum & 64 maximum" name="COMMENT"></textarea>
          <button type="submit">Envoyer</button>
        </form>
      </section>
    </main>
<?php include('../../app/inc/footer.php');?>
