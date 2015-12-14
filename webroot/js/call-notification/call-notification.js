
'use strict';

angular.module('ensApp.callNotification', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/callNotification', {
                templateUrl: '/Admin/template/call-notification',
                controller: 'CallNotificationCtrl'
            });
        }])
    .controller('CallNotificationCtrl', ['$scope', '$http', '$firebaseObject', '$firebaseArray', '$filter', function($scope, $http, $firebaseObject, $firebaseArray, $filter) {
            var scope = $scope;

            scope.graph = {
                labels: ["In Queue", "Calling", "Called", "Fail"],
                colors: ['#4EBABA', '#FFAE6B', '#5CDD5C', '#FF6B6B']
            };

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

                    if (response.data.response.status) {
//                        scope.sendQueueId = response.data.response.sendQueueId;

//                        var queueRef = new Firebase("https://ens.firebaseio.com/call/"+scope.sendQueueId);
//                        
//                        var currentQueueObj = $firebaseObject(queueRef);
//                        
//                        currentQueueObj.$bindTo(scope, "currentQueue");

                        scope.sendQueueId = response.data.response.sendQueueId;
                        scope.numberCount = response.data.response.numberCount;

                        if (scope.numberCount <= 2000) {
                            var queueNumbersRef = new Firebase("https://ens.firebaseio.com/call/" + scope.sendQueueId + '/numbers');

                            scope.queueArray = $firebaseArray(queueNumbersRef);

                            scope.queueArray.$watch(function() {
                                var sending = $filter('filter')(scope.queueArray, {status: 2}),
                                    sent = $filter('filter')(scope.queueArray, {status: 3}),
                                    fail = $filter('filter')(scope.queueArray, {status: 4});

                                var count = [
                                    sending ? sending.length : 0,
                                    sent ? sent.length : 0,
                                    fail ? fail.length : 0
                                ];
                                var inQueue = scope.numberCount - count[0] - count[1] - count[2];
                                count.unshift(inQueue);
                                scope.graph.data = count;
                            });
                        }
                    }
                });
            };
        }]);