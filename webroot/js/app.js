'use strict';

// Declare app level module which depends on views, and components
angular.module('ensApp', [
    'ngRoute',
    'ngAria',
    'ngAnimate',
    'ngMaterial',
    'ngFileUpload',
    'firebase',
    'chart.js',
    'angular-toArrayFilter',
    'ensApp.dashboard',
    'ensApp.listManagement',
    'ensApp.smsNotification',
    'ensApp.audioManagement',
    'ensApp.callNotification',
]).config(['$routeProvider', '$mdThemingProvider', 'ChartJsProvider', function($routeProvider, $mdThemingProvider, ChartJsProvider) {
        $routeProvider.otherwise({redirectTo: '/dashboard'});

        $mdThemingProvider.theme('default')
            .primaryPalette('deep-orange')
            .accentPalette('pink')
            .warnPalette('red');

        // Configure all charts
        ChartJsProvider.setOptions({
            colours: ['#FF5252', '#FF8A80'],
            responsive: false,
            animationEasing: 'linear',
            animationSteps: 10
        });
    }])
    .controller('MainCtrl', ['$scope', '$mdSidenav', function($scope, $mdSidenav) {
            var scope = $scope;

            scope.systemName = 'OSU';
            
            scope.openLeft = function () {
                $mdSidenav('left').open();
            };
        }]);