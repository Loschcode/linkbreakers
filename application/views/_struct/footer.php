  <!-- Simple footer (settings) -->
  <div class="spacer-before-footer"></div>
  
  <div id="footer" class="footer navbar-fixed-bottom">

    <div class="inner">

    <div id="inject-team"></div>

    <!-- Contact us if you live in bordeaux ;) -->
    <div class="pull-left">

      <i class="icon-heart"></i> <?= $footer_crafted_with_love_in_bordeaux ?>

    </div>

    <!-- Settings -->
    <div class="pull-right settings">


      <!-- Permalink -->
      <? if ($permalink): ?>

      <a id="share-0" class="btn btn-default" href="#share-0" data-id="0" data-share="<?= $permalink ?>" href="#"><i class="icon-plus"></i> Partager</a>
      <div class="separate-from-right form-same-line"></div>

      <? endif; ?>

      <? if ($page === 'log'): ?>

      <? if ($subpage === 'auth'): ?>

      <!-- Log-in forgot password button -->
      <form method="post" action="<?= base_url('log/forgot') ?>" class="form-basic form-same-line">
      <button type="submit" class="btn btn-default btn-blue"><i class="icon-asterisk"></i> Mot de passe oublié</button>
      </form>

      <? endif; ?>

      <? endif; ?>

      <? if ($page === 'creations'): ?>

      <!-- Search through my creations -->
      <form method="get" action="<?= base_url('profile/creations') ?>" class="form-basic form-same-line separate-from-right">

        <? if ($this->input->get('search')): ?>
        <a class="btn btn-blue tcolor-white" href="<?= base_url('profile/creations') ?>"><i class="icon-remove"></i></a>
      <? else: ?>
      <!-- <a class="btn btn-danger disabled" href="<?= base_url('profile/creations') ?>"><i class="icon-remove"></i></a>-->
    <? endif; ?>

    <input type="text" name="search" autocomplete="off" value="<?= $search_creation ?>" class="form-control form-same-line" placeholder="Chercher dans mes créations">


    <input type="submit" class="btn btn-orange" />

  </form>
      <!-- /search my creations -->

      <? endif; ?>


      <!-- Choose lang -->
      <div id="lang" class="btn-group dropup">

        <button id="button" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <?= $footer_languages ?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu"> 
          <li><a href="<?= LINKBREAKERS_URL_FR . $_SERVER['REQUEST_URI'] ?>"><? if (Language::pick('short') === 'fr'): ?><i class="icon-caret-right"></i><? endif; ?> Français</a></li>
          <li><a href="<?= LINKBREAKERS_URL_EN . $_SERVER['REQUEST_URI'] ?>"><? if (Language::pick('short') === 'en'): ?><i class="icon-caret-right"></i><? endif; ?> English</a></li>
          <!-- Dropdown menu links -->
        </ul>
      </div>

      <!-- /choose lang -->

      <? if ($page === 'search'): ?>

        <? if (($subpage !== 'tools/text') && ($subpage !== 'tools/stick')): ?>

      <!-- Prefs default result -->
     	<button data-setting="prefs_default_result" type="button" class="btn btn-default btn-blue"><i class="icon-ok-sign"></i> <?= $footer_google_as_default_result ?></button>

          <? if ($userid): ?>

          <!-- Prefs smart domains -->
          <button data-setting="prefs_smart_domains" type="button" class="btn btn-default btn-blue"><i class="icon-ok-sign"></i> <?= $footer_smart_domains ?></button>

          <!-- Prefs clever returns -->
          <button data-setting="prefs_clever_returns" type="button" class="btn btn-default btn-blue"><i class="icon-ok-sign"></i> <?= $footer_clever_returns ?></button>

          <? endif; ?>

        <? endif; ?>

   	  <? endif; ?>


      <? if ($page === 'tag'): ?>

      <? if ($subpage === 'add_tag'): ?>

      <!-- Add tag Guess my entry -->
      <button data-option="guess_my_entry" type="button" class="btn btn-default btn-danger disabled"><i class="icon-star"></i> <?= $footer_guess_my_entry ?></button>

      <? endif; ?>

      <? endif; ?>

    </div>
  </div>
  </div>




  <!-- #Javascript -->

  <? $this->load->view('_repeat/js') ?>

</body>
</html>