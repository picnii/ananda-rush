angular.module('dataServices', ['ngResource']).
    factory('Npop', function($resource){
  return $resource('data/data.json', {}, {
    query: {method:'GET'}
  });
});
    //, params:{unitId:'data'}, isArray:false