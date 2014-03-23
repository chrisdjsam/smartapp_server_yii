<html>
<head>
<title>Web Service Test Console</title>
</head>
<style type="text/css">
.center {
	margin-left: auto;
	margin-right: auto;
	width: 100%;
}

div.description {
	font-style: italic;
}

table#api_entry {
	border: 1px solid green;
	width: 100%;
	background-color: #FCF7F9;
}

table#api_entry ul {
	list-style-type: circle;
}

td.entry-td {
	padding-top: 15px;
}

a.new-api {
	color: red;
	background-image: url(new.jpg);
	background-repeat: no-repeat;
	background-position: top right;
	padding-right: 50px;
}
</style>
<body>
	<table class="center">
		<thead>
			<tr>
				<th align="left">
					<h3>API Test Console Quick Links</h3>
				
				
				<th>
			
			</tr>
		</thead>
		<tr>
			<td>
				<div class='description'>
					This page is a quick link page to navigate to all the web services related test consoles. Please
					<span id='bookmarkme'>bookmark</span>
					this page for future use.
				</div>
			</td>
		</tr>
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td>
							<a href="user.php">User test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that are used for Users. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li>
												<a href="user.php#Set API key">Set API key</a>
											</li>
											<li>
												<a href="user.php#Get API Version">Get API Version</a>
											</li>
											<li>
												<a href="user.php#Check For Upgrades">Check For Upgrades</a>
											</li>
											<li>
												<a href="user.php#Create User">Create User</a>
											</li>
											<li>
												<a href="user.php#Get User Auth Token">Get User Auth Token</a>
											</li>
											<li>
												<a href="user.php#Change Password">Change Password</a>
											</li>
											<li>
												<a href="user.php#Forget Password">Forget Password</a>
											</li>
											<li>
												<a href="user.php#Set User Account Details">Set User Account Details</a>
											</li>
											<li>
												<a href="user.php#Get Country Code Details" class="new-api">Get Country Code Details</a>
											</li>
											<li>
												<a href="user.php#Get User Account Details">Get User Account Details</a>
											</li>
											<li>
												<a href="user.php#Set Attributes">Set Attributes</a>
											</li>
											<li>
												<a href="user.php#Get Attributes">Get Attributes</a>
											</li>
											<li>
												<a href="user.php#Get User associated robots">Get User associated robots</a>
											</li>
											<li>
												<a href="user.php#Update User Auth Token Expiry">Update User Auth Token Expiry</a>
											</li>
											<li>
												<a href="user.php#Logout User Auth Token">Logout User Auth Token</a>
											</li>
											<li>
												<a href="user.php#Disassociate User from Robot or Robots">Disassociate User from Robot or Robots</a>
											</li>
											<li>
												<a href="user.php#Create User 2">Create User 2</a>
											</li>
											<li>
												<a href="user.php#Is User Validated">Is User Validated?</a>
											</li>
											<li>
												<a href="user.php#Resend Validation Email">Resend Validation Email</a>
											</li>
											<li>
												<a href="user.php#Get Error Code">Get Error Detail By Error Code</a>
											</li>
											<li>
												<a href="user.php#Create User 3" class="new-api">Create User 3</a>
											</li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td>
							<a href="robot.php">Robot test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that are used for Robots. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li>
												<a href="robot.php#Create Robot">Create Robot</a>
											</li>
											<li>
												<a href="robot.php#Create Robot 2" class="new-api">Create Robot 2</a>
											</li>
											<li>
												<a href="robot.php#Check if robot is online">Check If Robot Is Online</a>
											</li>
											<li>
												<a href="robot.php#Set Robot Profile Details 3" class="new-api">Set Robot Profile Details 3</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Profile Details">Get Robot Profile Details</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Profile Details 2" class="new-api">Get Robot Profile Details 2</a>
											</li>
											<li>
												<a href="robot.php#Delete Robot Profile Key 2" class="new-api">Delete Robot Profile Key 2</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Details">Get Robot Details</a>
											</li>
											<li>
												<a href="robot.php#Get Robot associated users details">Get Robot Associated Users Details</a>
											</li>
											<li>
												<a href="robot.php#Set Robot User">Set Robot User</a>
											</li>
											<li>
												<a href="robot.php#Disassociate Robot from User or Users">Disassociate Robot from User or Users</a>
											</li>
											<li>
												<a href="robot.php#Delete Robot">Delete Robot</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Presence Status">Get Robot Presence Status</a>
											</li>
											<li>
												<a href="robot.php#Ping From Robot">Ping From Robot</a>
											</li>
											<li>
												<a href="robot.php#Is Robot Online Virtual">Is Robot Online Virtual</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Type Metadata Using Robot Type">Get Robot Type Metadata Using Robot Type</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Type Metadata Using Robot Id">Get Robot Type Metadata Using Robot Id</a>
											</li>
											<li>
												<a href="robot.php#Set Robot Configuration">Set Robot Configuration</a>
											</li>
											<li>
												<a href="robot.php#Set Robot Configuration 2" class="new-api">Set Robot Configuration 2</a>
											</li>
											<li>
												<a href="robot.php#Get Robot Configuration">Get Robot Configuration</a>
											</li>
											<li>
												<a href="robot.php#Clear Robot Data">Clear Robot Data</a>
											</li>
											<li>
												<a href="robot.php#Request Link Code">Request Link Code</a>
											</li>
											<li>
												<a href="robot.php#Initiate Link To Robot">Initiate Link To Robot</a>
											</li>
											<li>
												<a href="robot.php#Confirm Linking">Confirm Linking</a>
											</li>
											<li>
												<a href="robot.php#Reject Linking">Reject Linking</a>
											</li>
											<li>
												<a href="robot.php#Cancel Linking">Cancel Linking</a>
											</li>
											<li>
												<a href="robot.php#Link To Robot" class="new-api">Link To Robot</a>
											</li>
											<li>
												<a href="robot.php#Robot Health Check responder" class="new-api">Respond to Robot's Health Check</a>
											</li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td>
							<a href="messages.php">Message test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that are used for messages in user and robot. Available methods
										are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li>
												<a href="messages.php#Send Xmpp Message To Robot">Send Xmpp Message To Robot</a>
											</li>
											<li>
												<a href="messages.php#Send Message To Associated Users">Send Message To Associated Users</a>
											</li>
											<li>
												<a href="messages.php#Send XMPP Message To All Associated Users 2" class="new-api">Send XMPP Message To All Associated
													Users 2</a>
											</li>
											<li>
												<a href="messages.php#Send notification to given emails">Send notification to given emails</a>
											</li>
											<li>
												<a href="messages.php#Send notification to given Registration IDs">Send notification to given Registration IDs</a>
											</li>
											<li>
												<a href="messages.php#Store Registration Id To Send Notification For Given User">Register For Notification</a>
											</li>
											<li>
												<a href="messages.php#Remove Notification Registration Id">Unregister From Notification</a>
											</li>
											<li>
												<a href="messages.php#Set User Push Notification Options">Set User Push Notification Options</a>
											</li>
											<li>
												<a href="messages.php#Get User Push Notification Options">Get User Push Notification Options</a>
											</li>
											<li>
												<a href="messages.php#Send notification to all the users of robot2" class="new-api">Send notification to all the users
													of robot2</a>
											</li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='entry-td'></td>
		</tr>
		<tr>
			<td class='entry-td'></td>
		</tr>
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td>
							<a href="robot_schedule.php">Robot schedule test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that are used for Robot Schedules. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li>
												<a href="robot_schedule.php#Post Robot Schedule Data">Post Robot Schedule Data</a>
											</li>
											<li>
												<a href="robot_schedule.php#Get Robot Schedules">Get Robot Schedules</a>
											</li>
											<li>
												<a href="robot_schedule.php#Get Robot Schedule Data">Get Robot Schedule Data</a>
											</li>
											<li>
												<a href="robot_schedule.php#Update Robot Schedule Data">Update Robot Schedule Data</a>
											</li>
											<li>
												<a href="robot_schedule.php#Delete Robot Schedule Data">Delete Robot Schedule Data</a>
											</li>
											<li>
												<a href="robot_schedule.php#Get Schedule Based On Type" class="new-api">Get Schedule Based On Type</a>
											</li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='entry-td'></td>
		</tr>
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td>
							<a href="robot_custom.php">Robot custom test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that are used for handling custom data. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li>
												<a href="robot_custom.php#Post Robot Custom Data">Post Robot Custom Data</a>
											</li>
											<li>
												<a href="robot_custom.php#Get Robot Customs">Get Robot Customs</a>
											</li>
											<li>
												<a href="robot_custom.php#Get Robot Custom Data">Get Robot Custom Data</a>
											</li>
											<li>
												<a href="robot_custom.php#Update Robot Custom Data">Update Robot Custom Data</a>
											</li>
											<li>
												<a href="robot_custom.php#Delete Robot Custom Data">Delete Robot Custom Data</a>
											</li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td>
							<a href="robot_data_encode.php">Utilities</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>Utilities to help user's of API's</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li>
												<a href="robot_data_encode.php">Base64 encoding tool</a>
											</li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
