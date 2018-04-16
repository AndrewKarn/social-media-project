/*
*  This script is used to create the database and collections for mongo.
*/

var db = connect('127.0.0.1:27017/main');
db.createCollection('users');
db.createCollection('conversations');
var users = db.getCollection('users');