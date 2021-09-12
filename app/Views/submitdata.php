<?html>
  <head>
  </head>
  </body>
    <?= form_open_multipart('start/index', 'id="go_submit"'); ?>
      <input type="file" name="callFile" size="20">
      <input type="submit" value="envoyez">
    <?= form_close(); ?>
  </body>

</html>
