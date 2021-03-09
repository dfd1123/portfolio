const mysql = require('mysql2');

require('dotenv').config();

module.exports = mysql.createPool({
  host: process.env.DB_HOST_EXCHANGE,
  user: process.env.DB_USER_EXCHANGE,
  database: process.env.DB_NAME_EXCHANGE,
  password: process.env.DB_PASS_EXCHANGE,
  supportBigNumbers: true,
  bigNumberStrings: true,
});
