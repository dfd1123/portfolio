function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function initOnReady() {
	var udf_datafeed = new Datafeeds.UDFCompatibleDatafeed($('input[name="setting_url"]').val());

	var widget = window.tvWidget = new TradingView.widget({
		// debug: true, // uncomment this line to see Library errors and warnings in the console
		symbol: $('input[name="chart_symbol"]').val().toUpperCase(),
		autosize: true,
		width:600,
		interval: '60',
		toolbar_bg: '#f4f7f9',
		container_id: "chartdiv",
		top_toolbar : false,
		hide_side_toolbar : true,

		//	BEWARE: no trailing slash is expected in feed URL
		
		datafeed: udf_datafeed,
		library_path: "/vendor/tradingview_new/charting_library/",
		locale: getParameterByName('lang') || "en",

		disabled_features: ["save_chart_properties_to_local_storage", "volume_force_overlay","timeframes_toolbar","left_toolbar"],
		enabled_features: ["move_logo_to_main_pane"],
		overrides: {
			"mainSeriesProperties.style": 1,
			"symbolWatermarkProperties.color" : "#944",
			"volumePaneSize": "tiny"
		},
		studies_overrides: {
			"volume.volume.color.0": "#00FFFF",
			"volume.volume.color.1": "#0000FF",
			"volume.volume.transparency": 70,
			"volume.volume ma.color": "#FF0000",
			"volume.volume ma.transparency": 30,
			"volume.volume ma.linewidth": 5,
			"volume.show ma": true,
			"bollinger bands.median.color": "#33FF88",
			"bollinger bands.upper.linewidth": 7
		},
		charts_storage_api_version: "1.1",
		client_id: 'tradingview.com',
		user_id: 'public_user_id',
	});
};

/*function initOnReady()
{
	var udf_datafeed = new Datafeeds.UDFCompatibleDatafeed($('input[name="setting_url"]').val());

	var widget = window.tvWidget = new TradingView.widget({
		symbol: $('input[name="chart_symbol"]').val().toUpperCase(),
		autosize: true,
		width:600,
		height:600,
		interval: '60',
		toolbar_bg: '#f4f7f9',
		container_id: "chartdiv",
		datafeed: udf_datafeed,
		library_path: "/vendor/tradingview_new/charting_library/",
		locale: getParameterByName('lang') || "en",

		disabled_features: ["save_chart_properties_to_local_storage", "volume_force_overlay","timeframes_toolbar"],
		enabled_features: ["move_logo_to_main_pane"],
		overrides: {
			"mainSeriesProperties.style": 1,
			"symbolWatermarkProperties.color" : "#944",
			"volumePaneSize": "tiny"
		},
		studies_overrides: {
			"volume.volume.color.0": "#00FFFF",
			"volume.volume.color.1": "#0000FF",
			"volume.volume.transparency": 70,
			"volume.volume ma.color": "#FF0000",
			"volume.volume ma.transparency": 30,
			"volume.volume ma.linewidth": 5,
			"volume.show ma": true,
			"bollinger bands.median.color": "#33FF88",
			"bollinger bands.upper.linewidth": 7
		},
		charts_storage_api_version: "1.1",
		client_id: 'tradingview.com',
		user_id: 'public_user',
	});
}); // end of TradingView.onready*/

window.addEventListener('DOMContentLoaded', initOnReady, false);