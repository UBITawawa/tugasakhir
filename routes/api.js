var express = require('express');
var router = express.Router();
var request = require('request');
var redis = require('redis');
var client = redis.createClient();

//Keys
const SOUND_VOLUME = "VOLUME";
const SOUND_MUTE = "MUTE";
const PRESENTATION_ACTION = "ACTION";
const PRESENTATION_FILE = "FILE";


router.get('/setmute/:action', function(req,res){
	/*
		action: {true, false}
		selain itu gak boleh dimasukkin
	*/
	var action = req.params.action;

	if(action == "true"){
		client.get(SOUND_MUTE, function(err, value_before){
			client.set(SOUND_MUTE, "true");
			res.send({"status": true, "data":{"status_now": true, "status_before": value_before}});
		});
		
	}
	else if(action == "false"){
		client.get(SOUND_MUTE, function(err, value_before){
			client.set(SOUND_MUTE, "false");
			res.send({"status": true, "data":{"status_now": false, "status_before": value_before}});
		});
	}
	else{
		res.send({"status": false, "data":{"message": "Command yang diberikan tidak sesuai"}});
	}
});

router.get('/setvolume/:volume', function(req,res){
	var volume = parseInt(req.params.volume,10);

	if(!isNaN(volume) && volume >= 0 || volume <= 100){
		client.get(SOUND_VOLUME, function(err, value_before){
			client.set(SOUND_VOLUME, volume);
			res.send({"status": true, "data":{"volume_now": volume, "volume_before": value_before}});
		});
	}
	else{
		res.send({"status": false, "data":{"message": "Nilai volume yang anda berikan tidak sesuai"}});
	}
});

router.get('/getsound', function(req, res){
	//Next time i'll use promise to avoid callback hell
	client.get(SOUND_VOLUME, function(err1, volume){
		client.get(SOUND_MUTE, function(err2, mute){
			if(err2 || err1)
				res.send({"status": false, "data":{"message": "An non-obvious error occured"}});			
			res.send({"status": true, "data":{"volume": volume, "mute": mute}});
		});
	});
});



router.get('/getboolean', function(req,res){
	client.get("some_key", function(err, reply){
		res.send(reply);
	});
});

router.get('/settrue', function(req,res){
	client.set("some_key","true");
	res.send('SETTRUE');
});

router.get('/setfalse', function(req,res){
	client.set("some_key","false");
	res.send('SETFalse');
});

module.exports=router;
