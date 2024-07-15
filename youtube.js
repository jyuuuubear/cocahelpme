
//////////////////////////////////////////////////////////////////////
//                      BLOG BGM With Youtube 
//                   BY. https://d-dl.tistory.com/
//////////////////////////////////////////////////////////////////////

// [  SETTING  ]

const youtubeVideoKey = "cfTLvQ9TZhg";
const youtubePlayListKey = "PLV98xFIHm8QmQ734e2SGZjmWbblBR9Nrr";
const URLChange = true;
const setShuffle = true;
const volumeUpDownValue = 10; // 0~100
const config = {
    'loop': 1,
    'autoplay': 1,
    'controls': 1,
    'disablekb': 1,
    'enablejsapi': 1,
    'iv_load_policy': 3,
	'origin': document.location.origin
};

//////////////////////////////////////////////////////////////////////

// [  BODY  ]

let YTplayer = null;

window.addEventListener('DOMContentLoaded', (event) => {
	console.clear();
	if (parent[0].name !== '') return;

	let docIframe = document.createElement("iframe");
	docIframe.style.cssText = `width: 100vw; height: 100vh; overflow: auto; border: 0; margin: 0; position: absolute;`;
	docIframe.name = "MAIN";
	docIframe.id = "BGM_DOC_IFRAME";
	docIframe.src = document.location;
	docIframe.addEventListener("load", function(e) {
		const iframePath = docIframe.contentDocument.location.pathname;
		if (URLChange && document.location.pathname !== iframePath){
			history.replaceState(null, null, iframePath);
		}
	});

	let musicDiv = document.createElement("div");
	musicDiv.name = "MUSIC";
	musicDiv.id = "Youtube_Music_Iframe";

	document.body.style.overflow = "hidden";
	document.body.innerHTML = "";
	document.body.prepend(docIframe);
	document.body.prepend(musicDiv);

	let tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
});

function onYouTubeIframeAPIReady() {
    YTplayer = new YT.Player('Youtube_Music_Iframe', {
        videoId: youtubeVideoKey,
        playerVars: config,
        height: '0',
        width: '0',
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange,
			'onError': onPlayerError
        }
    });
}

let listSet = false;
function onPlayerReady(event) {
	if (!listSet && youtubePlayListKey !== null) {
        YTplayer.loadPlaylist({
			list:youtubePlayListKey,
			listType:'playlist',
			index:0,
			startSeconds:0,
			suggestedQuality:'small'
			});
		YTplayer.setShuffle(setShuffle);
		listSet = true;
    }
    event.target.playVideo();
}

function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.ENDED) {
		if (listSet)
			YTplayer.nextVideo();
		else
			YTplayer.playVideo();
    }
}

function onPlayerError(event) {
	console.error(event.data);
}

function receiveBGMMessage(event)
{
	let state = event.data.BGM;
	if (!state) return;
	switch (state.toString().toLowerCase()) {
		case 'play':
			YTplayer.playVideo();
			break;
		case 'pause':
			YTplayer.pauseVideo();
			break;
		case 'previous':
			YTplayer.previousVideo();
			YTplayer.playVideo();
			break;
		case 'next':
			YTplayer.nextVideo();
			YTplayer.playVideo();
			break;
		case 'volumeup':
			YTplayer.setVolume(YTplayer.getVolume() + volumeUpDownValue);
			break;
		case 'volumedown':
			YTplayer.setVolume(YTplayer.getVolume() - volumeUpDownValue);
			break;
	}
	console.log(`%c♬ BGM : NOW "${state.toUpperCase()}"`, 'color:blue');
}
addEventListener("message", receiveBGMMessage, false);