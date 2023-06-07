import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSReserveDialog extends SCRSDialog {
    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.action("ok").handle("click");
    }

    ok_click(e) {
        this.raise("ok", {});
//        this.close();
    }
}
