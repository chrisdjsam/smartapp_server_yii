'use strict';

var async = require('async')
  , xmpp = require('node-xmpp')
  , http = require('http')

var version_number = "V.0.0.1";

var hostname = 'neatodev.rajatogo.com';	// Change the hostname against which you want to run this test case against
var jabber_host = 'neatodev.rajatogo.com'; // Change it to the ejabberd hostname

var serial_base = "c8700000";

var SIZES = [500]; // You can add more entries here if you want to run it for different number of robots.

var lot_size = 10;    
var interval = 6000; 
var start_from = 1000;

var start = 0;
var end = 0;

http.globalAgent.maxSockets = 9999999;

//ping from online robot
function pingFromRobot(serial) {
	var post_options = { 
		host: hostname,
	    path: '/api/rest/json/?method=robot.ping_from_robot',
		method: 'POST',
	    headers: {
	      'Content-Type': 'application/x-www-form-urlencoded'
	    }
	}
	var post_req;
	var post_body;
	var response;
	post_req = http.request(post_options, function(res) {
		res.setEncoding('utf8');

		res.on('error', function(e) {
		    console.error(e);
		});
		
		// getting the JSON response for robot details		
	    res.on('data', function (reply) {
	    	// console.log(reply);	// uncomment if you want to view response
		});
	});
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&&notification_flag=0&serial_number=' + serial;
	post_req.write(post_body);
	post_req.end();
}

function performPingOp() {
	console.log('Calling ping web service using clients having serial number in the range ', serial_base + (start_from+start).toString(), '-', serial_base + (start_from+end).toString(), '...');
	for(var i=start; i<end; i++){
		var serial = serial_base + (i + start_from).toString();
		pingFromRobot(serial);
	}
}

async.forEachSeries(SIZES, function(n, cb) {
	console.log("Version Number :: " + version_number);
    var chunk = parseInt(n/lot_size);
    end = chunk;
    performPingOp();
    setInterval(function(){
 	   	start = end;
 		end = end+chunk;
    	if (start == n){
	 	   	start = 0;
	 		end = chunk;
    	}
    	performPingOp();
    }, interval);
});
