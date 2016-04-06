<br />
<br />
<div align="center">

  <table border="0" align="center">
    <tr>
      <td><?=img('dashboard.png')?></td>
      <td><h1 align="center">&nbsp;Dashboard</h1></td>
    </tr>
  </table>
  <br />

  <!-- DASHBOARD -->
    <div>

          <?php if (isset($find)): ?>

    <h2>Results for "<?=$find_text?>"</h2>

    <?php foreach ($find as $row): ?>

      <br /><strong><a href="<?=base_url("?request=".$row['autocomplete'])?>" target="_blank"><?=$row['string_clean']?></a> [ID <?=$row['id']?>]</strong>
      <a href="<?=base_url('admin/status/'.$row['id'].'/beta')?>" class="label label-success"> SWITCH TO BETA</a>

    <?php $results = TRUE; ?>

    <?php endforeach; ?>

    <?php if (!isset($results)): ?>Nothing<? endif; ?>

    <br /><br />

  <?php endif; ?>

        <h2>Last alpha-links</h2>

      <?php foreach ($lasts_alpha as $row): ?>

      <?
      // JSON TREATMENT // -> I know dat bitch shouldn't have been here, but whatever, it's an admin panel, nobody cares about optimization here
      $edit = json_decode($row['edit'], TRUE);
      ?>
      <p>
      <div class="breadcrumb">
      <br /><h2><?=$edit['string']?> [ID <?=$row['id']?>] <a href="#">Conflict : <?=$row['conflict']?></a></h2>
      <a href="<?=base_url('admin/status/'.$row['id'].'/beta')?>" class="label label-important">GO TO BETA</a>
      <?php if ($row['id_user'] != 0): ?><a href="<?=base_url('admin/status/'.$row['id'].'/private')?>" class="label label-info">RETURN TO PRIVATE</a><?php endif; ?>
      <a href="<?=base_url('admin/status/'.$row['id'].'/off')?>" class="label label-inverse">SET OFF</a>
      <br />
      <br />
      <strong>Link</strong> <?=$this->panda->code_coloration($row['url'])?>
      <br />
      <strong>Add</strong> <?=date('d/m/Y (H:i:s)', $row['date'])?>
      <br />
      <strong>User</strong> <?=$this->panda->code_coloration($row['id_user'])?>
      <br />

    </div>
      </p>

      <?php endforeach; ?>

      <br />
       <br />

      <h2>Last beta-links</h2>

      <?php foreach ($lasts_beta as $row): ?>

      <?
      // JSON TREATMENT // -> I know dat bitch shouldn't have been here, but whatever, it's an admin panel, nobody cares about optimization here
      $edit = json_decode($row['edit'], TRUE);
      ?>
      <p>
      <div class="breadcrumb">
      <br /><h2><a href="<?=base_url("?request=".$row['autocomplete'])?>" target="_blank"><?=$row['string_clean']?></a> [ID <?=$row['id']?>]</h2>
      <strong><?=$edit['string']?></strong>
      <br />
      <br />
      <a href="<?=base_url('admin/status/'.$row['id'].'/on')?>" class="label label-success">ON</a>
      <a href="<?=base_url('admin/status/'.$row['id'].'/trans')?>" class="label label-info">TRANS</a>
      <a href="<?=base_url('admin/status/'.$row['id'].'/beta')?>" class="label label-important">BETA</a>
      <a href="<?=base_url('admin/status/'.$row['id'].'/private')?>" class="label label-inverse">PRIVATE</a>
      <a href="<?=base_url('admin/status/'.$row['id'].'/off')?>" class="label label-inverse">OFF</a>
      <br />
      <br />

      <br />
      <strong>Link</strong> <?=$this->panda->code_coloration($row['url'])?>
      <br />
      <strong>Last search</strong> <?=date('d/m/Y (H:i:s)', $row['last_search'])?>
      <br />
      <strong>Add</strong> <?=date('d/m/Y (H:i:s)', $row['date'])?>
      <br />
      <strong>User</strong> <?=$this->panda->code_coloration($row['id_user'])?>

      <br />

    </div>
      </p>

      <?php endforeach; ?>

      <br />
       <br />


      <h2>Last transparents</h2>

      <?php foreach ($lasts_trans as $row): ?>
      <br /><strong><a href="<?=base_url("?request=".$row['autocomplete'])?>" target="_blank"><?=$row['string_clean']?></a> [ID <?=$row['id']?>]</strong>
      <a href="<?=base_url('admin/status/'.$row['id'].'/beta')?>" class="label label-info"> SWITCH TO BETA</a>

      <?php endforeach; ?>

       <br />
        <br />


      <h2>Last approved links</h2>

      <?php foreach ($lasts_on as $row): ?>
      <br /><strong><a href="<?=base_url("?request=".$row['autocomplete'])?>" target="_blank"><?=$row['string_clean']?></a> [ID <?=$row['id']?>]</strong>
      <a href="<?=base_url('admin/status/'.$row['id'].'/beta')?>" class="label label-success"> SWITCH TO BETA</a>

      <?php endforeach; ?>

       <br />
        <br />


      <h2>Disabled links</h2>

      <?php foreach ($lasts_off as $row): ?>
      <br /><strong><?=$row['string_clean']?> [ID <?=$row['id']?>]</strong>
      <a href="<?=base_url('admin/status/'.$row['id'].'/beta')?>" class="label label-important"> SWITCH TO BETA</a> <a href="<?=base_url('admin/delete/'.$row['id'])?>" class="label label-inverse">DELETE ENTRY</a>

      <?php endforeach; ?>

      <br />
      <br />

      <div class="breadcrumb" style="width:40em">
      <h2>Search & Edit</h2>

    <form id="form_search" name="form_search" method="POST" target="_self" action="<?=base_url('admin/find')?>">
      <br />
      <input type="text" class="span5" id="find" name="find" value="" autocomplete="off" maxlength="200">
      <br />
      <input type="submit" class="btn btn-large" name="send" id="send" value="Find">
    </form>
        </div>



    </div>


</div>