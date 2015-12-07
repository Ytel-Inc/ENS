<fieldset>
    <legend>Manually Add Number</legend>
    <form name="uploadLead" layout="column" layout-gt-md="row">
        <md-input-container flex>
            <label>Select List To Add</label>
            <md-select ng-model="input.list">
                <md-option ng-repeat="o in listOption | orderBy: 'list_name'" value="{{o.number_list_id}}">{{o.list_name}}</md-option>
            </md-select>
        </md-input-container>

        <md-input-container flex>
            <label>Number to Add</label>
            <input type="text" ng-model="input.number" />
        </md-input-container>

        <md-button type="button" class="md-primary md-raised">Add Number</md-button>
    </form>
</fieldset>

<fieldset>
    <legend>Add New List</legend>
    <form layout="column">
        <md-input-container flex>
            <label>CVS Number File</label>
            <input type="text" ng-model="addNewList.file" />
        </md-input-container>

        <md-input-container flex>
            <label>List Name</label>
            <input type="text" ng-model="addNewList.list_name" />
        </md-input-container>

        <md-button type="button" class="md-primary md-raised">Upload File & Add New List</md-button>
    </form>
</fieldset>