export class SCRSDialog extends SCRSComponent {
    #params = null;

    #eventTarget = null;
    #modalTarget = null;

    constructor(owner, dialogId, initializer=null, eventNames=null) {
        super(owner, dialogId, document.getElementById(dialogId));

        this.#eventTarget = new Proxy(this.target, new SCRSElementProxyHandler(this, dialogId));
        this.#modalTarget = new bootstrap.Modal(this.target, {});

        this.#eventTarget.handle("show.bs.modal", [ this, "show" ]);
        this.#eventTarget.handle("shown.bs.modal", [ this, "shown" ]);
        this.#eventTarget.handle("hide.bs.modal", [ this, "hide" ]);
        this.#eventTarget.handle("hidden.bs.modal", [ this, "hidden" ]);
        this.#eventTarget.handle("hidePrevented.bs.modal", [ this, "hidePrevented" ]);

        if (typeof(initializer) === "function") {
            initializer.apply(this, []);
        }
        if (eventNames instanceof Array) {
            eventNames
                .filter((eventName)=>(typeof(eventName) === "string"))
                .forEach((eventName)=>this.handle(eventName));
        }
    }

    handles(...eventNames) {
        eventNames
            .filter((eventName)=>(typeof(eventName) === "string"))
            .forEach((eventName)=>this.handle(eventName));
    }

    handle(eventName) {
        this.target.addEventListener(eventName, (e)=>{
            const methodName = this.id + "_" + eventName;
            const method = this.owner[methodName] ?? null;
            if (typeof(method) === "function") {
                method.apply(this.owner, [ e ]);
            }
        });
        return this;
    }

    get params() {
        return this.#params;
    }

    open(params=null) {
        this.#params = params || {};
        this.#modalTarget.show();
    }

    close() {
        this.#modalTarget.hide();
    }

    show(e) {
        if (!this.cancelableRaise("show", { sender: this })) {
            e.preventDefault();
        }
    }

    shown(e) {
        this.raise("shown", { sender: this });
    }

    hide(e) {
        if (!this.cancelableRaise("hide", { sender: this })) {
            e.preventDefault();
        }
    }

    hidden(e) {
        this.raise("hidden", { sender: this });
    }

    hideProvented(e) {
        this.raise("hideProvented", { sender: this });
    }
}
