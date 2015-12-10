<fieldset>
    <legend>Add New Audio</legend>
    <form name="addNewAudioForm" layout="column">
        <md-input-container flex>
            <md-button name="file"
                       type="file"
                       class="md-raised"
                       ngf-select
                       ng-model="addNewAudio.file"
                       accept="audio/*"
                       ngf-max-size="10MB"
                       required>
                Select File</md-button>
        </md-input-container>

        <md-button type="button"
                   class="md-primary md-raised"
                   ng-click="upload($evant)"
                   ng-disabled="addNewAudioForm.$invalid">Upload File</md-button>
    </form>

    <md-progress-linear md-mode="determinate" value="{{progressPercentage}}"></md-progress-linear>
</fieldset>

<fieldset layout="column">
    <legend>Audio</legend>
    <div layout ng-repeat="a in audios">
        <div flex>{{a.file_name}}</div>
        <div flex>{{a.create_datetime | date: 'medium'}}</div>
    </div>
</fieldset>