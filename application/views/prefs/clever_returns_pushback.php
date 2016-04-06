<!DOCTYPE html>
<html>

<head>

<title>Clever returns is pushing</title>

</head>

<body>
</body>

  <!-- #Javascript -->

  <!-- jQuery -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

  <!-- Strike Framework -->
  <script type="text/javascript" src="<?= js_url('strike') ?>"></script>

  <script>

  $(document).ready(function() {

    /*
    We prepare the clever system
    */
    STRIKE.dispatch('clever', 'clever_pushback', true);

  });

  </script>
</html>