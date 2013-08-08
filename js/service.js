angular.module('dataServices', ['ngResource']).
    factory('Npop', function($resource){
  return $resource('service?action=bill&unit_id=:uid&template_id=:tid', {}, {
    query: {method:'GET', params:{uid:'all', tid:'all'} }
  });
}).
    factory('Template', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'templates'} , isArray:true},
    get: {method:'GET', params:{action:'template', template_id:"all"} }
  });
}).factory('Print', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'POST', params:{action:'bills', unit_ids:'all', template_id:'all'} }
  });
}).factory('Payment', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'payments'}, isArray:true }
  });
}).factory('Type', function($resource){
  return $resource('service/index.php', {}, {
    getRoomType: {method:'GET', params:{action:'type', type:'room'}, isArray:true },
    getProjectsList: {method:'GET', params:{action:'type', type:'projects'}, isArray:true }
  });
}).factory('Unit', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'units', q:'*'}, isArray:true }
  });
}).factory('Variable', function($resource){
  return $resource('service/index.php', {}, {
    getAllTypes: {method:'GET', params:{action:'variablesType'}, isArray:true },
    query:{modeth:'GET', params:{action:'variables'}, isArray:true}
  });
})
    //, params:{unitId:'data'}, isArray:false