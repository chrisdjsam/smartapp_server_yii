var http = require('http');

var bucket_size = 100; //250;
var hostname = 'staging-smartapp.neatorobotics.com';
var jabber_host = 'staging-smartapp.neatorobotics.com';
var serial_base = "c8700000";
var email_user = "marco@visiblenergy.com";

// number of iterations
var SIZES = [200];

http.globalAgent.maxSockets = 9999999;

// return current time in milliseconds
function getNow() {
    return new Date().getTime()
}

// generate a UUID
function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
         s4() + '-' + s4() + s4() + s4();
}

// send a HTTP command to initiate a start cleaning command
function sendCmd(num) {
	var serial = serial_base + (num + 1000).toString();
	
	console.log("in sendCmd", serial);

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
	xml_cmd += 'start';
	xml_cmd += '</command><requestId>';

	// generate a UUID
	uuid = guid();
	xml_cmd += uuid;
	
	// set the timestamp
	xml_cmd += '</requestId><timeStamp>'
	xml_cmd += getNow().toString();
	
	// complete the xml command
	xml_cmd += '</timeStamp><retryCount>0</retryCount><responseRequired>false</responseRequired><distributionMode>2</distributionMode><replyTo>9</replyTo><params><cleaningModifier>1</cleaningModifier><cleaningMode>1</cleaningMode><cleaningCategory>2</cleaningCategory></params></request></payload></packet>';

	// get the robot details (cleaning Command)
	post_req = http.request(post_options, function(res) {
	    res.setEncoding('utf8');

		res.on('error', function(e) {
		    console.error(e)
		});

		// getting the response 
	    res.on('data', function (reply) {
			console.log(reply);
		});
		
		
	});
	
	post_body = 'api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&' + '&serial_number=' + serial;
	post_body += '&source_serial_number=&source_smartapp_id=' + email_user;
	post_body += '&cause_agent_id=' + uuid;
	post_body += '&value_extra=&notification_flag=1&profile[cleaningCommand]=' + xml_cmd;
 	
    // to deal with  ECONNRESET when the server closes the connection for overload
       post_req.on('error', function() {
           console.log("Server closed connection for serial ", serial);
       });

	post_req.write(post_body);
	post_req.end();	
}

var k = 0;	// eventually useful for an outer loop
var start_time = getNow();

for (var i = 0; i < SIZES[k]; i++) {
	console.log(i);
	
	// get a random serial number 
	var serial  = Math.floor(Math.random() * bucket_size);
	sendCmd(serial);
	
	// throttling
	// setTimeout(sendCmd, i*100, serial);	
}

