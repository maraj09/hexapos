// FullScreen Funtionality Start
var elem = document.documentElement;
var size_icon = document.querySelector("#screen_size_icon");
var isFullscreen = 0;
function toggle_Screen() {
	if (isFullscreen == 0) {
		size_icon.classList.toggle("glyphicon-fullscreen");
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.webkitRequestFullscreen) {
			/* Safari */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) {
			/* IE11 */
			elem.msRequestFullscreen();
		}
		isFullscreen = 1;
	} else if (isFullscreen == 1) {
		size_icon.classList.toggle("glyphicon-fullscreen");
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.webkitExitFullscreen) {
			/* Safari */
			document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) {
			/* IE11 */
			document.msExitFullscreen();
		}
		isFullscreen = 0;
	}
	return false;
}
if (document.addEventListener) {
	document.addEventListener("webkitfullscreenchange", exitHandler, false);
	document.addEventListener("mozfullscreenchange", exitHandler, false);
	document.addEventListener("fullscreenchange", exitHandler, false);
	document.addEventListener("MSFullscreenChange", exitHandler, false);
}
function exitHandler() {
    if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        isFullscreen = 0;
        size_icon.classList.add("glyphicon-fullscreen");
    }
}
// For FullScreen Funtionality End