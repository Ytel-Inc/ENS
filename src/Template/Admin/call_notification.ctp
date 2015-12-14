<fieldset>
    <legend>Send Message</legend>
    <md-content>
        <form name="callForm">
            <div layout="column">
                <md-input-container class="md-block">
                    <label>Message</label>
                    <textarea name="message"
                              ng-model="call.message"
                              ng-disabled="processing"
                              rows="5"
                              maxlength="500"
                              md-maxlength="500"></textarea>
                </md-input-container>

                <md-input-container>
                    <label>Audio File</label>
                    <md-select name="list"
                               ng-model="call.audio_id"
                               ng-disabled="processing"
                               required>
                        <md-option ng-repeat="o in audios| orderBy: 'file_name'" value="{{o.audio_id}}">{{o.file_name}}</md-option>
                    </md-select>
                </md-input-container>

                <md-input-container>
                    <label>Select List</label>
                    <md-select name="list"
                               ng-model="call.number_list_id"
                               ng-disabled="processing"
                               required>
                        <md-option ng-repeat="o in listOption| orderBy: 'list_name'" value="{{o.number_list_id}}">{{o.list_name}}</md-option>
                    </md-select>
                </md-input-container>

                <md-progress-linear class="md-primary"
                                    md-mode="indeterminate"
                                    ng-show="processing"></md-progress-linear>

                <md-button type="button"
                           class="md-warn md-raised"
                           ng-disabled="callForm.$invalid || processing"
                           ng-click="sendCall()">Send Emergency Notification</md-button>
            </div>
        </form>
    </md-content>
</fieldset>

<fieldset>
    <legend>Status</legend>
    <p>Current Job ID: {{sendQueueId}} | Status: {{currentQueue.status}}</p>
<!--    <md-progress-linear class="md-primary"
                        md-mode="indeterminate"
                        ng-show="currentQueue.status == 2"></md-progress-linear>-->
<!--    <table>
        <thead>
            <tr>
                <th>Number</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="q in currentQueue.numbers track by q.number_id">
                <td>+{{q.country_code}}{{q.phone_number}}</td>
                <td ng-switch="q.status">
                    <span ng-switch-when="2">Calling...</span>
                    <span ng-switch-when="3">Called</span>
                    <span ng-switch-when="4">Fail</span>
                    <span ng-switch-default>Pending...</span>
                </td>

                <td ng-switch="q.call_status">
                    <span ng-switch-when="1">Pending...</span>
                    <span ng-switch-when="2">Playing...</span>
                    <span ng-switch-when="3">Played</span>
                    <span ng-switch-when="4">Fail</span>
                    <span ng-switch-default>Waiting to call and answer...</span>
                </td>
            </tr>
        </tbody>
    </table>-->

    <md-content layout="row">
        <md-list flex class="table-fixed-width md-whiteframe-z1"
                 ng-hide="numberCount > 2000">
            <md-list layout="row">
                <md-subheader flex="40">Number</md-subheader>
                <md-subheader flex>Status</md-subheader>
            </md-list>
            <md-divider></md-divider>
            <md-virtual-repeat-container style="height:500px">
                <md-list-item md-virtual-repeat="q in queueArray" class="repeated-item" flex>
                    <md-list flex layout="row">
                        <md-list-item flex>+{{q.country_code}}{{q.phone_number}}</md-list-item>
                        <md-list-item flex ng-switch="q.status">
                            <span ng-switch-when="2">Calling...</span>
                            <span ng-switch-when="3">Called</span>
                            <span ng-switch-when="4">Fail</span>
                            <span ng-switch-default>Pending...</span>
                        </md-list-item>
                        <md-list-item flex ng-switch="q.call_status">
                            <span ng-switch-when="1">Pending...</span>
                            <span ng-switch-when="2">Playing...</span>
                            <span ng-switch-when="3">Played</span>
                            <span ng-switch-when="4">Fail</span>
                            <span ng-switch-default>Waiting to call and answer...</span>
                        </md-list-item>
                    </md-list>
                    <md-divider flex></md-divider>
                </md-list-item>
            </md-virtual-repeat-container>
        </md-list>

        <canvas id="pie"
                class="chart chart-pie"
                chart-data="graph.data"
                chart-labels="graph.labels"
                chart-colours="graph.colors"
                chart-legend="true">
        </canvas>
</fieldset>