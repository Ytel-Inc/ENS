
'use strict';

angular.module('ensApp.smsNotification', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/smsNotification', {
                templateUrl: '/Admin/template/sms-notification',
                controller: 'SmsNotificationCtrl'
            });
        }])
    .controller('SmsNotificationCtrl', ['$scope', '$http', '$firebaseObject', '$firebaseArray', '$timeout', '$filter', '$location', function($scope, $http, $firebaseObject, $firebaseArray, $timeout, $filter, $location) {
            var scope = $scope;

            var orgName = 'Oregon State University';

            scope.smsTemplates = [
                {
                    title: 'Test',
                    text: 'This is a test of ' + orgName + ' Alert, ' + orgName + ' emergency alert system. If you have received this in error, send email to [email]'
                },
                {
                    title: 'Biological threat',
                    text: orgName + ' Emergency! ' + orgName + ' has received a biological threat. Prepare to evacuate. Follow instructions from authorities.'
                },
                {
                    title: 'Bomb threat',
                    text: orgName + ' Emergency! ' + orgName + ' has received a bomb threat at [building]. Evacuate if in that building. Follow instructions from authorities.'
                },
                {
                    title: 'Bomb found',
                    text: orgName + ' Emergency! A bomb has been found on campus in [building]. Prepare to evacuate. Follow instructions from authorities.'
                },
                {
                    title: 'Class Cancellation',
                    text: orgName + ' ALERT: Classes have been canceled due to [reason for cancellation]. Staff should report at their normal time, safety permitting.'
                },
                {
                    title: 'Shooting - Suspect NOT in custody',
                    text: orgName + ' Alert: A [shooting/stabbing] has occurred at [building]. A suspect is NOT in custody, Shelter in place. See email for more information.'
                },
                {
                    title: 'Shooting - Suspect IN custody',
                    text: orgName + ' Alert: A [shooting/stabbing] has occurred at [building]. A suspect is in custody. Police are on scene. See email for more information.'
                },
                {
                    title: 'Unknown situation',
                    text: orgName + ' ALERT! Police are investigating an incident at [building/location]. Please avoid the area. See email for more information.'
                },
                {
                    title: 'All Clear',
                    text: orgName + ' Alert ALL CLEAR: The situation is all clear, see campus email for more information. '
                }

            ];
            scope.sms = {};
            scope.templateSelected = function(templateId) {
                if (templateId)
                    scope.sms.message = scope.smsTemplates[templateId].text;
            };

            $http.get('/List/ajaxListSelect.json').then(function(response) {
                scope.listOption = response.data.response;
            });

            scope.sendSms = function() {
                scope.processing = true;
                $http.post('/Sms/ajaxSendSmsMessage.json', {sms: scope.sms}).then(function(response) {
                    scope.processing = false;
                    if (response.data.response.status) {
                        scope.sendQueueId = response.data.response.sendQueueId;
                        scope.numberCount = response.data.response.numberCount;

                        if (scope.numberCount <= 2000) {
                            var queueNumbersRef = new Firebase("https://ens.firebaseio.com/sms/" + scope.sendQueueId + '/numbers');

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

            scope.graph = {
                labels: ["In Queue", "Sending", "Sent", "Fail"],
                colors: ['#4EBABA', '#FFAE6B', '#5CDD5C', '#FF6B6B']
            };

            var searchObject = $location.search();
            if (typeof searchObject.sendQueueId !== 'undefined') {
                scope.sendQueueId = searchObject.sendQueueId;
//                scope.numberCount = response.data.response.numberCount;

//                var queueRef = new Firebase("https://ens.firebaseio.com/sms/" + scope.sendQueueId);

                var queueNumbersRef = new Firebase("https://ens.firebaseio.com/sms/" + scope.sendQueueId + '/numbers');

                scope.queueArray = $firebaseArray(queueNumbersRef);

                if (scope.numberCount <= 2000) {
                    var queueNumbersRef = new Firebase("https://ens.firebaseio.com/sms/" + scope.sendQueueId + '/numbers');

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
        }]);