
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
         <h3>Pas encore membre ? </h3><hr>Il vous suffit de remplir les deux champs ci-dessous, vous serez automatiquement inscrit et connecté.

        </div>

        <div class="pspacer"></div>

        <div class="form-basic">

          <form method="POST" target="_self" action="<?=base_url('log/exec')?>">

            <!-- Field Request -->
            <div class="row">

              <div class="col-lg-3">
                <label for="username"><i class="icon-user icon-flip-horizontal sign-form-icon <?= error_css('username', 'sign-form-icon-error') ?>"></i></label>

              </div>

              <div class="col-lg-8">
                <p class="help pull-left">
                  <?= form_error('username', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>
                <input id="username" type="text" autocomplete="off" spellcheck="false" class="form-control input-lg <?= error_css('username') ?>" name="username" value="<?= set_value('username') ?>">
                <p class="help pull-left">
                  <i class="icon-question-sign"></i> Votre nom d'utilisateur (Ex. Luke Skywalker) ou email pour les inscrits<br/>
                </p>
                <br/>
             

              </div>

            </div>
            <!-- /field Request -->

            <div class="pspacer"></div>

            <!-- Field Link -->
            <div class="row">

              <div class="col-lg-3">
                <label for="password"><i class="icon-lock icon-flip-horizontal sign-form-icon2 <?= error_css('password', 'sign-form-icon2-error') ?>"></i></label>

              </div>

              <div class="col-lg-8">

                <p class="help pull-left">
                  <?= form_error('password', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>

                <input type="password" id="password" autocomplete="off" spellcheck="false"  class="form-control input-lg <?= error_css('password') ?>" name="password" />

                <p class="help pull-left">
                  <i class="icon-question-sign"></i> Votre mot de passe<br/>
                </p>

              </div>

            </div>
            <!-- /field Request -->

            <div class="pspacer"></div>

            <input type="hidden" name="method" id="method" value="GET">

            <hr>
            
            <button type="submit" class="btn btn-outline btn-large btn-orange"><i class="icon-rocket"></i> Let's Go</button>

          </form>

          <div class="pspacer"></div>


        </div>

      </div>
      <!-- /container -->

    </div>
    <!-- /block-idea -->


  </div> 
  <!-- /jumbotron -->









