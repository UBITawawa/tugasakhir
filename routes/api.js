var express = require('express');
var router = express.Router();
var request = require('request');
var multer = require('multer');
var path = require('path');
var redis = require('redis');
var client = redis.createClient();

//Keys
const SOUND_VOLUME = "VOLUME";
const SOUND_MUTE = "MUTE";
const PRESENTATION_ACTION = "ACTION";
const PRESENTATION_FILE = "FILE";


//router.get('/setmute/:action', function(req,res){
router.get('/index.php?fungsi=setmute&arg1=:action', function(req,res){
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


//Define config for file upload
var storage = multer.diskStorage({
	destination: function(req, file, cb){
		cb(null, 'public/uploads');
	},
	filename: function(req, file, cb){
   		cb(null, file.originalname);
	}
});
var upload = multer({
	storage: storage,
	fileFilter: function(req, file, cb){
		if (!file.originalname.match(/\.(pdf|ppt|pptx|mp4|flv)$/))
        	return cb(new Error('Only pdf/ppt/pptx/mp4/flv files are allowed!'));
   		cb(null, true);
	}
}).single('file');



router.post('/setfilepresentasi', function(req, res){
	upload(req, res, function(err){
		if(err)
			res.send({"status": false, "data":{"message": "Only pdf/ppt/pptx/mp4/flv files are allowed!"}});
		//Save file path to redis
		var filepath = req.file.path;
		client.set(PRESENTATION_FILE, filepath);
		res.send({"status": true, "data":{"message": "Success upload file", "nama_file": filepath}});
	});
});

router.get('/getfilepresentasi', function(req, res){
	//Ambil file path dari redis
	client.get(PRESENTATION_FILE, function(err, filepath){
		if(err)
			res.send({"status": false, "data":{"message": "An non-obvious error occured"}});
		res.send({"status": true, "data":{"file": filepath}});
	});
});

router.get('/setaction/:action', function(req,res){
	var action = req.params.action;
	console.log(action);
	if((action === "play" | action === "pause" | action === "stop" | action === "next" | action === "prev")){
		client.get(PRESENTATION_FILE, function(err, nama_file){
			client.set(PRESENTATION_ACTION, action);
			res.send({"status": true, "data":{"nama_file": nama_file, "action": action}});
		});
	}
	else{
		res.send({"status": false, "data":{"message": "Command yang diberikan tidak sesuai"}});
	}
});

router.get('/getaction', function(req, res){
	client.get(PRESENTATION_FILE, function(err1, nama_file){
		client.get(PRESENTATION_ACTION, function(err2, action){
			if(err1 || err2)
				res.send({"status": false, "data":{"message": "An non-obvious error occured"}});
			res.send({"status": true, "data":{"nama_file": nama_file, "action": action}});
		});
	});
});



module.exports=router;
