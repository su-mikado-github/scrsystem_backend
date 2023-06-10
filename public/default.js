class Enum {
    constructor(source) {
        Object.setPrototypeOf(this, source);
        // for (let name in source) {
        //     this[name] = source[name];
        // }
    }
}

class SCRSElementProxyHandler {
    #owner = null;
    #targetName = null;

    #handle(target, eventName, handler) {
        if (target) {
            let handlerObject = this.#owner;
            let handlerName = this.#targetName + "_" + eventName;
            let handlerMethod = null;
            if (typeof(handler) === "string") {
                handlerName = handler;
                handlerMethod = handlerObject[handlerName] ?? null;
            }
            else if (typeof(handler) === "function") {
                handlerMethod = handler;
            }
            else if (handler instanceof Array) {
                const [ ho, hm ] = handler;
                if (typeof(ho) === "object") {
                    handlerObject = ho;
                }
                if (typeof(hm) === "function") {
                    handlerMethod = hm;
                }
                else if (typeof(hm) === "string") {
                    handlerName = hm;
                    handlerMethod = handlerObject[handlerName] ?? null;
                }
            }
            else if (typeof(handler) === "object" && handler) {
                handlerObject = handler;
                handlerMethod = handlerObject[handlerName] ?? null;
            }
            else {
                handlerMethod = handlerObject[handlerName] ?? null;
            }

            if (typeof(handlerMethod) === "function") {
                target.addEventListener(eventName, (e)=>handlerMethod.apply(this.#owner, [ e ]));
            }
        }
    }

    #text(target, texts) {
        if (target) {
            target.innerText = texts.join("\n");
        }
    }

    #html(target, htmls) {
        if (target) {
            target.innerHTML = htmls.join("<br>");
        }
    }

    #css(target, flag, classNames) {
        if (target) {
            if (flag) {
                target.classList.add.apply(target.classList, classNames);
            }
            else {
                target.classList.remove.apply(target.classList, classNames);
            }
        }
    }

    #rcss(target, flag, classNames) {
        if (target) {
            if (flag) {
                target.classList.remove.apply(target.classList, classNames);
            }
            else {
                target.classList.add.apply(target.classList, classNames);
            }
        }
    }

    #addClass(target, classNames) {
        if (target) {
            target.classList.add.apply(target.classList, classNames);
        }
    }

    #removeClass(target, classNames) {
        if (target) {
            target.classList.remove.apply(target.classList, classNames);
        }
    }

    #hasClass(target, className) {
        return (!target ? false : target.classList.contains(className));
    }

    constructor(owner, targetName) {
        this.#owner = owner;
        this.#targetName = targetName;
    }

    get owner() {
        return this.#owner;
    }

    get targetName() {
        return this.#targetName;
    }

    get(target, name, receiver) {
        if (name === "handle") {
            return (eventName, handler=null)=>{ this.#handle(target, eventName, handler); return receiver; }
        }
        else if (name === "text") {
            return (...texts)=>{ this.#text(target, texts); return receiver; }
        }
        else if (name === "html") {
            return (...htmls)=>{ this.#html(target, htmls); return receiver; }
        }
        else if (name === "css") {
            return (flag, ...classNames)=>{ this.#css(target, flag, classNames); return receiver; }
        }
        else if (name === "rcss") {
            return (flag, ...classNames)=>{ this.#rcss(target, flag, classNames); return receiver; }
        }
        else if (name === "addClass") {
            return (...classNames)=>{ this.#addClass(target, classNames); return receiver; }
        }
        else if (name === "removeClass") {
            return (...classNames)=>{ this.#removeClass(target, classNames); return receiver; }
        }
        else if (name === "hasClass") {
            return (className)=>this.#hasClass(target, className);
        }
        else if (name === "_target") {
            return target;
        }
        else if (this[name] ?? null) {
            return this[name];
        }
        else {
            const value = target[name];
            if (typeof(value) === "function") {
                return value.bind(target);
            }
            else {
                return value;
            }
        }
    }

    set(target, name, value) {
        target[name] = value;
        return true;
    }
}

class SCRSComponent {
    #owner = null;
    #id = null;

    #target = null;

    constructor(owner, id, target) {
        this.#owner = owner ?? this;
        this.#id = id;
        this.#target = target;
    }

    get owner() {
        return this.#owner;
    }

    get id() {
        return this.#id;
    }

    get target() {
        return this.#target;
    }

    raise(eventName, detail=null) {
        return this.target.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    cancelableRaise(eventName, detail=null) {
        const e = new CustomEvent(eventName, { detail, cancelable: true });
        return this.target.dispatchEvent(e);
    }

    find(id) {
        const target = this.target.querySelector(`#${id}`);
        return (!target ? null : new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
    }

    findNoProxy(id) {
        return this.target.querySelector(`#${id}`);
    }

    fields(componentName, propertyName="field") {
        const targets = this.target.querySelectorAll(`[data-${propertyName}='${componentName}']`);
        const result = [];
        for (let target of targets) {
            result.push(new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
        }
        return result;
    }

    field(componentName, propertyName="field") {
        const target = this.target.querySelector(`[data-${propertyName}='${componentName}']`);
        return (!target ? null : new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
    }

    fieldsNoProxy(componentName, propertyName="field") {
        const targets = this.target.querySelectorAll(`[data-${propertyName}='${componentName}']`);
        const result = [];
        for (let target of targets) {
            result.push(target);
        }
        return result;
    }

    fieldNoProxy(componentName, propertyName="field") {
        return this.target.querySelector(`[data-${propertyName}='${componentName}']`);
    }

    actions(componentName, handles, propertyName="action") {
        const targets = this.target.querySelectorAll(`[data-${propertyName}='${componentName}']`);
        const handleArray = (handles instanceof Array && handles.length > 0 ? handles : []);
        const result = [];
        for (let target of targets) {
            const actionTarget = new Proxy(target, new SCRSElementProxyHandler(this, componentName));
            for (let handle of handles) {
                actionTarget.handle(handle);
            }
            result.push(actionTarget);
        }
        return result;
    }

    action(componentName, handles, propertyName="action") {
        const target = this.target.querySelector(`[data-${propertyName}='${componentName}']`);
        const result = (!target ? null : new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
        if (result) {
            if (handles instanceof Array && handles.length > 0) {
                for (let handle of handles) {
                    result.handle(handle);
                }
            }
        }
        return result;
    }

    actionsNoProxy(componentName, propertyName="action") {
        const targets = this.target.querySelectorAll(`[data-${propertyName}='${componentName}']`);
        const result = [];
        for (let target of targets) {
            result.push(target);
        }
        return result;
    }

    actionNoProxy(componentName, propertyName="action") {
        return this.target.querySelector(`[data-${propertyName}='${componentName}']`);
    }

    proxy(target, componentName) {
        return (!target ? null : new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
    }
}
