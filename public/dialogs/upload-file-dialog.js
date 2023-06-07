import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSUploadFileDialog extends SCRSDialog {
    #target_file = null;
    #input_file = null;
    #form = null;

    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.#target_file = this.field("target_file")?.handle("click")?.handle("dragover")?.handle("drop");
        this.#input_file = this.field("input_file")?.handle("change");
        this.#form = this.field("upload_form");

        this.action("ok")?.handle("click");
    }

    target_file_click(e) {
        this.#input_file.click();
    }

    target_file_dragover(e) {
        e.preventDefault();
        this.#target_file.style.backgroundColor = "rgba(1,1,1,0.8)";
    }

    target_file_drop(e) {
        e.preventDefault();
        this.#input_file.files = e.dataTransfer.files;
        for (let file of this.#input_file.files) {
            console.log(file.name);
        }
        this.#target_file.style.backgroundColor = "transparent";
    }

    input_file_change(e) {
        for (let file of e.target.files) {
            console.log(file.name);
        }
    }

    ok_click(e) {
        const form_data = new FormData(this.#form._target);
        // const xhr = new XMLHttpRequest();
        // // xhr.upload.addEventListener("load", (e)=>console.log(xhr.responseText));
        // // xhr.upload.addEventListener("error", (e)=>console.log(xhr.responseText));
        // xhr.open("POST", this.#form.action, true);
        // xhr.upload.addEventListener("progress", (e)=>{
        //     console.log(e.total);
        // });
        // xhr.addEventListener("readystatechange", ()=>{
        //     if (xhr.readyState === XMLHttpRequest.DONE) {
        //         const status = xhr.status;
        //         if (status === 0 || (status >= 200 && status < 400)) {
        //             console.log("OK");
        //         }
        //         else {
        //             console.log("ERROR: "+status);
        //         }
        //     }
        // });
        // xhr.send(form_data);
        axios.post(this.#form.action, form_data)
            .then((response)=>{
                this.raise("ok", response);
            })
            .catch((error)=>{
                this.raise("error", {})
            });

//        this.raise("ok", { files: this.#input_file.files });
//        this.close();
    }
}
