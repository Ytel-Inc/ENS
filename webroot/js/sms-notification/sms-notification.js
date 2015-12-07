
'use strict';

angular.module('ensApp.smsNotification', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/smsNotification', {
                templateUrl: '/Admin/template/sms-notification',
                controller: 'SmsNotificationCtrl'
            });
        }])
    .controller('SmsNotificationCtrl', ['$scope', '$http', function($scope, $http) {
            var scope = $scope;
            
            $http.get('/List/ajaxListSelect.json').then(function(response) {
                scope.listOption = response.data.response;
            });
        }]);