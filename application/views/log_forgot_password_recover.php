
<body>

  <? $this->load->view('_repeat/nav') ?>


  <div id="jumbotron" class="jumbotron">

    <!-- End Nav -->

    <div id="block-sign-in" class="container">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <div class="container">

        <!-- Logo -->
        <a href="#" data-transition-href="<?= base_url() ?>">
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
        </a>

        <!-- Punchline -->
        <h1 id="punchline" class="punchline"><?= $log_sign_in ?></h1>

        <div class="sign-in">
         <h3>Il n'y a plus qu'à attendre !</h3><hr>Vous allez recevoir un e-mail contenant un code secret vous permettant de réinitialiser votre mot de passe. Entrez-le dans le champ ci-dessous dés que possible.

        </div>

        <div class="pspacer"></div>

        <div class="form-basic">

          <form method="POST" target="_self" action="<?=base_url('log/recover_password_exec')?>">

            <!-- Field Request -->
            <div class="row">

              <div class="col-lg-3">
                <label for="recoverkey"><i class="icon-key sign-form-icon <?= error_css('recoverkey', 'sign-form-icon-error') ?>"></i></label>

              </div>

              <div class="col-lg-8">

                <p class="help pull-left">
                  <?= form_error('recoverkey', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>
                <input id="recoverkey" type="text" autocomplete="off" spellcheck="false" class="form-control input-lg <?= error_css('recoverkey') ?>" name="recoverkey" value="<?= set_value('recoverkey') ?>">
                <p class="help pull-left">
                  <i class="icon-question-sign"></i> La clé de réinitialisation (Ex. A6876D6A87)<br/>
                </p>

              </div>

            </div>

            <div class="pspacer"></div>

            <!-- Field Request -->
            <div class="row">

              <div class="col-lg-3">
                <label for="newpassword"><i class="icon-magic sign-form-icon <?= error_css('newpassword', 'sign-form-icon-error') ?>"></i></label>

              </div>

              <div class="col-lg-8">

                <p class="help pull-left">
                  <?= form_error('newpassword', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>

                <input type="password" id="newpassword" autocomplete="off" spellcheck="false"  class="form-control input-lg <?= error_css('newpassword') ?>" name="newpassword" />
                <p class="help pull-left">
                  <i class="icon-question-sign"></i> Votre nouveau mot de passe<br/>
                </p>

                <br/>
              </div>

            </div>
            <!-- /field Request -->

            <hr>
            
            <button type="submit" class="btn btn-outline btn-large btn-orange"><i class="icon-gear"></i> Enregistrer mon nouveau mot de passe</button>

          </form>

          <div class="pspacer"></div>


        </div>

      </div>
      <!-- /container -->

    </div>
    <!-- /block-idea -->


  </div> 
  <!-- /jumbotron -->

