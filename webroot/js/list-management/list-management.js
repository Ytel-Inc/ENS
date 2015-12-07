
'use strict';

angular.module('ensApp.listManagement', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/listManagement', {
                templateUrl: '/Admin/template/list-management',
                controller: 'ListManagementCtrl'
            });
        }])
    .controller('ListManagementCtrl', ['$scope', '$http', function($scope, $http) {
            var scope = $scope;
            
            $http.get('/List/ajaxListSelect.json').then(function(response) {
                scope.listOption = response.data.response;
            });
        }]);