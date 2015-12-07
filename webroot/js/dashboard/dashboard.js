
'use strict';

angular.module('ensApp.dashboard', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/dashboard', {
                templateUrl: '/Admin/template/dashboard',
                controller: 'DashboardCtrl'
            });
        }])
    .controller('DashboardCtrl', [function() {

        }]);