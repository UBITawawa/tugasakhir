var Client = require('node-ssdp').Client
   , client = new Client();

var npmredis = require('redis');
var redis = npmredis.createClient();

client.on('response', function (headers, code, rinfo) {
 
	/*
	* Saya ambil identifier dari USN
	* Most Likely bentuk stringnya seperti dibawah
	* urn:schemas-upnp-org:device:Presentation:1
	* Saya split by ':', lalu saya ambil indeks ke 3
	*/
	var arr = headers.ST.split(":").map(function (val) {
	  		return val;
	});
	var identifier = arr.length > 2? arr[3]:'root';
	var location = headers.LOCATION; //Base_URL
	var statusCode = code;

  	redis.hmset(identifier, 'location', location, 'statusCode', statusCode);
	console.log('found service: ' + identifier + ' in location: ' + location + ' and status code: ' + statusCode);
})


client.search('ssdp:all');

console.log('searching');

setTimeout(function() {
  client.search('ssdp:all')
}, 5000);//Searching every 5 sec
