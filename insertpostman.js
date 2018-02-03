var mysql = require('mysql');
const express=require('express');
const bodyParser=require('body-parser');
const fileup=require("express-fileupload");
var path=require("path");
var LocalStrategy = require('passport-local').Strategy;
var cors = require('cors')
var ps=require('passport');
var mysql = require('mysql');
const { check, validationResult } = require('express-validator/check');
//var mv=require('mv');

var app = express();

app.use(bodyParser());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true, limit: '5mb' }));
app.use(bodyParser.json({limit: '1mb'}));
app.use(cors())
app.use(fileup());

app.use(express.static(__dirname + '/img'));



//app.set('views',__dirname+'/views');

app.use(require('express-session')({secret:'keyboard',resave:true,saveUninitialized:true}));
var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "LANETTEAM1",
    database: "demo"
});
con.connect();

ps.serializeUser(function(user, done) {
    done(null, user);
});

// used to deserialize the user
ps.deserializeUser(function(id, done) {
    con.query("select * from ab where id = "+id, function(err, rows) {
        done(err, rows);
    });
});

ps.use(new LocalStrategy((username,password,done) =>{
    console.log('start')
    var sql = "SELECT * FROM ab WHERE name='"+username+"' and password='"+password+"'";
    console.log(sql);
    con.query(sql,function(err, rows) {
        if (err)
            return done(err);
        if (!rows.length) {
            return done(null, false, {message: 'Wrong user'});
        }
        return done(null,rows);
    });
}));

app.use(ps.initialize());
app.use(ps.session());

app.get('/login',(req,res)=>{
    // res.render('login');
    // res.sendFile(__dirname+"/views/login.html");
    res.send('invalid');
    // res.send("Hallo")
})

app.post('/login',ps.authenticate('local',{failureRedirect:'/login'}),(req,res)=>{
    // res.sendFile(__dirname+"/views/home.html");
    console.log('success');
    res.send('success');
})



app.post('/insert',[check('name').isLength({min : 3})],(req,res,errors)=>{
     errors = validationResult(req);
    //console.log(errors);
    if (!errors.isEmpty()) {
        console.log('must be 5');
        return res.send({errors:errors});
        //console.log('must be 5');
    }

    if(req.files.sample==undefined)
        return res.status(400).send('no found');

    var f=req.files.sample;
  //  console.log(f);
    // let uploadpath=path.join("/var/www/html/img/"+f.name);
    var uploadpath=path.join(__dirname+"/img/"+f.name);
   // console.log(json(f.name));
 //   console.log(uploadpath);

    f.mv(uploadpath);
   // res.status(200).send('success');
//    console.log(uploadpath);


     var s1="insert into ab(name,password,city,image)values('"+req.body.name+"','"+req.body.password+"','"+req.body.city+"','"+f.name+"')";
     console.log(s1);
    con.query(s1,(err)=>{
         console.log('callback function start');
        if(err) {
            console.log('Error', err);
        } else {
            res.send('inserting');
        }
        console.log('callback function end');
    });
    console.log("insert");
    //res.send('inserting');


});
app.get('/images', (req, res) => {

    var ss="select * from ab";
    con.query(ss,(err,rows)=>{
        console.log('success');
        let uploadpath=path.join(__dirname+"/img/");
        console.log(uploadpath+" "
            +rows);
        res.send({rows:rows,path:uploadpath});
    });
})
app.get('/show',(req,res)=>{


    //     var ak="SELECT * FROM ab";
    // con.query(ss,(err,rows)=>{
    //     console.log('success');
    //     // let uploadpath=path.join("/var/www/html/img/");
    //     let uploadpath=path.join(__dirname+"/img/");
    //     console.log(uploadpath+" "
    //         +rows);
    //     res.send({rows:rows,path:uploadpath});
    // });
             con.query("SELECT * FROM ab", function (err, result, fields) {
            if (err) throw err;
          //  console.log(result);
          //  var out=table(result);
           // console.log(out);
            res.send(result);

        })


});

app.get('/del',(req,res)=>{
    var id=req.query.id;
    con.query("delete from ab where id='"+id+"'", function (err, result) {
        if (err) throw err;
        console.log("deleted");
        //  var out=table(result);
         //console.log(result);
       res.redirect('/show');

    })


});


app.post('/update',(req,res)=>{
    var id=req.query.id;
    var name=req.body.name;
    var city=req.body.city;
    console.log(name);
    var que="update ab set name='"+name+"',city='"+city+"' where id='"+id+"'";
    console.log(que);
    con.query(que,function (err,result) {
       // console.log(query);
        if(err)throw err;
        console.log(err);
        res.send(result);
    })
})

app.get('/sel',(req,res)=>{
    var id=req.query.id;
    var qu="select * from ab where id='"+id+"'";
    console.log(qu);
    con.query(qu,function (err,result) {
        console.log(result);
        res.send(result);

    })
})

app.listen(4000);