<?php 
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://127.0.0.1:3000/allRides",
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
      $dataString = '';

      foreach ($decode_data[0] as $key => $value) {
        foreach($value as $key1 => $value1) {
          if ($key1 === 'lifecycle') {
            $value1 === '0' && $value1 = 'waiting';
            $value1 === '1' && $value1 = 'ongoing';
            $value1 === '2' && $value1 = 'completed';
          }

          $dataString = $dataString.'<br>'.$key1.' -> '.$value1;
          
          if ($key1 === 'timeElapsed' && $dataString !== '') {
            array_push($arr, $dataString);
            $dataString = '';
          }
        }
      }
  } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Cabbies</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Drivers
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="driver1.php">Driver-1</a></li>
              <li><a href="driver2.php">Driver-2</a></li>
              <li><a href="driver3.php">Driver-3</a></li>
              <li><a href="driver4.php">Driver-4</a></li>
              <li><a href="driver5.php">Driver-5</a></li>
            </ul>
          </li>
          <li><a href="customer.html">Customer</a></li>
        </ul>
      </div>
    </nav>  
<div class="container">
  <h3>Cabbies</h3>
  <p>An assignment for docsApp</p>
  <p>NOTE: Time Elapsed is in mins.</p>
  <div class="container"> 
  <table class="table table-bordered">
    <thead>
    </thead>
    <tbody>

      <?php for($i = 0; $i < sizeOf($arr); $i++) {
        $arrpol = array();
        $arrpol = explode('<br>', $arr[$i]);
        ?>
        <form method='post' action="driver5.php">
        <tr>
          <td>
            <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading" id='data'>
                  <input type="hidden" name="rideId" value="<?php echo $arrpol[1]; ?>"></input>
                  <label id='rideId' name='rideId'><?php echo $arrpol[1]; ?></label><br>
                  <label id='custId'><?php echo $arrpol[2]; ?></label><br>
                  <label id='lifecycle'><?php echo $arrpol[3]; ?></label><br>
                  <label id='createdAt'><?php echo $arrpol[4]; ?></label><br>
                  <label id='driverId'><?php echo $arrpol[5]; ?></label><br>

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
