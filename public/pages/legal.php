<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  $env = NULL;
  $env['conf']['session'] = new Session();

  include('../../app/inc/head.php');
  include('../../app/inc/header.php');
?>
    <main >
      <h1>POLITIQUE DE CONFIDENTIALITÉ</h1>
      <h2>Qui sommes-nous ?</h2>
      <p>L’adresse de notre site Web est : .<a href="..">GBAF.fr</a></p>
      <h2>Utilisation des données personnelles collectées</h2>
      <h3>Commentaires</h3>
      <p>Quand vous laissez un commentaire sur notre site web, les données inscrites dans le formulaire de commentaire, mais aussi votre adresse IP et l’agent utilisateur de votre navigateur sont collectés pour nous aider à la détection des commentaires indésirables. Une chaîne anonymisée créée à partir de votre adresse de messagerie (également appelée hash) peut être envoyée au service Gravatar pour vérifier si vous utilisez ce dernier. Les clauses de confidentialité du service Gravatar sont disponibles ici : https://automattic.com/privacy/. Après validation de votre commentaire, votre photo de profil sera visible publiquement à coté de votre commentaire.<p>
      <h3>Médias</h3>
      <p>Si vous êtes un utilisateur ou une utilisatrice enregistré·e et que vous téléversez des images sur le site web, nous vous conseillons d’éviter de téléverser des images contenant des données EXIF de coordonnées GPS. Les visiteurs de votre site web peuvent télécharger et extraire des données de localisation depuis ces images.</p>
      <h3>Durées de stockage de vos données</h3>
      <p>Si vous laissez un commentaire, le commentaire et ses métadonnées sont conservés indéfiniment. Cela permet de reconnaître et approuver automatiquement les commentaires suivants au lieu de les laisser dans la file de modération. Pour les utilisateurs et utilisatrices qui s’enregistrent sur notre site (si cela est possible), nous stockons également les données personnelles indiquées dans leur profil. Tous les utilisateurs et utilisatrices peuvent voir, modifier ou supprimer leurs informations personnelles à tout moment (à l’exception de leur nom d’utilisateur·ice). Les gestionnaires du site peuvent aussi voir et modifier ces informations.</p>
    </main>
<?php include('../../app/inc/footer.php');?>
