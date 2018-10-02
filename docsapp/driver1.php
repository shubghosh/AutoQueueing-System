<?php 

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://127.0.0.1:3000/fetchRides?dId=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
      $decode_data = json_decode($response, true);
      $arr = array();
      $arr1 = array();
      $arr2 = array();
      $dataString = '';

      foreach ($decode_data[0] as $key => $value) {
          foreach($value as $key1 => $value1) {
            if ($key1 !== 'lifecycle' && $key1 !== 'driverID') {
              $dataString = $dataString.'<br>'.$key1.' -> '.$value1;
            }
            
            if ($key1 === 'createdAt' && $dataString !== '') {
              array_push($arr, $dataString);
              $dataString = '';
            }
        }
      }

      foreach ($decode_data[1] as $key => $value) {
        foreach($value as $key1 => $value1) {
          if ($key1 !== 'lifecycle' && $key1 !== 'driverID') {
            $dataString = $dataString.'<br>'.$key1.' -> '.$value1;
          }
          
          if ($key1 === 'createdAt' && $dataString !== '') {
            array_push($arr1, $dataString);
            $dataString = '';
          }
      }
    }

    foreach ($decode_data[2] as $key => $value) {
      foreach($value as $key1 => $value1) {
        if ($key1 !== 'lifecycle' && $key1 !== 'driverID') {
          $dataString = $dataString.'<br>'.$key1.' -> '.$value1;
        }
        
        if ($key1 === 'createdAt' && $dataString !== '') {
          array_push($arr2, $dataString);
          $dataString = '';
        }
    }
  }
    }

  if(isset($_POST['rideId']))
  {
    $newstring = substr($_POST['rideId'], 10);
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://127.0.0.1:3000/submitRide?driverId=1"."&rideId=".$newstring,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_HTTPHEADER => array(),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    die();
    } else {
     echo $response;
      die();
    }


  } 

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Driver</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Driver <?php echo $driverId; ?></h2>      
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Waiting</th>
      </tr>
    </thead>
    <tbody>

      <?php for($i = 0; $i < sizeOf($arr); $i++) {
        $arrpol = array();
        $arrpol = explode('<br>', $arr[$i]);
        ?>
        <form method='post' action="driver1.php">
        <tr>
          <td>
            <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading" id='data'>
                  <input type="hidden" name="rideId" value="<?php echo $arrpol[1]; ?>"></input>
                  <label id='rideId' name='rideId'><?php echo $arrpol[1]; ?></label><br>
                  <label id='custId'><?php echo $arrpol[2]; ?></label><br>
                  <label id='time'><?php echo $arrpol[3]; ?></label><br>
                  <label> </label>
                </div>
                <?php
                  if ($decode_data[1] === []) {
                ?>
                <button type='submit' id='hello'>Accept Ride </button>
                  <?php } else { ?>
                    <button type='submit' id='hello' disabled>Accept Ride </button>
                  <?php } ?>
              </div>
            </div>
        </td>
      <tr>
      </form>
      <?php } ?>
    </tbody>
    <thead>
      <tr>
        <th>Ongoing</th>
      </tr>
    </thead>
    <tbody>

<?php for($i = 0; $i < sizeOf($arr1); $i++) {
  $arrpol = array();
  $arrpol = explode('<br>', $arr1[$i]);
  ?>
  <form method='post' action="">
  <tr>
    <td>
      <div class="panel-group">
        <div class="panel panel-default">
          <div class="panel-heading" id='data'>
            <input type="hidden" name="rideId" value="<?php echo $arrpol[1]; ?>"></input>
            <label id='rideId' name='rideId'><?php echo $arrpol[1]; ?></label><br>
            <label id='custId'><?php echo $arrpol[2]; ?></label><br>
            <label id='time'><?php echo $arrpol[3]; ?></label><br>
            <label> </label>
          </div>
        </div>
      </div>
  </td>
<tr>
</form>
<?php } ?>
</tbody>
<thead>
      <tr>
        <th>Complete</th>
      </tr>
    </thead>

    <tbody>

<?php for($i = 0; $i < sizeOf($arr2); $i++) {
  $arrpol = array();
  $arrpol = explode('<br>', $arr2[$i]);
  ?>
  <form method='post' action="drivers.php">
  <tr>
    <td>
      <div class="panel-group">
        <div class="panel panel-default">
          <div class="panel-heading" id='data'>
            <input type="hidden" name="rideId" value="<?php echo $arrpol[1]; ?>"></input>
            <label id='rideId' name='rideId'><?php echo $arrpol[1]; ?></label><br>
            <label id='custId'><?php echo $arrpol[2]; ?></label><br>
            <label id='time'><?php echo $arrpol[3]; ?></label><br>
            <label> </label>
          </div>
        </div>
      </div>
  </td>
<tr>
</form>
<?php } ?>
</tbody>
  </table>
</div>

</body>
</html>
