<div class="users-list">
    <header>
        <h2>Users</h2>
    </header>
</div>
<div class="roles-list">
    <div class="flexbox">
        <div class="overview">
            <h3>Overview</h3>
            {foreach(users as user)}
            <div data-link="/users" data-id="${user.id}">
                    <span class="icon-text">
                        {include(includes/svg;icon=vt-edit)}
                    </span>
                <span>${user.username} (${user.firstname} ${user.lastname})</span>
            </div>
            {/for}
        </div>
        <div class="add-new">
            <h3>Add new User</h3>
            {include(includes/input;label=New User Name;name=newUserName;error=New User Name is required;type=text)}
            {include(includes/btn;type=primary;content=Add)}
        </div>
    </div>
</div>