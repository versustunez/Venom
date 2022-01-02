<div class="pages-list">
    <header>
        <h2>Pages</h2>
    </header>
        <div class="add-new">
            <h3>Add new Page</h3>
            {include(includes/input;label=New Page Name;name=newPageName;error=New Page Name is required;type=text)}
            {include(includes/btn;type=primary;content=Add)}
        </div>
        <div class="overview">
            <h3>All Pages</h3>
            {foreach(pages as page,key)}
            <div data-link="/pages" data-id="${page.id}">
            <span>
                {include(includes/svg;icon=$page.icon)}
            </span>
                <span>${page.name}</span>
            </div>
            {/for}
        </div>
</div>

