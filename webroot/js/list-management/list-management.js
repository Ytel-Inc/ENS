
'use strict';

angular.module('ensApp.listManagement', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/listManagement', {
                templateUrl: '/Admin/template/list-management',
                controller: 'ListManagementCtrl'
            });
        }])
    .controller('ListManagementCtrl', ['$scope', '$http', 'Upload', '$mdDialog', function($scope, $http, Upload, $mdDialog) {
            var scope = $scope;

            $http.get('/List/ajaxListSelect.json').then(function(response) {
                scope.listOption = response.data.response;
            });

            scope.input = {
                country_id: '1'
            };

            $http.get('/List/ajaxCountryPhoneCodeSelect.json').then(function(response) {
                scope.countryPhoneCodeOption = response.data.response;
            });

            scope.addNumber = function(ev) {
                $http.post('/Number/addNumber.json', scope.input).then(function(response) {
                    if (response.data.response.status == 1) {
                        scope.input = {
                            country_id: '1'
                        };

                        $mdDialog.show(
                            $mdDialog.alert()
                            .parent(angular.element(document.querySelector('body')))
                            .clickOutsideToClose(true)
                            .title('Success')
                            .textContent('Number Added.')
                            .ariaLabel('Alert Success')
                            .ok('Got it!')
                            .targetEvent(ev)
                            );
                    } else {
                        $mdDialog.show(
                            $mdDialog.alert()
                            .parent(angular.element(document.querySelector('body')))
                            .clickOutsideToClose(true)
                            .title('Error')
                            .textContent(response.data.response.message)
                            .ariaLabel('Alert Failure')
                            .ok('Got it!')
                            .targetEvent(ev)
                            );
                    }
                }).then(function() {
                    $mdDialog.show(
                        $mdDialog.alert()
                        .parent(angular.element(document.querySelector('body')))
                        .clickOutsideToClose(true)
                        .title('Error')
                        .textContent('Something went wrong, please try again later.')
                        .ariaLabel('Alert Error')
                        .ok('Got it!')
                        .targetEvent(ev)
                        );
                });
            };

            scope.upload = function(ev) {
                scope.processing = true;
                Upload.upload({
                    url: '/Number/upload.json',
                    data: scope.addNewList
                }).then(function(resp) {
                    console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
                    scope.addNewList = {};
                    scope.processing = false;
                    scope.progressPercentage = 0;

                    $mdDialog.show(
                        $mdDialog.alert()
                        .parent(angular.element(document.querySelector('body')))
                        .clickOutsideToClose(true)
                        .title('Success')
                        .textContent('List created, data uploaded and processed.')
                        .ariaLabel('Alert Success')
                        .ok('Got it!')
                        .targetEvent(ev)
                        );

                }, function(resp) {
                    console.log('Error status: ' + resp.status);
                    scope.processing = false;
                    scope.progressPercentage = 0;

                    $mdDialog.show(
                        $mdDialog.alert()
                        .parent(angular.element(document.querySelector('body')))
                        .clickOutsideToClose(true)
                        .title('Error')
                        .textContent('Something went wrong, please try again later.')
                        .ariaLabel('Alert Error')
                        .ok('Got it!')
                        .targetEvent(ev)
                        );

                }, function(evt) {
                    scope.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    console.log('progress: ' + scope.progressPercentage + '% ' + evt.config.data.file.name);
                });
            };
        }]);