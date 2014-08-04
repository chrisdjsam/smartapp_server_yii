'use strict';

var async = require('async')
  , xmpp = require('node-xmpp')
  , http = require('http')

var version_number = "V.0.0.1";

var hostname = 'neatodev.rajatogo.com';	// Change the hostname against which you want to run this test case against
var jabber_host = 'neatodev.rajatogo.com'; // Change it to the ejabberd hostname

var serial_base = "c8700000";
var web_service = 'set_profile_details3';

var SIZES = [500]; // You can add more entries here if you want to run it for different number of robots.

var lot_size = 20;
var interval = 30000;
var start_from = 1000;

var start = 0;
var end = 0;

http.globalAgent.maxSockets = 9999999;

// return current time in milliseconds
function getNow() {
    return new Date().getTime()
}

//generate a UUID
function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
         s4() + '-' + s4() + s4() + s4();
}

function set_profile_details3(serial) {

	var post_options = { 
		host: hostname,
	    path: '/api/rest/json/?method=robot.set_profile_details3',
		method: 'POST',
	    headers: {
	      'Content-Type': 'application/x-www-form-urlencoded'
	    }
	}
	var post_req;
	var post_body;
	var response;
	var uuid;
	
	// prepare the command for the robot in XML
	var xml_cmd = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>';

	// add the command code
	xml_cmd += '101';
	xml_cmd += '</command><requestId>';

	// generate a UUID
	uuid = guid();
	xml_cmd += uuid;
	
	// set the timestamp
	xml_cmd += '</requestId><timeStamp>'
	xml_cmd += getNow().toString();
	
	// complete the xml command
	xml_cmd += '</timeStamp><retryCount>0</retryCount><responseRequired>false</responseRequired><distributionMode>2</distributionMode><replyTo>9</replyTo><params><cleaningModifier>1</cleaningModifier><cleaningMode>1</cleaningMode><cleaningCategory>2</cleaningCategory></params></request></payload></packet>';

	post_req = http.request(post_options, function(res) {
		res.setEncoding('utf8');

		res.on('error', function(e) {
		    console.error(e)
		});

		// getting the JSON response for robot details
    	res.on('data', function (reply) {
    		//	console.log(reply);  // uncomment if you want to view response
		});
	});
	
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&' + '&serial_number=' + serial;
	post_body += '&source_serial_number='+serial;
	post_body += '&cause_agent_id=' + uuid;
	post_body += '&value_extra=&notification_flag=1&profile[cleaningCommand]=' + xml_cmd;	
	post_req.write(post_body);
	post_req.end();
}

function get_profile_details2(serial){
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
	post_req = http.request(post_options, function(res) {
		res.setEncoding('utf8');

		res.on('error', function(e) {
		    console.error(e)
		});

		// getting the JSON response for robot details
	    res.on('data', function (reply) {
			//console.log(reply); // uncomment if you want to view response
		});
	});
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&serial_number=' + serial;
	post_req.write(post_body);
	post_req.end();
}

function performSetGetOp() {
	console.log('Calling', web_service, 'web service using clients having serial number in range ', serial_base + (start_from+start).toString(), '-', serial_base + (start_from+end).toString(), '...');
	for(var i=start; i<end; i++){
		var serial = serial_base + (i + start_from).toString();
		eval(web_service+'("'+serial+'")');
	}
}

//perform a series sequential connections cycling through the values of SIZES
async.forEachSeries(SIZES, function(n, cb) {
	console.log("Version Number :: " + version_number);
    var chunk = parseInt(n/lot_size);
    end = chunk;
    performSetGetOp(web_service);
    setInterval(function(){
 	   	start = end;
 		end = end+chunk;
    	if (start == n){
	 	   	start = 0;
	 		end = chunk;
    	}
    	if(web_service == 'set_profile_details3'){
    		web_service = 'get_profile_details2';
    	}else {
    		web_service = 'set_profile_details3';
    	}
    	performSetGetOp();
    }, interval);
});
