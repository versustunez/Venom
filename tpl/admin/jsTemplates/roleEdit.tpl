<div class="role-edit">
    <header>
        <h2>Role: ${roles.name}</h2>
    </header>
    <span data-link="/roles" class="icon-back">
        {include(includes/svg;class=back-arrow;icon=vt-arrow-back)}
    </span>
    <div class="spacer">
        {include(includes/switch;id=${switch.id};name=permissionEditMetaData;desc=Active)}
        {include(includes/input;class=input-group;label=Name;name=roleName;error=Name is required;default=$roles.name;classes=spacer)}
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
            {include(includes/btn;type=warn;content=Delete Role)}
        </div>
    </div>
</div>