angular.module('dataServices', ['ngResource']).
    factory('Npop', function($resource){
  return $resource('service?action=bill&unit_id=:uid&template_id=:tid', {}, {
    query: {method:'GET', params:{uid:'all', tid:'all'} }
  });
}).
    factory('Template', function($resource){
  return $resource('service?action=template', {}, {
    query: {method:'GET' , isArray:true}
  });
}).factory('Print', function($resource){
  return $resource('service', {}, {
    query: {method:'POST', params:{action:'bills', unit_ids:'all', template_id:'all'} }
  });
});
    //, params:{unitId:'data'}, isArray:false