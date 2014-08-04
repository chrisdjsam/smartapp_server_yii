/*
 * Test Neato SmartApp Server 
 *
 * Start a number of client connections to the SmartApp server.
 *
 * Usage: node server_connections.js
 *
 * Tune the test by setting the number of iterations and connections in the SIZES[] array.
 * The script performs one iteration for each element of the array, connecting the specified number of clients.
 *
 */

'use strict';

var async = require('async')
  , xmpp = require('node-xmpp')
  , http = require('http')

var hostname = 'staging-smartapp.neatorobotics.com';
var jabber_host = 'staging-smartapp.neatorobotics.com';
var serial_base = "c8700000";
var SIZES = [5, 10, 50, 100, 200, 500, 750, 1000];

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

// connect num clients and execute the callback cb upon completion
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

			// getting the JSON response for robot details
		    res.on('data', function (reply) {
		        // console.log('Response: ' + reply);
				// assume all the response is received in one chunk
		        response = JSON.parse(reply);
				// get chat id and password
				chat_id = response.result.chat_id;
				chat_pwd = response.result.chat_pwd;
//				console.log(chat_id, chat_pwd);

				// log into the Jabber server
				var client = new xmpp.Client({
				    jid: chat_id, 
				    password: chat_pwd, 
					host: jabber_host
				});
				
				// login successful
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

// perform a series sequential connections cycling through the values of SIZES
async.forEachSeries(SIZES, function(n, cb) {
	console.log('Connecting', n, 'clients');
    var t1 = getNow();
    connectThem(n, function(e, clients) {
 	    console.log('Connected ', n, 'in', getNow() - t1, 'ms\r\n');
	    // close the clients and iterate to the next series
	    async.forEachSeries(clients, function(cl, cb2) {
            cl.on('close', function() {
                cb2();
            });
            cl.end();
        }, cb);
	});
});
