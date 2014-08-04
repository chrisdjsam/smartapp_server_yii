Last Updated: 06/03/2014

Date we received these test scripts: 05/06/2014

This README describes what all test scripts we received from Marco.

Installation:

If you are running these scripts on a Ubuntu machine, please follow these instructions:
- Install Node by using apt-get install nodejs
- There are 2 dependencies that you can install using npm (i.e. node package manager)
- Make sure that you are in the directory where these test cases are located.
- Install XMPP client using npm install xmpp-node
- Install async processing using npm install async

Each script gives you a starting counter for the serial number. You need to have these robot serial numbers available beforehand.

Also change the values for the following parameters:
- hostname, where the APIs are located
- jabber_host, jabber hostname
- serial_base, the starting counter for the serial number
- email_user, which is a valid user email on the SmartApp backend
- size, the number of robots that are in the system. If you want to run it for multiple sizes, you can add values in the SIZES[] array.

--------------------------------------
load_generator.js
--------------------------------------

Description:
 
	- This test script sends the setRobotProfile3 API call for the given robots.
	- This script also has hook to capture the time taken for these API calls but it is not utilized.

How to run?: 
	node load_generator.js

--------------------------------------
server_connection.js
--------------------------------------

Description:

 	- At the onset this script starts the time counter.
	- This test script first, makes the API call to get the robot details.
	- Chat ID and password is read out of the response of the above API call and it is used to XMPP login.
	- Next, it sends the presence packet for each of the robots that are connected over XMPP.
	- Once all the XMPP connections are established, it captures the time and prints the time taken.
	- At the end this script logs off all the XMPP logins.
	
How to run?: 
	node server_connection.js

--------------------------------------
server_pool_connections.js
--------------------------------------

Description:
 	- At the onset this script starts the time counter.
 	- At the onset this script starts the time counter.
	- This test script first, makes the API call to get the robot details.
	- Chat ID and password is read out of the response of the above API call and it is used to XMPP login.
	- Next it makes robot.delete_robot_profile_key2 API calls for each of these robots and responds to the XMPP messages received.

How to run?: 
	server_pool_connections.js


--------------------------------------
client.rb
--------------------------------------
@TODO: Need to understand this test case and add a write up on it.
