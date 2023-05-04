export class SCRSPage extends SCRSComponent {
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
        else {
            return false;
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
}
