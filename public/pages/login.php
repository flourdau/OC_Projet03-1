<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  $env = NULL;
  $env['conf']['session'] = new Session();
  if ($env['conf']['session']->read('username'))
    Lib::redirect("./list.php");
  $classAnimate = NULL;
  $tmpPost = [
              'USERNAME' => "Nom d' utilisateur",
              'PASSWORD' => "Mot de passe"
            ];
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST))  {
    $tabErrors = [];
    $pass = NULL;
    if (!file_exists('../../app/db.json'))
      Errors::Error(0, "db file");
    $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
    $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
    if ($usr = Post::checkUsername($tabErrors, $env['conf']['pdo'], $_POST['USERNAME']))  {
      if (Lib::checkCrypt($_POST['PASSWORD'], $usr->password)) {
        $env['conf']['session']->write('nom', $usr->nom);
        $env['conf']['session']->write('prenom', $usr->prenom);
        $env['conf']['session']->write('username', $usr->username);
        Lib::redirect("../index.php");
      }
    }
    $classAnimate = "class=\"error\"";
  }
  include('../../app/inc/head.php');
  include('../../app/inc/header.php');
?>
    <main id='mylog'>
      <?php if (isset($classAnimate)): ?>
        <div>
            <p class="myRED">Error!..</p>
        </div>
      <?php endif;?>
      <form <?php echo $classAnimate;?> method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <i class="material-icons">account_box</i>
        <input <?php if (isset($classAnimate)):?> class="errorsChamps" <?php endif; ?>name="USERNAME" type="text" value="<?php if (!empty($_POST['USERNAME'])) echo ($_POST['USERNAME']);?>" placeholder="<?php echo $tmpPost['USERNAME'];?>" required autofocus>
        <input name="PASSWORD" type="password" value="<?php if (!empty($_POST['PASSWORD'])) echo ($_POST['PASSWORD']);?>" placeholder="<?php echo $tmpPost['PASSWORD'];?>" required>
        <a href="forget.php">Mot de passe oublié...</a>
        <div>
          <button type="submit">Connection</button>
          <!-- <a class="creatButton" href="create.php">S'inscrire...</a> -->
        </div>
      </form>
    </main>
<?php include('../../app/inc/footer.php');?>
