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
        <h1 id="punchline" class="punchline">Vos créations</h1>

        <div class="creations">

          <? if ($my_creations): ?>

          <div class="nb-creations">
            <?= count($my_creations) ?> créations 

            <? if ($my_hits > 0): ?>

             / <?=$my_hits?> hits

            <? endif; ?>


          </div>

          <? foreach ($my_creations as $k => $v): ?>

          <div class="result col-12">

            <div class="row">
              <div class="col-lg-6 pull-right text-right">

                <span class="label <?= $v['color_status'] ?>"><i class="icon-bookmark"></i> <?= $v['status'] ?></span>
                <div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
                    <i class="icon-cog"></i> Actions <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a class="" href="<?=base_url('tag/edit_specific/'.$v['id'])?>"><i class="icon-pencil"></i> Editer</a></li>
                    <li><a class="red" href="<?=base_url('tag/delete_specific/'.$v['id'])?>"><i  class="icon-trash"></i> Supprimer</a></li>
                    <li class="divider"></li>
                    <li><a class="" data-toggle="tooltip" title="Rendre global" href="<?= base_url('tag/ask_global/' . $v['id']) ?>"><i class="icon-cloud"></i> Rendre global</a></li>
                  </ul>
                </div>
              </div>

              <div class="col-lg-6 pull-left text-left">
                <p class="request"><i class="icon-tag"></i> <?= $v['string_pure'] ?></p>
              </div>
            </div>

            <hr>

            <div class="code">
              <div class="row">
                <div class="col-lg-1 text-center block1">
                  <i class="icon-code"></i>
                </div>

                <div class="col-lg-11">
                  <?= $v['url_pure'] ?>
                </div>
              </div>
            </div>

            <div class="pspacer-little"></div>

            <div class="row details">
              <div class="col-lg-6 pull-left text-left">
                <a id="share-<?= $v['id'] ?>" class="btn btn-default btn-sm" href="#share-<?= $v['id'] ?>" data-id="<?= $v['id'] ?>" data-share="<?= base_url('/' . $userid . '/' . str_replace(' ', '%20', $v['autocomplete'])) ?>" href="#"><i class="icon-plus"></i> Partager</a>
              </div>

              <div class="col-lg-6 pull-right text-right created">

               <? if ($v['hits'] != 0) echo $v['hits'] . ' hits - '; ?> 


               <? if ($v['last_update']) echo 'mis à jour ' . $v['last_update'] . ' - '; ?>

               ajouté <?= $v['elapsed_time'] ?> 
              </div>


            </div>



          </div>



          

        <? endforeach; ?>

      <? else: ?>
      Aucune création pour le moment
    <? endif; ?>

  </div>
  <!-- /creations -->
</div>
<!-- /container -->

</div>
<!-- /block-idea -->


</div> 
<!-- /jumbotron -->









