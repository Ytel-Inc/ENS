
'use strict';

angular.module('ensApp.audioManagement', ['ngRoute'])

    .config(['$routeProvider', function($routeProvider) {
            $routeProvider.when('/audioManagement', {
                templateUrl: '/Admin/template/audio-management',
                controller: 'AudioManagementCtrl'
            });
        }])
    .controller('AudioManagementCtrl', ['$scope', '$http', 'Upload', '$mdDialog', function($scope, $http, Upload, $mdDialog) {
            var scope = $scope;

            $http.get('/Audio/ajaxList.json').then(function(response) {
                scope.audios = response.data.response;
            });

            scope.upload = function(ev) {
                Upload.upload({
                    url: '/Audio/upload.json',
                    data: scope.addNewAudio
                }).then(function(resp) {
                    console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
                    scope.addNewAudio = {};

                    $http.get('/Audio/ajaxList.json').then(function(response) {
                        scope.audios = response.data.response;
                    });

                    $mdDialog.show(
                        $mdDialog.alert()
                        .parent(angular.element(document.querySelector('body')))
                        .clickOutsideToClose(true)
                        .title('Success')
                        .textContent('Audio uploaded.')
                        .ariaLabel('Alert Success')
                        .ok('Got it!')
                        .targetEvent(ev)
                        );

                }, function(resp) {
                    console.log('Error status: ' + resp.status);

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