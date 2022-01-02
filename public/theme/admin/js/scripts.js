class VUtils {
    static makePublic() {
        if (VUtils.isUsed) {
            return;
        }
        this.initHandlers();
        VUtils.isUsed = true;
        console.log("[VUtils] is now available in the Global Space! no VUtils. anymore needed");
    }

    static initHandlers() {
        window.$ = this.$;
        window.$$ = this.$$;
        window.tryCatch = this.tryCatch;
        VUtils.nodePrototypes();
    }

    static $(selector, from) {
        from = from || document;
        return from.querySelector(selector);
    }

    static $$(selector, from) {
        from = from || document;
        return from.querySelectorAll(selector);
    }

    static tryCatch(data, callback, error) {
        data = VUtils.wrap(data, []);
        error = error || console.error;
        callback = callback || console.log;
        try {
            callback(...data);
        } catch (e) {
            error(e);
        }
    }

    static forEach(items, cb, error) {
        for (let i = 0; i < items.length; i++) {
            VUtils.tryCatch([items[i], i], cb, error);
        }
    }

    static get(valueOne, value) {
        return this.wrap(valueOne, value);
    }

    static mergeKeys(root, target) {
        root = root || {};
        let keys = Object.keys(root);
        for (let key of keys) {
            target[key] = root[key];
        }
        return target;
    }

    static mergeOptions(target, root) {
        root = root || {};
        let keys = Object.keys(root);
        for (let key of keys) {
            target[key] = VUtils.get(root[key], target[key]);
        }
        return target;
    }

    static wrap(valueOne, valueTwo) {
        let x = typeof valueTwo;
        if (!(valueOne instanceof Array) && valueTwo instanceof Array) {
            return [valueOne];
        }
        if (x === 'string' && valueOne instanceof Array) {
            return valueOne.join(".");
        }
        return valueOne === undefined ? valueTwo : valueOne;
    }

    static tempId() {
        return 'temp_' + Math.random().toString(36).substr(2, 16);
    }

    static nodePrototypes() {
        Node.prototype.find = function (selector) {
            return this.closest(selector);
        };
        Node.prototype.createNew = function (tag, options) {
            let el = document.createElement(tag);
            if (options.classes) {
                el.classList.add(...VUtils.get(options.classes, []));
            }
            el.id = VUtils.get(options.id, '');
            el.innerHTML = VUtils.get(options.content, "");
            VUtils.mergeKeys(options.dataset, el.dataset);
            if (VUtils.get(options.append, true) === true) {
                this.appendChild(el);
            }

            return el;
        };
        Node.prototype.addDelegatedEventListener = function (type, aim, callback, err) {
            if (!callback || !type || !aim)
                return;
            this.addMultiListener(type, (event) => {
                let target = event.target;
                if (event.detail instanceof HTMLElement) {
                    target = event.detail;
                }
                if (target instanceof HTMLElement) {
                    if (target.matches(aim)) {
                        VUtils.tryCatch([event, target], callback, err);
                    } else {
                        const parent = target.find(aim);
                        if (parent) {
                            VUtils.tryCatch([event, parent], callback, err);
                        }
                    }
                }
            });
        };
        Node.prototype.addMultiListener = function (listener, cb, options = {}) {
            let splits = listener.split(" ");
            for (let split of splits) {
                this.addEventListener(split, cb, options);
            }
        };
    }
}

VUtils.makePublic();


class VRipple {
    constructor(options = {}) {
        if (!VUtils.isUsed) {
            throw "VRipply is only with Public VUtils usable!"
        }
        let self = this;
        self.options = JSON.parse('{"classes":["btn-ripple__effect"],"target":"body","selector":".btn-ripple"}');
        VUtils.mergeOptions(self.options, options);
        if (self.options.selector.indexOf("#") > -1) {
            throw "ID's are not allowed as selector!";
        }
        this.instanceCheck();
        this.ripples = [];
        requestAnimationFrame(this.initHandler.bind(this));
    }

    instanceCheck() {
        let opts = this.options;
        const rawKey = [opts.target, opts.selector, opts.classes.join(".")].join(" ");
        VRipple.instances = VRipple.instances || {};
        VRipple.instances[rawKey] = this;
    }

    initHandler() {
        let self = this;
        let selector = self.options.selector;
        let target = $(self.options.target);
        target.addDelegatedEventListener('mousedown touchstart', selector, (e, el) => {
            let pos = e.touches ? e.touches[0] : e;
            let parent = el.parentNode;
            let circle = el.createNew('span', self.options);
            let bounds = parent.getBoundingClientRect();
            let x = pos.clientX - bounds.left;
            let y = pos.clientY - bounds.top;
            circle.style.top = y + 'px';
            circle.style.left = x + 'px';
            circle._mouseDown = true;
            circle._animationEnded = false;
            self.ripples.push(circle);
        });
        document.body.addDelegatedEventListener('animationend', '.' + VUtils.get(self.options.classes, ''), self.rippleEnd.bind(self))
        if (!document.body._vRippleInit) {
            document.body.addMultiListener('mouseup touchend mouseleave rippleClose', e => {
                let keys = Object.keys(VRipple.instances);
                for (let key of keys) {
                    for (let ripple of VRipple.instances[key].ripples) {
                        self.rippleEnd.bind(VRipple.instances[key])(e, ripple);
                    }
                }
            })
            document.body._vRippleInit = true;
        }
    }

    rippleEnd(ev, el) {
        const parent = el.parentNode;
        if (parent) {
            if (ev.type === 'animationend') {
                el._animationEnded = true;
            } else {
                el._mouseDown = false;
            }
            if (!el._mouseDown && el._animationEnded) {
                if (el.classList.contains('to-remove')) {
                    el.parentNode.removeChild(el);
                    this.ripples.splice(this.ripples.indexOf(el), 1)
                } else {
                    el.classList.add('to-remove');
                }
            }
        }
    }
}

const rippler = new VRipple();



(function () {
    window._openVSelect = null;

    requestAnimationFrame(e => {
        document.body.addEventListener('click', ev => {
            if (window._openVSelect && ev.target.closest('v-select') !== window._openVSelect) {
                window._openVSelect.toggle(false);
            }
        })
    })

    class VSelectElement extends HTMLElement {
        constructor() {
            super();
            let self = this;
            self._in = this.attachInternals();
            self._in.role = 'select';
            self.setAttribute('tabindex', 0);
            self.update();
        }

        static get formAssociated() {
            return true;
        }

        static get observedAttributes() {
            return ['required', 'validity'];
        }

        get required() {
            return this.hasAttribute('required');
        }

        set required(flag) {
            this.toggleAttribute('required', Boolean(flag));
        }

        get name() {
            return this.getAttribute('name');
        }

        set name(val) {
            this.toggleAttribute('name', val);
        }

        get form() {
            return this._in.form;
        }

        get options() {
            return $$('v-options v-option', this);
        }

        get selected() {
            return $$('v-options v-option[selected]', this);
        }

        update() {
            let selected = [],
                lbl = $('v-label', this),
                fd = new FormData();
            this.selected.forEach(e => {
                selected.push(e.innerText);
                fd.append(this.name, e.value);
            })
            lbl.attributeChangedCallback('value', '', selected.join(", "));
            if (this.required && selected.length === 0) {
                this._in.setValidity({customError: true}, "Option is needed");
            } else {
                this._in.setValidity({});
            }
            this._in.setFormValue(fd);
        }

        checkValidity() {
            return this._in.checkValidity();
        }

        reportValidity() {
            return this._in.reportValidity();
        }

        toggle(open) {
            if (window._openVSelect && open) {
                window._openVSelect.toggleSelect(false);
            }
            const options = $('v-options', this);
            if (!open || this.isOpen) {
                options.style.maxHeight = '0';
                window._openVSelect = false;
                this.isOpen = false;
                this.update();
            } else {
                options.focus();
                let height = 0,
                    children = options.children;
                for (let i = 0; i < children.length; i++) {
                    height += children[i].offsetHeight;
                }
                options.style.maxHeight = height + 'px';
                window._openVSelect = this;
                this.isOpen = true;
            }
            let l = $('v-label', this).classList;
            if (this.isOpen) {
                l.add('open');
            } else {
                l.remove('open');
            }
        }
    }

    class VSelectOptionElement extends HTMLElement {
        constructor() {
            super();
            this._in = this.attachInternals();
            this._in.role = 'option';
            this.addEventListener('click', e => {
                let parent = this.parentNode.parentNode,
                    select = !this.selected;
                if (!parent.hasAttribute('multiple')) {
                    parent.toggle(false);
                    for (let item of parent.selected) {
                        if (item !== this) {
                            item.removeAttribute('selected');
                        }
                    }
                }
                if (!this.disabled) {
                    this.attributeChangedCallback('selected', false, select, true);
                    this.parentNode.parentNode.update();
                }
            });
        }

        static get observedAttributes() {
            return ['selected', 'disabled', 'value'];
        }

        attributeChangedCallback(name, oldValue, newValue, force) {
            if (name === 'selected' && this.hasAttribute('disabled')) {
                this.removeAttribute(name);
                return;
            }
            if (name === 'disabled' && newValue === true && this.hasAttribute('selected')) {
                this.attributeChangedCallback('selected', false, false);
            }

            if (force) {
                if (newValue) {
                    this.setAttribute(name, newValue);
                } else {
                    this.removeAttribute(name);
                }
            }
            this[name] = newValue;
        }
    }

    class VLabel extends HTMLElement {
        constructor() {
            super();
            this.empty = this.getAttribute('empty') || "";
            this.innerHTML = this.getAttribute("value") || this.empty;
            this.addEventListener('click', this.openPopUp.bind(this));
        }

        static get observedAttributes() {
            return ['empty', 'value'];
        }

        openPopUp() {
            this.parentNode.toggle(true);
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (name === 'value') {
                this.innerHTML = newValue || this.empty;
            }
            this[name] = newValue;
        }
    }

    customElements.define("v-label", VLabel);
    customElements.define("v-option", VSelectOptionElement);
    customElements.define("v-select", VSelectElement);
})();



class FormHandler {
    constructor(selector, parent, cb, err) {
        this.cb = cb || console.log;
        this.err = err || console.err;
        $(parent).addDelegatedEventListener('submit', selector, this.handleEvent.bind(this));
    }

    handleEvent(e, el) {
        e.preventDefault();
        if (el.checkValidity()) {
            const url = el.action ?? '';
            if (url === '') {
                console.error("No URL Found on Form", el);
                return;
            }
            fetch(el.action, {
                method: el.method.toUpperCase(),
                credentials: 'same-origin',
                body: new FormData(el),
                redirect: 'manual'
            }).then(res => {
                if (!res.ok) {
                    throw new Error('Network response errored');
                }
                return res.json()
            }).then(ev => this.cb(ev, el)).catch(ev => this.err(ev, el));
        } else {
            VUtils.forEach($$('input', el), ele => {
                if (!ele.checkValidity()) {
                    let parent = ele.parentNode;
                    parent.classList.remove('valid');
                    parent.classList.add('invalid');
                }
            });
        }
    }
}

(function () {
    class VInput extends HTMLElement {
        constructor() {
            super();
            let self = this;
            self.id = self.id || VUtils.tempId();
            let val = self.innerHTML;
            self.innerHTML = '';
            let input = self.input = self.createNew('input', {id: self.id})
            let label = self.createNew('label', {content: self.dataset.label});
            self.createNew('span', {classes: 'error', content: self.dataset.error});
            label.setAttribute('for', self.id);
            input.type = self.getAttribute('type') || 'text';
            input.value = val.trim();
            input.required = self.hasAttribute('required');
            input.name = self.getAttribute('name');
            input.addMultiListener('change input', self.cb.bind(self));
        }

        connectedCallback() {
            this.cb({currentTarget: this.input}, true);
        }

        cb(e, noInvalid) {
            let el = e.currentTarget
            let errorMessage = $('.error-message', el.find('form'));
            if (errorMessage) {
                errorMessage.classList.add('hide')
            }
            let cl = this.classList;
            if (el.value === "") {
                cl.remove('focus')
            } else {
                cl.add('focus')
            }
            if (el.checkValidity()) {
                cl.add('valid');
                cl.remove('invalid');
            } else {
                if (!noInvalid) {
                    cl.remove('valid');
                    cl.add('invalid');
                }
            }
        }
    }

    class VSwitch extends HTMLElement {
        constructor() {
            super();
            const id = this.dataset.id || VUtils.tempId();
            $('input', this).id = id;
            $('label', this).setAttribute('for', id);
        }
    }

    customElements.define("v-input", VInput);
    customElements.define("v-switch", VSwitch);

    if ($('#login')) {
        new FormHandler('form#login', 'body', () => {
            location.reload();
        }, (e, el) => {
            $('.error-message', el).classList.remove('hide');
        })
    }
})();
(() => {
    class VEdit {
        constructor(selector) {
            let self = this;
            self.editor = selector instanceof HTMLElement ? selector : $(selector);
            self.todoOnKey = {};
            self.keys = [];
            self.backup = [];
            self.taberr = [">", " ", "\n", "<"];
            self.name = 'veditor-' + self.editor.id;
            self.init();
            self.selfClosing = ["img", "area", "base", "br", "col", "embed", "hr", "img", "input", "link", "menuitem", "meta", "param", "source", "track"];
            self.restore();
        }

        init() {
            let self = this;
            self.editor.addEventListener('keydown', self.handleKey.bind(self));
            self.addKey('Tab', self.pressTab.bind(self));
            self.addKey('<', self.addEmptyTags.bind(self));
            self.addKey('ctrl-z', self.undo.bind(self));
            self.addKey('ctrl-s', self.store.bind(self));
            self.addKey('ctrl-shift-S', self.delete.bind(self));
            self.editor.classList.add(self.name, 'veditor')
        }

        registerSelfClosing(name) {
            this.selfClosing.push(name);
        }

        restore() {
            let item = localStorage.getItem(this.name);
            if (item) {
                this.editor.value = item;
            }
        }

        delete() {
            localStorage.removeItem(this.name);
            console.log(`[VEdit] Editor: ${this.name} removed`);
        }

        store() {
            localStorage.setItem(this.name, this.editor.value);
            console.log(`[VEdit] Editor: ${this.name} saved`);
        }

        handleKey(e) {
            let self = this;
            if ((e.ctrlKey && e.key === 'Control')
                || (e.shiftKey && e.key === 'Shift')) {
                return;
            }
            let key;
            if (e.ctrlKey && e.shiftKey) {
                key = 'ctrl-shift-' + e.key;
            } else if (e.ctrlKey) {
                key = 'ctrl-' + e.key;
            }
            if (key) {
                if (this.keys.indexOf(key) !== -1) {
                    e.preventDefault();
                    this.todoOnKey[key]();
                    return;
                }
            }
            let cont = self.editor.value;
            const pos = self.editor.selectionStart
            if (self.backup.length > 50) {
                self.backup.shift();
            }
            self.backup.push([cont, pos]);
            if (self.keys.indexOf(e.key) > -1) {
                e.preventDefault();
                let w = self.todoOnKey[e.key](pos, cont, this.editor);
                w[0].push(cont.substr(pos))
                self.afterWork(w);
            }
        }

        undo() {
            let back = this.backup.pop() || [this.editor.value, this.editor.selectionStart];
            this.editor.value = back[0];
            this.editor.setSelectionRange(back[1], back[1]);
        }

        afterWork(data) {
            this.setText(data[0].join(""));
            this.editor.setSelectionRange(data[1], data[1]);
        }

        setText(text) {
            this.editor.value = text;
        }

        addKey(name, func) {
            this.todoOnKey[name] = func;
            this.keys.push(name);
        }

        addEmptyTags(pos, cont, e) {
            return [[cont.substr(0, pos), '<>'], pos + 1];
        }

        pressTab(pos, cont, e) {
            let self = this;
            let sub, prevContent, moveTo = pos;
            if (pos === 0 || self.taberr.indexOf(cont[pos - 1]) !== -1) {
                sub = `    `;
                moveTo += 4;
                prevContent = cont.substr(0, pos);
            } else if (self.taberr.indexOf(cont[pos - 1]) === -1) {
                let i = 2;
                while (self.taberr.indexOf(cont[pos - i]) === -1 && pos - i > 0) {
                    i++;
                }
                if (pos - i > 0) {
                    i -= 1;
                }
                let gen = self.generateTag(cont.substr(pos - i, i).trim());
                sub = gen[0];
                moveTo = pos - i + gen[1];
                prevContent = cont.substr(0, pos - i);
            }
            return [[prevContent, sub], moveTo]
        }

        generateTag(sub) {
            let raw,
                groups = {'.': [], '#': []},
                keys = Object.keys(groups),
                cGroup = 'cl',
                split = sub.split(/([#.])/g);
            raw = split.shift();
            for (let item of split) {
                if (keys.indexOf(item) > -1) {
                    cGroup = item;
                    continue;
                }
                groups[cGroup].push(item);
            }
            let second = '';
            if (groups["."].length > 0) {
                second += ` class="${groups["."].join(" ")}"`;
            }
            if (groups['#'].length > 0) {
                second += ` id="${groups['#'].join("-")}"`;
            }
            const c = this.selfClosing;
            let close = '';
            if (c.indexOf(raw.trim()) === -1) {
                close = `</${raw}>`;
            }
            let pre = `<${raw}${second}>`;
            return [`${pre}${close}`, pre.length];
        }
    }

    class VEditor extends HTMLElement {
        constructor() {
            super();
            this.editor = document.createElement('textarea');
            this.editor.innerHTML = this.innerHTML;
            this.editor.id = this.getAttribute('name');
            for (let attribute of this.attributes) {
                this.editor.setAttribute(attribute.name, attribute.value);
            }
            this.innerHTML = '';
            this.appendChild(this.editor);
            this.edit = new VEdit(this.editor);
        }

        connectedCallback() {
            this.edit.restore();
        }

        disconnectedCallback() {
            this.edit.save();
        }
    }

    customElements.define("v-editor", VEditor);
})();
(() => {
    const mobileBreakpoint = 1023;
    const main = $('main');

    function isMobileDevice() {
        return window.matchMedia("(max-width: " + mobileBreakpoint + "px)").matches;
    }
    window.isMobileDevice = isMobileDevice;
    $('body').addDelegatedEventListener('click', '.nav-toggle', (e, el) => {
        if (isMobileDevice()) {
            main.classList.toggle('nav-open');
        }
    });

})();
(() => {
    class Router {
        constructor(options) {
            let self = this;
            self.options = options;
            document.body.addDelegatedEventListener('click', '[data-link]', (e, el) => {
                e.preventDefault();
                $$('[data-link].active').forEach(ex => (ex.classList.remove('active')));
                let loader = $('.loader').classList;
                loader.remove('hide');
                this.handleRouting(el.dataset).then(e => {
                    loader.add('hide');
                    el.classList.add('active');
                });
            })
            document.body.addEventListener('triggerRouter', e => {
                let storage = sessionStorage.getItem('url') || JSON.stringify({data: {link: $('[data-link].active').dataset.link}});
                this.handle(storage);
            })
            window.addEventListener('popstate', e => {
                this.handle(e.state);
            })
            self.components = {};
            window.dispatchEvent(new CustomEvent('routerReady'));
            window.routerIsReady = true;
        }

        handle(item) {
            if (!item) {
                return;
            }
            item = JSON.parse(item);
            this.handleRouting(item.data).then(r => {
                let it = $('[data-link="' + item.data.link + '"]');
                if (it) {
                    it.classList.add('active');
                }
            });
        }

        async handleRouting(dataset) {
            try {
                let url = dataset.link,
                    comp = this.components[url];
                if (url === "") return null;
                if (comp) url = comp.getUrl(dataset);
                let data = await this.handleRequest(url, true); // we know the admin backend only returns json so we cheat a bit :)
                if (data.reload) {
                    return location.reload();
                }
                comp = comp || this.components[data.component] || null;
                if (comp) {
                    sessionStorage.setItem('url', JSON.stringify({data: dataset}));
                    comp.handle(data, dataset).then(r => {
                        $(this.options.toReplace).innerHTML = r;
                        history.pushState(JSON.stringify({data: dataset}), document.title);
                    });
                } else {
                    await alert("Error");
                }
                return null;
            } catch (e) {
                return e;
            }
        }

        async handleRequest(url, forceJSON) {
            url = url.trim();
            if (url === '') return;
            // await ;)
            return await fetch(url, {
                credentials: "same-origin"
            }).then(res => {
                if (!res.ok) {
                    throw `URL is Status: ${res.status}`;
                }
                let c = res.headers.get("Content-Type");
                if (c.indexOf('json') !== -1 || forceJSON) return res.json();
                if (c.indexOf('text') !== -1) return res.text();
                return res.blob();
            }).catch(err => {
                console.error(err)
                return null;
            });
        }

        addComponent(name, component) {
            this.components[name] = component;
        }
    }

    window.router = new Router({
        toReplace: '.content-area'
    })
})();
'use strict';

class VTpeLCore {
    constructor(options = {}) {
        this.templates = {};
        this.dir = options.path || '/tpl/';
        this.suffix = options.suffix || 'tpl';
        this.path = options.template || `${this.dir}%s.${this.suffix}`;
        this.cache = options.cache === undefined ? true : options.cache;
    }

    async loadTemplate(name) {
        if (this.templates[name]) {
            return null;
        }
        let path = this.path.replace('%s', name);
        let rawData = await fetch(path, {cache: "force-cache"});
        if (rawData.ok) {
            let data = await rawData.text();
            this.addTpl(name, data);
        }
        return null;
    }

    async loadArray(names) {
        for (let name of names) {
            await this.loadTemplate(name);
        }
    }

    addTpl(name, content) {
        let temp = this.templates[name] = new VTpeLTemplate(name, content, this);
        temp.parseContent(this.cache);
    }

    async renderOn(name, data) {
        if (this.templates[name]) {
            return await this.templates[name].render(data);
        }
        return '';
    }
}

'use strict';

const VParserTypes = {
    content: 0,
    variable: 1,
    for: 2,
    forEach: 3,
    forContent: 4,
    forEnd: 5,
    if: 6,
    ifContent: 7,
    ifEnd: 8,
    assign: 9,
    include: 10,
    none: -1,
};


class VTpeLParser {
    constructor(name, content) {
        let self = this;
        self.name = name;
        self.legex = content.trim();
        self.index = 0;
        self.content = '';
        self.parsed = [];
        self.contexts = [0];
    }

    tokenize() {
        let self = this;
        for (self.index = 0; self.index < self.legex.length; self.index++) {
            let i = self.index,
                char = self.legex.charAt(i);
            if (self.nextContains('/*', i, true)) {
                self.extract('*/', VParserTypes.none)
            } else if (self.nextContains('// ', i, true)) {
                self.extract("\n", VParserTypes.none);
            } else if (self.nextContains('<!--', i, true)) {
                self.extract('-->', VParserTypes.none);
            } else if (self.nextContains('{for(', i, true)) {
                self.extract(')}', VParserTypes.for);
                self.contexts.push(VParserTypes.for);
            } else if (self.nextContains('{include(', i, true)) {
                self.extract(')}', VParserTypes.include);
                self.contexts.push(VParserTypes.include);
            }  else if (self.nextContains('{foreach(', i, true)) {
                self.extract(')}', VParserTypes.forEach);
                self.contexts.push(VParserTypes.forEach);
            } else if (self.nextContains('{/for}', i, true)) {
                self.addType(VParserTypes.forEnd);
                self.contexts.pop();
            } else if (self.nextContains('{if(', i, true)) {
                self.extract(')}', VParserTypes.if);
                self.contexts.push(VParserTypes.if);
            } else if (self.nextContains('{/if}', i, true)) {
                self.addType(VParserTypes.ifEnd);
                self.contexts.pop();
            } else if (self.nextContains('$${', i, true)) {
                self.extract('}', VParserTypes.assign);
            } else if (self.nextContains('${', i, true)) {
                self.extract('}', VParserTypes.variable);
            } else {
                self.content += char;
            }
        }
        self.addType(VParserTypes.content);
        return self.parsed;
    }

    addType(type) {
        let self = this;
        let content = self.content.replace(/^\n+|\n+$/g, ''),
            instructions = self.findInstructions(type);
        self.content = '';
        if (type !== VParserTypes.none) {
            if (type === VParserTypes.content && content === '') {
                return null;
            }
            return self.parsed.push({
                content: content,
                type: type,
                context: self.contexts[self.contexts.length - 1],
                instructions: instructions
            });
        }
        return null;
    }

    nextContains(find, index, add = false) {
        let count = this.nextContainsRaw(this.legex, find, index);
        if (add && count > 0) {
            this.index += count;
        }
        return count > 0 || count === -1;
    }

    nextContainsRaw(raw, find, index) {
        if (typeof find === "string") {
            find = find.split("");
        }
        let count = find.length;
        if (count < 1) {
            return -1;
        }
        for (let i = 0; i < count; i++) {
            let nc = raw.charAt(index + i);
            if ((find[i] === '\n' && nc === undefined)) {
                return count;
            }
            if (find[i] !== nc) {
                return 0;
            }
        }
        return count;
    }

    extract(findUntil = '}', type = 1) {
        let self = this;
        self.addType(0);
        findUntil = findUntil.split("")
        let content = '',
            index = self.index,
            legex = self.legex,
            firstFind = findUntil.shift();
        for (let i = self.index; i < legex.length; i++) {
            let char = legex.charAt(i);
            if (char === firstFind && self.nextContains(findUntil, i + 1)) {
                console.debug(`[Parser][${index} > ${i}] >> ${content}`);
                self.index = i + findUntil.length;
                self.content = content.trim();
                self.addType(type);
                return;
            }
            content += char;
        }
        if (firstFind === "\n") {
            self.index = legex.length;
            self.content = content.trim();
            self.addType(type);
            return
        }
        throw 'Template variable at Position: ' + index + ' not closed!';
    }

    // @todo implement split... is needed for if statements and math stuff or get it stupid!
    getOperator(string) {
        let operators = [];
        for (let i = 0; i < string.length; i++) {
            if (this.nextContainsRaw(string, "(", i)) {
                let innerCon = '';
                for (let x = 0; x < string.length; x++) {
                    let char = string.charAt(i + x);
                    if (char === ')') {
                        break;
                    }
                    innerCon += char;
                }
                operators = [...operators, this.getOperator(innerCon)];
            } else {
            }
        }
        return operators;
    }

    findInstructions(type) {
        if (type === VParserTypes.if) {
            return this.getOperator(this.content);
        }
        return [];
    }
}

'use strict';

class VTepLInterpreter {
    constructor(parser, core) {
        this.parser = parser;
        this.data = [];
        this.content = '';
        this.core = core;
    }

    async render(data) {
        let self = this;
        self.data = data;
        let newData = await self.interpreter(self.parser.parsed);
        self.data = [];
        return newData[0];
    }

    async interpreter(parsed, index = 0) {
        let self = this;
        let types = VParserTypes;
        let tplCont = '';
        for (let i = index; i < parsed.length; i++) {
            let item = parsed[i],
                content = item.content;
            switch (item.type) {
                case types.content:
                    tplCont += content;
                    break;
                case types.variable:
                    tplCont += self.getVariable(content)
                    break;
                case types.assign:
                    let data = content.split("="),
                        key = data.shift();
                    self.setVariable(data.join("=").trim(), key.trim());
                    break;
                case types.forEach:
                    let d = await this.handleForEach(item, parsed, i);
                    i = d[0];
                    tplCont += d[1];
                    break;
                case types.for:
                    let fd = await this.handleFor(item, parsed, i);
                    i = fd[0];
                    tplCont += fd[1];
                    break;
                case types.if:
                    let id = await this.handleIf(item, parsed, i);
                    i = id[0];
                    tplCont += id[1];
                    break;
                case types.include:
                    tplCont += await this.handleInclude(item);
                    break;
                case types.ifEnd:
                    tplCont += content;
                    return [tplCont, i];
                case types.forEnd:
                    tplCont += content;
                    return [tplCont, i];
                default:
                    console.warn("Invalid Type found");
                    break;
            }
        }
        return [tplCont, parsed.length];
    }

    getVariable(variable) {
        variable = variable.toString();
        if (this.data[variable]) {
            return this.data[variable];
        }
        let split = variable.split("."),
            prevVar = this.data;
        for (let i = 0; i < split.length; i++) {
            prevVar = prevVar[split[i]] || prevVar;
        }
        if (typeof prevVar === 'string') {
            return prevVar;
        }
        if (typeof prevVar === 'number') {
            return prevVar.toString();
        }
        return '';
    }

    setVariable(value, variable) {
        let c = this.getVariable(value);
        if (c !== '') {
            value = c;
        }
        this.data[variable] = value;
    }

    async handleForEach(item, parsed, i) {
        let content = item.content.split(" as ");
        let root = this.getVariable(content[0].trim());
        let addTo = 0,
            isInvalid = false;
        if (root === '') {
            isInvalid = true;
            root = {invalid: "true"};
        }
        let d = Object.keys(root),
            raw = '',
            varContent = content[1].trim().split(",");
        for (let x of d) {
            if (varContent.length === 2) {
                this.setVariable(x, varContent[1]);
            }
            this.setVariable(root[x], varContent[0]);
            let data = await this.interpreter(parsed, i + 1);
            addTo = data[1];
            raw += data[0];
        }
        if (isInvalid) {
            raw = '';
        }
        return [addTo, raw];
    }

    async handleInclude(item) {
        let split = item.content.split(";"),
            name = split.shift(),
            data = {};
        await this.core.loadTemplate(name);
        for (let item of split) {
            let d = item.split("="),
                index = d.shift(),
                dat = d.join("=");
            if (dat.startsWith("$")) {
                dat = this.getVariable(dat.substr(1, dat.length));
            }
            data[index] = dat;
        }
        return await this.core.renderOn(name, data);
    }

    async handleFor(item, parsed, ind) {
        let content = item.content.split(" as "),
            addTo = 0,
            count = content[0].trim().split(".."),
            max = parseInt(count[1]),
            min = parseInt(count[0]),
            newContent = '';
        for (let i = min; i < max; i++) {
            this.setVariable(i.toString(), content[1]);
            let data = await this.interpreter(parsed, ind + 1);
            addTo = data[1];
            newContent += data[0];
        }
        return [addTo, newContent];
    }

    async handleIf(item, parsed, i) {
        let data = await this.interpreter(parsed, i + 1);
        return [data[1], data[0]];
    }
}

'use strict';

class VTpeLTemplate {
    constructor(name, content, core) {
        this.name = name;
        this.tpl = content;
        this.parser = new VTpeLParser(name, content);
        this.core = core;
    }

    async render(data = {}) {
        return await new VTepLInterpreter(this.parser, this.core).render(data);
    }

    parseContent(cache) {
        if (cache) {
            let storage = localStorage.getItem("vtepl-"+this.name);
            if (storage) {
                this.parser.parsed = JSON.parse(storage);
                return;
            }
        }
        this.parser.tokenize();
        if (cache) {
            localStorage.setItem("vtepl-"+this.name, JSON.stringify(this.parser.parsed));
        }
    }
}

(() => {
    window.tpl = new VTpeLCore({
        template: '/admin/api/templateLoader?tpl=%s',
        cache: !document.body.hasAttribute('debug')
    });
    //preload includes to make sure they are loaded always :)
    window.tpl.loadArray([
        'includes/btn',
        'includes/input',
        'includes/select',
        'includes/svg',
        'includes/switch'
    ])
})();