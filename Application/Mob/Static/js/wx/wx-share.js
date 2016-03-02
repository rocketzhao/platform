var isWXBrowser = window.isWXBrowser;
// isWXBrowser = true;
var defaultWXData = {
	"link" : "http://www.dingdangpai.com",
	"title" : "叮当派",
	"desc" : "国际化家庭活动社群",
	"imgUrl" : "http://www.dingdangpai.com/static/images/icoS.png",
	"isAddPoints":true
};
var getWxparamsCount = 3;
$(function() {
	var url = window.location.href;
	url = url.split("#")[0];
	var wxData = $.extend(defaultWXData, window.wxdata);
	var initCallback = function() {
		wx
				.ready(function() {
					var wxData = $.extend(defaultWXData, window.wxdata);
					wxData.success = function() {
						wxSharMask.remove();
						if(wxData.isAddPoints){
							$.ajax({
								"url" : basePath + "/user/points/share.json",
								"type" : "POST"
							});
						}
						if (window.wxdata
								&& typeof window.wxdata.success === 'function') {
							window.wxdata.success();
						}
					};
					wxData.cancel = function() {
						wxSharMask.remove();
						if (window.wxdata
								&& typeof window.wxdata.cancel === 'function') {
							window.wxdata.cancel();
						}
					};
					wx.onMenuShareTimeline(wxData);
					wx.onMenuShareAppMessage(wxData);
					wx.onMenuShareQQ(wxData);
					wx.onMenuShareWeibo(wxData);
				});
		wx.error(function(res) {
			$.ajax({
				"url" : basePath + "/share/WX/params",
				"data" : {
					"url" : url,
					"forceRefresh":true
				},
				"type" : "POST",
				"success" : function(data) {
					var opts = $.extend(data, {
						"jsApiList" : [ "onMenuShareTimeline",
								"onMenuShareAppMessage", "onMenuShareQQ",
								"onMenuShareWeibo" ]
					});
					if(getWxparamsCount > 0){
						wx.config(opts);
						wxInit(url, initCallback);
						getWxparamsCount --;
					}
					
				}
			});
		});
	};
	if (isWXBrowser) {
		var wxSharMask = $('<div class="ext8" id="wxSharMask"> <div class="flaot10"><img src="'
				+ imgPath + '/fx_zy.png"/></div></div>');
		wxInit(url, initCallback);
		$("#wxShare").click(function() {
			$(".content").append(wxSharMask);
		});
		wxSharMask.find("img").click(function() {
			wxSharMask.remove();
		});
	} else {

		var qrcodeBox = $('<div id="qrcodeBox" class="ext9" style="display:none;"> <div id="info" class="flaot11"><div class="code"><div class="top"><div class="left">分享到微信朋友圈</div>'
				+ '<div class="right close" ><img src="'
				+ imgPath
				+ '/end2.0.png"/></div></div> <div class="center">'
				+ '<img src="'
				+ basePath
				+ '/qrcode.jpg?url='
				+ url
				+ '&w=220&h=220"/></div><div class="bottom" style="position: relative;"><span class="s1">打开微信，使用 “扫一扫” 即可将网页分享到我的朋友圈</span></div></div></div>');
		$(".content").append(qrcodeBox);
		$("#wxShare").click(function() {
			qrcodeBox.show();

		});
		qrcodeBox.find(".close").click(function() {
			qrcodeBox.hide();
		});
	}
});

function wxInit(url, callback) {
	if (isWXBrowser) {
		$.ajax({
			"url" : basePath + "/share/WX/params",
			"data" : {
				"url" : url
			},
			"type" : "POST",
			"success" : function(data) {
				var opts = $.extend(data, {
					"jsApiList" : [ "onMenuShareTimeline",
							"onMenuShareAppMessage", "onMenuShareQQ",
							"onMenuShareWeibo" ]
				});
				wx.config(opts);
				if (typeof callback === 'function') {
					callback(true);
				}
			},
			"error" : function() {
				if (typeof callback === 'function') {
					callback(true);
				}
			}
		});
	} else {
		if (typeof callback === 'function') {
			callback(false);
		}
	}
}