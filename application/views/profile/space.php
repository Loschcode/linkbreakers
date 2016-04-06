<body>

  <? $this->load->view('_repeat/nav') ?>

  <div id="jumbotron" class="jumbotron">

    <!-- End Nav -->

    <div id="block-space" class="container block-space">

      <!-- Spacer -->
      <div class="pspacer"></div>

      <div class="container">

        <!-- Logo -->
        <a href="#" data-transition-href="<?= base_url() ?>">
          <img id="logo" class="logo" src="<?= assets_url('img/logo/linkbreakers-logo.png') ?>" />
        </a>

        <!-- Punchline -->
        <h1 id="punchline" class="punchline">Votre espace</h1>

        <div class="pspacer-medium"></div>


        <div class="well">
          <h1>Logo d'accueil (Avatar)</h1>
          <hr>

          <?php if ($space_avatar): ?>
          <img class="roundpicture logoresize" src="<?=$space_avatar?>">
        <?php else: ?>
        <h2>Aucun</h2>
      <?php endif; ?>

      <div class="pspacer-little"></div>


      <div class="tcomment">Format conseillé : 800x180</div>

      <div class="pspacer-medium"></div>

      <form method="post" action="<?=base_url('profile/update/space_avatar')?>" enctype="multipart/form-data">     
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152">    

        <?= form_error('avatar_file', SUFFIX_ERROR, PREFIX_ERROR) ?>
        <div align="center">
          <input type="file" name="avatar_file">
        </div>


        <div class="pspacer-medium"></div>

        <button type="submit" class="btn btn-primary" data-loading-text="Chargement ...">
          <i class="icon-wrench"></i> Changer mon avatar
        </button>
        <a class="btn btn-danger" href="<?=base_url('profile/update/space_avatar/delete')?>"><i class="icon-trash"></i> Supprimer </a> 

      </form>

    </div>
    <!-- /well avatar -->

    <a name="sd"></a>

    <div class="well">

      <h1>Message d'accueil</h1>
      <hr>

      <form id="form_add" name="form_add" method="POST" target="_self" action="<?=base_url('profile/update/space_description#sd')?>">

       <?= form_error('space_description_edit', SUFFIX_ERROR, PREFIX_ERROR) ?>
       
       <textarea align="left" type="text" class="form-control input-lg textarea-transition <?= error_css('space_description_edit') ?>" id="space_description_edit" placeholder="Ajoutez une description ..." name="space_description_edit" autocomplete="off" maxlength="2000">
<?
        if (set_value('space_description_edit')) echo set_value('space_description_edit');
        else echo $space_description_edit;
        
        ?></textarea>

       <div class="pspacer-medium"></div>
       <div class="tcomment">Cette description sera visible sous la barre de recherche de votre espace. LBL autorisé.</div>
       <div class="pspacer"></div>

       <button type="submit" class="btn btn-primary">
        <i class="icon-edit"></i> Changer ma description
      </button>

    </form>

  </div>
  <!-- /message -->


  <a name="sr"></a>

  <div class="well">
    <h1>Redirection par défaut</h1>
    <hr>

      <form id="form_add" name="form_add" method="POST" target="_self" action="<?=base_url('profile/update/space_redirection#sr')?>">

      <?= form_error('space_redirection_edit', SUFFIX_ERROR, PREFIX_ERROR) ?>

   <textarea align="left" type="text" class="form-control input-lg textarea-transition <?= error_css('space_redirection_edit') ?>" id="space_redirection_edit" placeholder="http://www.google.com/#q={SEARCH:URL}" name="space_redirection_edit" autocomplete="off" maxlength="2000"><?

        if (set_value('space_redirection_edit')) echo set_value('space_redirection_edit');
        else echo $space_redirection_edit;
        
        ?></textarea>

   <br />
   <div class="tcomment">Si rien n'est trouvé à travers l'espace de l'utilisateur, une redirection personnalisée peut être définie. Utilise <a href="<?= lbl_doc_function_url('search') ?>" target="_blank">{SEARCH}</a> pour replacer la recherche de l'utilisateur. LBL (evidemment) autorisé.</div>
    <div class="pspacer"></div>
    
    <button type="submit" class="btn btn-primary">
      <i class="icon-edit"></i> Personnaliser la redirection
    </button>  

  </form>

  </div>
  <!-- /redirection -->

  <a name="opt"></a>

  <div class="well tleft">
    <h1>Options</h1>
    <hr>
    <? if (!$space_home): ?>
    <a href="<?=base_url('profile/update/space_home/activate')?>" class="btn btn-large btn-default"><i class="icon-road"></i> Activer l'accueil de mon espace</a>
    <? else: ?>
    <a href="<?=base_url('profile/update/space_home/deactivate')?>" class="btn btn-large btn-danger"><i class="icon-road"></i> Désactiver l'accueil de mon espace</a>
    <? endif; ?>

  </div>
  <!-- /options -->

    </div>
    <!-- /container -->

  </div>
  <!-- /block-idea -->


</div> 
<!-- /jumbotron -->









