<div class="users-edit">
    <header>
        <h2>User: engineertrooper</h2>
    </header>
    <div>
    <span data-link="/users" class="icon-text">
        {include(includes/svg;class=back-arrow;icon=vt-arrow-back)}
    </span>
    </div>
    <h3>User Data</h3>
    <div class="spacer">
        {include(includes/input;class=input-group;label=Username;name=newUserName;error=New User Name is required;default=EngineerTrooper)}
        {include(includes/input;class=input-group;label=Author Name;name=newAuthorName;error=New Author Name is required;default=Dominic Seela;classes=spacer)}
        {include(includes/input;class=input-group;label=E-Mail;name=newEMailAddress;error=E-Mail Address is required;default=kontakt@engineertrooper.com;classes=spacer)}
        {include(includes/input;class=input-group;label=Password;name=newPassword;type=password;error=Password is required;classes=spacer)}
        {include(includes/input;class=input-group;label=Password (Repeat);name=newPasswordRepeat;type=password;error=Password (Repeat) is required;classes=spacer)}
    </div>
    <v-table class="privileges">
        <h3>Privileges</h3>
        <v-table-body>
            <v-table-header>
                <v-cell class="name">Module</v-cell>
                <v-cell class="name">Edit</v-cell>
                <v-cell class="name">View</v-cell>
            </v-table-header>
            <v-table-row>
                <v-cell class="description">Meta-Data</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionEditMetaData)}</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionViewMetaData)}</v-cell>
            </v-table-row>
            <v-table-row>
                <v-cell class="description">Pages</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionEditPages)}</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionViewPages)}</v-cell>
            </v-table-row>
            <v-table-row>
                <v-cell class="description">Roles</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionEditRoles)}</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionViewRoles)}</v-cell>
            </v-table-row>
            <v-table-row>
                <v-cell class="description">SEO-URL</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionEditSeoUrl)}</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionViewSeoUrl)}</v-cell>
            </v-table-row>
            <v-table-row>
                <v-cell class="description">Users</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionEditUsers)}</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionViewUsers)}</v-cell>
            </v-table-row>
            <v-table-row>
                <v-cell class="description">VENOM-Status</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionEditVenomStatus)}</v-cell>
                <v-cell>{include(includes/switch;id=${switch.id};name=permissionViewVenomStatus)}</v-cell>
            </v-table-row>
        </v-table-body>
    </v-table>
    <div class="btn-line">
        <div>
            {include(includes/btn;type=valid;content=Save)}
            {include(includes/btn;type=primary;content=Reset)}
            {include(includes/btn;type=warn;content=Delete User)}
        </div>
    </div>
</div>