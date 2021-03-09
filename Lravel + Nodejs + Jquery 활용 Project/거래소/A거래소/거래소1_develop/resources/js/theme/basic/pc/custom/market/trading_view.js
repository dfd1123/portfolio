function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

TradingView.onready(function()
{
	var udf_datafeed = new Datafeeds.UDFCompatibleDatafeed($('input[name="setting_url"]').val());

	var widget = window.tvWidget = new TradingView.widget({
		symbol: $('input[name="coin_apiname"]').val().toUpperCase(),
		autosize: true,
		width:600,
		height:600,
		interval: '60',
		toolbar_bg: '#f4f7f9',
		container_id: "chartdiv",
		datafeed: udf_datafeed,
		hide_top_toolbar : true,
		hide_side_toolbar : true,
		library_path: "/vendor/tradingview_new/charting_library/",
		locale: getParameterByName('lang') || __.message.trading_view,

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
		favorites: {
			intervals: ["1D", "3D", "3W", "W", "M"],
			chartTypes: ["Area", "candle"]
		}
	});
}); // end of TradingView.onready