var Server = require('node-ssdp').Server
, server = new Server();

server.addUSN('upnp:rootdevice');
server.addUSN('urn:schemas-upnp-org:device:Presentation:1');

server.on('advertise-alive', function(){
	
});

server.on('advertise-bye', function(){

});

server.start('0.0.0.0');

process.on('exit', function(){
	server.stop()
});
