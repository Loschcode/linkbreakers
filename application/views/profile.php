<body>

  <? $this->load->view('_repeat/nav') ?>

  <div id="jumbotron" class="jumbotron">

    <!-- End Nav -->

    <div id="block-profile" class="container">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <div class="container block-profile">

        <!-- Logo -->
        <a href="#" data-transition-href="<?= base_url() ?>">
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
        </a>

        <div class="pspacer"></div>

          <?php if (isset($profile_msg)): ?>
          <?=$profile_msg?>
          <?php endif; ?>

        <div class="well col-6">
          <h1>Mon espace</h1>
          <hr>

          <? if (! $userspace): ?>
            <a href="<?=base_url('profile/update/space/activate')?>" class="btn btn-large btn-default"><i class="icon-home"></i> Activer mon espace personnel</a>
          <? else: ?>
            <a href="<?=base_url('profile/update/space/deactivate')?>" class="btn btn-large btn-danger"><i class="icon-home"></i> Désactiver mon espace personnel</a>
          <? endif; ?>
          <div class="pspacer-medium"></div>

          <div class="alert alert-default">L'activation de votre espace permet d'avoir son propre moteur de recherche utilisant vos propres créations</div>
        </div>

        <div class="col-1"></div>

        <div class="well col-5">
          <h1>Avatar</h1>
          <hr>

          <div class="wrapper-avatar push-center">
          <img width="130" src="<?= $profile_picture ?>">
          </div>

          <div class="pspacer-medium"></div>

          <div class="alert alert-default">Votre avatar dépend de votre email ; utilisez <a href="http://www.gravatar.com" target="_blank">Gravatar</a></div>
        </div>


        <a name="e"></a>
        <div id="email" class="well col-5">
          <h1>Email 
          <? if ($this->pikachu->show('useremailvalid')): ?>
          <span class="tblue"><i class="icon-ok-sign"></i> Vérifié</span>
          <? else: ?>
          <span class="tred"><i class="icon-remove-sign"></i> Non vérifié</span>
          <? endif; ?></h1>
          <hr>

          <form method="post" action="<?= base_url('profile/update/email') ?>#e" class="form-basic">


            <label class="small">Adresse email</label> <br/>

            <?= form_error('email', SUFFIX_ERROR, PREFIX_ERROR) ?>

                <input type="text" name="email" value="<? if (set_value('email')) echo set_value('email'); else echo $this->pikachu->show('useremail') ?>" class="form-control focus-default <?= error_css('email') ?>" placeholder="contact@example.com">

                <br />

                <button class="btn btn-warning" type="submit"><i class="icon-pencil"></i> Mettre à jour</button>

          </form>

          <div class="clear"></div>

        </div>

        <div class="col-1"></div>


        <a name="as"></a>
        <div class="well col-6">
          <h1>Sécurité du compte</h1>
          <hr>

          <form method="post" name="password" action="<?= base_url('profile/update/password') ?>#as" class="form-basic">
          Ancien mot de passe <br/>
          <?= form_error('old_password', SUFFIX_ERROR, PREFIX_ERROR) ?>

          <input type="password" name="old_password" class="form-control focus-default <?= error_css('old_password') ?>"/><br/>

          Nouveau mot de passe <br/>
          <?= form_error('new_password', SUFFIX_ERROR, PREFIX_ERROR) ?>

          <input type="password" name="new_password" class="form-control focus-default <?= error_css('new_password') ?>"/><br/>

          Confirmation du nouveau mot de passe <br/>
          <?= form_error('new_password_repeat', SUFFIX_ERROR, PREFIX_ERROR) ?>

          <input type="password" name="new_password_repeat" class="form-control focus-default <?= error_css('new_password_repeat') ?>"/><br/>
          <button class="btn btn-danger"><i class="icon-pencil"></i> Modifier mot de passe</button>
        </form>

        </div>

        <a name="dr"></a>

        <div class="well col-12">
          <h1>Redirection par défaut</h1>
          <hr>

          <form method="post" action="<?= base_url('profile/update/prefs_default_result_url') ?>#dr" class="form-basic">

            <?= form_error('prefs_default_result_url_edit', SUFFIX_ERROR, PREFIX_ERROR) ?>

            <textarea type="text" class="form-control input-lg textarea-transition focus-default" id="prefs_default_result_url_edit <?= error_css('prefs_default_result_url') ?>" name="prefs_default_result_url_edit" autocomplete="off" maxlength="2000" placeholder="<?= LINKBREAKERS_NO_RESULT_REDIRECTION ?>"><? 
            
            if (set_value('prefs_default_result_url_edit')) echo set_value('prefs_default_result_url_edit');
            else echo json_decode($this->pikachu->get_cookie('prefs_default_result_url_edit'));

            ?></textarea>

            <div class="pspacer-medium"></div>

            <button class="btn btn-primary" type="submit"><i class="icon-pencil"></i> Changer de redirection</button>

          <div class="pspacer-medium"></div>

          <div class="alert alert-default">Si Linkbreakers ne trouve aucun résultat, vous serez redirigé vers un moteur par défaut que vous pouvez personnaliser. LBL autorisé</div>
        
        </form>

        </div>

      </div>
      <!-- /container -->

    </div>
    <!-- /block-profile -->


  </div> 
  <!-- /jumbotron -->









