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
        return this;
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
            return (eventName, handler=null)=>this.#handle(target, eventName, handler);
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

    field(componentName, propertyName="field") {
        const target = this.target.querySelector(`[data-${propertyName}='${componentName}']`);
        return (!target ? null : new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
    }

    fieldNoProxy(componentName, propertyName="field") {
        return this.target.querySelector(`[data-${propertyName}='${componentName}']`);
    }

    action(componentName, propertyName="action") {
        const target = this.target.querySelector(`[data-${propertyName}='${componentName}']`);
        return (!target ? null : new Proxy(target, new SCRSElementProxyHandler(this, componentName)));
    }

    actionNoProxy(componentName, propertyName="action") {
        return this.target.querySelector(`[data-${propertyName}='${componentName}']`);
    }
}
