!function(){var e=angular.module("Shift.Library.DefaultControllers",[]);e.provider("Controller",function(){this.index=function(e,r,t,o,n,u,i){u.registerFilters(resource.toLower(),[new Field.Text({name:"keyword",description:"Search by "+resourceLower+" name."})]),n(r,resourcePlural,resourcePlural),o(r,i,resourcePlural)},this.create=function(e,r,t,o){r[resourceLower]=new o,r[resourceLower].title="New "+res,r.save=this.saveResource(r)},this.update=function(e,r,t,o){return function(){r.resource=o,r.save=this.saveResource(r)}},this.saveResource=function(e){return function(){var r=!!e[resourceLower].id,t=r?"updating":"creating",o=r?"updated":"created";$rootScope.$broadcast(resourceLower+"."+t,e[resourceLower]),e[resourceLower].$save({},function(){$rootScope.$broadcast(resourceLower+"."+o,e[resourceLower]),e.go(resourcePlural)})}}})}(),function(){"use strict";var e=angular.module("Shift.Library.Defaults",["$ngResource"]);e.provider("DefaultRoute",["ShiftRouteProvider",function(e){return function(r,t){e.register(r,{templateUrl:r+"/index.html",controller:r,"package":t}),e.register(r+"/new",{templateUrl:r+"/form.html",controller:r+".new","package":t}),e.register(r+"/:id",{templateUrl:r+"/form.html",controller:r+".edit","package":t})}}]),e.provider("DefaultResolver",[function(){return{$get:[]}}]),e.service("Resource",["$resource",function(e){return function(r,t,o){var n={update:{method:"put",isArray:!1},create:{method:"post"}};o=_.extend(n,o);var u=e(r,t,o);return u.prototype.$save=function(e,r){this.id?this.$update(e,r):this.$create(e,r)},u.lower=function(){return this.name.toLowerCase()},u.lowerPlural=function(){return this.lower().pluralize()},u}}])}(),function(){"use strict";var e=angular.module("Shift.Library.Router",["ngRoute","Shift.Library.Core.Services"]);e.provider("ShiftRoute",function(){return{$get:["Config",function(e){var r=[],t=0;return{register:function(e,o){var n=angular.copy(o),u=this.routeUrl(e);n.url=u,n.templateUrl&&(n.templateUrl=this.viewPath(n)),"undefined"==typeof n.order&&(n.order=t,t+=10),r.push(n)},get:function(){return this.sortItems(r)},sortItems:function(e){return _.sortBy(e,function(e){return e.order})},routeUrl:function(r,t){return t=e.get(t?"app.url":"app.base"),"/"==r.substr(-1,1)&&(r=r.substr(0,r.length-1)),r=[e.get("app.base"),r].join("/")},viewPath:function(e){return e.bundle?viewPath(e.templateUrl,e.bundle):viewPath(e.templateUrl)},init:function(e){angular.forEach(this.get(),function(r){e.when(r.url,r)})}}}]}})}(),function(){"use strict";var e=angular.module("Shift.Library.Core.Services",["ngResource"]);e.service("Config",[function(){var e={};this.hydrate=function(r){angular.forEach(r,function(r,t){e[t]=r})},this.add=function(r,t){e[r]=t},this.get=function(r){return angular.isUndefined(e[r])?null:e[r]}}])}(),function(){"use strict";angular.module("Shift.Accounts.Controllers",["Shift.Library.Defaults"])}(),function(){"use strict";var e=angular.module("Shift.Accounts.Setup",["Shift.Library.Defaults"]);e.config(["ShiftRouteProvider",function(e){e("accounts","shift")}])}(),function(){"use strict";var e=angular.module("Shift.Home.Controllers",["Shift.Library.Defaults"]);e.controller("shift.home",["$scope",function(){}])}(),function(){"use strict";var e=angular.module("Shift.Home.Setup",["Shift.Library.Defaults"]);e.config(["ShiftRouteProvider",function(e){e("home",{templateUrl:"/packages/tectonic/shift/views/home.html"})}])}(),function(){"use strict";var e=angular.module("shift",["Shift.Library.Router","Shift.Home.Setup","Shift.Home.Controllers"]);e.config(["$locationProvider","ShiftRouteProvider",function(e){e.html5Mode(!0),Route.init()}])}();