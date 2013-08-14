angular.module('dataServices', ['ngResource']).
    factory('Npop', function($resource){
  return $resource('service?action=bill&unit_id=:uid&template_id=:tid', {}, {
    query: {method:'GET', params:{uid:'all', tid:'all'} }
  });
}).
    factory('Template', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'templates'} , isArray:true},
    get: {method:'GET', params:{action:'template', template_id:"all"} },
    createTemplate:{method:'POST'},
    updateTemplate:{method:'POST'},
    deleteTemplate:{method:'POST'},
    deleteTemplatePayment:{method:'POST'},
    createTemplatePayment:{method:'POST'}

  });
}).factory('Print', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'POST', params:{action:'bills', unit_ids:'all', template_id:'all'} }
  });
}).factory('Payment', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'payments'}, isArray:true },
    createPayment:{method:'POST', params:{action:'createPayment'} },
    updatePayment:{method:'POST', params:{action:'updatePayment'} },
    deletePayment:{method:'POST', params:{action:'deletePayment'} }
  });
}).factory('Type', function($resource){
  return $resource('service/index.php', {}, {
    getRoomType: {method:'GET', params:{action:'type', type:'room'}, isArray:true },
    getProjectsList: {method:'GET', params:{action:'type', type:'projects'}, isArray:true },
    getCompaniesList: {method:'GET', params:{action:'type', type:'companies'}, isArray:true }
  });
}).factory('Unit', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'units', q:'*'}, isArray:true }
  });
}).factory('Variable', function($resource){
  return $resource('service/index.php', {}, {
    getAllTypes: {method:'GET', params:{action:'variablesType'}, isArray:true },
    query:{method:'GET', params:{action:'variables'}, isArray:true},
    create:{method:'POST', params:{action:'createVariable',description:'none', codename:"-", name:"-", type:"-", value:"-"}},
    delete:{method:'POST', params:{action:'deleteVariable'}}
  });
}).factory('Bill', function($resource){
  return $resource('service/index.php', {}, {
    create:{method:'POST', params:{action:'createBills'}}
  });
})
    //, params:{unitId:'data'}, isArray:false