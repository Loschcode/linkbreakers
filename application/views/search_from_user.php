

<body>

    <? $this->load->view('_repeat/nav') ?>

  <div id="jumbotron" class="jumbotron">


    <div id="block-home">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <!-- Container -->
      <div class="container">

        <!-- Logo -->
        <a href="#" data-transition-href="<?= base_url($username) ?>">

          <? if ($space_avatar): ?>
          <img id="logo" class="roundpicture logoresize" src="<?=$space_avatar?>" />
          <? else: ?>
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />

         <!-- Punchline -->
        <h1 id="punchline" class="punchline">Mon moteur de recherche</h1>

          <? endif; ?>
        </a>

      </div>
      <!-- /contrainer -->

      <div class="container">

        <!-- Block search -->
        <form id="form-search" method="post" action="<?= base_url('search') ?>">

          <input type="hidden" name="by_specific_user" value="<?=uri_string()?>">

          <div class="block-search">
            <i class="icon-search"></i> <input autocomplete="off" id="search" type="text" spellcheck="false" name="search_text" placeholder="" /> <i class="icon-angle-right"></i>
          </div>

          <input type="hidden" name="force_clever_returns" value="0" />
        </form>
        <!-- /block search -->

        <div id="inject-autocomplete" class="search-autocomplete">

        </div>

         <? if ($stick_content): ?>
        <h2><?=$stick_content?></h2>
      <? else: ?>
      <h2><?=$space_description?></h2>
    <? endif; ?>

  </div>
</div>



  </div>

