if (!CFServerSelect) {
    var CFServerSelect = {};
}


CFServerSelect.STD_DATA = 
[

    {t:"上海电信", v:"81", opt_data_array:[

        {t: "上海电信一区",v: "320",status:"1", opt_data_array:[]}
	,
        {t: "上海电信二区",v: "326",status:"1", opt_data_array:[]}

]}
,
    {t:"广东电信", v:"82", opt_data_array:[

        {t: "广东电信一区",v: "318",status:"1", opt_data_array:[]}
	,
        {t: "广东电信二区",v: "327",status:"1", opt_data_array:[]}
	,
        {t: "广东电信三区",v: "338",status:"1", opt_data_array:[]}
	,
        {t: "广东电信四区",v: "339",status:"1", opt_data_array:[]}

]}
,
    {t:"广西电信", v:"102", opt_data_array:[

        {t: "广西电信一区",v: "353",status:"1", opt_data_array:[]}

]}
,
    {t:"南方电信", v:"92", opt_data_array:[

        {t: "南方电信大区",v: "342",status:"1", opt_data_array:[]}

]}
,
    {t:"湖南电信", v:"91", opt_data_array:[

        {t: "湖南电信一区",v: "341",status:"1", opt_data_array:[]}
	,
        {t: "湖南电信二区",v: "340",status:"1", opt_data_array:[]}

]}
,
    {t:"湖北电信", v:"85", opt_data_array:[

        {t: "湖北电信一区",v: "328",status:"1", opt_data_array:[]}
	,
        {t: "湖北电信二区",v: "329",status:"1", opt_data_array:[]}

]}
,
    {t:"浙江电信", v:"86", opt_data_array:[

        {t: "浙江电信一区",v: "325",status:"1", opt_data_array:[]}
	,
        {t: "浙江电信二区",v: "349",status:"1", opt_data_array:[]}

]}
,
    {t:"江苏电信", v:"94", opt_data_array:[

        {t: "江苏电信一区",v: "344",status:"1", opt_data_array:[]}
	,
        {t: "江苏电信二区",v: "357",status:"1", opt_data_array:[]}

]}
,
    {t:"福建电信", v:"87", opt_data_array:[

        {t: "福建电信一区",v: "324",status:"1", opt_data_array:[]}

]}
,
    {t:"江西电信", v:"101", opt_data_array:[

        {t: "江西电信一区",v: "352",status:"1", opt_data_array:[]}

]}
,
    {t:"陕西电信", v:"88", opt_data_array:[

        {t: "陕西电信一区",v: "330",status:"1", opt_data_array:[]}

]}
,
    {t:"四川电信", v:"90", opt_data_array:[

        {t: "四川电信一区",v: "333",status:"1", opt_data_array:[]}
	,
        {t: "四川电信二区",v: "356",status:"1", opt_data_array:[]}

]}
,
    {t:"重庆电信", v:"89", opt_data_array:[

        {t: "重庆电信一区",v: "332",status:"1", opt_data_array:[]}

]}
,
    {t:"安徽电信", v:"97", opt_data_array:[

        {t: "安徽电信一区",v: "347",status:"1", opt_data_array:[]}

]}
,
    {t:"云南电信", v:"98", opt_data_array:[

        {t: "云南电信一区",v: "348",status:"1", opt_data_array:[]}

]}
,
    {t:"北方网通", v:"93", opt_data_array:[

        {t: "北方网通大区",v: "343",status:"1", opt_data_array:[]}

]}
,
    {t:"辽宁网通", v:"84", opt_data_array:[

        {t: "辽宁网通一区",v: "322",status:"1", opt_data_array:[]}
	,
        {t: "辽宁网通二区",v: "323",status:"1", opt_data_array:[]}
	,
        {t: "辽宁网通三区",v: "336",status:"1", opt_data_array:[]}

]}
,
    {t:"黑龙江网通", v:"99", opt_data_array:[

        {t: "黑龙江网通区",v: "350",status:"1", opt_data_array:[]}

]}
,
    {t:"吉林网通", v:"100", opt_data_array:[

        {t: "吉林网通一区",v: "351",status:"1", opt_data_array:[]}

]}
,
    {t:"北京网通", v:"83", opt_data_array:[

        {t: "北京网通一区",v: "319",status:"1", opt_data_array:[]}
	,
        {t: "北京网通二区",v: "321",status:"1", opt_data_array:[]}
	,
        {t: "北京网通三区",v: "334",status:"1", opt_data_array:[]}
	,
        {t: "北京网通四区",v: "335",status:"1", opt_data_array:[]}

]}
,
    {t:"山东网通", v:"96", opt_data_array:[

        {t: "山东网通一区",v: "346",status:"1", opt_data_array:[]}
	,
        {t: "山东网通二区",v: "358",status:"1", opt_data_array:[]}

]}
,
    {t:"山西网通", v:"103", opt_data_array:[

        {t: "山西网通一区",v: "354",status:"1", opt_data_array:[]}

]}
,
    {t:"河南网通", v:"170", opt_data_array:[

        {t: "河南一区",v: "345",status:"1", opt_data_array:[]}
    ,
        {t: "河南二区",v: "359",status:"1", opt_data_array:[]}

]}
,
    {t:"河北网通", v:"171", opt_data_array:[

        {t: "河北一区",v: "355",status:"1", opt_data_array:[]}

]}
,
    {t:"移动专区", v:"540", opt_data_array:[

        {t: "移动专区",v: "360",status:"1", opt_data_array:[]}

]}
,
    {t:"教育网专区", v:"660", opt_data_array:[
    
        {t: "教育网专区",v: "361",status:"1", opt_data_array:[]}
        
]}      
, 
    {t:"体验服", v:"726", opt_data_array:[

        {t: "体验服",v: "450",status:"1", opt_data_array:[]}
    
]}
];



CFServerSelect.areaChange = function () {
    CFServerSelect.serverSelect.options.length = 0;

    var selectIndex = CFServerSelect.areaSelect.selectedIndex;
    for (var i = 0; i < CFServerSelect.STD_DATA[selectIndex].opt_data_array.length; i++) {
        var varItem = new Option(CFServerSelect.STD_DATA[selectIndex].opt_data_array[i].t, CFServerSelect.STD_DATA[selectIndex].opt_data_array[i].v);
        CFServerSelect.serverSelect.options.add(varItem);
    }

}


CFServerSelect.showzone = function (select_area, select_server) {
    CFServerSelect.areaSelect = select_area;
    CFServerSelect.serverSelect = select_server;

    for (var i = 0; i < CFServerSelect.STD_DATA.length; i++) {
        var varItem = new Option(CFServerSelect.STD_DATA[i].t, CFServerSelect.STD_DATA[i].v);
        CFServerSelect.areaSelect.options.add(varItem);
    }
    CFServerSelect.areaSelect.options[0].selected = true;
    CFServerSelect.areaSelect.attachEvent('onchange', CFServerSelect.areaChange);

    CFServerSelect.areaChange();

}


CFServerSelect.setZone =  function (area_id, server_id) {
    for(var i = 0; i<CFServerSelect.areaSelect.length; i++){
        if(area_id == CFServerSelect.areaSelect.options[i].value){
             CFServerSelect.areaSelect.options[i].selected = true;
             break;
        }
    }
    
    CFServerSelect.areaChange();
    
    for(var i = 0; i<CFServerSelect.serverSelect.length; i++){
        if(server_id == CFServerSelect.serverSelect.options[i].value){
             CFServerSelect.serverSelect.options[i].selected = true;
             break;
        }
    }    
    
}



