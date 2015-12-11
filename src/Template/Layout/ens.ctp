<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" ng-app="ensApp" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" ng-app="ensApp" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" ng-app="ensApp" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" ng-app="ensApp" class="no-js" ng-controller="MainCtrl"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title ng-bind="systemName + '- Emergency Notification System'">Emergency Notification System</title>
        <meta name="description" content="Emergency Notification System Demo">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="/ytel-favicon.png">

        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="bower_components/angular-chart.js/dist/angular-chart.css" />
        <link rel="stylesheet" href="bower_components/angular-material/angular-material.css">
    </head>
    <body layout="column" ng-cloak>

    <md-toolbar md-scroll-shrink ng-if="true">
        <div layout class="md-toolbar-tools">
            <md-button class="md-icon-button md-primary hide-gt-sm" aria-label="Left Menu" ng-click="openLeft()">
                <i class="fa fa-bars"></i>
            </md-button>
            <h3>
                <span>{{systemName}} - Emergency Notification System</span>
            </h3>
            <span flex></span>
            <span>Welcome, Admin</span>
        </div>
    </md-toolbar>

    <section layout="row" flex>

        <md-sidenav class="md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')">
            <md-content layout="column" layout-padding layout-fill>
                <section layout="column" flex>
                    <md-button type="button" ng-href="#/dashboard" class="md-primary">
                        Dashboard
                    </md-button>

                    <md-button type="button" ng-href="#/smsNotification" class="md-primary">
                        SMS Notification
                    </md-button>

                    <md-button type="button" ng-href="#/callNotification" class="md-primary">
                        Call Notification
                    </md-button>

                    <md-button type="button" ng-href="#/listManagement" class="md-primary">
                        List Management
                    </md-button>

                    <md-button type="button" ng-href="#/audioManagement" class="md-primary">
                        Audio Management
                    </md-button>
                </section>

                <img src="./img/osu.jpg" width="50%">
            </md-content>
        </md-sidenav>

        <md-content flex layout-padding>
            <div ng-view></div>
        </md-content>
    </section>

    <!-- Angular JS Libs -->
    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/angular-route/angular-route.js"></script>
    <script src="bower_components/angular-aria/angular-aria.js"></script>
    <script src="bower_components/angular-animate/angular-animate.js"></script>

    <!-- Angular file uploader -->
    <script src="bower_components/ng-file-upload/ng-file-upload-all.min.js"></script>

    <script src="bower_components/angular-toArrayFilter/toArrayFilter.js"></script>

    <!-- Chart -->
    <script src="bower_components/Chart.js/Chart.min.js"></script>
    <script src="bower_components/angular-chart.js/dist/angular-chart.min.js"></script>

    <!-- Angular material -->
    <script src="bower_components/angular-material/angular-material.js"></script>

    <!-- Firebase -->
    <script src="bower_components/firebase/firebase.js"></script>
    <script src="bower_components/angularfire/dist/angularfire.min.js"></script>

    <!-- System -->
    <script src="/js/app.js"></script>

    <script src="/js/dashboard/dashboard.js"></script>
    <script src="/js/list-management/list-management.js"></script>
    <script src="/js/sms-notification/sms-notification.js"></script>
    <script src="/js/audio-management/audio-management.js"></script>
    <script src="/js/call-notification/call-notification.js"></script>
</body>
</html>