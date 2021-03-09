const mysql = require('mysql2');

const MODE = process.env.NODE_ENV; // development or production

const devOption = {
  host: 'localhost',
  user: 'admin',
  password: 'roqkftjqjelql!@34',
  database: 'html',
  supportBigNumbers: true,
  bigNumberStrings: true,
};

const prodOption = {
  /*
  host: 'localhost',
  user: 'sbdb',
  password: 'tnpdjqlcm!@34',
  database: 'html',
  supportBigNumbers: true,
  bigNumberStrings: true,
  */
};

module.exports = mysql.createPool(
  MODE === 'development' ? devOption : prodOption,
);
