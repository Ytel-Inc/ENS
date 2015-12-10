
'use strict';

angular.module('ensApp.callNotification', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/callNotification', {
                templateUrl: '/Admin/template/call-notification',
                controller: 'CallNotificationCtrl'
            });
        }])
    .controller('CallNotificationCtrl', ['$scope', '$http', '$firebaseObject', function($scope, $http, $firebaseObject) {
            var scope = $scope;
            
            $http.get('/Audio/ajaxList.json').then(function(response) {
                scope.audios = response.data.response;
            });
            
            $http.get('/List/ajaxListSelect.json').then(function(response) {
                scope.listOption = response.data.response;
            });
            
            scope.sendCall = function() {
                scope.processing = true;
                $http.post('/Call/ajaxSendCall.json', {call: scope.call}).then(function(response) {
                    scope.processing = false;
                    if( response.data.response.status ) {
                        scope.sendQueueId = response.data.response.sendQueueId;
                        
                        var queueRef = new Firebase("https://ens.firebaseio.com/call/"+scope.sendQueueId);
                        
                        var currentQueueObj = $firebaseObject(queueRef);
                        
                        currentQueueObj.$bindTo(scope, "currentQueue");
                    }
                });
            };
        }]);