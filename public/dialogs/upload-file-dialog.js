import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSUploadFileDialog extends SCRSDialog {
    #target_file = null;
    #input_file = null;
    #form = null;

    #filename = null;
    #error = null;

    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.#target_file = this.field("target_file")?.handle("click")?.handle("dragover")?.handle("drop");
        this.#input_file = this.field("input_file");
        this.#form = this.field("upload_form");

        this.#filename = this.field("filename");
        this.#error = this.field("error");
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
        if (e.dataTransfer.files.length > 1) {
            this.#error.innerText = '一度にアップロードできるファイルは、１ファイルのみです。';
            this.#error.removeClass("d-none");
        }
        else if (e.dataTransfer.files.length == 1) {
            this.#error.addClass("d-none");
            this.#input_file.files = e.dataTransfer.files;
            this.#target_file.style.backgroundColor = "transparent";

            const filenames = [];
            for (let file of this.#input_file.files) {
                filenames.push(file.name);
            }
            this.#filename.innerHTML = filenames.join("");
            this.#filename.removeClass("d-none");
        }
        else {
            this.#filename.innerHTML = "";
            this.#filename.addClass("d-none");
        }
    }

    ok_click(e) {
        const form_data = new FormData(this.#form._target);

        axios.post(this.#form.action, form_data)
            .then((response)=>{
                this.raise("ok", response);
            })
            .catch((error)=>{
                this.raise("error", {})
            });
    }
}
