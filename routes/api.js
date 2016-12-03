var express = require('express');
var router = express.Router();
var request = require('request');
var redis = require('redis');
var redisClient = redis.createClient();

router.get('/getimages', function(req,res){
	res.send('OK');
});



module.exports=router;
