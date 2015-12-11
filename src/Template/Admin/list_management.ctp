<fieldset>
    <legend>Manually Add Number</legend>
    <form name="uploadLead" layout="column">
        <md-input-container flex>
            <label>Select List To Add</label>
            <md-select ng-model="input.number_list_id">
                <md-option ng-repeat="o in listOption| orderBy: 'list_name'" value="{{o.number_list_id}}">{{o.list_name}}</md-option>
            </md-select>
        </md-input-container>

        <div layout>
            <md-input-container flex>
                <label>Select Country Phone Code</label>
                <md-select ng-model="input.country_id">
                    <md-option ng-repeat="o in countryPhoneCodeOption| orderBy: 'country_id'" value="{{o.country_id}}">(+{{o.phone_code}}) {{o.name}}</md-option>
                </md-select>
            </md-input-container>

            <md-input-container flex>
                <label>Number to Add</label>
                <input type="text" ng-model="input.phone_number" />
            </md-input-container>
        </div>

        <md-button type="button" 
                   class="md-primary md-raised"
                   ng-click="addNumber($event)">Add Number</md-button>
    </form>
</fieldset>

<fieldset>
    <legend>Add New List</legend>
    <form name="addNewListForm" layout="column">
        <div layout>
            <md-input-container flex="20">
                <md-button name="file"
                           type="file"
                           class="md-raised"
                           ngf-select
                           ng-model="addNewList.file"
                           ng-disabled="processing"
                           accept="text/*"
                           ngf-max-size="10MB"
                           required>
                    Select File</md-button>
            </md-input-container>

            <p flex>{{addNewList.file.name}}</p>
        </div>

        <md-input-container flex>
            <label>List Name</label>
            <input name="list_name"
                   type="text"
                   ng-model="addNewList.list_name"
                   ng-disabled="processing"
                   maxlength="100"
                   required/>
        </md-input-container>

        <md-input-container flex>
            <label>List Description</label>
            <textarea name="list_discription"
                      ng-model="addNewList.list_description"
                      ng-disabled="processing"
                      columns="1"
                      md-maxlength="500"
                      rows="5"
                      required></textarea>
        </md-input-container>

        <md-button type="button" 
                   class="md-primary md-raised"
                   ng-click="upload($evant)"
                   ng-disabled="addNewListForm.$invalid || processing">Upload File & Add New List</md-button>

        <md-progress-linear md-mode="{{progressPercentage>=100 ? 'in':''}}determinate" value="{{progressPercentage}}"></md-progress-linear>
    </form>
</fieldset>