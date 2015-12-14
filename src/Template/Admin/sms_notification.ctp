<fieldset>
    <legend>Send Message</legend>
    <md-content>
        <form name="smsForm">
            <div layout="column">
                <md-input-container>
                    <label>Template</label>
                    <md-select ng-model="smsTemplateId"
                               ng-change="templateSelected(smsTemplateId)"
                               ng-disabled="processing">
                        <md-option ng-repeat="o in smsTemplates| orderBy: 'title'"
                                   value="{{$index}}">{{o.title}}</md-option>
                    </md-select>
                </md-input-container>

                <md-input-container class="md-block">
                    <label>Message</label>
                    <textarea name="message"
                              ng-model="sms.message"
                              ng-disabled="processing"
                              rows="5"
                              maxlength="500"
                              md-maxlength="500"
                              required></textarea>
                </md-input-container>

                <md-input-container>
                    <label>Select List</label>
                    <md-select name="list"
                               ng-model="sms.number_list_id"
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
                           ng-disabled="smsForm.$invalid || processing"
                           ng-click="sendSms()">Send Emergency Notification</md-button>
            </div>
        </form>
    </md-content>
</fieldset>

<fieldset ng-show="sendQueueId">
    <legend>Status</legend>
    <p>
        Current Job ID: {{sendQueueId}} | Number Count: {{numberCount}} |
<!--        Status:
        <span ng-switch="currentQueue.status">
            <span ng-switch-when="2">In progress...</span>
            <span ng-switch-when="3">Finished</span>
            <span ng-switch-when="4">Fail</span>
            <span ng-switch-default>In Queue...</span>
        </span>-->
    </p>
<!--    <md-progress-linear class="md-primary"
                        md-mode="indeterminate"
                        ng-show="currentQueue.status == 2"></md-progress-linear>-->

    <md-content layout="row">
        <md-list flex class="table-fixed-width md-whiteframe-z1"
                 ng-hide="numberCount > 2000">
            <md-list layout="row">
                <md-subheader flex>Number</md-subheader>
                <md-subheader flex>Status</md-subheader>
            </md-list>
            <md-divider></md-divider>
            <md-virtual-repeat-container style="height:500px">
                <md-list-item md-virtual-repeat="q in queueArray" class="repeated-item" flex>
                    <md-list flex layout="row">
                        <md-list-item flex>+{{q.country_code}}{{q.phone_number}}</md-list-item>
                        <md-list-item flex ng-switch="q.status">
                            <span ng-switch-when="2">Sending...</span>
                            <span ng-switch-when="3">Sent</span>
                            <span ng-switch-when="4">Fail</span>
                            <span ng-switch-default>In Queue...</span>
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
    </md-content>


</fieldset>