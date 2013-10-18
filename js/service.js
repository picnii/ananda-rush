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
    getCompaniesList: {method:'GET', params:{action:'type', type:'companies'}, isArray:true },
    getBillPayment:{method:'GET',params:{action:'billPayment'}}
  });
}).factory('Unit', function($resource){
  return $resource('service/index.php', {}, {
    query: {method:'GET', params:{action:'units', q:'*'}, isArray:true },
    find:{method:'GET', params:{action:'unit'}}
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
    create:{method:'POST', params:{action:'createBills'}},
    preview:{method:'POST', isArray:true},
    list:{method:'GET', params:{action:'listTransactions'}, isArray:true},
    test:{method:'POST', isArray:true},
    view:{method:'GET', params:{action:'transaction',id:""}},
    viewTransaction:{method:'GET', params:{action:'viewTransaction', unit_id:'*'}},
    createTransaction:{method:'POST', params:{action:'createTransaction'}}
  });
}).factory('Appoint', function($resource){
  return $resource('service/index.php', {}, {
    get:{method:'GET', params:{action:'appoint', itemId:'*'}},
    create:{method:'POST', params:{action:'createAppoint'} },
    getPaymentTypes:{method:'GET', params:{action:'getAppointmentPaymentTypes'}, isArray:true},
    getAppointAuthorizeStatus:{method:'GET', params:{action:'getAppointAuthorizeStatus'},isArray:true},

  });
}).factory('Promotion', function($resource){
  return $resource('service/index.php', {}, {
    forUser:{method:'GET', params:{action:'promotions'}, isArray:true},
    list:{method:'GET', params:{action:'promotions'}, isArray:true},
    listAx:{method:'GET', params:{action:'findPromotionsAx'}, isArray:true},
    getTypes:{method:'GET', params:{action:'promotionTypes'}, isArray:true},
    getPhases:{method:'GET', params:{action:'promotionPhases'}, isArray:true},
    getPaymentTypes:{method:'GET', params:{action:'promotionPaymentTypes'}, isArray:true},
    create:{method:'POST', params:{action:'createPromotion'}, isArray:true},
    query:{method:'GET', params:{action:'listPromotions'}, isArray:true},
    get:{method:'GET', params:{action:'promotion'}},
    update:{method:'POST', params:{action:'updatePromotion'}},
    delete:{method:'POST', params:{action:'deletePromotion'}},
    createCondition:{method:'POST', params:{action:'createPromotionCondition'}},
    listConditions:{method:'POST', params:{action:'listConditions'}, isArray:true},
    deleteCondition:{method:'POST', params:{action:'deleteCondition'}},
    matchPromotion:{method:'POST', params:{action:'matchPromotion'}, isArray:true},
    countUnit:{method:'GET', params:{action:'countAllUnitPromotion'}},
    findUnit:{method:'GET', params:{action:'findAllUnitPromotion'}, isArray:true},
    listAx:{method:'GET', params:{action:'listAx'}, isArray:true},
    createAx:{method:'POST', params:{action:'createPromotionAx'}},
    deleteAx:{method:'POST', params:{action:'deletePromotionAx'}, isArray:true},
    find:{method:'GET', params:{action:'findAllPromotionFromCondition'}, isArray:true},
    findPre:{method:'GET', params:{action:'findAllPrePromotionFromItemId'}, isArray:true},
    findAx:{method:'GET', params:{action:'findAllPromotionAxByItemId'}, isArray:true},
    confirmPromotion:{method:'POST', params:{action:'createConfirmPromotion'}},
    unConfirmPromotion:{method:'POST', params:{action:'deleteConfirmPromotion'}},
    checkConfirmPromotion:{method:'POST', params:{action:'isConfirmPromotion'}},
    updatePrePromotion:{method:'POST', params:{action:'updatePrePromotion'}},
    updateTranferPromotion:{method:'POST', params:{action:'updateTranferPromotion'}},
  });
})
    //, params:{unitId:'data'}, isArray:false