(function(){
    // Expose openVideoWindow globally
    function openVideoWindow(videoUrl, qid) {
        var win = document.getElementById('videoWindow');
        var content = document.getElementById('videoContent');
        if (!win || !content) return window.open(videoUrl, '_blank');
        content.innerHTML = '';
        // detect YouTube url
        var ytMatch = videoUrl.match(/(?:youtube\.com.*v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
        if (ytMatch) {
            var id = ytMatch[1];
            var iframe = document.createElement('iframe');
            iframe.width = '100%';
            iframe.height = '100%';
            iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1';
            iframe.frameBorder = '0';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            content.appendChild(iframe);
        } else if (videoUrl.match(/\.(mp4|webm|ogg)$/i)) {
            var vid = document.createElement('video');
            vid.src = videoUrl;
            vid.controls = true;
            vid.autoplay = true;
            vid.style.width = '100%';
            vid.style.height = '100%';
            content.appendChild(vid);
        } else {
            // fallback: open in new tab
            window.open(videoUrl, '_blank');
            return;
        }
        win.classList.remove('hidden');
        makeDraggable(document.getElementById('videoWindow'), document.getElementById('videoHeader'));
    }

    function makeDraggable(el, handle) {
        if (!el || !handle) return;
        var pos = {x: 0, y: 0, left: 0, top: 0};
        handle.onmousedown = dragMouseDown;
        handle.ontouchstart = touchStart;

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            pos.left = el.offsetLeft;
            pos.top = el.offsetTop;
            pos.x = e.clientX;
            pos.y = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function touchStart(e) {
            var touch = e.touches[0];
            pos.left = el.offsetLeft;
            pos.top = el.offsetTop;
            pos.x = touch.clientX;
            pos.y = touch.clientY;
            document.ontouchend = closeDragElement;
            document.ontouchmove = elementTouchDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            var dx = e.clientX - pos.x;
            var dy = e.clientY - pos.y;
            el.style.left = (pos.left + dx) + 'px';
            el.style.top = (pos.top + dy) + 'px';
        }

        function elementTouchDrag(e) {
            var touch = e.touches[0];
            var dx = touch.clientX - pos.x;
            var dy = touch.clientY - pos.y;
            el.style.left = (pos.left + dx) + 'px';
            el.style.top = (pos.top + dy) + 'px';
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
            document.ontouchend = null;
            document.ontouchmove = null;
        }
    }

    function closeVideoWindow(){
        var win = document.getElementById('videoWindow');
        var content = document.getElementById('videoContent');
        if (!win || !content) return;
        content.innerHTML = '';
        win.classList.add('hidden');
    }

    // Expose functions
    window.openVideoWindow = openVideoWindow;
    window.closeVideoWindow = closeVideoWindow;

    document.addEventListener('DOMContentLoaded', function(){
        var closeBtn = document.getElementById('videoClose');
        if(closeBtn) closeBtn.addEventListener('click', closeVideoWindow);
        // Escape to close
        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape') closeVideoWindow();
        });
    });
})();
