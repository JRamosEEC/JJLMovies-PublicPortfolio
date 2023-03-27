//DB Connection and Parser Functions
var mysql = require('mysql');
var fs = require('fs')
var ini = require('ini')

//Parse the config for connection information
var config = ini.parse(fs.readFileSync('./../Config/dbconfig.ini', 'utf-8'))

//Create a pool for connectivity (more efficient than a single connection)
var db_connection = mysql.createPool({
  connectionLimit : 10,
  multipleStatements: true,
  host     : config.database.servername,
  port     : config.database.port,
  database : config.database.dbname,
  user     : config.database.username,
  password : config.database.password,
  debug    : false,
});

exports.db_connection = db_connection;