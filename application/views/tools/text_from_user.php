
<body>

  <? $this->load->view('_repeat/nav') ?>


  <div id="jumbotron" class="jumbotron">

    <!-- End Nav -->

    <div id="block-tools-text" class="container">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <div class="container">

        <!-- Logo -->
        <a href="#" data-transition-href="<?=base_url($space_user)?>">
         
          <? if ($space_avatar): ?>
          <img id="logo" class="roundpicture logoresize" src="<?=$space_avatar?>" />
          <? else: ?>
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
        <? endif; ?>

        </a>

        <div class="pspacer"></div>

      </div>

        <div class="tool-text">
          <?=$content?>
        </div>


    </div>
    <!-- /block-tools-text -->


  </div> 
  <!-- /jumbotron -->









