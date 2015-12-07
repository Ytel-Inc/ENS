'use strict';

// Declare app level module which depends on views, and components
angular.module('ensApp', [
    'ngRoute',
    'ngAria',
    'ngAnimate',
    'ngMaterial',
    'ensApp.dashboard',
    'ensApp.listManagement',
    'ensApp.smsNotification'
]).config(['$routeProvider', '$mdThemingProvider', function($routeProvider, $mdThemingProvider) {
        $routeProvider.otherwise({redirectTo: '/dashboard'});

        $mdThemingProvider.theme('default')
            .primaryPalette('deep-orange')
            .accentPalette('pink')
            .warnPalette('red');
    }])
    .controller('MainCtrl', ['$scope', function($scope) {
            var scope = $scope;

            scope.systemName = 'OSU';
        }]);