let express = require('express'),
    mysql = require('mysql'),
    app = express(),
    uuid = require('uuid/v4');

// create mysql connection to the db
let connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : '',
  port     : '3306',
  database: 'docsapp'
});

connection.connect((err) => {
  if (err) {
      console.log(err);
      return;
  }

  console.log('Connection Successful');
});


app.post('/addRide', function(req, res) {
	console.log('addride');
  // error out if customer id is not sent in query params
  if (!req || !req.query || !req.query.cid) {
    res.status(400).send({ err: 'Send customer params'});
  }
  
  console.log(req.query.cid);

  let rideId = uuid(),
      query = 'INSERT INTO `driver-details` (`rideID`, `driverID`, `customerID`, `lifecycle`, `createdAt`, `updatedAt`) VALUES (\''+ rideId + '\', \'\',\'' + req.query.cid + '\', \'0\', CURRENT_TIMESTAMP, \'0000-00-00 00:00:00.000000\')';
  // add 
  connection.query(query, (err, rows) => {
    if (err) {
      res.status(400).send({error : err});
      return;
    }
    res.status(200).send({ success : 'Added new ride' });
    return;
  });
});

app.put('/submitRide', function(req, res) {
  // error out if ride Id and driver Id is not sent in query params
  if (!req || !req.query || !req.query.rideId || !req.query.driverId) {
    res.status(400).send({ err: 'Send correct params'});
  }

  let rideId = uuid(),
      query = 'UPDATE `driver-details` SET `driverID`= \'' + req.query.driverId + '\' , `lifecycle`=\'1\', `updatedAt`= CURRENT_TIMESTAMP WHERE rideID =\'' + req.query.rideId + '\';'
  // add 
  connection.query(query, (err, rows) => {
    if (err) {
      res.status(400).send({error : err});
      return;
    }
    res.status(200).send({ success : 'Data updated successfully' });
    return;
  });
});

app.get('/fetchRides', function(req, res) {
	console.log('fetchRides');
  if (!req || !req.query) {
    res.status(400).send({ err: 'Error. Please check the endpoint and try again' });
  }

  let query = 'select * from `driver-details`';
  // add 
  connection.query(query, (err, rows) => {
    if (err) {
      res.status(400).send({error : err});
      return;
    }
    res.status(200).send({ data: rows });
    return;
  });
});


app.listen(3000, () => {
  console.log('Listening on port 3000');
});



