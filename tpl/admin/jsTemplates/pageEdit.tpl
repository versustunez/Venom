<div class="page-edit">
    <header>
        <h2>Page Edit: Turbinen sind geil</h2>
    </header>
    <div>
        <span data-link="/pages" class="icon-text">
        {include(includes/svg;class=back-arrow;icon=vt-arrow-back)}
        </span>
    </div>

    <div>
        <h3>Page Information</h3>
        {include(includes/input;class=input-group;label=Page Name;name=PageName;error=Page Name is required;default=Turbinen sind geil)}
    </div>
    <div>
        {include(includes/select;name=pageVisibility;label=Current Author;object=$users)}
    </div>
    <div>
        <v-select required name="pageVisibility">
            <v-label empty="Page Visibility"></v-label>
            <v-options>
                <v-option value="visible">Visible</v-option>
                <v-option value="privat">Privat</v-option>
            </v-options>
        </v-select>
    </div>
    <div>
        <v-editor name="pageTextArea" rows="25">!</v-editor>
    </div>
    <div class="btn-line">
        {include(includes/btn;type=valid;content=Save)}
        {include(includes/btn;type=primary;content=Reset)}
        {include(includes/btn;type=warn;content=Delete Page)}
    </div>
</div>