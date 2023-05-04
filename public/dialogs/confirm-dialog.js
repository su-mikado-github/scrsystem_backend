import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSConfirmDialog extends SCRSDialog {
    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.action("ok").handle("click");
    }

    ok_click(e) {
        this.close();
    }
}
