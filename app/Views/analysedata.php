<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/datatables.min.js"></script>

  </head>
  <body>
    <?php foreach ($data as $key => $values) :?>
    <?php if ($key == "totalCall"): ?>
    <div>
      <p>Durée total des appels émis depuis le 15 février 2012 : <?php echo $values ?></p>
    </div>
  <?php endif; ?>
    <?php if ($key == "topTen"):  ?>
    <div>
      <table id="topTenId" class="display" style="width:100%">
        <thead>
          <tr>
            <th>user_id</th>
            <th>callTime</th>
            <th>billedDuration</th>
            <th>callType</th>
          <tr>
        </thead>
        <tbody>
          <?php foreach ($values as $value): ?>
            <tr>
             <td><?php echo $value['user_id']; ?></td>
             <td><?php echo $value['callTime']; ?></td>
             <td><?php echo $value['billedDuration']; ?></td>
             <td><?php echo $value['callType']; ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>

    </div>
  <?php endif; ?>
    <?php if ($key == "totalSms"): ?>
    <div>
      <p>Le total sms envoyé est : <?php echo $values; ?></p>
    </div>
  <?php endif; ?>
<?php endforeach; ?>
  </body>

</html>
<script>
  $(document).ready( function() {
    $('#topTenId').DataTable();
  });
</script>
