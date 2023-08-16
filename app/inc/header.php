<!--	BODY	-->
  <body class="text-center">
    <!-- HEADER -->
    <header>
      <nav>
        <div class="logo">
          <a href=".." title="Accueil..."><img class="myImg" alt="Logo GBAF" src="../design/img/logo.png"/></a>
        </div>
        <div>
      <?php if ($env['conf']['session']->read('username')) : ?>
          <div>
            <a href="logout.php" title="Déconnecter...">
              <i class="material-icons">power_settings_new</i>
            </a>
          </div>
          <div>
            <a href="modif.php"><?= $env['conf']['session']->read('prenom'); ?> <?= $env['conf']['session']->read('nom'); ?></a>
          </div>
      <?php endif; ?>
        </div>
      </nav>
    </header>
