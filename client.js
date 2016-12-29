var Client = require('node-ssdp').Client
   , client = new Client();

client.on('response', function (headers, statusCode, rinfo) {
	console.log('Got a response to an m-search.');
});

client.on('response', function inResponse(headers, code, rinfo) {
  console.log('Got a response to an m-search:\n%d\n%s\n%s', code, JSON.stringify(headers, null, '  '), JSON.stringify(rinfo, null, '  '))
})

// Or get a list of all services on the network
client.search('ssdp:all');

// Or maybe if you want to scour for everything after 5 seconds
setTimeout(function() {
  client.search('ssdp:all')
}, 5000)

// And after 10 seconds, you want to stop
// setTimeout(function () {
//   client.stop()
// }, 10000)