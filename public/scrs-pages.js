export class SCRSPage extends SCRSComponent {
    #waitScreen = null;

    static startup(pageCreator=null) {
        if (!pageCreator) {
            pageCreator = ()=>new SCRSPage();
        }
        if (typeof(pageCreator) === "function") {
            window.addEventListener("load", ()=>{
                pageCreator();
            });
        }
    }

    constructor() {
        super(null, "main", document.querySelector("body > main"));

        this.#waitScreen = document.getElementById("waitScreen");
    }

    url(url, params) {
        let path = null;
        if (url instanceof Array) {
            path = url.join("/");
        }
        else if (typeof(url) === "string") {
            path = url;
        }
        else {
            return false;
        }

        let query = null;
        if (params && typeof(params) === "object") {
            query = new URLSearchParams(params).toString();
        }

        return (!query ? path : `${path}?${query}`);
    }

    forward(url, params) {
        const href = this.url(url, params);
        if (href === false) {
            return false;
        }
        location.assign(href);
        return true;
    }

    backword() {
        history.back();
    }

    overwrite(url, params) {
        const href = this.url(url, params);
        if (href === false) {
            return false;
        }
        location.replace(href);
        return true;
    }

    reload(isForce=true) {
        location.reload(isForce);
    }

    submit(method=null, url=null, query=null) {
        const form = document.querySelector("body > main > form");
        let _method = form.querySelector("input[type='hidden'][name='_method']");
        if (["POST","GET"].includes(method)) {
            if (_method) {
                form.removeChild(_method);
            }
            form.method = method;
        }
        else if (["PUT","PATCH","DELETE"].includes(method)) {
            if (!_method) {
                _method = document.createElement("input");
                _method.type = "hidden";
                _method.name = "_method";
                form.appendChild(_method);
            }
            _method.value = method;
            form.method = "POST";
        }
        if (url) {
            const action = this.url(url, query);
            if (action) {
                form.action = action;
            }
        }
        form.submit();
    }

    get(url=null, query=null) {
        return this.submit("GET", url, query);
    }

    post(url=null, query=null) {
        return this.submit("POST", url, query);
    }

    put(url=null, query=null) {
        return this.submit("PUT", url, query);
    }

    patch(url=null, query=null) {
        return this.submit("PATCH", url, query);
    }

    delete(url=null, query=null) {
        return this.submit("DELETE", url, query);
    }

    waitScreen(flag) {
        if (flag) {
            this.#waitScreen.classList.remove("hidden");
        }
        else {
            this.#waitScreen.classList.add("hidden");
        }
    }
}
