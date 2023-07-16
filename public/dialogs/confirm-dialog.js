import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSConfirmDialog extends SCRSDialog {
    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.action("ok").handle("click");
        this.action("cancel").handle("click");
    }

    ok_click(e) {
        this.raise("ok", this.params||{});
//        this.close();
    }

    cancel_click(e) {
        this.raise("cancel", this.params||{});
    }
}
