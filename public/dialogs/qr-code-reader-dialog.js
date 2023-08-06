//import jsQR from '/jsqr';

import { SCRSDialog } from '/scrs-dialogs.js';

export class SCRSQrCodeReaderDialog extends SCRSDialog {

    #isShow = false;

    #camera = null;
    #video = null;
    #context2d = null;
    #code = null;

    constructor(owner, id, initializer=null, eventNames=null) {
        super(owner, id, initializer, eventNames);

        this.#camera = this.actionNoProxy("camera");  //this.action("camera").handle("click");
        this.#camera.addEventListener("click", (e)=>this.camera_click(e));
    }

    camera_click(e) {
        if (this.#code?.data) {
            this.raise("read", { sender: this, code: this.#code?.data });
        }
    }

    shown(e) {
        super.shown(e);

        this.#isShow = true;

        this.cameraStart();
    }

    hide(e) {
        this.cameraStop();

        this.#isShow = false;

        super.hide(e);
    }

    cameraStart() {
        if (!this.#video) {
            this.#video = document.createElement('video');
            navigator.mediaDevices.getUserMedia({ video: { facingMode: { exact: "environment" }, width: { min:720 }, height: { min: 720 } } }).then((stream)=>{
                this.#video.srcObject = stream;
                this.#video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
                this.#video.play();
                requestAnimationFrame(()=>this.tick());
            }).catch((e)=>{
                console.log(e.name + ": " + e.message);
                navigator.mediaDevices.getUserMedia({ video:true }).then((stream)=>{
                    this.#video.srcObject = stream;
                    this.#video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
                    this.#video.play();
                    requestAnimationFrame(()=>this.tick());
                }).catch((e)=>{
                    console.log(e.name + ": " + e.message);
                });
            });
        }
    }

    cameraStop() {
        if (this.#video) {
            this.#video.srcObject.getTracks().forEach(track => track.stop());
            this.#video.pause();
            this.#video.remove();
            this.#video = null;
        }
    }

    drawLine(context, begin, end, color) {
        context.beginPath();
        context.moveTo(begin.x, begin.y);
        context.lineTo(end.x, end.y);
        context.lineWidth = 4;
        context.strokeStyle = color;
        context.stroke();
    }

    tick() {
        if (this.#camera) {
            if (this.#video && this.#video.readyState === this.#video.HAVE_ENOUGH_DATA) {
                const sw = this.#video.videoWidth;
                const sh = this.#video.videoHeight;
                const dw = this.#camera.width;
                const dh = dw * sh / sw;

                this.#camera.height = dh;
                this.#camera.hidden = false;

                if (!this.#context2d) {
                    this.#context2d = this.#camera.getContext("2d", { willReadFrequently: true });
                }

                this.#context2d.drawImage(this.#video, 0, 0, this.#camera.width, this.#camera.height);
                var imageData = this.#context2d.getImageData(0, 0, this.#camera.width, this.#camera.height);
                var code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });
                if (code) {
                    this.drawLine(this.#context2d, code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                    this.drawLine(this.#context2d, code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                    this.drawLine(this.#context2d, code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                    this.drawLine(this.#context2d, code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                    this.#code = code;
                }
                else {
                    this.#code = null;
                }
            }
        }
        if (this.#isShow) {
            requestAnimationFrame(()=>this.tick());
        }
    }
}
