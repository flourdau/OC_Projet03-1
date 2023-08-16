<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  $env = NULL;
  $env['conf']['session'] = new Session();
  $tmpPost = ['REPONSE' => "Réponse"];
  $classAnimate = NULL;
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
    if (!file_exists('../../app/db.json'))
      Errors::Error(0, "db file");
    $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
    $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
    $tabErrors = [];
    if ($usr = Post::forgetReponse($tabErrors, $env['conf']['pdo'], $_POST['USERNAME'], $_POST['QUESTION'], $_POST['REPONSE'])) {
      $env['conf']['session']->write('nom', $usr->nom);
      $env['conf']['session']->write('prenom', $usr->prenom);
      $env['conf']['session']->write('username', $_POST['USERNAME']);
      Lib::redirect("modif.php");
    }
    $classAnimate = "class=\"error\"";
  }
  else if (empty($_GET))
    Lib::redirect("..");
  include('../../app/inc/head.php');
  include('../../app/inc/header.php');
?>
    <main>
      <?php if (isset($classAnimate)): ?>
        <div>
            <p class="myRED">Error!..</p>
        </div>
      <?php endif;?>
      <form <?php echo $classAnimate;?> method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?question=" . urlencode($_GET['question']) . "&username=" . urlencode($_GET['username']) . "&id_user=" . urlencode($_GET['id_user']);?>">
        <a href=".." class="close">&times;</a>
        <i class="material-icons">account_box</i>
        <h2><?php if (!empty($_GET['question'])) echo $_GET['question'];?>??</h2>
        <input type="hidden" name="ID_USER" value="<?php if (!empty($_GET['id_user'])) echo ($_GET['id_user']);?>">
        <input type="hidden" name="USERNAME" value="<?php if (!empty($_GET['username'])) echo ($_GET['username']);?>">
        <input type="hidden" name="QUESTION" value="<?php if (!empty($_GET['question'])) echo ($_GET['question']);?>">
        <input name="REPONSE" type="text" value="<?php if (!empty($_POST['REPONSE'])) echo ($_POST['REPONSE']);?>" placeholder="<?php echo $tmpPost['REPONSE'];?>" required autofocus>
        <div><button type="submit">Réccupérer</button></div>
      </form>
    </main>
<?php include('../../app/inc/footer.php');?>
