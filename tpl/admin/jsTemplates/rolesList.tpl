<div class="roles-list">
    <header>
        <h2>Roles</h2>
    </header>
    <div class="flexbox">
        <div class="overview">
            <h3>Overview</h3>
            {foreach(roles as role,key)}
            <div data-link="/roles" data-id="${role.id}">
                    <span>
                        {include(includes/svg;icon=$role.icon)}
                    </span>
                <span>${role.name}</span>
            </div>
            {/for}
        </div>
        <div class="add-new">
            <h3>Add new Role</h3>
            {include(includes/input;label=New Role Name;name=newRoleName;error=New Role Name is required;type=text)}
            {include(includes/btn;type=primary;content=Add)}
        </div>
    </div>
</div>