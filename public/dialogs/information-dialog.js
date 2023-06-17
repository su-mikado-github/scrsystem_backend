import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSInformationDialog extends SCRSDialog {
    #ok = null;

    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.#ok = this.action("ok", [ "click" ]);
    }

    ok_click(e) {
        this.raise("ok", this.params||{});
    }
}
