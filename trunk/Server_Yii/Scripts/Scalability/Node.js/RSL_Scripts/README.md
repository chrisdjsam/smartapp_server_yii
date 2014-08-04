Last Updated: 06/03/2014

This README describes what all test scripts are there and how to get them up and running.

Installation:

If you are running these scripts on a Ubuntu machine, please follow these instructions:
- Install Node by using apt-get install nodejs
- There are 2 dependencies that you can install using npm (i.e. node package manager)
- Make sure that you are in the directory where these test cases are located.
- Install XMPP client using npm install xmpp-node
- Install async processing using npm install async

Each script gives you a starting counter for the serial number. You need to have these robot serial numbers available beforehand.

--------------------------------------
marco_server_connections.js
--------------------------------------

Description:
 
	This test script does following things:
	- Starts the time counting
	- Fetches the robot details using the GetRobotDetail() API call
	- Reads the chat ID and chat password for each on of them and connects them to XMPP server using Node's XMPP client
	- Next it sends the presence packet for each of these XMPP logins
	- Stops the time counting and prints the time.
	- Logs off these XMPP logins

	If you want to run it for multiple sizes, you can add values in the SIZES[] array.

How to run?: 
	node server_connections.js

-------------------------------------- 
rsl_robot_login_and_presence_status.js
--------------------------------------

Description:
 
	This is a blocking test script that is used to keep the XMPP login on so that we can run other test cases.	
	
	- Fetches the robot details using the GetRobotDetail() API call
	- Reads the chat ID and chat password for each on of them and connects them to XMPP server using Node's XMPP client
	- It keeps sending the presence packet for each of these XMPP logins every 1 minute (configurable in the test)
	
	If you want to change the number of robots that are connected, you can add change the value in SIZES[] array.
	
	If you want to log off ALL these XMPP clients at any point of time. press CTRL+C and that would result in XMPP log off.
	

How to run?
	node rsl_robot_login_and_presence_status.js


--------------------------------------
rsl_robot_ping.js
--------------------------------------

Description:

	This script assumes that rsl_robot_login_and_presence_status.js is running.

	This scripts iterates over the serial numbers and keep sending HTTP Ping() API call every 1 minute.

	There are 3 parameters in the scripts that you can change to easily simulate the number of calls per minute:
		- size, an array that decides how many robots are there in total.
		- interval, in millisecond, that decides the frequency of the API call.
		- lot_size, an integer, that decides how many of the calls would be going at one go.

		If you have 100 robots and interval is set to 6000 and lot_size is set to 10 it means that every 6 seconds, 
		10 robots would make simultaneous Ping API call.
	
How to run?
	node rsl_robot_ping.js


--------------------------------------
rsl_robot_set_get_persistent.js
--------------------------------------

Description:

	This script assumes that rsl_robot_login_and_presence_status.js is running.

	This scripts iterates over the serial numbers and keeps sending GetRobotProfileDetail2() and SetRobotProfileDetails3().

	There are 3 parameters in the scripts that you can change to easily simulate the number of calls per minute:
		- size, an array that decides how many robots are there in total.
		- interval, in milisecond, that decides the frequency of the API call.
		- lot_size, an integer, that decides how many of the calls would be going at one go.

		If you have 100 robots and interval is set to 6000 and lot_size is set to 10 it means that every 6 seconds, 
		10 robots would make simultaneous GetRobotProfileDetail2() and SetRobotProfileDetails3() API calls.
	
How to run?
	node rsl_robot_set_get_persistent.js


--------------------------------------
rsl_robot_set_get.js
--------------------------------------

Description:

	This script assumes that rsl_robot_login_and_presence_status.js is running.
	This scripts iterates over the serial numbers and sends GetRobotProfileDetail2() and SetRobotProfileDetails3() API calls and records the time spent on making these API calls.

	
How to run?
	node rsl_robot_set_get.js