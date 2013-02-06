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
table#api_entry ul{
list-style-type:circle;
}
td.entry-td{
padding-top:15px;
}
a.new-api{
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
				<th align="left"><h3>API Test Console Quick Links</h3>
				
				<th>
			
			</tr>
		</thead>
		<tr>
			<td>
				<div class='description'>
					This page is a quick link page to navigate to all the web services
					related test consoles. Please <span id='bookmarkme'>bookmark</span>
					this page for future use.
				</div>

			</td>
		</tr>

		
		<tr>
			<td class='entry-td'>
				<table id='api_entry'>
					<tr>
						<td><a href="user.php">User test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that
										are used for Users. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li><a href="user.php#Set API key">Set API key</a>
											</li>
										   <li><a href="user.php#Get API Version">Get API Version</a>
											</li>
											<li><a href="user.php#Create User">Create User</a>
											</li>
											<li><a href="user.php#Get User Auth Token">Get User Auth
													Token</a>
											</li>
											<li><a href="user.php#Set User Account Details">Set User
													Account Details</a>
											</li>
											<li><a href="user.php#Get User Account Details">Get User
													Account Details</a>
											</li>
											<li><a href="user.php#Get User associated robots">Get User
													associated robots</a>
											</li>
											<li><a href="user.php#Update User Auth Token Expiry">Update
													User Auth Token Expiry</a>
											</li>
											<li><a href="user.php#Logout User Auth Token">Logout User
													Auth Token</a>
											</li>
											<li><a href="user.php#Disassociate User from Robot or Robots">Disassociate
													User from Robot or Robots</a>
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
						<td><a href="robot.php">Robot test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that
										are used for Robots. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li><a href="robot.php#Create Robot">Create Robot</a>
											</li>
											<li><a href="robot.php#Check if robot is online" class="new-api">Check if robot is online</a>
											</li>
											<li><a href="robot.php#Set Robot Profile Details" class="new-api">Set Robot Profile Details</a>
											</li>
											<li><a href="robot.php#Get Robot Details">Get Robot Details</a>
											</li>
											<li><a href="robot.php#Get Robot associated users details">Get
													Robot associated users details</a>
											</li>
											<li><a href="robot.php#Set Robot User">Set Robot User</a>
											</li>
											<li><a href="robot.php#Disassociate Robot from User or Users">Disassociate
													Robot from User or Users</a>
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
						<td><a href="robot_map.php">Robot map test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that
										are used for Robot map. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li><a href="robot_map.php#Post robot map data">Post robot
													map data</a>
											</li>
											<li><a href="robot_map.php#Get Robot Maps">Get Robot Maps</a>
											</li>
											<li><a href="robot_map.php#Get Robot Map Data">Get Robot Map
													Data</a>
											</li>
											<li><a href="robot_map.php#Update robot map data">Update
													robot map data</a>
											</li>
											<li><a href="robot_map.php#Delete Robot Map">Delete Robot Map</a>
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
					<td><a href="robot_atlas.php">Robot atlas test console</a>
					</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td>On this page, you can test all the web services APIs that are
									used for Robot Atlas. Available methods are:</td>
							</tr>
							<tr>
								<td>
									<ul>
										<li><a href="robot_atlas.php#Add robot atlas">Add Robot Atlas</a>
										</li>
										<li><a href="robot_atlas.php#Get Robot Atlas Data">Get Robot
												Atlas Data</a>
										</li>
										<li><a href="robot_atlas.php#Get atlas grid metadata">Get
												Atlas Grid Metadata</a>
										</li>
										<li><a href="robot_atlas.php#Update or add robot atlas data">Update
												Or Add Robot Atlas Data</a>
										</li>
										<li><a href="robot_atlas.php#Delete Robot Atlas">Delete Robot
												Atlas</a>
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
						<td><a href="robot_schedule.php">Robot schedule test console</a>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>On this page, you can test all the web services APIs that
										are used for Robot Schedules. Available methods are:</td>
								</tr>
								<tr>
									<td>
										<ul>
											<li><a href="robot_schedule.php#Post Robot Schedule Data">Post
													Robot Schedule Data</a>
											</li>
											<li><a href="robot_schedule.php#Get Robot Schedules">Get
													Robot Schedules</a>
											</li>
											<li><a href="robot_schedule.php#Get Robot Schedule Data">Get
													Robot Schedule Data</a>
											</li>
											<li><a href="robot_schedule.php#Update Robot Schedule Data">Update
													Robot Schedule Data</a>
											</li>
											<li><a href="robot_schedule.php#Delete Robot Schedule Data">Delete
													Robot Schedule Data</a>
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
					<td><a href="atlas_grid_image.php">Atlas grid-image test console</a>

					</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td>On this page, you can test all the web services APIs that are
									used for grid-image. Available methods are:</td>
							</tr>
							<tr>
								<td>
									<ul>
										<li><a href="atlas_grid_image.php#Post grid image">Post Grid
												Image</a>
										</li>
										<li><a href="atlas_grid_image.php#update grid image">Update
												Grid Image</a>
										</li>
										<li><a href="atlas_grid_image.php#delete grid image">Delete
												Grid Image</a>
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
					<td><a href="robot_custom.php">Robot custom test console</a></td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td>On this page, you can test all the web services APIs that are
									used for handling custom data. Available methods are:</td>
							</tr>
							<tr>
								<td>
									<ul>
										<li><a href="robot_custom.php#Post Robot Custom Data">Post
												Robot Custom Data</a>
										</li>
										<li><a href="robot_custom.php#Get Robot Customs">Get Robot
												Customs</a>
										</li>
										<li><a href="robot_custom.php#Get Robot Custom Data">Get Robot
												Custom Data</a>
										</li>
										<li><a href="robot_custom.php#Update Robot Custom Data">Update
												Robot Custom Data</a>
										</li>
										<li><a href="robot_custom.php#Delete Robot Custom Data">Delete
												Robot Custom Data</a>
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
					<td><a href="robot_data_encode.php">Utilities</a>
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
										<li><a href="robot_data_encode.php">Base64 encoding tool</a>
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
