<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  $env = NULL;
  $env['conf']['session'] = new Session();
  $tmpPost = ['USERNAME' => "Username"];
  $classAnimate = NULL;
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
    if (!file_exists('../../app/db.json'))
      Errors::Error(0, "db file");
    $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
    $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
    $tabErrors = [];
    $question = NULL;
    if ($ret = Post::forgetUsername($tabErrors, $env['conf']['pdo'], $_POST['USERNAME']))
      Lib::redirect("secret.php?question=" . urlencode($ret->question) . "&username=" . urlencode($_POST['USERNAME']) . "&id_user=" . urlencode($ret->id_user));
    $classAnimate = "class=\"error\"";
  }
  include('../../app/inc/head.php');
  include('../../app/inc/header.php');
?>
    <main>
      <?php if (isset($classAnimate)): ?>
        <div>
            <p class="myRED">Error!..</p>
        </div>
      <?php endif;?>
      <form <?=$classAnimate;?> method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <a href=".." class="close">&times;</a>
        <i class="material-icons">account_box</i>
        <h3>Récupération</h3>
        <input name="USERNAME" type="text" value="<?php if (!empty($_POST['USERNAME'])) echo ($_POST['USERNAME']);?>" placeholder="<?php echo $tmpPost['USERNAME'];?>"  required autofocus>
        <div><button type="submit">Récupérer...</button></div>
      </form>
    </main>
<?php include('../../app/inc/footer.php');?>
