function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

TradingView.onready(function()
{
    var widget = window.tvWidget = new TradingView.widget({
        //debug: true, // uncomment this line to see Library errors and warnings in the console
        //fullscreen: true,
        symbol: $('input[name="chart_symbol"]').val().toUpperCase(),		//symbol name
        timezone:'Asia/Seoul',
        autosize: true,
        interval : '60',
        container_id: "chartdiv",
        datafeed: new Datafeeds.UDFCompatibleDatafeed($('input[name="setting_url"]').val()),
        library_path: "/vendor/tradingview_new/charting_library/",
        theme: "Light",
        style: "1",
        locale: __.message.trading_view,
        toolbar_bg: "rgb(240,240,240)",
        enable_publishing: false,
        hide_legend: false,
        hide_top_toolbar: false,
        save_image:true,
        drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
        disabled_features: ["use_localstorage_for_settings","timeframes_toolbar"],
        //disabled_features: ["use_localstorage_for_settings","header_widget","symbol_description","left_toolbar","timeframes_toolbar","legend_context_menu","edit_buttons_in_legend","hide_last_na_study_output"],
        charts_storage_api_version: "1.1",
        client_id: 'trademarket.local',
        user_id: 'public_user_id',		
        chartTypes: ["Area", "Line"],
    });

});
