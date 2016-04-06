

<body>

    <? $this->load->view('_repeat/nav') ?>


  <div id="jumbotron" class="jumbotron">


    <div id="block-home">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <!-- Container -->
      <div class="container">

        <!-- Logo -->
        <a href="<?= base_url() ?>">
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
        </a>

        <!-- Punchline -->
        <h1 id="punchline" class="punchline"><?= $search_first_instant_search_engine ?></h1>

      </div>
      <!-- /contrainer -->

      <div class="container">

        <!-- Block search -->
        <form id="form-search" method="post" action="<?= base_url('search') ?>">
          
          <div class="block-search">
            <i class="icon-search"></i> <input autocomplete="off" id="search" type="text" spellcheck="false" name="search_text" placeholder="" /> <i class="icon-angle-right"></i>
          </div>

        </form>
        <!-- /block search -->

      <? if (!$stick_content): ?>

        <!-- Examples of search -->
        <p id="block-example">
          <span class="a-like"> Ex.</span> <span id="example">soundcloud jusai</span> / <span id="example">youtube crystal castles</span> / <span id="example">facebook messages</span>
        </p>


        <!-- You have no idea what are you doing ? -->
        <a id="what-btn" href="http://www.linkbreakers.com/docs/quest-ce-que-cest/" class="btn btn-outline btn-large btn-orange">
          <?= $search_what_is_this ?>
        </a>

            <? if (!$userid): ?>
            <!-- Beta display -->
            <div class="pspacer"></div>
            <div class="alert alert-danger tsmall">
              <?= $warning_beta_linkbreakers ?>
            </div>
            <? endif; ?>

        <? endif; ?>

        <div id="inject-autocomplete" class="search-autocomplete">
        </div>


        <? if ($stick_content): ?>
        <h2><?=$stick_content?></h2>
        <? endif; ?>

      </div>
    </div>



  </div>







