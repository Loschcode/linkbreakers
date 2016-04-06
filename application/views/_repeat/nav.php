<!--
-
- Navigation style and system
-
-->
<div class="nav nav-background navbar-fixed-top col-15">
  <ul class="jumbotron-links">

    <!--
    -
    - Documentation button
    -
    -->

    <li class="pull-left">
      <a href="<?= base_url('docs') ?>" target="_blank" class="btn btn-default"><i class="icon-book"></i> <?= $nav_documentation ?></a>
    </li>

    <!--
    -
    - If we're not on the research page
    -
    -->

    <? if ($page != 'search'): ?>

      <li>
        <a href="<?= base_url() ?>" class="btn btn-default"><i class="icon-search"></i> <?= $nav_search ?></a>
      </li>
    
    <? endif; ?>

    <!--
    -
    - If we're in the user space
    -
    -->

    <?php if ($userspace): ?>

    <? if ((($page != 'search_from_user') && ($page != 'profile')) || (($page == 'profile') && ($subpage != 'space'))): ?>

    <div class="btn-group">

      <button id="button" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <i class="icon-home"></i> Mon espace <span class="icon-caret-down"></span>
      </button>

      <ul class="dropdown-menu dropdown-blue" role="menu"> 

        <? if ($space_home): ?>

        <li>
          <a href="<?= base_url($username) ?>"><i class="icon-rocket"></i> Accéder à l'espace</a>
        </li>
      
        <? endif; ?>

      <li>
        <a href="<?= base_url('profile/space') ?>"> <i class="icon-edit"></i> Modifier</a>
      </li>

      </ul>

    </div> 

    <? elseif ($page == 'search_from_user'): ?>

    <li>
      <a href="<?= base_url('profile/space') ?>" class="btn btn-default"> <i class="icon-edit"></i> Modifier mon espace</a>
    </li>

    <? elseif (($page == 'profile') && ($subpage == 'space') && ($space_home)): ?>

    <li>
      <a href="<?= base_url($username) ?>" class="btn btn-default"><i class="icon-rocket"></i> Visualiser mon espace</a>
    </li>

    <? endif; ?>

<? endif; ?>

<!--
-
- Not connected
-
-->
<? if (!$userid): ?>

  <li>
    <a href="<?= base_url('tag/add') ?>" class="<?= nav_active('tag', $page) ?> btn btn-default btn-blue"><i class="icon-pencil"></i> <?= $nav_make_your_idea ?></a>
  </li>

<? if ($canEdit): ?>

      <li>
       <a href="<?= base_url('tag/edit') ?>" class="btn btn-default"><i class="icon-edit"></i> Editer</a>
     </li>

<? endif; ?>

  <li>
    <a href="<?= base_url('log') ?>" href="#" class="<?= nav_active('log', $page) ?> btn btn-orange"><i class="icon-plus"></i> <?= $nav_sign_in ?></a>
  </li>

<!--
-
- Connected
-
-->
<? else: ?>

<!--
-
- There's no user creation
-
-->
<? if ($usercreations > 0): ?>

<div class="btn-group">

  <button type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown">
    <i class="icon-gear"></i> Gestion des créations <i class="icon-caret-down"></i> 
  </button>

  <ul class="dropdown-menu dropdown-blue" role="menu">

    <li>
      <a href="<?= base_url('tag/add') ?>"><i class="icon-pencil"></i> Créer une nouvelle idée</a>
    </li>

    <!--
    -
    - If the user can edit an idea
    -
    -->
    <? if ($canEdit): ?>

      <li>
       <a href="<?= base_url('tag/edit') ?>"><i class="icon-edit"></i> Editer ma dernière idée</a>
     </li>

   <? endif; ?>

   <li class="divider"></li>

   <li>
    <a href="<?= base_url('profile/creations') ?>"><i class="icon-th"></i> Mes créations (<?= $num_creations ?>)</a>
  </li>

  </ul>
</div>

<? else: ?>

<li>
  <a href="<?= base_url('tag/add') ?>" class="<?= nav_active('tag', $page) ?> btn btn-default btn-blue"><i class="icon-pencil"></i> <?= $nav_make_your_idea ?></a>
</li>

<? endif; ?>

<li>
  <div class="btn-group">

    <button type="button" class="btn btn-orange dropdown-toggle" data-toggle="dropdown">
      <i class="icon-user"></i> <?= $username_pretty ?> <i class="icon-caret-down"></i> 
    </button>

    <ul class="dropdown-menu" role="menu">

      <!-- Display dashboard if admin type -->
      <? if ($userstatus == 'admin'): ?>

      <li>
      <a href="<?= base_url('admin') ?>"><i class="icon-dashboard"></i> Dashboard</a>
      </li>

      <li>
      <a href="<?= base_url('profile/devlog') ?>" target="_blank"><i class="icon-terminal"></i> Devlog</a>
      </li>

      <li class="divider"></li>

      <? endif; ?>

    <? if ($onSandbox): ?>

    <li>
    <a href="<?= base_url('tools/sandbox') ?>"><i class="icon-code"></i> Sandbox</a>
    </li>
    
    <? endif; ?>

    <li>
    <a href="<?= base_url('profile') ?>"><i class="icon-user"></i> Mon profil</a>
    </li>

    <li>
    <a href="<?= base_url('log/deco') ?>"><i class="icon-off"></i> Déconnexion</a>
    </li>

    </ul>
  </div>
</li>

<? endif; ?>

</ul> 
</div>
<!-- End of navigation menu -->
