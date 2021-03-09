const mysql = require('mysql2');

require('dotenv').config();

module.exports = mysql.createPool({
  host: process.env.DB_HOST_LOCAL,
  user: process.env.DB_USER_LOCAL,
  database: process.env.DB_NAME_LOCAL,
  password: process.env.DB_PASS_LOCAL,
  supportBigNumbers: true,
  bigNumberStrings: true,
});
