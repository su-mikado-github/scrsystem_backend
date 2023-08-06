<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>カメラテスト</title>
    <script type="text/javascript">
window.addEventListener("load", function() {
    const cameraImage = document.getElementById("cameraImage");
    const cameraShooting = document.getElementById("cameraShooting");
    cameraShooting.addEventListener("click", function(e) {
        cameraImage.click();
    });
});
    </script>
</head>
<body><form id="form1" enctype="multipart/form-data">
<input id="cameraImage" type="file" accept="image/*" capture="environment" name="camera_image">
<button id="cameraShooting" type="button">カメラ撮影</button>
</form>
<a href="https://line.me/R/nv/camera/">LINEブラウザでカメラ起動</a>
</body>
</html>
