/*
 * Test Neato SmartApp Server 
 *
 * Start a pool of client connections to the SmartApp server. Listen to incoming XPPP messages and respond with a typical command received sequence.
 *
 * Usage: node server_pool_connections.js
 *
 */

'use strict';

var async = require('async')
  , xmpp = require('node-xmpp')
  , http = require('http')

var hostname = 'staging-smartapp.neatorobotics.com';
var jabber_host = 'staging-smartapp.neatorobotics.com';
var serial_base = "c8700000";
var SIZES = [100];

var post_options = { 
	host: hostname,
    path: '/api/rest/json/?method=robot.get_details',
	method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
}
http.globalAgent.maxSockets = 9999999;

// return current time in milliseconds
function getNow() {
    return new Date().getTime()
}

// step 2 - send HTTP robot.delete_robot_profile_key2 after receiving response from the previous operation
//
function sendCmdSequence_step2(serial) {
	console.log("in sendCmdSequence2", serial);

	var post_options = { 
		host: hostname,
	    path: '/api/rest/json/?method=robot.delete_robot_profile_key2',
		method: 'POST',
	    headers: {
	      'Content-Type': 'application/x-www-form-urlencoded'
	    }
	}
	var post_req;
	var post_body;
	var response;

	// get the robot details (cleaning Command)
	post_req = http.request(post_options, function(res) {
	    res.setEncoding('utf8');

		res.on('error', function(e) {
		    console.error(e)
		});

		// getting the JSON response for robot details
	    res.on('data', function (reply) {
			console.log(reply);
		});
	});
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&&notification_flag=0&key=cleaningCommand&cause_agent_id=' + serial + '&serial_number=' + serial;
	post_req.write(post_body);
	post_req.end();
}

// step 1: send HTTP robot.get_profile_details2 after receiving XMPP attention message
//
function sendCmdSequence(serial) {
	// get a random serial
	// var serial = serial_base + (Math.floor(Math.random() * SIZES[0]) + 1000).toString() 
	console.log("in sendCmdSequence", serial);
	
	var post_options = { 
		host: hostname,
	    path: '/api/rest/json/?method=robot.get_profile_details2',
		method: 'POST',
	    headers: {
	      'Content-Type': 'application/x-www-form-urlencoded'
	    }
	}
	var post_req;
	var post_body;
	var response;
	
	// get the robot details (cleaning Command)
	post_req = http.request(post_options, function(res) {
	    res.setEncoding('utf8');

		res.on('error', function(e) {
		    console.error(e)
		});

		// getting the JSON response for robot details
	    res.on('data', function (reply) {
			console.log(reply);
			response = JSON.parse(reply);
			var res_serial = response.result.profile_details.serial_number.value;
			//{"status":0,"result":{"success":true,"profile_details":{"name":{"value":"MEG-test","timestamp":0},"serial_number":{"value":"c87000001062","timestamp":0}}}}

			sendCmdSequence_step2(res_serial);
		});
	});
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&serial_number=' + serial;
	post_req.write(post_body);
	post_req.end();
}

// connect num clients and execute the callback cb upon completion
//
function connectThem(num, cb) {	
    var cls = [];
    var connected = 0;

	console.log('Connecting from serial ' + serial_base + (1000).toString() + ' to ' + serial_base + (num + 1000).toString() + ' ...');

	for (var i = 0; i < num; i++) {
		var serial = serial_base + (i + 1000).toString();
		var post_req;
		var post_body;
		var response;
		var chat_id, chat_pwd;

		// get the robot details including jabberid and password
		post_req = http.request(post_options, function(res) {
		    	res.setEncoding('utf8');

			res.on('error', function(e) {
			    console.error(e)
			});

			// getting the JSON response for robot details
		    	res.on('data', function (reply) {
		        	// console.log('Response: ' + reply);
				// assume all the response is received in one chunk
		        	response = JSON.parse(reply);
				// get chat id and password
				chat_id = response.result.chat_id;
				chat_pwd = response.result.chat_pwd;
				console.log(chat_id, chat_pwd);

				// log into the Jabber XMPP server
				var client = new xmpp.Client({
				    jid: chat_id, 
				    password: chat_pwd, 
					host: jabber_host
				});
				
				// XMPP login successful
				client.on('online', function() {
//				    console.log(client.jid + ' online');
				    // client.send('<presence/>');
				    client.send(new xmpp.Element('presence', { })
				      .c('show').t('chat').up()
				      .c('status').t('Happily connected')
				    );
					
					cls.push(client);
					connected++;
					if (connected == num)
						// all clients are connected
						cb(null, cls);
				});

				// XMPP message received   
				client.on('stanza', function(stanza) {
					// received XMPP ROBOT_PROFILE_DATA_CHANGED 
				    if (stanza.is('message') &&
				      // never reply to errors
				      (stanza.attrs.type !== 'error')) {
					    var res = stanza.toString();
					    console.log(res);
					    // extract the 12 characters serial number starting with c8700
					    var p = res.indexOf("c8700");
					    sendCmdSequence(res.substring(p, p + 12));
				    }
				});

				client.on('error', function(e) {
		    		    console.log("Error in xmpp.", e);
				});

		    });
		});
		// to deal with  ECONNRESET when the server closes the connection for overload
		post_req.on('error', function() {
		    console.log("Error in http request.");
		});

		post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&serial_number=' + serial;
		post_req.write(post_body);
		post_req.end();
	} // for
}

// perform a series sequential connections
async.forEachSeries(SIZES, function(n, cb) {
	console.log('Connecting', n, 'clients');
    var t1 = getNow();
    connectThem(n, function(e, clients) {
	 	console.log('Connected ', n, 'in', getNow() - t1, 'ms\r\n');
	});
});

// idle waiting to receive XMPP attention messages
//
function idle() {
   setTimeout(idle, 3600 * 1000);
};

setTimeout(idle, 3600 * 1000);
