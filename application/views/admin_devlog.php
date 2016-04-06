<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Linkbreakers Devlog</title>

  <link rel="icon" href="<?= assets_url('img/icon.ico') ?>" />

  <!-- #Css -->

  <!-- Layout -->
  <link rel="stylesheet" type="text/css" href="<?= css_url('devlog.css') ?>">


</head>
<body>

<div class="content">
<h1>Devlog System (<?= date('d-m-Y / H:i:s') ?> )</h1>
<hr/>
<div class="pspacer"></div>

<? if (!$devlog_content): ?>

There's no log to show.

<? endif; ?>

<!-- Loop all -->
<? foreach ($devlog_content as $type => $content): ?>

  <!-- Loop content -->
  <? foreach ($content as $final_content): ?>
    
    <? if ($final_content['status'] !== 'admin'): ?>  

      <!-- You are admin so you can display without limit -->

    	<div class="time">[<i><?= $final_content['time'] ?></i>]</div>
      <div class="msg <?= $final_content['color'] ?>"><?= preg_replace('#&quot;(.*)&quot;#', '<span class="arg">"$1"</span>', $final_content['log_msg']) ?></div>

    <? elseif ($final_content['status'] === 'admin' && $userstatus === 'admin'): ?>

      <!-- You can view this if you are admin, we will add a special color -->

      <div class="time">[<i><?= $final_content['time'] ?></i>]</div>
      <div class="msg <?= $final_content['color'] ?>"><?= preg_replace('#&quot;(.*)&quot;#', '<span class="arg">"$1"</span>', $final_content['log_msg']) ?></div>

    <? endif; ?>

  <? endforeach; ?>

<? endforeach; ?>

<br class="clear" />
<div class="spacer"></div>
<hr>
<p class="copyright">
	Powered by Linkbreakers
</p>
</div>

</body>
</htmL>