<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Linkbreakers beta</title>

  <link rel="icon" href="<?= assets_url('img/icon.ico') ?>" />

</head>

<body>
<input type="hidden" name="redirect_soon" />
</body>

  <!-- #Javascript -->

  <!-- jQuery -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

  <!-- Strike Framework -->
  <script type="text/javascript" src="<?= js_url('strike') ?>"></script>

  <script>

  $(document).ready(function() {

    STRIKE.dispatch('clever', 'render', true);

    // Bug fixed on 22/09/2013 by Laurent SCHAFFNER
    // I added a urlencode to avoid simple quote conflict
    
    // Run clever controler with run method
    // false = No object jquery
    // $search_text = The research 
    // $result = Url to redirect
    
     STRIKE.dispatch('clever', 'run', false, '<?= urlencode($search_text) ?>', '<?= urlencode($old_search_text) ?>', '<?= $result ?>');

  });

  </script>
</html>