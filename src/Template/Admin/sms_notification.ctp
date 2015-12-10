<fieldset>
    <legend>Send Message</legend>
    <md-content>
        <form name="smsForm">
            <div layout="column">
                <md-input-container class="md-block">
                    <label>Message</label>
                    <textarea name="message"
                              ng-model="sms.message"
                              ng-disabled="processing"
                              columns="1"
                              md-maxlength="150"
                              rows="5"
                              required></textarea>
                </md-input-container>

                <md-input-container>
                    <label>Select List</label>
                    <md-select name="list"
                               ng-model="sms.number_list_id"
                               ng-disabled="processing"
                               required>
                        <md-option ng-repeat="o in listOption | orderBy: 'list_name'" value="{{o.number_list_id}}">{{o.list_name}}</md-option>
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

<fieldset>
    <legend>Status</legend>
    <p>Current Job ID: {{sendQueueId}} | Status: {{currentQueue.status}}</p>
    <md-progress-linear class="md-primary"
                        md-mode="indeterminate"
                        ng-show="currentQueue.status==2"></md-progress-linear>
    <table>
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
                    <span ng-switch-when="2">Sending...</span>
                    <span ng-switch-when="3">Sent</span>
                    <span ng-switch-when="4">Fail</span>
                    <span ng-switch-default>Pending...</span>
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>