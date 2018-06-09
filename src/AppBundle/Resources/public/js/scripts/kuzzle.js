// import 'kuzzle-sdk';
var readYml = require('read-yaml');

let data = readYml.sync('./../.././../../../app/config/parameters.yml');

console.log(data);

let optKuzzle = {
  defaultIndex : '',
  autoReconnect: true,
  port: ''
};

// let kuzzleDso = new Kuzzle('localhost', optKuzzle, (err, res) => {
//   if (err) {
//     console.log(err.message);
//   }
// });
//
// export default kuzzleDso;