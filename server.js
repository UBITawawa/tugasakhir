var Server = require('node-ssdp').Server
, server = new Server({
	location: require('ip').address()
});

server.addUSN('upnp:rootdevice');
server.addUSN('urn:schemas-upnp-org:device:Presentation:1');

server.on('advertise-alive', function(){

});

server.on('advertise-bye', function(){
	console.log('bye');
});

server.start('0.0.0.0');//Ganti dengan server 10.10.101.202

process.on('exit', function(){
	server.stop()
});
