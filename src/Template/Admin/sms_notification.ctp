<fieldset>
    <legend>Send Message</legend>

    <form name="smsForm" layout="column">
        <md-input-container class="md-block">
            <label>Message</label>
            <textarea name="message" ng-model="sms.message" columns="1" md-maxlength="150" rows="5" required></textarea>
        </md-input-container>

        <md-input-container>
            <label>Select List</label>
            <md-select name="list" ng-model="sms.list" required>
                <md-option ng-repeat="o in listOption | orderBy: 'list_name'" value="{{o.number_list_id}}">{{o.list_name}}</md-option>
            </md-select>
        </md-input-container>

        <md-button type="button" class="md-warn md-raised" ng-disabled="smsForm.$invalid">Send Emergency Notification</md-button>
    </form>
</fieldset>