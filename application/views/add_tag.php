
<body>

  <? $this->load->view('_repeat/nav') ?>


  <div id="jumbotron" class="jumbotron">

    <!-- End Nav -->

    <div id="block-idea" class="container">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <div class="container">

        <!-- Logo -->
        <a href="#" data-transition-href="<?= base_url() ?>">
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
        </a>

        <!-- Punchline -->
        <? if (set_value('edit_id')): ?>
          <h1 id="punchline" class="punchline"><?= $tag_edit_idea ?></h1>
        <? else: ?>
          <h1 id="punchline" class="punchline"><?= $tag_make_your_idea_real ?></h1>
        <? endif; ?>

        <div class="pspacer"></div>

        <div class="form-basic">

          <form id="form_add" name="form_add" method="POST" target="_self" action="<?=base_url('tag/exec')?>">

            <? if (set_value('edit_id')): ?>
                <input type="hidden" name="edit_id" id="edit_id" value="<?=set_value('edit_id')?>">
            <? endif; ?>

            <!-- Field Request -->
            <div class="row">

              <div class="col-lg-1">
                <label for="string"><i class="icon-pencil icon-flip-horizontal idea-form-icon <?= error_css('string', 'idea-form-icon-error') ?>"></i></label>

              </div>

              <div class="col-lg-11">
                <p class="help pull-left">
                  <?= form_error('string', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>
                <input id="string" type="text" autocomplete="off" spellcheck="false" class="form-control input-lg <?= error_css('string') ?>" name="string" value="<?= set_value('string') ?>">
                <p class="help pull-left">
                  <i class="icon-question-sign"></i> La requête qui sera tapée sur le moteur de recherche (Ex. fb)<br/>
                </p>
                <br/>
             

              </div>

            </div>
            <!-- /field Request -->

            <div class="pspacer"></div>

            <!-- Field Link -->
            <div class="row">

              <div class="col-lg-1">
                <label for="url"><i class="icon-link icon-flip-horizontal idea-form-icon2 <?= error_css('string', 'idea-form-icon2-error') ?>"></i></label>

              </div>

              <div class="col-lg-11">

                <p class="help pull-left">
                  <?= form_error('url', SUFFIX_ERROR, PREFIX_ERROR) ?>
                </p>

                <textarea id="url" autocomplete="off" spellcheck="false"  class="form-control input-lg <?= error_css('url') ?>" name="url"><?= set_value('url') ?></textarea>

                <p class="help pull-left">
                  <i class="icon-question-sign"></i> Le lien vers lequel la requête devra rediriger (Ex. www.facebook.com)<br/>
                </p>

              </div>

            </div>
            <!-- /field Request -->

            <div class="pspacer"></div>

            <input type="hidden" name="method" id="method" value="GET">

            <hr>
            
            <button type="submit" class="btn btn-outline btn-large btn-orange"><i class="icon-rocket"></i> 

              <? if (set_value('edit_id')): ?>
                Valider les modifications
              <? else: ?>
                Valider ma création

              <? endif; ?>

            </button>

          </form>

          <div class="pspacer"></div>

        </div>

      </div>
      <!-- /container -->

    </div>
    <!-- /block-idea -->


  </div> 
  <!-- /jumbotron -->









