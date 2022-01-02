class Component {
    constructor(name) {
        this.name = name;
        this.start();
    }

    handle(data, ds) {
    }

    init() {
    }

    getUrl(ds) {
        return '';
    }

    start() {
        if (window.routerIsReady) {
            router.addComponent(this.name || VUtils.tempId(), this);
            this.init();
        } else {
            window.addEventListener('routerReady', this.start.bind(this));
        }
    }
}
class MetaDataComponent extends Component {
    constructor() {
        super("/metaData");
        this.tpl = "metaDataList";
        this.tpl2 = "metaDataEdit";
        this._url = "/admin/api/metaData";
    }

    async handle(data, ds) {
        let meTpl = ds.id ? this.tpl2 : this.tpl;
        await tpl.loadTemplate(meTpl);
        return await tpl.renderOn(meTpl, data.content);
    }

    getUrl(ds) {
        let url = this._url;
        if (ds.id) {
            url += '/' + ds.id;
        }
        return url;
    }
}
class OverviewComponent extends Component {
    constructor() {
        super("/overview");
        this.tpl = "overview";
        this._url = "/admin/api/overview";
    }

    async handle(data, ds) {
        await tpl.loadTemplate(this.tpl);
        return await tpl.renderOn(this.tpl, data.content);
    }

    getUrl(ds) {
        return this._url;
    }
}
class PagesComponent extends Component {
    constructor() {
        super("/pages");
        this.tpl = "pagesList";
        this.tpl2 = "pageEdit";
        this._url = "/admin/api/pages";
    }

    async handle(data, ds) {
        let meTpl = ds.id ? this.tpl2 : this.tpl;
        await tpl.loadTemplate(meTpl);
        return await tpl.renderOn(meTpl, data.content);
    }

    getUrl(ds) {
        let url = this._url;
        if (ds.id) {
            url += '/' + ds.id;
        }
        return url;
    }
}
class RolesComponent extends Component {
    constructor() {
        super("/roles");
        this.tpl = "rolesList";
        this.tpl2 = "roleEdit";
        this._url = "/admin/api/roles";
    }

    async handle(data, ds) {
        let meTpl = ds.id ? this.tpl2 : this.tpl;
        await tpl.loadTemplate(meTpl);
        return await tpl.renderOn(meTpl, data.content);
    }

    getUrl(ds) {
        let url = this._url;
        if (ds.id) {
            url += '/' + ds.id;
        }
        return url;
    }
}
class SeoUrlComponent extends Component {
    constructor() {
        super("/seoUrl");
        this.tpl = "seoUrlList";
        this.tpl2 = "seoUrlEdit";
        this._url = "/admin/api/seoUrl";
    }

    async handle(data, ds) {
        let meTpl = ds.id ? this.tpl2 : this.tpl;
        await tpl.loadTemplate(meTpl);
        return await tpl.renderOn(meTpl, data.content);
    }

    getUrl(ds) {
        let url = this._url;
        if (ds.id) {
            url += '/' + ds.id;
        }
        return url;
    }
}
class UsersComponent extends Component {
    constructor() {
        super("/users");
        this.tpl = "usersList";
        this.tpl2 = "userEdit";
        this._url = "/admin/api/users";
    }

    async handle(data, ds) {
        let meTpl = ds.id ? this.tpl2 : this.tpl;
        await tpl.loadTemplate(meTpl);
        return await tpl.renderOn(meTpl, data.content);
    }

    getUrl(ds) {
        let url = this._url;
        if (ds.id) {
            url += '/' + ds.id;
        }
        return url;
    }
}
class VenomStatusComponent extends Component {
    constructor() {
        super("/venomStatus");
        this.tpl = "venomStatus";
        this._url = "/admin/api/venomStatus";
    }

    async handle(data, ds) {
        await tpl.loadTemplate(this.tpl);
        return await tpl.renderOn(this.tpl, data.content);
    }

    getUrl(ds) {
        return this._url;
    }
}
(() => {
    // init all Components ;)
    new MetaDataComponent();
    new OverviewComponent();
    new PagesComponent();
    new RolesComponent();
    new SeoUrlComponent();
    new UsersComponent();
    new VenomStatusComponent();


    if (routerIsReady) {
        document.body.dispatchEvent(new CustomEvent('triggerRouter'));
    } else {
        document.addEventListener('routerReady', e => {
            document.body.dispatchEvent(new CustomEvent('triggerRouter'));
        })
    }
})();