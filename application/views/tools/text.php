
<body>

  <? $this->load->view('_repeat/nav') ?>


  <div id="jumbotron" class="jumbotron">

    <!-- End Nav -->

    <div id="block-tools-text" class="container">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <div class="container">

        <!-- Logo -->
        <a href="#" data-transition-href="<?= base_url() ?>">
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
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









