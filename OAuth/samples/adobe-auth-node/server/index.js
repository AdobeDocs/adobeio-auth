const express = require('express');
const app = express();
const session = require('express-session')
const { default: axios } = require('axios');

/* Dependencies for setting up https server */
const https = require('https');
const fs = require('fs');
const path = require('path');

/* Declare host name and port */
const hostname = 'localhost';
const port = 8000;

/* Variables needed for authorization */
const scopes = 'openid,creative_sdk,profile,address,AdobeID,email,offline_access' 
const redirect_uri = 'https://localhost:8000/callback/'

/* Configuring client application credentials stored in .env file */
require('dotenv').config();
const adobeApiKey = process.env.API_KEY;
const adobeApiSecret = process.env.API_SECRET;

/* Middlewares */
app.use(express.static(path.join(__dirname, '../public')));
app.set('views', path.join(__dirname, '../views'))
app.set('view engine', 'pug')
app.use(session({
	secret: 'secret-value-123',
    resave: false,
    saveUninitialized: true,
    cookie: { 
        maxAge: 6000000
    }
}));

/* Routes */
app.get('/', function (req, res) {
	res.render('index');
})

app.get('/login', function(req, res){
	/* This will prompt user with the Adobe auth screen */
	res.redirect(`https://ims-na1.adobelogin.com/ims/authorize/v2?client_id=${adobeApiKey}&scope=${scopes}&response_type=code&redirect_uri=${redirect_uri}`)
})

app.get('/callback', async function(req, res){
	/* Retrieve authorization code from request */
	let code = req.query.code;
	let uri = `https://ims-na1.adobelogin.com/ims/token/v3?grant_type=authorization_code&client_id=${adobeApiKey}&client_secret=${adobeApiSecret}&code=${code}`;
	axios.post(uri)
		.then(function(response){
			req.session.token = response.data.access_token;
			res.render('index', {'response': 'User logged in!'});
		})
		.catch(function (error){
			console.log(error);
			res.render('index', {'response':'Log in failed!'})
		})
})

app.get('/profile', async function(req, res){
	let uri = `https://ims-na1.adobelogin.com/ims/userinfo/v2?client_id=${adobeApiKey}`;
	let options = {
		headers: {
			"x-api-key": process.env.API_KEY,
			Authorization: `Bearer ${req.session.token}`,
    },};
	if (req.session.token) {
		axios.get(uri, options)
			.then(function (response){
				res.render('index', {'response': JSON.stringify(response.data)});
			})
			.catch(function (error){
				console.log(error);
			})
	}
	else{
		res.render('index', {'response':'You need to log in first'});
	}	
})

app.get('/logout', function(req, res){
	if (req.session) {
		req.session.destroy(err => {
			if (err){
				res.render('index', {'response': 'Unable to log out'});
			} else{
				res.render('index', {'response': 'Logout successful!'})
			}
		});
	} else {
		res.render('index', {'response': 'Token not stored in session'})
	}
});


/* Set up a HTTPS server with the signed certification */
var httpsServer = https.createServer({
	key: fs.readFileSync(path.join(__dirname,'./localhost-key.pem')),
	cert: fs.readFileSync(path.join(__dirname, './localhost.pem'))
}, app).listen(port, hostname, (err) => {
	if (err) console.log(`Error: ${err}`);
	console.log(`listening on port ${port}!`);
});