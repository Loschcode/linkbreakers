
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
         <h3>Un oublie de mot de passe ?</h3><hr>Si vous avez pensé à ajouter un e-mail à votre compte, le mot de passe sera réinitialisé grâce à celui-ci, sinon il vous faudra contacter le service technique de Linkbreakers.

        </div>

        <div class="pspacer"></div>

        <div class="form-basic">

          <form method="POST" target="_self" action="<?=base_url('log/forgot_exec')?>">

            <!-- Field Request -->
            <div class="row">

              <div class="col-lg-3">
                <label for="useremail"><i class="icon-envelope sign-form-icon <?= error_css('useremail', 'sign-form-icon-error') ?>"></i></label>

              </div>

              <div class="col-lg-8">
                <p class="help pull-left">
                  <?= form_error('useremail', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>
                <input id="useremail" type="text" autocomplete="off" spellcheck="false" class="form-control input-lg <?= error_css('useremail') ?>" name="useremail" value="<?= set_value('useremail') ?>">
                <p class="help pull-left">
                  <i class="icon-question-sign"></i> Votre e-mail (Ex. steve.jobs@apple.com)<br/>
                </p>
                <br/>
             

              </div>

            </div>
            <!-- /field Request -->

            <hr>
            
            <button type="submit" class="btn btn-outline btn-large btn-orange"><i class="icon-rocket"></i> Valider la réinitialisation</button>

          </form>

          <div class="pspacer"></div>


        </div>

      </div>
      <!-- /container -->

    </div>
    <!-- /block-idea -->


  </div> 
  <!-- /jumbotron -->





