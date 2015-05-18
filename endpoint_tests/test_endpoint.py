import requests


def retrieve_instafish():
	url = "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php"
	payload = {'userID': '4'}

	r = requests.post(url, data=payload)

	print(r.text)

def upload_instafish():
	url = "http://45.55.190.168/Instafish/endpoints/insertRecords.php"
	payload = {'userID': '4', 'time': '9', 'date': '2015-04-01', 'fishType': 'Tuna', 'comments': 'Bosss', 'amount': '1', 'latitude': '35.233', 'longitude': '-121.212', 'weight': '12.322'}
	files = {'fileName': open('ello.jpg', 'rb')}

	r = requests.post(url, data=payload, files=files)

	print(r.text)

def user_pins():
	url = "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php"
	payload = {'userID': '4', 'thisUser': True}

	r = requests.post(url, data=payload)

	print(r.text)

def update_pin():
	url = "http://gallery-armani.codio.io:3000/Instafish/endpoints/insertRecords.php"
	payload = {'userID': '1', 'updatePin': '1', 'pinID': '1', 'time': '9', 'date': '1994-08-15', 'fishType': 'Salmon', 'comments': 'I love salmon honestly man', 'amount': '1', 'latitude': '35.233', 'longitude': '-121.212'}
	files = {'fileName': open('danieldiaz.png', 'rb')}

	r = requests.post(url, data=payload, files=files)

	print(r.text)

def delete_pin():
	url = "http://gallery-armani.codio.io:3000/Instafish/endpoints/insertRecords.php"
	payload = {'userID': '1', 'deletePin': '1', 'pinID': '5c'}

	r = requests.post(url, data=payload)

	print(r.text)

def get_average():
	url = "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php"
	payload = {'userID': '4', 'thisUserAverage': True}

	r  = requests.post(url, data=payload)

	print(r.text)

def get_all_pins():
	url = "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php"
	payload = {'userID': 4, 'mainPage': 1}

	r=requests.post(url, data=payload)

	print r.text


def main():
	upload_instafish()

main()
