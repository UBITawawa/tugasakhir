var express = require('express');
var bodyParser = require('body-parser');
var redis = require('redis');
var api = require('./routes/api');
var app = express();

app.set('views',__dirname+'/views');
app.set('view engine', 'ejs');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended:false}));
app.use(express.static(__dirname+'/public'));
app.engine('html', require('ejs').renderFile);
app.set('view engine', 'html');

app.get('/', function(req,res){
	//res.send('ubi');
  res.render('index.html');
});
app.get('/simulator', function(req,res){
  //res.send('ubi');
  res.render('simulator.html');
});
//Implement api service di /routes/api.js
app.use('/api',api);

app.listen(6969);
