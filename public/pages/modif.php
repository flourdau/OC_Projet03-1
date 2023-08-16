<?php
  namespace Gen;;
  require_once	'../../app/class/Autoload.php';
  Autoload::register();

  if (!file_exists('../../app/db.json'))
    Errors::setupError(0, "db file");
  $db = NULL;
  $db = json_decode(file_get_contents("../../app/db.json", "r"), true);
  $env = NULL;
  $env['conf']['pdo'] = new Database($db['USER'], $db['PASS'], $db['DSN']);
  $env['conf']['session'] = Session::getInstance();
  if (!$env['conf']['session']->read('username'))
    Lib::redirect("..");
  if ($env['conf']['session']->read('errorEmpty')) {
    $tabErrors = Errors::errorTab(0, "firstLog");
    $env['conf']['session']->delete('errorEmpty');
  }

// Debug::print_debug_die($env['conf']['session']->read('errorEmpty'));
  $classAnimate = NULL;

  if ($ret = $env['conf']['pdo']->query("SELECT `id_user`, `nom`, `prenom`, `username`, `question`, `reponse` FROM `users` WHERE `username` = ?", [$env['conf']['session']->read('username')])->fetch()) {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {

      $flagUnik = NULL;
      if ($ret->username !== $_POST['USERNAME'])
        $flagUnik = TRUE;

      //  CLEAN & VERRIF DATA
      $tabErrors = [];
      if ($_POST['MDP'] === $db['DEFAULTPASS'])  {
        $tabErrors += Errors::errorTab(0, "mdpFirst");
        $env['conf']['session']->write('errorEmpty', "empty");
      }
      $_POST['NOM'] = Post::verifCleanNom($tabErrors, $_POST['NOM']);
      $_POST['PRENOM'] = Post::verifCleanPrenom($tabErrors, $_POST['PRENOM']);
      $_POST['USERNAME'] = Post::verifCleanUsername($tabErrors, $env['conf']['pdo'], $_POST['USERNAME'], $flagUnik);
      $_POST['MDP'] = Post::verifCryptPassword($tabErrors, $_POST['MDP'], $_POST['MDP2']);
      $_POST['QUESTION'] = Post::verifCleanQuestion($tabErrors, $_POST['QUESTION']);
      $_POST['REPONSE'] = Post::verifCleanReponse($tabErrors, $_POST['REPONSE']);

      //  UPDATE DB
      if (empty($tabErrors)) {
        $ret2 = $env['conf']['pdo']->query(
                                            "UPDATE `users` SET
                                            `nom` = '$_POST[NOM]',
                                            `prenom` = '$_POST[PRENOM]',
                                            `username` = '$_POST[USERNAME]',
                                            `password` = '$_POST[MDP]',
                                            `question` = '$_POST[QUESTION]',
                                            `reponse` = '$_POST[REPONSE]' WHERE `id_user` = $ret->id_user"
                                          );
        $ret = $env['conf']['pdo']->query("SELECT `id_user`, `nom`, `prenom`, `username`, `question`, `reponse` FROM `users` WHERE `username` = ?", [$_POST['USERNAME']])->fetch();
      }
      else {
        $classAnimate = "class=\"error\"";
      }
    }
    $env['conf']['session']->write('nom', $ret->nom);
    $env['conf']['session']->write('prenom', $ret->prenom);
    $env['conf']['session']->write('username', $ret->username);
    include('../../app/inc/head.php');
    include('../../app/inc/header.php');
  }
?>
    <main>
      <?php if (isset($tabErrors['firstLog'])): ?>
        <div>
            <p class="myRED">Modifier vos information ainsi que votre mot de passe! Merci</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['mdpFirst'])): ?>
        <div>
            <p class="myRED">Changer votre mot de passe! Merci</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['usernameunik'])): ?>
        <div>
            <p class="myRED">Username déjà utilisé!..</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['pass'])): ?>
        <div>
            <p class="myRED">Mot de passe érronée!..</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['passConf'])): ?>
        <div>
            <p class="myRED">Confirmation du mot de passe érronée!..</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['nom'])): ?>
        <div>
            <p class="myRED">Nom incorrect!</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['prenom'])): ?>
        <div>
            <p class="myRED">Prénom incorrect!</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['question'])): ?>
        <div>
            <p class="myRED">Question incorrect!</p>
        </div>
      <?php endif;?>
      <?php if (isset($tabErrors['reponse'])): ?>
        <div>
            <p class="myRED">Réponse incorrect!</p>
        </div>
      <?php endif;?>
      <form <?php echo $classAnimate;?> method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <a href=".." class="close">&times;</a>
        <input title="Complètez le champs avec 1 caractères minimum & 64 maximum, minuscule ou majuscule uniquement" pattern="[A-Za-z]{1,64}" <?php if (isset($tabErrors['nom'])): ?> class="errorsChamps" <?php endif; ?> name="NOM" type="text" value="<?php if (!empty($ret->nom)) echo ($ret->nom);?>" placeholder="<?php echo $ret->nom;?>" required autofocus>
        <input title="Complètez le champs avec 2 caractères minimum & 64 maximum, minuscule ou majuscule uniquement" pattern="[A-Za-z]{1,64}" <?php if (isset($tabErrors['prenom'])): ?> class="errorsChamps" <?php endif; ?>name="PRENOM" type="text" value="<?php if (!empty($ret->prenom)) echo ($ret->prenom);?>" placeholder="<?php echo $ret->prenom;?>" required>
        <input title="Complètez le champs avec 8 caractères minimum & 16 maximum, avec 1 minuscule ou 1 majuscule et 1 chiffre " pattern="[A-Za-z0-9]{8,16}" <?php if (isset($tabErrors['username'])): ?> class="errorsChamps" <?php endif; ?>name="USERNAME" type="text" value="<?php if (!empty($ret->username)) echo ($ret->username);?>" placeholder="<?php echo $ret->username;?>" required>
        <input title="Complètez le champs avec 8 caractères minimum & 16 maximum, avec une majuscule minimum, ume minuscule minimum et 1 chiffre minimum" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" <?php if (isset($tabErrors['pass'])): ?> class="errorsChamps" <?php endif; ?>name="MDP" type="password" placeholder="<?php echo "Password";?>" required>
        <input title="Complètez le champs avec 8 caractères minimum & 16 maximum, avec une majuscule minimum, ume minuscule minimum et 1 chiffre minimum" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" <?php if (isset($tabErrors['confpass'])): ?> class="errorsChamps" <?php endif; ?>name="MDP2" type="password" placeholder="<?php echo "Confirmez votre Password";?>" required>
        <input title="Complètez le champs avec 4 caractères minimum & 128 maximum" pattern="{4,128}" <?php if (isset($tabErrors['question'])): ?> class="errorsChamps" <?php endif; ?>name="QUESTION" type="text" value="<?php if (!empty($ret->question)) echo ($ret->question);?>" placeholder="<?php echo $ret->question;?>" required>
        <input title="Complètez le champs avec 4 caractères minimum & 128 maximum" pattern="{4,128}" <?php if (isset($tabErrors['reponse'])): ?> class="errorsChamps" <?php endif; ?>name="REPONSE" type="text" value="<?php if (!empty($ret->reponse)) echo ($ret->reponse);?>" placeholder="<?php echo $ret->reponse;?>" required>
        <div>
          <button type="submit">Modifier</button>
        </div>
      </form>
    </main>
<?php include('../../app/inc/footer.php');?>
