const mysql = require('mysql2');
const admin = require('./admin');

const MODE = process.env.NODE_ENV; // development or production

const devOption = {
  host: '178.128.214.133',
  user: admin.dbUser,
  password: admin.dbPassword,
  database: 'tellusart',
  supportBigNumbers: true,
  bigNumberStrings: true,
};

const prodOption = {
  host: 'localhost',
  user: admin.dbUser,
  password: admin.dbPassword,
  database: 'tellusart',
  supportBigNumbers: true,
  bigNumberStrings: true,
};

module.exports = mysql.createPool(
  MODE === 'development' ? devOption : prodOption,
);
