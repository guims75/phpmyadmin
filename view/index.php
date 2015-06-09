<?php


include './includes/head.php';
?>
<div id="principal">
  <?php $view->showError(); ?>
  <form method="<?php echo $view->getMethod();?>" action="index.php" class="connexion">
  <fieldset>
  <legend>Login</legend>
  <?php TemplateBase::displayField('host', 'text', $host, 'H&ocirc;te : ') ?>
  <?php TemplateBase::displayField('user', 'text', $user, 'Utilisateur : ') ?>
  <?php TemplateBase::displayField('passwd', 'password', null, 'Mot de passe : '); ?>
  </fieldset>
  <?php $view->showSubmit(); ?>
  </form>
  </div>
  <?php
  include './includes/footer.php';
?>
