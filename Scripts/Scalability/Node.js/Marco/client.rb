#
# Simple robot traffic simulator for sending cleaning command.
#
# @usage: ruby client.rb <robot_serial> <user_email>
#
#
require 'rubygems'
require 'net/http'
require 'json'
require 'blather/client'
$stdout.sync = true

BASE_URL = "http://staging-smartapp.neatorobotics.com/api/rest/json/?method="

if ARGV.length < 2
        puts "usage: ruby client.rb <robot_serial> <user_email> "
        exit
end

# globals
@serial = ARGV[0]
@email = ARGV[1]

#
# send HTTP POST to the server
#
# @param method - the method's name
# @param body - the POST body other than the api_key and serial
# @return - the result body
#
def sendCmd(method, body)
  puts method
	uri = URI(BASE_URL + method)

	req = Net::HTTP::Post.new(uri.request_uri)
	http = Net::HTTP.new(uri.host, uri.port)
  req.body = "api_key=1e26686d806d82144a71ea9a99d1b3169adaad917&serial_number=" + @serial + "&" + body
  res = http.request(req)
  res.body
end

# TODO: check the robot is associated to the specified user
#

# get robot details
# 
# {"status":0,"result":{"id":"936","name":"MEG-1","serial_number":"c80000000001","chat_id":"1396542294_robot@rajatogo","chat_pwd":"1396542294_robot","users":[{"id":"682","name":"Marco Graziano","email":"marco@visiblenergy.com","chat_id":"1395077533_user@rajatogo"}]}}
res = sendCmd("robot.get_details", "a=a")
details = JSON.parse(res)
puts details

@chat_id = details["result"]["chat_id"]
@chat_pwd = details["result"]["chat_pwd"]
puts @chat_id, @chat_pwd
 
#setup @chat_id, @chat_pwd, 'rajatogo.com' 
setup @chat_id, @chat_pwd, 'rajatogo.com'
#setup '1396542294_robot@rajatogo', 'chat_pwd":"1396542294_robot', 'rajatogo.com'
 
# callback upon receiving XMPP message
#
message :chat?, :body do |m|
  # MSG 1: S->R: attention
  start_time = Time.now
  puts "Received XMPP ROBOT_PROFILE_DATA_CHANGED"
   
  #MSG 2: R->S: get_profile_details2
  #MSG 2: S->R: cleaningCommand
  res = sendCmd("robot.get_profile_details2", "a=a")
  h = JSON.parse(res)
  puts "Received cleaning command"
  puts "success: " + h["result"]["success"].to_s
  #puts h["result"]["profile_details"]["cleaningCommand"]["value"]
  
  #MSG 3: R->S: delete_robot_profile_key2 (key=cleaningCommand)
  #MSG 3: S->R: ok
  res = sendCmd("robot.delete_robot_profile_key2", "key=cleaningCommand&cause_agent_id=" + @serial + "&notification_flag=0")
  puts "Sent delete cleaning command"
  h = JSON.parse(res)
  # puts "success: " + h["result"]["success"].to_s
  puts res
  
  #MSG 4: R->S: set_profile_details3 (profile[robotCurrentState]=10002)
  #MSG 4: S->R: ok
  res = sendCmd("robot.set_profile_details3", "source_serial_number=" + @serial  + "&cause_agent_id=" + @serial + "&notification_flag=1&profile[robotCurrentState]=10002")
  puts "Sent setrobotCurrentState"
  h = JSON.parse(res)
  puts "success: " + h["result"].to_s
  
  puts "time: " + (Time.now.to_i - start_time.to_i).to_s
end


@thread_id = Thread.new { 
  #  puts 'Sending XMPP presence'
  #  presence = Stanza::Presence.new
  
  ##  status = Stanza::Presence::Status.new 
  ##  status.priority = [-128..+127] 
  #  write_to_stream presence
  #  puts 'Sent XMPP presence'
  #  sleep 60
}
@thread_id.join

