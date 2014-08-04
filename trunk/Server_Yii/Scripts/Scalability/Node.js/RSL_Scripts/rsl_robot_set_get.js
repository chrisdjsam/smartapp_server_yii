'use strict';

var async = require('async')
  , xmpp = require('node-xmpp')
  , http = require('http')


var version_number = "V.0.0.1";

var hostname = 'neatodev.rajatogo.com';
var jabber_host = 'neatodev.rajatogo.com';

var serial_base = "c8700000";

var SIZES = [1000];

var lot_size = 1;
var interval = 30000;
var start = 0;
var end = 0;
var start_from = 1000;
var startTime = 0;

var enable_chunk = false;
var getComplete = false;
var setComplete = false;

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

function set_profile_details3(serial, lastCall) {

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
			console.log(reply);
			if(lastCall){
				setComplete = true;
				printIfCompletedAllCalls();
			}			
		});
	});
	
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&' + '&serial_number=' + serial;
	post_body += '&source_serial_number='+serial;
	post_body += '&cause_agent_id=' + uuid;
	post_body += '&value_extra=&notification_flag=1&profile[cleaningCommand]=' + xml_cmd;	
	post_req.write(post_body);
	post_req.end();
}

function get_profile_details2(serial, lastCall){
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
			console.log(reply);
			if(lastCall){
				getComplete = true;
				printIfCompletedAllCalls();
			}			
		});
	});
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&serial_number=' + serial;
	post_req.write(post_body);
	post_req.end();
}

function printIfCompletedAllCalls(){
	if(getComplete && setComplete){
	    console.log('printIfCompletedAllCalls ', end, 'in', getNow() - startTime, 'ms\r\n');
	}
}

function performSetGetOp() {
	startTime = getNow();
	var lastCall = false;
	console.log('Calling get and set web service using clients having serial number in range ', serial_base + (start_from+start).toString(), '-', serial_base + (start_from+end).toString(), '...');
	
	for(var i=start; i<end; i++){
		if(i == (end-1)){
			lastCall = true;
		}
		var serial = serial_base + (i + start_from).toString();
		set_profile_details3(serial, lastCall);
		get_profile_details2(serial, lastCall);
	} // for
}

//perform a series sequential connections cycling through the values of SIZES
async.forEachSeries(SIZES, function(n, cb) {
	console.log("Version Number :: " + version_number);
	
	if(enable_chunk){
	    var chunk = parseInt(n/lot_size);
	    end = chunk;
	    performSetGetOp();
	    setInterval(function(){
	 	   	start = end;
	 		end = end+chunk;
	    	if (start == n){
		 	   	start = 0;
		 		end = chunk;
	    	}
	    	performSetGetOp();
	    }, interval);
	}else{
	    end = n;
	    performSetGetOp();
	}

});