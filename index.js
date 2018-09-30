let express = require('express'),
    mysql = require('mysql'),
    app = express(),
    uuid = require('uuid/v4'),
    _ = require('lodash'),
    db=require('./dbconfig');

// create mysql connection to the db
let connection = mysql.createConnection({
  host     : db.host,
  user     : db.user,
  password : db.password,
  port     : db.port,
  database : db.database
});

connection.connect((err) => {
  if (err) {
      console.log(err);
      return;
  }

  console.log('Connection Successful');
});

//API to add a ride entry from customer in database
app.post('/addRide', function(req, res) {
  // error out if customer id is not sent in query params
  if (!req || !req.query || !req.query.cid) {
    res.status(400).send({ err: 'Send customer params'});
  }

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

//API to select a ride request by driver.
app.put('/submitRide', function(req, res) {
  // error out if ride Id and driver Id is not sent in query params
  if (!req || !req.query || !req.query.rideId || !req.query.driverId) {
    res.status(400).send({ err: 'Send correct params'});
  }

  let initialQuery = 'SELECT `lifecycle` from `driver-details` WHERE rideID =\'' + req.query.rideId + '\';'

  connection.query(initialQuery, (err, rows) => {

    if (err || _.head(rows).lifecycle === '1') {
      res.status(400).send({ error: 'Could not update ride' });
      return;
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
      setTimeout(() => {
        let q = 'UPDATE `driver-details` SET `lifecycle`= \'2\' WHERE rideID =\'' + req.query.rideId + '\';'

        connection.query(q, (err) => {
          if (err) {
            console.log(err);
          }
          console.log(`Ride is ${req.query.rideId} has lifecycle 2 now`)
        });
      }, 360000);
      return;
    });
  });
});


//API to display ride request to driver
app.get('/fetchRides', function(req, res) {
  if (!req || !req.query || !req.query.dId) {
    res.status(400).send({ err: 'Error. Please check the endpoint and try again' });
  }

  let query = 'select `rideId`, `customerID`, `lifecycle`, `createdAt`, `driverID` from `driver-details`';

  // add 
  connection.query(query, (err, rows) => {
    if (err) {
      res.status(400).send({error : err});
      return;
    }


    let waitingRides = _.filter(rows, { lifecycle: '0' }),
        ongoingRides = _.filter(rows, { driverID: req.query.dId, lifecycle: '1' }),
        completedRides = _.filter(rows, {driverID: req.query.dId, lifecycle: '2' });

    res.status(200).send([ waitingRides, ongoingRides, completedRides]);
    return;
  });
});

//API to display details in the frontend dashboard.
app.get('/allRides', function(req, res) {
  if (!req || !req.query) {
    res.status(400).send({ err: 'Error. Please check the endpoint and try again' });
  }

  let query = 'select `rideId`, `customerID`, `lifecycle`, `createdAt`, `driverID` from `driver-details`';

  // add 
  connection.query(query, (err, rows) => {
    if (err) {
      res.status(400).send({error : err});
      return;
    }

    _.forEach(rows, (row) => {
      if (row.createdAt) {
        row.timeElapsed = (Date.now() - row.createdAt)/60000; 
      }


      delete row.createdAt;
      delete row.updatedAt;
    });

    res.status(200).send([rows]);
    return;
  });
});


app.listen(3000, () => {
  console.log('Listening on port 3000');
});