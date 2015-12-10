
'use strict';

angular.module('ensApp.smsNotification', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/smsNotification', {
                templateUrl: '/Admin/template/sms-notification',
                controller: 'SmsNotificationCtrl'
            });
        }])
    .controller('SmsNotificationCtrl', ['$scope', '$http', '$firebaseObject', function($scope, $http, $firebaseObject) {
            var scope = $scope;
            
            $http.get('/List/ajaxListSelect.json').then(function(response) {
                scope.listOption = response.data.response;
            });
            
            scope.sendSms = function() {
                scope.processing = true;
                $http.post('/Sms/ajaxSendSmsMessage.json', {sms: scope.sms}).then(function(response) {
                    scope.processing = false;
                    if( response.data.response.status ) {
                        scope.sendQueueId = response.data.response.sendQueueId;
                        
                        var queueRef = new Firebase("https://ens.firebaseio.com/sms/"+scope.sendQueueId);
                        
                        var currentQueueObj = $firebaseObject(queueRef);
                        
                        currentQueueObj.$bindTo(scope, "currentQueue");
                    }
                });
            };
        }]);