import requests, time

userdata = {"firstname": "John", "lastname": "Doe", "password": "jdoe123"}
while True:
	resp = requests.post('http://localhost/test/catchrequest.php', data = userdata)
	print "request sent", resp.status_code
	#print resp.text
	print resp.content
	time.sleep(1)
