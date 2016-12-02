var express = require('express');
var router = express.Router();
var request = require('request');

router.get('/getimages', function(req,res){
	res.send('OK');
});

module.exports=router;
