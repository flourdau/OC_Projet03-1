<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  if (!file_exists('../../app/db.json'))
    Errors::Error(0, "db file");
  $env = NULL;
  $env['conf']['session'] = Session::getInstance();
  if (empty($env['conf']['session']->read('username')))
    Lib::redirect("..");

  $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
  $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
  $usr = $env['conf']['pdo']->query("SELECT `id_user`, `question`, `password`, `reponse`, `nom`, `prenom` FROM `users` WHERE `username` = ?", [$env['conf']['session']->read('username')])->fetch();
  if (empty($usr->nom) || empty($usr->prenom) || empty($usr->question) || empty($usr->reponse)) {
    $env['conf']['session']->write('errorEmpty', "empty");
    Lib::redirect("modif.php");
  }

  $partenaire = $env['conf']['pdo']->query("SELECT `logo`, `acteur`, `description`, `id_acteur` FROM `acteur`")->fetchAll();
  include('../../app/inc/head.php');
  include('../../app/inc/header.php');
?>
    <main id='myblock'>
      <section>
        <h1>GBAF référence pour vous le meilleur de nos partenaires, consulter, évaluer, commenter...</h1>
      </section>
      <h2>Voici nos partenaires...</h2>
      <section>
<?php foreach ($partenaire as $value) :?>
      <div>
        <img alt="logo de l' entreprise" src="../img/<?= $value->logo;?>"/>
        <div>
          <h3><?= $value->acteur;?></h3>
          <p><?php $tmp = explode(".", $value->description, 42);echo $tmp[0] . "...";unset($tmp);?></p>
        </div>
        <a class="mybtn" href="acteur.php?id_acteur=<?= $value->id_acteur;?>">lire la suite...</a>
      </div>
      <hr>
<?php endforeach;?>
      </section>
    </main>
<?php include('../../app/inc/footer.php');?>
