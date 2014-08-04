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
var cls = [];

var post_options = { 
	host: hostname,
    path: '/api/rest/json/?method=robot.get_details',
	method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
}
http.globalAgent.maxSockets = 9999999;

// connect num clients and execute the callback cb upon completion
function connectThem(num, cb) {	
    cls = [];
    var connected = 0;
    
	console.log('Connecting from serial ' + serial_base + (start_from).toString() + ' to ' + serial_base + (num + start_from).toString() + ' ...');

	for (var i = 0; i < num; i++) {
		var serial = serial_base + (i + start_from).toString();
		var post_req;
		var post_body;
		var response;
		var chat_id, chat_pwd;

		// get the robot details including jabberid and password
		post_req = http.request(post_options, function(res) {
		    res.setEncoding('utf8');

			// getting the JSON response for robot details
		    res.on('data', function (reply) {
				// assume all the response is received in one chunk
		        response = JSON.parse(reply);
				// get chat id and password
				chat_id = response.result.chat_id;
				chat_pwd = response.result.chat_pwd;
				
				// log into the Jabber server
				var client = new xmpp.Client({
				    jid: chat_id, 
				    password: chat_pwd, 
					host: jabber_host
				});
				
				// login successful
				client.on('online', function() {
				    console.log(client.jid + ' came online...');
				    // client.send('<presence/>');
				    client.send(new xmpp.Element('presence', { })
				      .c('show').t('chat').up()
				      .c('status').t('Happily connected')
				    );
					
					cls.push(client);
					connected++;
					if (connected == num)
						// all clients are connected
						cb();
				});

				// not used in the loop test (the connections don't stay open)
				client.on('stanza', function(stanza) {
					// received XMPP ROBOT_PROFILE_DATA_CHANGED 
				    if (stanza.is('message') &&
				      // never reply to errors
				      (stanza.attrs.type !== 'error')) {
					    console.log(stanza.toString());
				    }
				});

				client.on('error', function(e) {
				    console.error(e)
				});

		    });
		});

		post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&serial_number=' + serial;
		post_req.write(post_body);
		post_req.end();
	} // for
}

function sendPresenceStatus(){
	console.log('Sending presence package by clients having serial number in range ', serial_base + (start_from+start).toString(), '-', serial_base + (start_from+end).toString(), '...');
	for(var i=start; i<end; i++){
		var client = cls[i]; 
		client.send(new xmpp.Element('presence', { })
			.c('show').t('chat').up()
			.c('status').t('Happily connected')
		);
	}
}

// perform a series sequential connections cycling through the values of SIZES
async.forEachSeries(SIZES, function(n, cb) {
	console.log("Version Number :: " + version_number);
	console.log('Connecting', n, 'clients');
    connectThem(n, function() {
 	   var total_online_clients = cls.length;
 	   var chunk = parseInt(total_online_clients/lot_size);
 	   end = chunk;
 	   sendPresenceStatus();
 	   setInterval(function(){
 		  start = end;
	 	  end = end+chunk;
 	      if (start == total_online_clients){
 	    	  start = 0;
 		 	  end = chunk;
 	      }
 	      sendPresenceStatus();
 	    }, interval);
	});
});
